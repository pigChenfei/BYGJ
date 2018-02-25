<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierServiceTeamDataTable;
use App\Helpers\Caches\RouteCacheHelper;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierServiceTeamRequest;
use App\Http\Requests\Carrier\UpdateCarrierServiceTeamRequest;
use App\Models\CarrierServiceTeam;
use App\Models\CarrierServiceTeamRole;
use App\Repositories\Carrier\CarrierPermissionGroupRepository;
use App\Repositories\Carrier\CarrierPermissionRepository;
use App\Repositories\Carrier\CarrierServiceTeamRepository;
use App\Repositories\Carrier\CarrierServiceTeamRoleRepository;
use App\Vendor\Pay\Gateway\PayOrderInterface;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use App\Models\RolesModel\PermissionGroup;
use App\Models\CarrierUser;

class CarrierServiceTeamController extends AppBaseController
{
    /** @var  CarrierServiceTeamRepository */
    private $carrierServiceTeamRepository;

    public function __construct(CarrierServiceTeamRepository $carrierServiceTeamRepo)
    {
        $this->carrierServiceTeamRepository = $carrierServiceTeamRepo;
    }

    /**
     * Display a listing of the CarrierServiceTeam.
     *
     * @param CarrierServiceTeamDataTable $carrierServiceTeamDataTable
     * @return Response
     */
    public function index(CarrierServiceTeamDataTable $carrierServiceTeamDataTable)
    {
        return $carrierServiceTeamDataTable->render('Carrier.carrier_service_teams.index');
    }

