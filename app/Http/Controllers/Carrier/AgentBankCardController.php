<?php

namespace App\Http\Controllers\Carrier;

use App\Http\Requests\Carrier\CreateAgentBankCardRequest;
use App\Http\Requests\Carrier\UpdateAgentBankCardRequest;
use App\Repositories\Carrier\AgentBankCardRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AgentBankCardController extends AppBaseController
{
    /** @var  AgentBankCardRepository */
    private $agentBankCardRepository;

    public function __construct(AgentBankCardRepository $agentBankCardRepo)
    {
        $this->agentBankCardRepository = $agentBankCardRepo;
    }

    /**
     * Display a listing of the AgentBankCard.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->agentBankCardRepository->pushCriteria(new RequestCriteria($request));
        $agentBankCards = $this->agentBankCardRepository->all();

        return view('agent_bank_cards.index')
            ->with('agentBankCards', $agentBankCards);
    }

    /**
     * Show the form for creating a new AgentBankCard.
     *
     * @return Response
     */
    public function create()
    {
        return view('agent_bank_cards.create');
    }

    /**
     * Store a newly created AgentBankCard in storage.
     *
     * @param CreateAgentBankCardRequest $request
     *
     * @return Response
     */
    public function store(CreateAgentBankCardRequest $request)
    {
        $input = $request->all();

        $agentBankCard = $this->agentBankCardRepository->create($input);

        Flash::success('Agent Bank Card saved successfully.');

        return redirect(route('agentBankCards.index'));
    }

    /**
     * Display the specified AgentBankCard.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $agentBankCard = $this->agentBankCardRepository->findWithoutFail($id);

        if (empty($agentBankCard)) {
            Flash::error('Agent Bank Card not found');

            return redirect(route('agentBankCards.index'));
        }

        return view('agent_bank_cards.show')->with('agentBankCard', $agentBankCard);
    }

    /**
     * Show the form for editing the specified AgentBankCard.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $agentBankCard = $this->agentBankCardRepository->findWithoutFail($id);

        if (empty($agentBankCard)) {
            Flash::error('Agent Bank Card not found');

            return redirect(route('agentBankCards.index'));
        }

        return view('agent_bank_cards.edit')->with('agentBankCard', $agentBankCard);
    }

    /**
     * Update the specified AgentBankCard in storage.
     *
     * @param  int              $id
     * @param UpdateAgentBankCardRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAgentBankCardRequest $request)
    {
        $agentBankCard = $this->agentBankCardRepository->findWithoutFail($id);

        if (empty($agentBankCard)) {
            Flash::error('Agent Bank Card not found');

            return redirect(route('agentBankCards.index'));
        }

        $agentBankCard = $this->agentBankCardRepository->update($request->all(), $id);

        Flash::success('Agent Bank Card updated successfully.');

        return redirect(route('agentBankCards.index'));
    }

    /**
     * Remove the specified AgentBankCard from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $agentBankCard = $this->agentBankCardRepository->findWithoutFail($id);

        if (empty($agentBankCard)) {
            Flash::error('Agent Bank Card not found');

            return redirect(route('agentBankCards.index'));
        }

        $this->agentBankCardRepository->delete($id);

        Flash::success('Agent Bank Card deleted successfully.');

        return redirect(route('agentBankCards.index'));
    }
}
