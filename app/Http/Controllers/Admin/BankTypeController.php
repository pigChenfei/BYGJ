<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\BankTypeDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateBankTypeRequest;
use App\Http\Requests\Admin\UpdateBankTypeRequest;
use App\Repositories\Admin\BankTypeRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class BankTypeController extends AppBaseController
{
    /** @var  BankTypeRepository */
    private $bankTypeRepository;

    public function __construct(BankTypeRepository $bankTypeRepo)
    {
        $this->bankTypeRepository = $bankTypeRepo;
    }

    /**
     * Display a listing of the BankType.
     *
     * @param BankTypeDataTable $bankTypeDataTable
     * @return Response
     */
    public function index(BankTypeDataTable $bankTypeDataTable)
    {
        return $bankTypeDataTable->render('bank_types.index');
    }

    /**
     * Show the form for creating a new BankType.
     *
     * @return Response
     */
    public function create()
    {
        return view('bank_types.create');
    }

    /**
     * Store a newly created BankType in storage.
     *
     * @param CreateBankTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateBankTypeRequest $request)
    {
        $input = $request->all();

        $bankType = $this->bankTypeRepository->create($input);

        Flash::success('Bank Type saved successfully.');

        return redirect(route('bankTypes.index'));
    }

    /**
     * Display the specified BankType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $bankType = $this->bankTypeRepository->findWithoutFail($id);

        if (empty($bankType)) {
            Flash::error('Bank Type not found');

            return redirect(route('bankTypes.index'));
        }

        return view('bank_types.show')->with('bankType', $bankType);
    }

    /**
     * Show the form for editing the specified BankType.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $bankType = $this->bankTypeRepository->findWithoutFail($id);

        if (empty($bankType)) {
            Flash::error('Bank Type not found');

            return redirect(route('bankTypes.index'));
        }

        return view('bank_types.edit')->with('bankType', $bankType);
    }

    /**
     * Update the specified BankType in storage.
     *
     * @param  int              $id
     * @param UpdateBankTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBankTypeRequest $request)
    {
        $bankType = $this->bankTypeRepository->findWithoutFail($id);

        if (empty($bankType)) {
            Flash::error('Bank Type not found');

            return redirect(route('bankTypes.index'));
        }

        $bankType = $this->bankTypeRepository->update($request->all(), $id);

        Flash::success('Bank Type updated successfully.');

        return redirect(route('bankTypes.index'));
    }

    /**
     * Remove the specified BankType from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $bankType = $this->bankTypeRepository->findWithoutFail($id);

        if (empty($bankType)) {
            Flash::error('Bank Type not found');

            return redirect(route('bankTypes.index'));
        }

        $this->bankTypeRepository->delete($id);

        Flash::success('Bank Type deleted successfully.');

        return redirect(route('bankTypes.index'));
    }
}