    /**
     * Show the form for creating a new CarrierServiceTeam.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.carrier_service_teams.create');
    }

    /**
     * Store a newly created CarrierServiceTeam in storage.
     *
     * @param CreateCarrierServiceTeamRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierServiceTeamRequest $request)
    {
        $input = $request->all();
        $input['carrier_id'] = \Auth::user()->carrier_id;

        $carrierServiceTeam = $this->carrierServiceTeamRepository->create($input);
        if($request->ajax()){

            return self::sendResponse([],'ok');
        }

        Flash::success('Carrier Service Team saved successfully.');

        return redirect(route('carrierServiceTeams.index'));
    }

    /**
     * Display the specified CarrierServiceTeam.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierServiceTeam = $this->carrierServiceTeamRepository->findWithoutFail($id);

        if (empty($carrierServiceTeam)) {
            Flash::error('Carrier Service Team not found');

            return redirect(route('carrierServiceTeams.index'));
        }

        return view('Carrier.carrier_service_teams.show')->with('carrierServiceTeam', $carrierServiceTeam);
    }

    /**
     * Show the form for editing the specified CarrierServiceTeam.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id,CarrierServiceTeamRepository $repository)
    {
        $carrierServiceTeam = $this->carrierServiceTeamRepository->findWithoutFail($id);

        if (empty($carrierServiceTeam)) {
            Flash::error('Carrier Service Team not found');

            return redirect(route('carrierServiceTeams.index'));
        }

        return view('Carrier.carrier_service_teams.edit')->with('carrierServiceTeam', $carrierServiceTeam);
    }

    /**
     * Update the specified CarrierServiceTeam in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierServiceTeamRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierServiceTeamRequest $request)
    {
        $carrierServiceTeam = $this->carrierServiceTeamRepository->findWithoutFail($id);
        if($carrierServiceTeam->is_administrator == true){
            return $this->sendErrorResponse('不能更新管理员部门');
        }
        if (empty($carrierServiceTeam)) {
            return $this->sendNotFoundResponse();
        }
        $this->carrierServiceTeamRepository->update($request->all(), $id);
        return $this->sendSuccessResponse();
    }

    /**
     * Remove the specified CarrierServiceTeam from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id,Request $request)
    {
        $carrierServiceTeam = $this->carrierServiceTeamRepository->findWithoutFail($id);
        if($carrierServiceTeam->is_administrator == true){
            return $this->sendErrorResponse('不能删除管理员部门');
        }
        if (empty($carrierServiceTeam)) {
            return $this->sendNotFoundResponse();
        }
        $user = CarrierUser::where('team_id',$id)->first();
        if (!empty($user)) {
            return $this->sendErrorResponse('该部门下有管理员账号，不能直接删除', 404);
        }
        $result = $this->carrierServiceTeamRepository->delete($id);
        if ($result){
            CarrierServiceTeamRole::where('team_id',$id)->delete();
        }
        return $this->sendSuccessResponse(route('carrierServiceTeams.index'));

    }
    /**
     * 显示客服部门对应的权限信息
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function permissionSetShow($id){
        $carrierId = \WinwinAuth::carrierUser()->carrier_id;
        //根据当前运营商ID拿当前运营商管理员的权限
        $serviceAdminTeam = CarrierServiceTeam::byCarrierId($carrierId)->administrator()->with('teamRoles')->first();
        if (!$serviceAdminTeam) {
            return $this->sendErrorResponse('缺少管理部门,请联系管理员添加');
        }


        $teamPermissions = $serviceAdminTeam->teamRoles->map(function($element){
            return $element->permission_id;
        })->toArray();


        //获取当前部门权限
        $carrierServiceTeam = $this->carrierServiceTeamRepository->with('teamPermissions')->findWithoutFail($id);

        foreach ($carrierServiceTeam->teamPermissions as $k=>$v){
            $hasPermissions[] = $v['id'];
        }
        $hasPermissions[] = 0;

        $permissionGroups = PermissionGroup::orderBySort('asc')->with(['groups.permissions' => function($query)use($teamPermissions){
            $query->whereIn('id',$teamPermissions);
        }])->topGroup()->get();

        //一级权限
//        $parnetPermission = PermissionGroup::with(['permissions.roles' => function($query)use($teamPermissions){
//            $query->where('role_id',CarrierUser::CARRIER_ADMIN_ROLE_ID)->whereIN('permission_id',$teamPermissions);
//        }])->where('parent_id',0)->get();

        //子权限
//        $chilrenPermission = PermissionGroup::with(['permissions.roles' => function($query)use($teamPermissions){
//            $query->where('role_id',CarrierUser::CARRIER_ADMIN_ROLE_ID)->whereIN('permission_id',$teamPermissions);
//        }])->where('parent_id','!=',0)->get();

       if (empty($carrierServiceTeam)) {

           Flash::error('Carrier Service Team not found');

           return redirect(route('carrierServiceTeams.index'));
       }

       return view('Carrier.carrier_service_teams.list')->with(['permissionGroups'=>$permissionGroups,'hasPermissions'=>$hasPermissions,'team_id'=>$id]);

    }

    public function permissionSave(Request $request,CarrierServiceTeamRoleRepository $repository){
        try{
            \DB::transaction(function ()use($request){
            $id = $request->get('team_id');
            CarrierServiceTeamRole::where('team_id',$id)->delete();
            $teamPermission = $request->get('ids');
            $inserData = array();
            $tableName = null;
            if ($teamPermission){
                foreach ($teamPermission as $k => $value){
                    $role = new CarrierServiceTeamRole();
                    $role->team_id = $id;
                    $role->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                    $role->permission_id = $value;
                    $tableName = $role->table;
                    $inserData[] = $role->toArray();
            }
            $tableName && $inserData && DB::table($tableName)->insert($inserData);
        }
        });
            \Cache::tags(\Config::get('entrust.permission_role_table'))->flush();
            \Cache::tags(\Config::get('entrust.role_user_table'))->flush();
            RouteCacheHelper::clearAllCachedRoutes('carrier');
            return $this->sendSuccessResponse(route('carrierServiceTeams.index'));
        //dd($teamPermission);
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }

        //DB::table('inf_carrier_service_team_role')->where('team_id',$id)->delete();
        //$carrierServiceTeamRole = DB::table('inf_carrier_service_team_role')->insert($inserData);
    }
}
