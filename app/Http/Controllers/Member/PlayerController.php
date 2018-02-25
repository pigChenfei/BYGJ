<?php

namespace App\Http\Controllers\Member;

use App\DataTables\Member\PlayerDataTable;
use App\Http\Requests\Member;
use App\Http\Requests\Member\CreatePlayerRequest;
use App\Http\Requests\Member\UpdatePlayerRequest;
use App\Repositories\Member\PlayerRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class PlayerController extends AppBaseController
{
    /** @var  PlayerRepository */
    private $playerRepository;

    public function __construct(PlayerRepository $playerRepo)
    {
        $this->playerRepository = $playerRepo;
    }

    /**
     * Display a listing of the Player.
     *
     * @param PlayerDataTable $playerDataTable
     * @return Response
     */
    public function index(PlayerDataTable $playerDataTable)
    {
        return $playerDataTable->render('Member.players.index');
    }

    /**
     * Show the form for creating a new Player.
     *
     * @return Response
     */
    public function create()
    {
        return view('players.create');
    }

    /**
     * Store a newly created Player in storage.
     *
     * @param CreatePlayerRequest $request
     *
     * @return Response
     */
    public function store(CreatePlayerRequest $request)
    {
        $input = $request->all();

        $player = $this->playerRepository->create($input);

        Flash::success('Player saved successfully.');

        return redirect(route('players.index'));
    }

    /**
     * Display the specified Player.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $player = $this->playerRepository->findWithoutFail($id);

        if (empty($player)) {
            Flash::error('Player not found');

            return redirect(route('players.index'));
        }

        return view('players.show')->with('player', $player);
    }

    /**
     * Show the form for editing the specified Player.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $player = $this->playerRepository->findWithoutFail($id);

        if (empty($player)) {
            Flash::error('Player not found');

            return redirect(route('players.index'));
        }

        return view('players.edit')->with('player', $player);
    }

    /**
     * Update the specified Player in storage.
     *
     * @param  int              $id
     * @param UpdatePlayerRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlayerRequest $request)
    {
        $player = $this->playerRepository->findWithoutFail($id);

        if (empty($player)) {
            Flash::error('Player not found');

            return redirect(route('players.index'));
        }

        $player = $this->playerRepository->update($request->all(), $id);

        Flash::success('Player updated successfully.');

        return redirect(route('players.index'));
    }

    /**
     * Remove the specified Player from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $player = $this->playerRepository->findWithoutFail($id);

        if (empty($player)) {
            Flash::error('Player not found');

            return redirect(route('players.index'));
        }

        $this->playerRepository->delete($id);

        Flash::success('Player deleted successfully.');

        return redirect(route('players.index'));
    }
}
