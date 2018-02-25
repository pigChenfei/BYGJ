<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CarrierDataTable;
use App\Helpers\Caches\CarrierInfoCacheHelper;
use App\Helpers\Caches\RouteCacheHelper;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateCarrierRequest;
use App\Http\Requests\Admin\UpdateCarrierRequest;
use App\Models\CarrierPlayerLevel;
use App\Models\CarrierServiceTeam;
use App\Models\CarrierServiceTeamRole;
use App\Models\CarrierUser;
use App\Models\Conf\CarrierDashLoginConf;
use App\Models\Conf\CarrierDepositConf;
use App\Models\Conf\CarrierInvitePlayerConf;
use App\Models\Conf\CarrierPasswordRecoverySiteConf;
use App\Models\Conf\CarrierRegisterBasicConf;
use App\Models\Conf\CarrierWebSiteConf;
use App\Models\Conf\CarrierWithdrawConf;
use App\Models\RolesModel\Permission;
use App\Models\RolesModel\PermissionGroup;
use App\Models\Def\Template;
use App\Repositories\Admin\CarrierRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use League\Flysystem\Config;
use Response;
use App\Models\CarrierBackUpDomain;

class CarrierController extends AppBaseController
{
    /** @var  CarrierRepository */
    private $carrierRepository;

    public function __construct(CarrierRepository $carrierRepo)
    {
        $this->carrierRepository = $carrierRepo;
    }

    /**
     * Display a listing of the Carrier.
     *
     * @param CarrierDataTable $carrierDataTable
     * @return Response
     */
    public function index(CarrierDataTable $carrierDataTable)
    {
        return $carrierDataTable->render('Admin.carriers.index');
    }

    /**
     * Show the form for creating a new Carrier.
     *
     * @return Response
     */
    public function create()
    {
        $template =Template::all();
        return view('Admin.carriers.create')->with('template',$template);
    }

