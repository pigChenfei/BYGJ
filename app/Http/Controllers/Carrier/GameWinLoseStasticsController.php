<?php

namespace App\Http\Controllers\Carrier;

use App\DataTables\Carrier\GameWinLoseStasticsDataTable;
use App\Http\Requests\Carrier;
use App\Http\Requests\Carrier\CreateGameWinLoseStasticsRequest;
use App\Http\Requests\Carrier\UpdateGameWinLoseStasticsRequest;
use App\Repositories\Carrier\GameWinLoseStasticsRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class GameWinLoseStasticsController extends AppBaseController
{
    /** @var  GameWinLoseStasticsRepository */
    private $gameWinLoseStasticsRepository;

    public function __construct(GameWinLoseStasticsRepository $gameWinLoseStasticsRepo)
    {
        $this->gameWinLoseStasticsRepository = $gameWinLoseStasticsRepo;
    }

    /**
     * Display a listing of the GameWinLoseStastics.
     *
     * @param GameWinLoseStasticsDataTable $gameWinLoseStasticsDataTable
     * @return Response
     */
    public function index(GameWinLoseStasticsDataTable $gameWinLoseStasticsDataTable)
    {
        return $gameWinLoseStasticsDataTable->render('Carrier.game_win_lose_stastics.index');
    }

    /**
     * Show the form for creating a new GameWinLoseStastics.
     *
     * @return Response
     */
    public function create()
    {
        return view('Carrier.game_win_lose_stastics.create');
    }

    /**
     * Store a newly created GameWinLoseStastics in storage.
     *
     * @param CreateGameWinLoseStasticsRequest $request
     *
     * @return Response
     */
    public function store(CreateGameWinLoseStasticsRequest $request)
    {
        $input = $request->all();

        $gameWinLoseStastics = $this->gameWinLoseStasticsRepository->create($input);

        Flash::success('Game Win Lose Stastics saved successfully.');

        return redirect(route('gameWinLoseStastics.index'));
    }

    /**
     * Display the specified GameWinLoseStastics.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $gameWinLoseStastics = $this->gameWinLoseStasticsRepository->findWithoutFail($id);

        if (empty($gameWinLoseStastics)) {
            Flash::error('Game Win Lose Stastics not found');

            return redirect(route('gameWinLoseStastics.index'));
        }

        return view('Carrier.game_win_lose_stastics.show')->with('gameWinLoseStastics', $gameWinLoseStastics);
    }

    /**
     * Show the form for editing the specified GameWinLoseStastics.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $gameWinLoseStastics = $this->gameWinLoseStasticsRepository->findWithoutFail($id);

        if (empty($gameWinLoseStastics)) {
            Flash::error('Game Win Lose Stastics not found');

            return redirect(route('gameWinLoseStastics.index'));
        }

        return view('Carrier.game_win_lose_stastics.edit')->with('gameWinLoseStastics', $gameWinLoseStastics);
    }

    /**
     * Update the specified GameWinLoseStastics in storage.
     *
     * @param  int              $id
     * @param UpdateGameWinLoseStasticsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGameWinLoseStasticsRequest $request)
    {
        $gameWinLoseStastics = $this->gameWinLoseStasticsRepository->findWithoutFail($id);

        if (empty($gameWinLoseStastics)) {
            Flash::error('Game Win Lose Stastics not found');

            return redirect(route('gameWinLoseStastics.index'));
        }

        $gameWinLoseStastics = $this->gameWinLoseStasticsRepository->update($request->all(), $id);

        Flash::success('Game Win Lose Stastics updated successfully.');

        return redirect(route('gameWinLoseStastics.index'));
    }

    /**
     * Remove the specified GameWinLoseStastics from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $gameWinLoseStastics = $this->gameWinLoseStasticsRepository->findWithoutFail($id);

        if (empty($gameWinLoseStastics)) {
            Flash::error('Game Win Lose Stastics not found');

            return redirect(route('gameWinLoseStastics.index'));
        }

        $this->gameWinLoseStasticsRepository->delete($id);

        Flash::success('Game Win Lose Stastics deleted successfully.');

        return redirect(route('gameWinLoseStastics.index'));
    }
}
