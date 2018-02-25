<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierAgentNewsDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateCarrierAgentNewsRequest;
use App\Http\Requests\Carrier\UpdateCarrierAgentNewsRequest;
use App\Jobs\SendNewsAll;
use App\Repositories\Carrier\CarrierAgentNewsRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\CarrierAgentUser;
use App\Models\AgentNews\AgentNewsRelation;

class CarrierAgentNewsController extends AppBaseController
{
    /** @var  CarrierAgentNewsRepository */
    private $carrierAgentNewsRepository;

    public function __construct(CarrierAgentNewsRepository $carrierAgentNewsRepo)
    {
        $this->carrierAgentNewsRepository = $carrierAgentNewsRepo;
    }

    /**
     * Display a listing of the CarrierAgentNews.
     *
     * @param CarrierAgentNewsDataTable $carrierAgentNewsDataTable
     * @return Response
     */
    public function index(CarrierAgentNewsDataTable $carrierAgentNewsDataTable)
    {
        return $carrierAgentNewsDataTable->render('Carrier.carrier_agent_news.index');
    }

    /**
     * Show the form for creating a new CarrierAgentNews.
     *
     * @return Response
     */
    public function create()
    {
        $agentUsers = CarrierAgentUser::where(['status'=>1,'is_default'=>0])->get();
        return view('Carrier.carrier_agent_news.create')->with(['agentUsers'=>$agentUsers]);
    }

    /**
     * Store a newly created CarrierAgentNews in storage.
     *
     * @param CreateCarrierAgentNewsRequest $request
     *
     * @return Response
     */
    public function store(CreateCarrierAgentNewsRequest $request)
    {
//        if($request->get('agent_user_id_json') == "[]")
//        {
//            return $this->sendErrorResponse('请选择代理用户！');
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
        $carrierAgentNews = $this->carrierAgentNewsRepository->create($input)->id;
        if (empty($carrierAgentNews)) {
            return $this->sendNotFoundResponse();
        }
        
        if($agentUserIdJsonData = $request->get('agent_user_id_json')){
            if ($agentUserIdJsonData == "[]"){
                $playerUserIdArray = CarrierAgentUser::active()->where('carrier_id',\WinwinAuth::carrierUser()->carrier_id)->pluck('id');
                dispatch(new SendNewsAll(\WinwinAuth::carrierUser()->carrier_id,$playerUserIdArray,'agent',$carrierAgentNews));
            }else{
            $agentUserIdArray = json_decode($agentUserIdJsonData,true);
            $agentUserIdArray = array_filter($agentUserIdArray,function ($element){
                return $element['selectedAgentPages'] && is_array($element['selectedAgentPages']);
            });
            
            foreach ($agentUserIdArray as $agents){
                foreach ($agents['selectedAgentPages'] as $key => $value) {
                    $carrierPlat = new AgentNewsRelation();
                    $carrierPlat->agent_news_id = $carrierAgentNews;
                    $carrierPlat->carrier_id = \WinwinAuth::carrierUser()->carrier_id;
                    $carrierPlat->agent_id = $value;
                    $carrierPlat->save();
                }
            }
        }}
        return $this->sendSuccessResponse(route('carrierAgentNews.index'));
    }

    /**
     * Display the specified CarrierAgentNews.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierAgentNews = $this->carrierAgentNewsRepository->findWithoutFail($id);

        if (empty($carrierAgentNews)) {
            Flash::error('Carrier Agent News not found');

            return redirect(route('carrierAgentNews.index'));
        }

        return view('carrier_agent_news.show')->with('carrierAgentNews', $carrierAgentNews);
    }

    /**
     * Show the form for editing the specified CarrierAgentNews.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierAgentNews = $this->carrierAgentNewsRepository->findWithoutFail($id);

        if (empty($carrierAgentNews)) {
            Flash::error('Carrier Agent News not found');

            return redirect(route('carrierAgentNews.index'));
        }

        return view('carrier_agent_news.edit')->with('carrierAgentNews', $carrierAgentNews);
    }

    /**
     * Update the specified CarrierAgentNews in storage.
     *
     * @param  int              $id
     * @param UpdateCarrierAgentNewsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCarrierAgentNewsRequest $request)
    {
        $carrierAgentNews = $this->carrierAgentNewsRepository->findWithoutFail($id);

        if (empty($carrierAgentNews)) {
            Flash::error('Carrier Agent News not found');

            return redirect(route('carrierAgentNews.index'));
        }

        $carrierAgentNews = $this->carrierAgentNewsRepository->update($request->all(), $id);

        Flash::success('Carrier Agent News updated successfully.');

        return redirect(route('carrierAgentNews.index'));
    }

    /**
     * Remove the specified CarrierAgentNews from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $carrierAgentNews = $this->carrierAgentNewsRepository->findWithoutFail($id);

        if (empty($carrierAgentNews)) {
            Flash::error('Carrier Agent News not found');

            return redirect(route('carrierAgentNews.index'));
        }

        $this->carrierAgentNewsRepository->delete($id);

        Flash::success('Carrier Agent News deleted successfully.');

        return redirect(route('carrierAgentNews.index'));
    }
}