    /**
     * Store a newly created Carrier in storage.
     *
     * @param CreateCarrierRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierRequest $request)
    {
        $input = $request->all();
        if($request->get('id')){
            return $this->update($request->get('id'),$input);
        }
    
        try{
            $carrier = null;
                $a =\DB::select('select * from inf_agent_domain where website=?',[$input['site_url']]);
                $b =\DB::select('select * from inf_carrier where site_url=?',[$input['site_url']]);
                $c =\DB::select('select * from inf_carrier_back_up_domain where domain=?',[$input['site_url']]);

                if($a||$b||$c)
                {
                   return $this->sendErrorResponse('此域名已存在');
                }

            \DB::transaction(function () use (&$carrier,$input){

                //创建登录配置数据
                $carrier = $this->carrierRepository->create($input);
                $loginConf = new CarrierDashLoginConf();
                $loginConf->carrier_id = $carrier->id;
                $loginConf->saveOrFail();

                //创建存款配置数据
                $depositConf = new CarrierDepositConf();
                $depositConf->carrier_id = $carrier->id;
                $depositConf->saveOrFail();

                //邀请好友设置
                $invitePlayerConf = new CarrierInvitePlayerConf();
                $invitePlayerConf->carrier_id = $carrier->id;
                $invitePlayerConf->saveOrFail();

                //邮箱设置
                $emailConf = new CarrierPasswordRecoverySiteConf();
                $emailConf->carrier_id = $carrier->id;
                $emailConf->saveOrFail();

                //网站基本配置表
                $webBasicConf = new CarrierWebSiteConf();
                $webBasicConf->carrier_id = $carrier->id;
                $webBasicConf->site_title = $carrier->name;
                $webBasicConf->online_service_file_path = $input['online_service_file_path'];
                $webBasicConf->saveOrFail();

                //取款设置表
                $carrierWithdrawConf = new CarrierWithdrawConf();
                $carrierWithdrawConf->carrier_id = $carrier->id;
                $carrierWithdrawConf->saveOrFail();

                //默认会员等级
                $defaultPlayerLevel = new CarrierPlayerLevel();
                $defaultPlayerLevel->level_name = '默认等级';
                $defaultPlayerLevel->carrier_id = $carrier->id;
                $defaultPlayerLevel->sort = 1;
                $defaultPlayerLevel->upgrade_rule = '["",{"ruleTypeInputStack":[],"ruleRelationInputStack":[],"largeOrLessRelationInputStack":[],"numberInputStack":[],"totalInputStack":[],"operatorInputStack":[],"viewMeta":{"ruleType":{"betAmount":"投注额","depositAmount":"存款额"},"ruleRelation":{"leftRequire":{"content":"(","data":"("},"rightRequire":{"content":")","data":")"},"andRelation":{"content":"且","data":"and"},"orRelation":{"content":"或","data":"or"}},"operatorRelation":{"plus":"+","reduce":"-"},"largeOrLessRelation":{"large":">","less":"<","largeAndEqual":">=","lessAndEqual":"<="}},"currentInputValue":null}]';
                $defaultPlayerLevel->is_default = true;
                $defaultPlayerLevel->save();
            });
            return self::sendResponse($carrier);
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified Carrier.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrier = $this->carrierRepository->with(['pins.defPin','webSiteConf'])->findWithoutFail($id);
        if (empty($carrier)) {
            return $this->sendNotFoundResponse();
        }
        return view('Admin.carriers.show')->with('carrier', $carrier);
    }


    /**
     * 所有权限列表
     * @param $id
     * @return Response
     */
    public function showAssignPermissions($carrierId){
        $permissionGroups = PermissionGroup::orderBySort('asc')->with(['groups.permissions'])->topGroup()->get();

        $serviceAdminTeam = CarrierServiceTeam::byCarrierId($carrierId)->administrator()->with('teamRoles')->first();
        if (!$serviceAdminTeam) {
            return $this->sendErrorResponse('缺少管理部门,请联系管理员添加');
        }
        $teamPermissions = $serviceAdminTeam->teamRoles->map(function($element){
            return $element->permission_id;
        })->toArray();
        return view('Admin.carriers.permissionsList')->with('permissionGroups',$permissionGroups)->with('teamPermissions',$teamPermissions);
    }


