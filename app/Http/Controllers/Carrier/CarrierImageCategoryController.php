<?php

namespace App\Http\Controllers\Carrier;

use App\Http\Requests\Carrier\CreateCarrierImageCategoryRequest;
use App\Http\Requests\Carrier\UpdateCarrierImageCategoryRequest;
use App\Repositories\Carrier\CarrierImageCategoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CarrierImageCategoryController extends AppBaseController
{
    /** @var  CarrierImageCategoryRepository */
    private $carrierImageCategoryRepository;

    public function __construct(CarrierImageCategoryRepository $carrierImageCategoryRepo)
    {
        $this->carrierImageCategoryRepository = $carrierImageCategoryRepo;
    }

    /**
     * Display a listing of the CarrierImageCategory.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->carrierImageCategoryRepository->pushCriteria(new RequestCriteria($request));
        $carrierImageCategories = $this->carrierImageCategoryRepository->all();

        return view('Carrier.carrier_image_categories.index')
            ->with('carrierImageCategories', $carrierImageCategories);
    }

    /**
     * Show the form for creating a new CarrierImageCategory.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.carrier_image_categories.create');
    }

    /**
     * Store a newly created CarrierImageCategory in storage.
     *
     * @param CreateCarrierImageCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierImageCategoryRequest $request)
    {
        $input = $request->all();

        $input['carrier_id'] = \WinwinAuth::carrierUser()->carrier_id;

        $input['created_user_id'] = \WinwinAuth::carrierUser()->id;

        $this->carrierImageCategoryRepository->create($input);

        return $this->sendSuccessResponse($request);
    }

    /**
     * Display the specified CarrierImageCategory.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierImageCategory = $this->carrierImageCategoryRepository->findWithoutFail($id);

        if (empty($carrierImageCategory)) {
            Flash::error('Carrier Image Category not found');

            return redirect(route('carrierImageCategories.index'));
        }

        return view('Carrier.carrier_image_categories.show')->with('carrierImageCategory', $carrierImageCategory);
    }

    /**
     * Show the form for editing the specified CarrierImageCategory.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierImageCategory = $this->carrierImageCategoryRepository->findWithoutFail($id);

        if (empty($carrierImageCategory)) {
            Flash::error('Carrier Image Category not found');

            return redirect(route('carrierImageCategories.index'));
        }

        return view('Carrier.carrier_image_categories.edit')->with('carrierImageCategory', $carrierImageCategory);
    }

    /**
     * Update the specified CarrierImageCategory in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierImageCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierImageCategoryRequest $request)
    {
        $carrierImageCategory = $this->carrierImageCategoryRepository->findWithoutFail($id);

        if (empty($carrierImageCategory)) {

            return $this->sendNotFoundResponse();
        }

        $this->carrierImageCategoryRepository->update($request->all(), $id);

        return $this->sendSuccessResponse($request);
    }

    /**
     * Remove the specified CarrierImageCategory from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $carrierImageCategory = $this->carrierImageCategoryRepository->findWithoutFail($id);

        if (empty($carrierImageCategory)) {
            Flash::error('Carrier Image Category not found');

            return redirect(route('carrierImageCategories.index'));
        }

        $this->carrierImageCategoryRepository->delete($id);

        Flash::success('Carrier Image Category deleted successfully.');

        return redirect(route('carrierImageCategories.index'));
    }
}
