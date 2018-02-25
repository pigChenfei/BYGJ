<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierImageDataTable;
use App\Http\Requests\Carrier\CreateCarrierImageRequest;
use App\Http\Requests\Carrier\UpdateCarrierImageRequest;
use App\Models\Image\CarrierImageCategory;
use App\Models\Map\CarrierGame;
use App\Repositories\Carrier\CarrierImageRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Response;

class CarrierImageController extends AppBaseController
{
    /** @var  CarrierImageRepository */
    private $carrierImageRepository;

    public function __construct(CarrierImageRepository $carrierImageRepo)
    {
        $this->carrierImageRepository = $carrierImageRepo;
    }

    /**
     * Display a listing of the CarrierImage.
     *
     * @param CarrierImageDataTable $carrierImageDataTable
     * @return mixed
     */
    public function index(CarrierImageDataTable $carrierImageDataTable)
    {
        $category = CarrierImageCategory::all();
        return $carrierImageDataTable->render('Carrier.carrier_images.index',['categories' => $category]);
    }

    /**
     * Show the form for creating a new CarrierImage.
     *
     * @return Response
     */
    public function create()
    {
        $category = CarrierImageCategory::all();
        $games = array(
            'parentOne'=>'真人娱乐',
            '/players.loginBBinHall/live'=>'BBIN真人',
            '/players.gameLauncher/SB/Sunbet_Lobby'=>'申博真人',
            '/players.loginPTGame/bal'=>'PT真人',
            '/players.launchItem/1172/1001'=>'MG真人',
            'parentTwo'=>'捕鱼游戏',
            '/players.joinElectronicGame/114'=>'捕鱼大师',
            '/players.joinElectronicGame/113'=>'捕鱼达人',
            'parentThree'=>'体育投注',
            '/players.loginOneWorkHall'=>'沙巴体育',
            '/players.loginBBinHall/ball'=>'BBIN体育',
            'parentFour'=>'彩票投注',
            '/players.loginVRHall'=>'VR彩票',
            '/players.loginBBinHall/Ltlottery'=>'BBIN彩票',
            'parentFive'=>'电子游艺',
        );
        $carrierGames = CarrierGame::open()->get();
        foreach ($carrierGames as $v){
            $games['/players.joinElectronicGame/'.$v->game_id] = $v->display_name;
        }
        return view('Carrier.carrier_images.create', compact('category', 'games'));
    }

