<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateAdminUserRequest;
use App\Http\Requests\Admin\UpdateAdminUserRequest;
use App\Models\AdminUser;
use App\Repositories\Admin\AdminUserRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AdminUserController extends AppBaseController
{
    /** @var  AdminUserRepository */
    private $adminUserRepository;

    public function __construct(AdminUserRepository $adminUserRepo)
    {
        $this->adminUserRepository = $adminUserRepo;
    }

    /**
     * Display a listing of the AdminUser.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        $adminUser = new AdminUser();

        $adminUser->email = 'wugang@163.com';

        $adminUser->mobile = 13000000000;

        $adminUser->username = 'wugang';

        $adminUser->save();


//        $this->adminUserRepository->pushCriteria(new RequestCriteria($request));
//        $adminUsers = $this->adminUserRepository->all();
//
//        dd($adminUsers);
//
//        return view('admin_users.index')
//            ->with('adminUsers', $adminUsers);
    }

    /**
     * Show the form for creating a new AdminUser.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin_users.create');
    }

    /**
     * Store a newly created AdminUser in storage.
     *
     * @param CreateAdminUserRequest $request
     *
     * @return Response
     */
    public function store(CreateAdminUserRequest $request)
    {
        $input = $request->all();

        $adminUser = $this->adminUserRepository->create($input);

        Flash::success('Admin User saved successfully.');

        return redirect(route('adminUsers.index'));
    }

    /**
     * Display the specified AdminUser.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $adminUser = $this->adminUserRepository->findWithoutFail($id);

        if (empty($adminUser)) {
            Flash::error('Admin User not found');

            return redirect(route('adminUsers.index'));
        }

        return view('admin_users.show')->with('adminUser', $adminUser);
    }

    /**
     * Show the form for editing the specified AdminUser.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $adminUser = $this->adminUserRepository->findWithoutFail($id);

        if (empty($adminUser)) {
            Flash::error('Admin User not found');

            return redirect(route('adminUsers.index'));
        }

        return view('admin_users.edit')->with('adminUser', $adminUser);
    }

    /**
     * Update the specified AdminUser in storage.
     *
     * @param  int              $id
     * @param UpdateAdminUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAdminUserRequest $request)
    {
        $adminUser = $this->adminUserRepository->findWithoutFail($id);

        if (empty($adminUser)) {
            Flash::error('Admin User not found');

            return redirect(route('adminUsers.index'));
        }

        $adminUser = $this->adminUserRepository->update($request->all(), $id);

        Flash::success('Admin User updated successfully.');

        return redirect(route('adminUsers.index'));
    }

    /**
     * Remove the specified AdminUser from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $adminUser = $this->adminUserRepository->findWithoutFail($id);

        if (empty($adminUser)) {
            Flash::error('Admin User not found');

            return redirect(route('adminUsers.index'));
        }

        $this->adminUserRepository->delete($id);

        Flash::success('Admin User deleted successfully.');

        return redirect(route('adminUsers.index'));
    }
}
