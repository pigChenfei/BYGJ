<?php
namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierWinLoseStasticsDataTable;
use App\Http\Requests\Carrier;
use App\Repositories\Carrier\CarrierWinLoseStasticsRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\DataTables\Carrier\AgentWinLoseStasticsDataTable;

class AgentWinLoseStasticsController extends AppBaseController
{

    /** @var  CarrierWinLoseStasticsRepository */
    private $agentWinLoseStasticsRepository;

    public function __construct(AgentWinLoseStasticsDataTable $carrierWinLoseStasticsRepo)
    {
        $this->agentWinLoseStasticsRepository = $carrierWinLoseStasticsRepo;
    }

    /**
     * Display a listing of the CarrierWinLoseStastics.
     *
     * @param AgentWinLoseStasticsDataTable $carrierWinLoseStasticsDataTable            
     * @return Response
     */
    public function index(AgentWinLoseStasticsDataTable $agentWinLoseStasticsRepo)
    {
        return $agentWinLoseStasticsRepo->render('Carrier.agent_win_lose_stastics.index');
    }

    /**
     * Remove the specified CarrierWinLoseStastics from storage.
     *
     * @param int $id            
     *
     * @return Response
     */
    public function destroy($id)
    {
        $carrierWinLoseStastics = $this->agentWinLoseStasticsRepository->findWithoutFail($id);
        
        if (empty($carrierWinLoseStastics)) {
            Flash::error('Agent Win Lose Stastics not found');
            
            return redirect(route('agentWinLoseStastics.index'));
        }
        
        $this->agentWinLoseStasticsRepository->delete($id);
        
        Flash::success('Agent Win Lose Stastics deleted successfully.');
        
        return redirect(route('agentWinLoseStastics.index'));
    }
}