    /**
     * Store a newly created CarrierImage in storage.
     *
     * @param CreateCarrierImageRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierImageRequest $request)
    {
        $input['url_type'] = $request->input('url_type');

        $input['url_link'] = $request->input('url_link');

        if ($input['url_type'] != 'undefined' && $input['url_type'] == 0){
            if (!strstr($input['url_link'], 'http://') && !strstr($input['url_link'], 'https://')){
                return $this->sendErrorResponse('图片外部链接地址格式不正确',403);
            }
        }
        if ($input['url_type'] == 'undefined'){
            unset($input['url_type']);
            unset($input['url_link']);
        }
        if ($request->file('file')){

            $file = $request->file('file');

            $fileName = md5($file->getRealPath().time());

            $path = $file->storeAs(\WinwinAuth::carrierUser()->carrier_id.'/images',$fileName.'.'.$file->getClientOriginalExtension(),'carrier');

            $input['image_path'] = $path;

            $input['image_size'] = $file->getSize();
        }else{
            $input['image_path'] = $request->input('image_path');
        }
        $input['image_category'] = $request->input('image_category');

        $input['carrier_id'] = \WinwinAuth::carrierUser()->carrier_id;

        $input['uploaded_user_id'] = \WinwinAuth::carrierUser()->id;

        if ($request->input('id')){
            $this->carrierImageRepository->update($input,$request->input('id'));
        }else{
            $this->carrierImageRepository->create($input);
        }

        return $this->sendSuccessResponse($request);
    }

    /**
     * Display the specified CarrierImage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierImage = $this->carrierImageRepository->findWithoutFail($id);

        if (empty($carrierImage)) {
            Flash::error('Carrier Image not found');

            return redirect(route('carrierImages.index'));
        }

        return view('Carrier.carrier_images.show')->with('carrierImage', $carrierImage);
    }

    /**
     * Show the form for editing the specified CarrierImage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierImage = $this->carrierImageRepository->findWithoutFail($id);

        if (empty($carrierImage)) {
            Flash::error('Carrier Image not found');

            return redirect(route('carrierImages.index'));
        }
        $category = CarrierImageCategory::all();
        $games = array(
            'parentOne'=>'真人娱乐',
            '/players.loginBBinHall/live'=>'BBIN真人',
            '/players.gameLauncher/SB/Sunbet_Lobby'=>'申博真人',
            '/players.loginPTGame/bal'=>'PT真人',
            '/players.launchItem/1172/1001'=>'MG真人',
            'parentTwo'=>'捕鱼游戏',
            '/players.joinElectronicGame/114'=>'捕鱼大师',
            '/players.joinElectronicGame/113'=>'捕鱼达人',
            'parentThree'=>'体育投注',
            '/players.loginOneWorkHall'=>'沙巴体育',
            '/players.loginBBinHall/ball'=>'BBIN体育',
            'parentFour'=>'彩票投注',
            '/players.loginVRHall'=>'VR彩票',
            '/players.loginBBinHall/Ltlottery'=>'BBIN彩票',
            'parentFive'=>'电子游艺',
        );
        $carrierGames = CarrierGame::open()->get();
        foreach ($carrierGames as $v){
            $games['/players.joinElectronicGame/'.$v->game_id] = $v->display_name;
        }
        return view('Carrier.carrier_images.edit', compact('category', 'games','carrierImage'));
    }

    /**
     * Update the specified CarrierImage in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierImageRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierImageRequest $request)
    {
        $carrierImage = $this->carrierImageRepository->findWithoutFail($id);

        if (empty($carrierImage)) {
            Flash::error('Carrier Image not found');

            return redirect(route('carrierImages.index'));
        }

        $carrierImage = $this->carrierImageRepository->update($request->all(), $id);

        Flash::success('Carrier Image updated successfully.');

        return redirect(route('carrierImages.index'));
    }

    /**
     * Remove the specified CarrierImage from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id,Request $request)
    {
        $carrierImage = $this->carrierImageRepository->findWithoutFail($id);

        if (empty($carrierImage)) {

            return $this->sendNotFoundResponse();
        }

        $path = $carrierImage->image_path;

        $this->carrierImageRepository->delete($id);

        \Storage::disk('carrier')->delete($path);
        Flash::success('操作成功');
        return $this->sendSuccessResponse(route('carrierImages.index'));

    }


    public function showUploadImageModal(){

        $category = CarrierImageCategory::all();
        $games = array(
            'parentOne'=>'真人娱乐',
            '/players.loginBBinHall/live'=>'BBIN真人',
            '/players.gameLauncher/SB/Sunbet_Lobby'=>'申博真人',
            '/players.loginPTGame/bal'=>'PT真人',
            '/players.launchItem/1172/1001'=>'MG真人',
            'parentTwo'=>'捕鱼游戏',
            '/players.joinElectronicGame/114'=>'捕鱼大师',
            '/players.joinElectronicGame/113'=>'捕鱼达人',
            'parentThree'=>'体育投注',
            '/players.loginOneWorkHall'=>'沙巴体育',
            '/players.loginBBinHall/ball'=>'BBIN体育',
            'parentFour'=>'彩票投注',
            '/players.loginVRHall'=>'VR彩票',
            '/players.loginBBinHall/Ltlottery'=>'BBIN彩票',
            'parentFive'=>'电子游艺',
        );
        $carrierGames = CarrierGame::open()->get();
        foreach ($carrierGames as $v){
            $games['/players.joinElectronicGame/'.$v->game_id] = $v->display_name;
        }
        return view('Carrier.carrier_images.create', compact('category', 'games'));
    }

}
