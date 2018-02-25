<?php
namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\CarrierAgentDepositPayLogDataTable;
use App\Http\Requests\Carrier\CreateCarrierAgentDepositPayLogRequest;
use App\Http\Requests\Carrier\UpdateCarrierAgentDepositPayLogRequest;
use App\Repositories\Carrier\CarrierAgentDepositPayLogRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use App\DataTables\Carrier\CarrierAgentDepositVerifyDataTable;

class CarrierAgentDepositVerifyController extends AppBaseController
{

    /** @var  CarrierAgentDepositPayLogRepository */
    private $carrierAgentDepositPayLogRepository;

    public function __construct(CarrierAgentDepositPayLogRepository $carrierAgentDepositPayLogRepo)
    {
        $this->carrierAgentDepositPayLogRepository = $carrierAgentDepositPayLogRepo;
    }

    /**
     * Display a listing of the CarrierAgentDepositPayLog.
     *
     * @param Request $request            
     * @return Response
     */
    public function index(CarrierAgentDepositVerifyDataTable $carrierAgentDepositVerifyDataTable)
    {
        return $carrierAgentDepositVerifyDataTable->render('Carrier.carrier_agent_deposit_pay_logs.index');
    }

    /**
     * Show the form for creating a new CarrierAgentDepositPayLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('carrier_agent_deposit_pay_logs.create');
    }

    /**
     * Store a newly created CarrierAgentDepositPayLog in storage.
     *
     * @param CreateCarrierAgentDepositPayLogRequest $request            
     *
     * @return Response
     */
    public function store(CreateCarrierAgentDepositPayLogRequest $request)
    {
        $input = $request->all();
        
        $carrierAgentDepositPayLog = $this->carrierAgentDepositPayLogRepository->create($input);
        
        Flash::success('Carrier Agent Deposit Pay Log saved successfully.');
        
        return redirect(route('carrierAgentDepositPayLogs.index'));
    }

    /**
     * Display the specified CarrierAgentDepositPayLog.
     *
     * @param int $id            
     *
     * @return Response
     */
    public function show($id)
    {
        $carrierAgentDepositPayLog = $this->carrierAgentDepositPayLogRepository->findWithoutFail($id);
        
        if (empty($carrierAgentDepositPayLog)) {
            Flash::error('Carrier Agent Deposit Pay Log not found');
            
            return redirect(route('carrierAgentDepositPayLogs.index'));
        }
        
        return view('carrier_agent_deposit_pay_logs.show')->with('carrierAgentDepositPayLog', $carrierAgentDepositPayLog);
    }

    /**
     * Show the form for editing the specified CarrierAgentDepositPayLog.
     *
     * @param int $id            
     *
     * @return Response
     */
    public function edit($id)
    {
        $carrierAgentDepositPayLog = $this->carrierAgentDepositPayLogRepository->findWithoutFail($id);
        
        if (empty($carrierAgentDepositPayLog)) {
            Flash::error('Carrier Agent Deposit Pay Log not found');
            
            return redirect(route('carrierAgentDepositPayLogs.index'));
        }
        
        return view('carrier_agent_deposit_pay_logs.edit')->with('carrierAgentDepositPayLog', $carrierAgentDepositPayLog);
    }

    /**
     * Update the specified CarrierAgentDepositPayLog in storage.
     *
     * @param int $id            
     * @param UpdateCarrierAgentDepositPayLogRequest $request            
     *
     * @return Response
     */
    public function update($id, UpdateCarrierAgentDepositPayLogRequest $request)
    {
        $carrierAgentDepositPayLog = $this->carrierAgentDepositPayLogRepository->findWithoutFail($id);
        
        if (empty($carrierAgentDepositPayLog)) {
            Flash::error('Carrier Agent Deposit Pay Log not found');
            
            return redirect(route('carrierAgentDepositPayLogs.index'));
        }
        
        $carrierAgentDepositPayLog = $this->carrierAgentDepositPayLogRepository->update($request->all(), $id);
        
        Flash::success('Carrier Agent Deposit Pay Log updated successfully.');
        
        return redirect(route('carrierAgentDepositPayLogs.index'));
    }

    /**
     * Remove the specified CarrierAgentDepositPayLog from storage.
     *
     * @param int $id            
     *
     * @return Response
     */
    public function destroy($id)
    {
        $carrierAgentDepositPayLog = $this->carrierAgentDepositPayLogRepository->findWithoutFail($id);
        
        if (empty($carrierAgentDepositPayLog)) {
            Flash::error('Carrier Agent Deposit Pay Log not found');
            
            return redirect(route('carrierAgentDepositPayLogs.index'));
        }
        
        $this->carrierAgentDepositPayLogRepository->delete($id);
        
        Flash::success('Carrier Agent Deposit Pay Log deleted successfully.');
        
        return redirect(route('carrierAgentDepositPayLogs.index'));
    }
}
