<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\PlayerBetFlowLogDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreatePlayerBetFlowLogRequest;
use App\Http\Requests\Carrier\UpdatePlayerBetFlowLogRequest;
use App\Repositories\Carrier\PlayerBetFlowLogRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\Log\PlayerBetFlowLog;
use Illuminate\Http\Request;

class PlayerBetFlowLogController extends AppBaseController
{
    /** @var  PlayerBetFlowLogRepository */
    private $playerBetFlowLogRepository;

    public function __construct(PlayerBetFlowLogRepository $playerBetFlowLogRepo)
    {
        $this->playerBetFlowLogRepository = $playerBetFlowLogRepo;
    }

    /**
     * Display a listing of the PlayerBetFlowLog.
     *
     * @param PlayerBetFlowLogDataTable $playerBetFlowLogDataTable
     * @return Response
     */
    public function index(PlayerBetFlowLogDataTable $playerBetFlowLogDataTable)
    {
        return $playerBetFlowLogDataTable->render('Carrier.player_bet_flow_logs.index');
    }

    /**
     * Show the form for creating a new PlayerBetFlowLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.player_bet_flow_logs.create');
    }

    /**
     * Store a newly created PlayerBetFlowLog in storage.
     *
     * @param CreatePlayerBetFlowLogRequest $request
     *
     * @return Response
     */
    public function store(CreatePlayerBetFlowLogRequest $request)
    {
        $input = $request->all();

        $playerBetFlowLog = $this->playerBetFlowLogRepository->create($input);

        Flash::success('Player Bet Flow Log saved successfully.');

        return redirect(route('playerBetFlowLogs.index'));
    }

    /**
     * Display the specified PlayerBetFlowLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $playerBetFlowLog = $this->playerBetFlowLogRepository->findWithoutFail($id);

        if (empty($playerBetFlowLog)) {
            Flash::error('Player Bet Flow Log not found');

            return redirect(route('playerBetFlowLogs.index'));
        }

        return view('Carrier.player_bet_flow_logs.show')->with('playerBetFlowLog', $playerBetFlowLog);
    }

    /**
     * Show the form for editing the specified PlayerBetFlowLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $playerBetFlowLog = $this->playerBetFlowLogRepository->findWithoutFail($id);

        if (empty($playerBetFlowLog)) {
            Flash::error('Player Bet Flow Log not found');

            return redirect(route('playerBetFlowLogs.index'));
        }

        return view('Carrier.player_bet_flow_logs.edit')->with('playerBetFlowLog', $playerBetFlowLog);
    }

    /**
     * Update the specified PlayerBetFlowLog in storage.
     *
     * @param  int              $id
     * @param UpdatePlayerBetFlowLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlayerBetFlowLogRequest $request)
    {
        $playerBetFlowLog = $this->playerBetFlowLogRepository->findWithoutFail($id);

        if (empty($playerBetFlowLog)) {
            Flash::error('Player Bet Flow Log not found');

            return redirect(route('playerBetFlowLogs.index'));
        }

        $playerBetFlowLog = $this->playerBetFlowLogRepository->update($request->all(), $id);

        Flash::success('Player Bet Flow Log updated successfully.');

        return redirect(route('playerBetFlowLogs.index'));
    }

    /**
     * Remove the specified PlayerBetFlowLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $playerBetFlowLog = $this->playerBetFlowLogRepository->findWithoutFail($id);

        if (empty($playerBetFlowLog)) {
            Flash::error('Player Bet Flow Log not found');

            return redirect(route('playerBetFlowLogs.index'));
        }

        $this->playerBetFlowLogRepository->delete($id);

        Flash::success('Player Bet Flow Log deleted successfully.');

        return redirect(route('playerBetFlowLogs.index'));
    }
    /**
     * 设为有效/无效
     * @param Request $request
     */
    public function passBetFlowAvailable(Request $request){
        $this->validate($request,[
            'passType' => 'required|in:all,none',
            'logIds'   => 'required|array',
            'logIds.*' => 'integer'
        ],[
            'logIds.required' => '请选择需要处理的数据'
        ]);
        $logs = PlayerBetFlowLog::unsettledInIds($request->get('logIds'))->with(['player.agent.agentLevel'])->get();
        if (empty($logs) || $logs->count() == 0) {
            return $this->sendNotFoundResponse();
        }
        try{
            if($request->get('passType') == 'all'){
                $logs->each(function(PlayerBetFlowLog $log){
                    $log->bet_flow_available = 1;
                    $log->update();
                });
            }else if($request->get('passType') == 'none'){
                $logs->each(function(PlayerBetFlowLog $log){
                    $log->bet_flow_available = 0;
                    $log->update();
                });
            }
            return $this->sendSuccessResponse();
        }catch (\Exception $e){
            return $this->sendErrorResponse($e->getMessage());
        }
    }
    
}
