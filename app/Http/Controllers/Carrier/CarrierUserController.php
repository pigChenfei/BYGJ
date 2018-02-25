<?php

namespace App\Http\Controllers\Carrier;

use App\Criteria\Carrier\CarrierUserSelectCriteria;
use App\DataTables\Carrier\CarrierUserDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierUserRequest;
use App\Http\Requests\Carrier\UpdateCarrierUserRequest;
use App\Repositories\Carrier\CarrierServiceTeamRepository;
use App\Repositories\Carrier\CarrierUserRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;

class CarrierUserController extends AppBaseController
{
    /** @var  CarrierUserRepository */
    private $carrierUserRepository;

    public function __construct(CarrierUserRepository $carrierUserRepo)
    {
        $this->carrierUserRepository = $carrierUserRepo;
    }

    /**
     * Display a listing of the CarrierUser.
     *
     * @param CarrierUserDataTable $carrierUserDataTable
     * @return Response
     */
    public function index(CarrierUserDataTable $carrierUserDataTable)
    {
        return $carrierUserDataTable->render('Carrier.carrier_users.index');
    }

    /**
     * Show the form for creating a new CarrierUser.
     *
     * @return Response
     */
    public function create(CarrierServiceTeamRepository $repository)
    {
        $carrierServiceTeams = $repository->allServiceTeams();//获得全部部门信息
        return view('Carrier.carrier_users.create')->with('carrierServiceTeams',$carrierServiceTeams);
    }

    /**
     * Store a newly created CarrierUser in storage.
     *
     * @param CreateCarrierUserRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierUserRequest $request)
    {
        $input = $request->all();

        $input['carrier_id'] = \Auth::user()->carrier_id;

        $carrierUser = $this->carrierUserRepository->create($input);
        if($request->ajax()){

            return self::sendResponse([],'ok');
        }

        Flash::success('Carrier User saved successfully.');

        return redirect(route('carrierUsers.index'));
    }

    /**
     * Display the specified CarrierUser.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierUser = $this->carrierUserRepository->findWithoutFail($id);

        if (empty($carrierUser)) {
            Flash::error('Carrier User not found');

            return redirect(route('carrierUsers.index'));
        }

        return view('Carrier.carrier_users.show')->with('carrierUser', $carrierUser);
    }

    /**
     * Show the form for editing the specified CarrierUser.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id,CarrierServiceTeamRepository $repository)
    {
        $carrierUser = $this->carrierUserRepository->findWithoutFail($id);

        if (empty($carrierUser)) {
            Flash::error('Carrier User not found');

            return redirect(route('carrierUsers.index'));
        }
        $carrierid = \Auth::user()->carrier_id;
        $carrierServiceTeams = $repository->allServiceTeams();//获得全部部门信息\
        return view('Carrier.carrier_users.edit',[
            'carrierUser'=>$carrierUser,
            'carrierServiceTeams'=>$carrierServiceTeams
            ]);
    }

    /**
     * Update the specified CarrierUser in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierUserRequest $request)
    {
        $carrierUser = $this->carrierUserRepository->findWithoutFail($id);

        if (empty($carrierUser)) {
            Flash::error('Carrier User not found');

            return redirect(route('carrierUsers.index'));
        }

        $carrierUser = $this->carrierUserRepository->update($request->all(), $id);
        if($request->ajax()){

            return self::sendResponse([],'ok');
        }
        Flash::success('更新成功.');

        return redirect(route('carrierUsers.index'));
    }

    /**
     * Remove the specified CarrierUser from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id,Request $request)
    {
        $carrierUser = $this->carrierUserRepository->findWithoutFail($id);

        if (empty($carrierUser)) {
            return $this->sendNotFoundResponse();
        }

        $this->carrierUserRepository->delete($id);

        return $this->sendSuccessResponse(route('carrierUsers.index'));
    }


    public function editPassword($id,Request $request)
    {
        $carrierUser = $this->carrierUserRepository->findWithoutFail($id);

        if (empty($carrierUser)) {
            return $this->sendNotFoundResponse();
        }
        return view('Carrier.carrier_users.edit_password',['carrierUser'=>$carrierUser]);
    }

    public function savePassword($id,Request $request)
    {
        if(!empty($request->get('password')) && !empty($request->get('confirm_password')))
        {
            $confirm_password = $request->get('confirm_password');
            if($request->get('password') === $confirm_password)
            {
                $data['password'] = bcrypt($request->get('password'));
                $carrierUser = $this->carrierUserRepository->update($data, $id);
                if (empty($carrierUser)) {
                    return $this->sendNotFoundResponse();
                }
                return $this->sendSuccessResponse( route('carrierUsers.index'));
            }else{
                $error_respon = array('success' => false, 'message' => '二次密码不一致。');
                return $error_respon;
            }
        }else{
            $error_respon = array('success' => false, 'message' => '密码不能为空。');
            return $error_respon;
        }

    }
}
