<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierPlayerNewsDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierPlayerNewsRequest;
use App\Http\Requests\Carrier\UpdateCarrierPlayerNewsRequest;
use App\Jobs\SendNewsAll;
use App\Repositories\Carrier\CarrierPlayerNewsRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\Player;
use App\Models\CarrierPlayerLevel;
use App\Models\PlayerNews\PlayerLevelNewsRelation;
use App\Models\PlayerNews\PlayerNewsRelation;

class CarrierPlayerNewsController extends AppBaseController
{
    /** @var  CarrierPlayerNewsLogRepository */
    private $carrierPlayerNewsRepository;

    public function __construct(CarrierPlayerNewsRepository $carrierPlayerNewsRepo)
    {
        $this->carrierPlayerNewsRepository = $carrierPlayerNewsRepo;
    }

    /**
     * Display a listing of the CarrierPlayerNewsLog.
     *
     * @param CarrierPlayerNewsLogDataTable $carrierPlayerNewsLogDataTable
     * @return Response
     */
    public function index(CarrierPlayerNewsDataTable $carrierPlayerNewsDataTable)
    {
        
        return $carrierPlayerNewsDataTable->render('Carrier.carrier_player_news.index');
    }

    /**
     * Show the form for creating a new CarrierPlayerNewsLog.
     *
     * @return Response
     */
    public function create()
    {
        $players = Player::where("carrier_id",\WinwinAuth::carrierUser()->carrier_id)->get();
        $playerLevels = CarrierPlayerLevel::where('status',1)->get();
        return view('Carrier.carrier_player_news.create')->with(['players'=>$players,'playerLevels'=>$playerLevels]);
    }

    /**
     * Store a newly created CarrierPlayerNews in storage.
     *
     * @param CreateCarrierPlayerNewRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierPlayerNewsRequest $request)
    {
//        if($request->get('player_user_id_json') == "[]")
//        {
//            return $this->sendErrorResponse('请选择会员！');
//        }
        
        if(!empty($request->get('title')))
        {
            $input['title'] = $request->get('title');
        }else{
            return $this->sendErrorResponse('信息标题不能为空！');
        }
        if(!empty($request->get('remark')))
        {
            $input['remark'] = $request->get('remark');
        }else{
            return $this->sendErrorResponse('信息内容不能为空！');
        }
        $input['carrier_id'] = \WinwinAuth::carrierUser()->carrier_id;
        $input['operator_reviewer_id'] = \WinwinAuth::carrierUser()->id;
        $carrierPlayerNews = $this->carrierPlayerNewsRepository->create($input)->id;
        if (empty($carrierPlayerNews)) {
            return $this->sendNotFoundResponse();
        }
        
        if($playerUserIdJsonData = $request->get('player_user_id_json')){
            if ($playerUserIdJsonData == "[]"){
                $playerUserIdArray = Player::active()->where('carrier_id',\WinwinAuth::carrierUser()->carrier_id)->pluck('player_id');

                dispatch(new SendNewsAll(\WinwinAuth::carrierUser()->carrier_id,$playerUserIdArray,'player',$carrierPlayerNews));
            }else{

                $playerUserIdArray = json_decode($playerUserIdJsonData,true);
                $playerUserIdArray = array_filter($playerUserIdArray,function ($element){
                    return $element['selectedPlayerPages'] && is_array($element['selectedPlayerPages']);
                });

                foreach ($playerUserIdArray as $players){
                    foreach ($players['selectedPlayerPages'] as $key => $value) {
                        $carrierPlat = new PlayerNewsRelation();
                        $carrierPlat->player_news_id = $carrierPlayerNews;
                        $carrierPlat->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                        $carrierPlat->player_id = $value;
                        $carrierPlat->save();
                    }
                }
            }
        }
        
//        if($playerleveljsonData = $request->get('player_level_json')){
//            $playerlevelArray = explode(",", $playerleveljsonData); 
//            foreach ($playerlevelArray as $key => $value){
//                $carrierPlayerlevel = new PlayerLevelNewsRelation();
//                $carrierPlayerlevel->player_news_id = $carrierPlayerNews;
//                $carrierPlayerlevel->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
//                $carrierPlayerlevel->player_level_id = $value;
//                $carrierPlayerlevel->save();
//            }
//        }
        return $this->sendSuccessResponse(route('carrierPlayerNews.index'));
    }

    /**
     * Display the specified CarrierPlayerNewsLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierPlayerNews = $this->carrierPlayerNewsRepository->findWithoutFail($id);

        if (empty($carrierPlayerNewsLog)) {
            Flash::error('Carrier Player News not found');

            return redirect(route('carrierPlayerNewss.index'));
        }

        return view('carrier_player_news.show')->with('carrierPlayerNews', $carrierPlayerNews);
    }

    /**
     * Show the form for editing the specified CarrierPlayerNewsLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierPlayerNews = $this->carrierPlayerNewsRepository->findWithoutFail($id);

        if (empty($carrierPlayerNews)) {
            Flash::error('Carrier Player News not found');

            return redirect(route('carrierPlayerNewss.index'));
        }

        return view('carrier_player_news.edit')->with('carrierPlayerNews', $carrierPlayerNews);
    }

    /**
     * Update the specified CarrierPlayerNewsLog in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierPlayerNewsLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierPlayerNewsRequest $request)
    {
        $carrierPlayerNews = $this->carrierPlayerNewsRepository->findWithoutFail($id);

        if (empty($carrierPlayerNews)) {
            Flash::error('Carrier Player News not found');

            return redirect(route('carrierPlayerNewss.index'));
        }

        $carrierPlayerNews = $this->carrierPlayerNewsRepository->update($request->all(), $id);

        Flash::success('Carrier Player News updated successfully.');

        return redirect(route('carrierPlayerNews.index'));
    }

    /**
     * Remove the specified CarrierPlayerNewsLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $carrierPlayerNews = $this->carrierPlayerNewsRepository->findWithoutFail($id);

        if (empty($carrierPlayerNewsLog)) {
            Flash::error('Carrier Player News not found');

            return redirect(route('carrierPlayerNewss.index'));
        }

        $this->carrierPlayerNewsRepository->delete($id);

        Flash::success('Carrier Player News deleted successfully.');

        return redirect(route('carrierPlayerNews.index'));
    }
}