    /**
     * 给运营商分配权限, 即是赋予的最高管理员权限
     * @param $carrierId
     * @param Request $request
     * @return mixed|Response
     */
    public function saveAssignPermissions($carrierId, Request $request){
        $this->validate($request,[
            'permission' => 'array',
        ]);
        if($permissions = $request->get('permission')){
            $count = Permission::whereIn('id',$permissions)->count('id');
            if($count != count($permissions)){
                return $this->sendErrorResponse('又不存在的权限数据');
            }
        }
        $carrier = $this->carrierRepository->findWithoutFail($carrierId);
        if (empty($carrier)) {
            return $this->sendNotFoundResponse();
        }
        //获取当前运营商的管理部门的所有角色, 对当前的角色进行比较, 如果相对于旧的权限有减少, 那么需要其他部门删除多余的权限;
        $serviceAdminTeam = CarrierServiceTeam::byCarrierId($carrierId)->administrator()->with('teamRoles')->first();
        if (!$serviceAdminTeam) {
            return $this->sendErrorResponse('缺少管理部门,请联系管理员添加');
        }
        $teamPermissions = $serviceAdminTeam->teamRoles->map(function($element){
            return $element->permission_id;
        })->toArray();

        $deletePermissions = array_diff($teamPermissions,$request->get('permission',[]));
        $insertPermissions = array_diff($request->get('permission',[]),$teamPermissions);
        try{
            \DB::transaction(function () use ($insertPermissions,$deletePermissions,$serviceAdminTeam,$carrierId){
                foreach ($insertPermissions as $permission){
                    $serviceTeamRole = new CarrierServiceTeamRole();
                    $serviceTeamRole->permission_id = $permission;
                    $serviceTeamRole->carrier_id = $carrierId;
                    $serviceTeamRole->team_id = $serviceAdminTeam->id;
                    $serviceTeamRole->save();
                }
                if($deletePermissions){
                    CarrierServiceTeamRole::permissionIds($deletePermissions)->byCarrierId($carrierId)->delete();
                }
            });
            \Cache::tags(\Config::get('entrust.permission_role_table'))->flush();
            \Cache::tags(\Config::get('entrust.role_user_table'))->flush();
            RouteCacheHelper::clearAllCachedRoutes('carrier');
            return $this->sendSuccessResponse();
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
    }

    /**
     * 显示运营商账号列表
     * @param $carrierId
     * @return $this|Response
     */
    public function showCarrierUsers($carrierId){
        $carrier = $this->carrierRepository->with('carrierUsers.serviceTeam')->findWithoutFail($carrierId);
        if (empty($carrier)) {
            return $this->sendNotFoundResponse();
        }
        return view('Admin.carriers.carrier_user')->with('carrier',$carrier);
    }

    /**
     * 显示创建运营商账号模态框
     * @param $carrierId
     * @return $this|Response
     */
    public function showCreateUserModal($carrierId){
        $carrier = $this->carrierRepository->with('serviceTeams')->findWithoutFail($carrierId);
        if (empty($carrier)) {
            return $this->sendNotFoundResponse();
        }
        return view('Admin.carriers.carrier_user_create')->with('carrier',$carrier);
    }

    /**
     * 创建运营商后台账号
     * @param $carrierId
     * @param Request $request
     * @return mixed|Response
     */
    public function createUser($carrierId, Request $request){
        $this->validate($request,[
            'user_name' => ['required','min:6','regex:/^(\w|_){6,}$/','unique:inf_carrier_user,username,NULL,id,carrier_id,'.$carrierId],
            'password'  => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'service_team' => 'required|exists:inf_carrier_service_team,id,carrier_id,'.$carrierId
        ],[
            'user_name.regex' => '用户名必须是由字母,数字或下划线组成'
        ],[
            'user_name' => '用户名',
            'password'  => '密码',
            'confirm_password' => '密码',
            'service_team' => '部门'
        ]);
        $carrier = $this->carrierRepository->findWithoutFail($carrierId);
        if (empty($carrier)) {
            return $this->sendNotFoundResponse();
        }
        $carrierUser = new CarrierUser();
        $carrierUser->carrier_id = $carrierId;
        $carrierUser->username = $request->get('user_name');
        $carrierUser->password = bcrypt($request->get('password'));
        $carrierUser->team_id = $request->get('service_team');
        $carrierMember = CarrierUser::where('is_super_admin',1)->where('username','<>','超级管理员')->first();
        if(is_null($carrierMember))
        {
            $carrierUser->is_super_admin=1;
        }
        $carrierUser->save();
        return $this->sendSuccessResponse();
    }

    public function showEditUserModal($carrierUserId){
        $carrierUser = CarrierUser::findOrFail($carrierUserId);
        $carrier = $this->carrierRepository->with('serviceTeams')->findWithoutFail($carrierUser->carrier_id);
        if (empty($carrier)) {
            return $this->sendNotFoundResponse();
        }
        return view('Admin.carriers.carrier_user_edit')->with('carrierUser',$carrierUser)->with('carrier',$carrier);
    }


    public function updateUser($carrierUserId,Request $request){
        $carrierUser = CarrierUser::findOrFail($carrierUserId);
        $this->validate($request,[
            'password'  => 'min:6',
            'confirm_password' => 'same:password',
            'service_team' => 'required|exists:inf_carrier_service_team,id,carrier_id,'.$carrierUser->carrier_id
        ],[],[
            'password'  => '密码',
            'confirm_password' => '密码',
            'service_team' => '部门'
        ]);
        if($password = $request->get('password')){
            $carrierUser->password = bcrypt($password);
        }
        $carrierUser->team_id = $request->get('service_team');
        $carrierUser->update();
        return $this->sendSuccessResponse();
    }

    /**
     * Show the form for editing the specified Carrier.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrier = $this->carrierRepository->findWithoutFail($id);
        if (empty($carrier)) {
            return $this->sendNotFoundResponse();
        }
        $template =Template::all();
        return view('Admin.carriers.edit')->with('carrier', $carrier)->with('template',$template);
    }

    /**
     * Update the specified Carrier in storage.
     * @return Response
     */
    public function update($id, Array $input)
    {
        $carrier = $this->carrierRepository->with('webSiteConf')->findWithoutFail($id);
        $oldHost = $carrier->site_url;
        if($oldHost){
            CarrierInfoCacheHelper::clearCarrierInfoByHost($oldHost);
        }
        if (empty($carrier)) {
            return $this->sendNotFoundResponse();
        }

        $a =\DB::select('select * from inf_agent_domain where website=? and carrier_id<>?',[$input['site_url'],$id]);
        $b =\DB::select('select * from inf_carrier where site_url=? and id<>?',[$input['site_url'],$id]);
        $c =\DB::select('select * from inf_carrier_back_up_domain where domain=?',[$input['site_url']]);
        if($a||$b||$c)
        {
            return $this->sendErrorResponse('此域名已被使用');
        }
        \DB::beginTransaction();
        try{
            $carrier->webSiteConf->update(['online_service_file_path'=>$input['online_service_file_path']]);
            $this->carrierRepository->update($input, $id);
            \DB::commit();
        }catch (\Exception $e){
            \DB::rollBack();
            \Log::error('总后台更新运营商错误',[$e->getMessage()]);
            return $this->sendErrorResponse('操作失败，请重试');
        }
        return $this->sendSuccessResponse();
    }

    /**
     * @param $id
     * @return mixed|Response
     */
    public function toggleCarrierStatus($id){
        $carrier = $this->carrierRepository->findWithoutFail($id);
        if (empty($carrier)) {
            return $this->sendNotFoundResponse();
        }
        $carrier->is_forbidden = !$carrier->is_forbidden;
        $carrier->update();
        return $this->sendSuccessResponse();
    }

    public function toggleCarrierUserStatus($carrierUserId){
        $carrierUser = CarrierUser::findOrFail($carrierUserId);
        if($carrierUser->status == CarrierUser::STATUS_NORMAL){
            $carrierUser->status = CarrierUser::STATUS_FREEZE;
        }else{
            $carrierUser->status = CarrierUser::STATUS_NORMAL;
        }
        //解除锁定

        $keys = Redis::command('keys',['laravel:'.$carrierUser->username.'*']);
        foreach ($keys as $key){
            Redis::command('del',[$key]);
        }
        $carrierUser->update();
        return $this->sendSuccessResponse();
    }


    public function loginCarrierAdminSystem($carrierId){
        $carrier = $this->carrierRepository->findWithoutFail($carrierId);
        if (empty($carrier)) {
            return $this->sendNotFoundResponse();
        }
        $carrierUser = CarrierUser::superAdmin()->where(['carrier_id'=>$carrier->id, 'username' => '超级管理员'])->first();
//        if (\WinwinAuth::carrierUser() && \WinwinAuth::carrierUser()->username == '超级管理员'){
//            dump(1111);die;
//            return redirect( 'http://'.$carrier->site_url.'/carrier');
//        }
        if(!$carrierUser){
            $carrierUser = new CarrierUser();
            $carrierUser->is_super_admin  = true;
            $carrierUser->username = '超级管理员';
            $team = CarrierServiceTeam::where('carrier_id',$carrier->id)->first();
            $carrierUser->team_id = $team->id;
            $carrierUser->carrier_id = $carrier->id;
            $carrierUser->password   = bcrypt(random_int(1000000,9999999));
            $carrierUser->save();
        }
        //随机获取一个运营商管理员账号
        $webSite = 'http://'.$carrier->site_url.'/carrier/login?token='.base64_encode($carrierUser->password);
//        dump(2222);die;
        return redirect($webSite);
    }

    /**
     * Remove the specified Carrier from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        return $this->sendSuccessResponse();
    }
    
    /**
     * 显示运营商备用域名列表
     * @param $carrierId
     * @return $this|Response
     */
    public function showCarrierBackUpDomain($carrierId){
        $carrierBackUpDomain = null;
        $carrierBackUpDomain = CarrierBackUpDomain::where(['carrier_id'=>$carrierId])->get();
        if (empty($carrierBackUpDomain)) {
            return $this->sendNotFoundResponse();
        }
        $carrierId = $carrierId;
        return view('Admin.carriers.carrier_back_up_domain')->with(['carrierId'=>$carrierId,'carrierBackUpDomain'=>$carrierBackUpDomain]);
    }
    
    public function toggleCarrierBackUpDomainStatus($carrierBackUpDomainId){
        $carrierBackUpDomain = CarrierBackUpDomain::findOrFail($carrierBackUpDomainId);
        $carrierBackUpDomain->status = !$carrierBackUpDomain->status;
        $carrierBackUpDomain->update();
        return $this->sendSuccessResponse();
    }
    
    /**
     * 显示创建运营商域名模态框
     * @param $carrierId
     * @return $this|Response
     */
    public function showCreateBackUpDomainModal($carrierId){
        if (empty($carrierId)) {
            return $this->sendNotFoundResponse();
        }
        return view('Admin.carriers.carrier_back_up_domain_create')->with(['carrier_id'=>$carrierId]);
    }
    
    /**
     * 创建运营商域名
     * @param $carrierId
     * @param Request $request
     * @return mixed|Response
     */
    public function createDomain($carrierId, Request $request){
        $this->validate($request,[
            'domain' => ['required','regex:/^((\w|-)+\.)+\w+$/']
        ],[],[
            'domain' => '域名',
        ]);

        if (empty($carrierId)) {
            return $this->sendNotFoundResponse();
        }

        $a =\DB::select('select * from inf_agent_domain where website=?',[$request->get('domain')]);
        $b =\DB::select('select * from inf_carrier where site_url=?',[$request->get('domain')]);
        $c =\DB::select('select * from inf_carrier_back_up_domain where domain=?',[$request->get('domain')]);
        if($a||$b||$c)
        {
            return $this->sendErrorResponse('此域名已被使用');
        }

        $carrierDomain = new CarrierBackUpDomain();
        $carrierDomain->carrier_id = $carrierId;
        $carrierDomain->domain = $request->get('domain');
        $carrierDomain->save();
        return $this->sendSuccessResponse();
    }
    
    public function showEditDomainModal($carrierDomainId){
        $carrierDomain = CarrierBackUpDomain::findOrFail($carrierDomainId);
        if (empty($carrierDomain)) {
            return $this->sendNotFoundResponse();
        }
        return view('Admin.carriers.carrier_back_up_domain_edit')->with('carrierDomain',$carrierDomain);
    }
    
    public function updateDomain($carrierDomainId,Request $request){
        $carrierDomain = CarrierBackUpDomain::findOrFail($carrierDomainId);
        $this->validate($request,[
            'domain' => ['required','regex:/^((\w|-)+\.)+\w+$/']
        ],[],[
            'domain' => '域名',
        ]);

        $a =\DB::select('select * from inf_agent_domain where website=?',[$request->get('domain')]);
        $b =\DB::select('select * from inf_carrier where site_url=?',[$request->get('domain')]);
        $c =\DB::select('select * from inf_carrier_back_up_domain where domain=? and carrier_id<>?',[$request->get('domain'),$carrierDomain->carrier_id]);
        if($a||$b||$c)
        {
            return $this->sendErrorResponse('此域名已被使用');
        }

        $carrierDomain->domain = $request->get('domain');
        $carrierDomain->update();
        return $this->sendSuccessResponse();
    }
    
}
