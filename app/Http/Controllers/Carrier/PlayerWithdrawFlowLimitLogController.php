<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\PlayerWithdrawFlowLimitLogDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreatePlayerWithdrawFlowLimitLogRequest;
use App\Http\Requests\Carrier\UpdatePlayerWithdrawFlowLimitLogRequest;
use App\Repositories\Carrier\PlayerWithdrawFlowLimitLogRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Illuminate\Http\Request;
use App\Models\Log\PlayerWithdrawFlowLimitLog;
use App\Models\Log\PlayerWithdrawFlowLimitLogDetail;

class PlayerWithdrawFlowLimitLogController extends AppBaseController
{
    /** @var  PlayerWithdrawFlowLimitLogRepository */
    private $playerWithdrawFlowLimitLogRepository;

    public function __construct(PlayerWithdrawFlowLimitLogRepository $playerWithdrawFlowLimitLogRepo)
    {
        $this->playerWithdrawFlowLimitLogRepository = $playerWithdrawFlowLimitLogRepo;
    }

    /**
     * Display a listing of the PlayerWithdrawFlowLimitLog.
     *
     * @param PlayerWithdrawFlowLimitLogDataTable $playerWithdrawFlowLimitLogDataTable
     * @return Response
     */
    public function index(PlayerWithdrawFlowLimitLogDataTable $playerWithdrawFlowLimitLogDataTable)
    {
        return $playerWithdrawFlowLimitLogDataTable->render('Carrier.player_withdraw_flow_limit_logs.index');
    }

    /**
     * Show the form for creating a new PlayerWithdrawFlowLimitLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.player_withdraw_flow_limit_logs.create');
    }

    /**
     * Store a newly created PlayerWithdrawFlowLimitLog in storage.
     *
     * @param CreatePlayerWithdrawFlowLimitLogRequest $request
     *
     * @return Response
     */
    public function store(CreatePlayerWithdrawFlowLimitLogRequest $request)
    {
        $input = $request->all();
        $this->playerWithdrawFlowLimitLogRepository->create($input);
        return redirect(route('playerWithdrawFlowLimitLogs.index'));
    }

    /**
     * Display the specified PlayerWithdrawFlowLimitLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $playerWithdrawFlowLimitLog = $this->playerWithdrawFlowLimitLogRepository->findWithoutFail($id);
        if (empty($playerWithdrawFlowLimitLog)) {
            return redirect(route('playerWithdrawFlowLimitLogs.index'));
        }
        return view('Carrier.player_withdraw_flow_limit_logs.show')->with('playerWithdrawFlowLimitLog', $playerWithdrawFlowLimitLog);
    }

    /**
     * Show the form for editing the specified PlayerWithdrawFlowLimitLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $playerWithdrawFlowLimitLog = $this->playerWithdrawFlowLimitLogRepository->findWithoutFail($id);
        if (empty($playerWithdrawFlowLimitLog)) {
            return redirect(route('playerWithdrawFlowLimitLogs.index'));
        }
        return view('Carrier.player_withdraw_flow_limit_logs.edit')->with('playerWithdrawFlowLimitLog', $playerWithdrawFlowLimitLog);
    }

    /**
     * Update the specified PlayerWithdrawFlowLimitLog in storage.
     *
     * @param  int              $id
     * @param UpdatePlayerWithdrawFlowLimitLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlayerWithdrawFlowLimitLogRequest $request)
    {
        $playerWithdrawFlowLimitLog = $this->playerWithdrawFlowLimitLogRepository->findWithoutFail($id);
        if (empty($playerWithdrawFlowLimitLog)) {
            return redirect(route('playerWithdrawFlowLimitLogs.index'));
        }
        $this->playerWithdrawFlowLimitLogRepository->update($request->all(), $id);
        return redirect(route('playerWithdrawFlowLimitLogs.index'));
    }




    /**
     * Remove the specified PlayerWithdrawFlowLimitLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $playerWithdrawFlowLimitLog = $this->playerWithdrawFlowLimitLogRepository->findWithoutFail($id);
        if (empty($playerWithdrawFlowLimitLog)) {
            return redirect(route('playerWithdrawFlowLimitLogs.index'));
        }
        $this->playerWithdrawFlowLimitLogRepository->delete($id);
        return redirect(route('playerWithdrawFlowLimitLogs.index'));
    }
    
    /**
     * 完成流水
     * @param Request $request
     */
    public function passCompleteFinished(Request $request){
        $this->validate($request,[
            'passType' => 'required|in:all,none',
            'logIds'   => 'required|array',
            'logIds.*' => 'integer'
        ],[
            'logIds.required' => '请选择需要处理的数据'
        ]);
        $logs = PlayerWithdrawFlowLimitLog::unsettledInIds($request->get('logIds'))->with(['player.agent.agentLevel'])->get();
        if (empty($logs) || $logs->count() == 0) {
            return $this->sendNotFoundResponse();
        }
        try{
            if($request->get('passType') == 'all'){
                $logs->each(function(PlayerWithdrawFlowLimitLog $log){
                    $log->is_finished = 1;
                    $log->update();
                });
            }else if($request->get('passType') == 'none'){
                $logs->each(function(PlayerWithdrawFlowLimitLog $log){
                    $log->is_finished = 0;
                    $log->complete_limit_amount = 0;
                    $log->update();
                    PlayerWithdrawFlowLimitLogDetail::where('withdraw_flow_limit_id',$log->id)->delete();
                });
            }
            return $this->sendSuccessResponse();
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
    }
}
