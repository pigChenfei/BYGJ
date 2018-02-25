<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\GameDataTable;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateGameRequest;
use App\Http\Requests\Admin\UpdateGameRequest;
use App\Models\Carrier;
use App\Models\Def\Game;
use App\Models\Def\GamePlat;
use App\Models\Def\MainGamePlat;
use App\Models\Map\CarrierGame;
use App\Models\Map\CarrierGamePlat;
use App\Repositories\Admin\GameRepository;
use Carbon\Carbon;
use Flash;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Response;

class GameController extends AppBaseController
{

    /** @var  GameRepository */
    private $gameRepository;

    public function __construct(GameRepository $gameRepo)
    {
        $this->gameRepository = $gameRepo;
    }

    /**
     * Display a listing of the Game.
     *
     * @param GameDataTable $gameDataTable
     * @return Response
     */
    public function index(GameDataTable $gameDataTable)
    {
        return $gameDataTable->render('Admin.games.index');
    }

    /**
     * Show the form for creating a new Game.
     *
     * @return Response
     */
    public function create()
    {
        $game_plat = GamePlat::with('mainGamePlat')->where('status', 1)->get();
        $game_mcategory_array = config('constants.game_mcategory');
        $game_lines_array = config('constants.game_lines');
        return view('Admin.games.create', compact('game_plat', 'game_mcategory_array', 'game_lines_array'));
    }

    /**
     * Store a newly created Game in storage.
     *
     * @param CreateGameRequest $request
     *
     * @return Response
     */
    public function store(CreateGameRequest $request)
    {
        $input = $request->only([
            'game_plat_id',
            'english_game_name',
            'game_name',
            'game_type',
            'game_kind',
            'sub_game_kind',
            'game_code',
            'game_lines',
            'game_mcategory',
            'return_award_rate',
            'status',
            'game_popularity',
            'is_recommend',
            'is_demo',
            'gold_pool',
            'flashcode',
            'is_wap'
        ]);
        if (empty($input['game_kind']))
            unset($input['game_kind']);
        if (empty($input['sub_game_kind']))
            unset($input['sub_game_kind']);
        if (empty($input['game_type']))
            unset($input['game_type']);
        if (empty($input['return_award_rate']))
            unset($input['return_award_rate']);
        if (empty($input['gold_pool']))
            unset($input['gold_pool']);
        if (empty($input['game_code']))
            unset($input['game_code']);
        
        $game_plat = explode('+', $input['game_plat_id']);
        
        $input['main_game_plat_id'] = $game_plat[1];
        
        $input['game_plat_id'] = $game_plat[0];
        
        if ($request->has('game_id')) {
            $info = $this->gameRepository->find($request->input('game_id'));
            if (! $info) {
                Flash::error('游戏修改失败');
                
                return redirect()->back();
            }
            if ($request->hasFile('game_icon_path_up')) {
                $input['game_icon_path'] = $request->file('game_icon_path_up')->store('app/img/admin/game', 'admin_game');
                @unlink(public_path($info->game_icon_path));
            }
            
            $info->update($input);
            
            Flash::success('游戏修改成功');
            
            return redirect(route('games.index'));
        } else {
            $input['game_icon_path'] = null;
            if (! is_null($request->file('game_icon_path'))) {
                $input['game_icon_path'] = $request->file('game_icon_path')->store('app/img/admin/game', 'admin_game');
            }
            
            $this->gameRepository->create($input);
            
            Flash::success('游戏添加成功');
            
            return redirect(route('games.index'));
        }
    }

    /**
     * Display the specified Game.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $game = $this->gameRepository->findWithoutFail($id);
        
        if (empty($game)) {
            Flash::error('Game not found');
            
            return redirect(route('games.index'));
        }
        $game_plat = GamePlat::where('status', 1)->get();
        $game_mcategory_array = config('constants.game_mcategory');
        $game_lines_array = config('constants.game_lines');
        return view('Admin.games.view', compact('game_plat', 'game_mcategory_array', 'game', 'game_lines_array'));
    }

    /**
     * Show the form for editing the specified Game.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $game = $this->gameRepository->findWithoutFail($id);
        
        if (empty($game)) {
            Flash::error('Game not found');
            
            return redirect(route('Admin.games.index'));
        }
        
        return view('Admin.games.edit')->with('game', $game);
    }

    /**
     * Update the specified Game in storage.
     *
     * @param int $id
     * @param UpdateGameRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGameRequest $request)
    {
        $game = $this->gameRepository->findWithoutFail($id);
        if (empty($game)) {
            return $this->sendNotFoundResponse();
        }
        $this->gameRepository->update($request->all(), $id);
        return $this->sendSuccessResponse();
    }

    /**
     * 更新游戏状态
     *
     * @param
     *            $gameId
     * @return mixed|Response
     */
    public function toggleGameStatus($gameId)
    {
        $game = $this->gameRepository->findWithoutFail($gameId);
        if (empty($game)) {
            return $this->sendNotFoundResponse();
        }
        $game->status = ! $game->status;
        $game->update();
        return $this->sendSuccessResponse();
    }

    public function showAssignCarriersModal(Request $request)
    {
        $this->validate($request, [
            'game_ids' => 'required|array',
            'game_ids.*' => 'integer'
        ], [], [
            'game_ids' => '游戏'
        ]);
        $games = Game::inIds($request->get('game_ids'))->get();
        if ($games->count() == 1) {
            $selectedCarriers = CarrierGame::byGameIds([
                $games->first()->game_id
            ])->with('carrier')
                ->get()
                ->map(function ($carrierGame) {
                return $carrierGame->carrier;
            });
        } else {
            $selectedCarriers = Collection::make([]);
        }
        $allCarriers = Carrier::all();
        return view('Admin.games.assign_carriers')->with('games', $games)
            ->with('allCarriers', $allCarriers)
            ->with('selectedCarriers', $selectedCarriers);
    }

    public function updateCarriersGames(Request $request)
    {
        $this->validate($request, [
            'game_ids' => 'required|array',
            'game_ids.*' => 'integer',
            'carrier_ids' => 'array',
            'carrier_ids.*' => 'integer'
        ]);
        
        foreach ($request->get('game_ids') as $id) {
            \DB::delete('delete from map_carrier_games where game_id=' . $id);
        }
        
        $insertGameCarrierIds = $request->get('carrier_ids') ?: [];
        
        try {
            \DB::transaction(function () use ($insertGameCarrierIds, $request) {
                
                if ($insertGameCarrierIds) {
                    foreach ($request->get('game_ids') as $gameId) {
                        $game = Game::findOrFail($gameId);
                        // 从性能上面考虑, 不得已用DB操作
                        \DB::table('map_carrier_games')->insert(array_map(function ($element) use ($gameId, $game) {
                            return [
                                'carrier_id' => $element,
                                'game_id' => $gameId,
                                'display_name' => $game->game_name,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ];
                        }, $insertGameCarrierIds));
                    }
                    // 设置运营商游戏平台数据
                    foreach ($insertGameCarrierIds as $carrierId) {
                        $carrierGamePlat = CarrierGamePlat::byCarrierId($carrierId)->get()
                            ->map(function ($element) {
                            return $element->game_plat_id;
                        })
                            ->toArray();
                        $gamePlat = CarrierGame::byCarrierIds([
                            $carrierId
                        ])->with('game.gamePlat')
                            ->get()
                            ->map(function ($element) {
                            return $element->game->gamePlat->game_plat_id;
                        })
                            ->unique()
                            ->toArray();
                        // dd($gamePlat,$carrierGamePlat);
                        $deleteGamePlats = array_diff($carrierGamePlat, $gamePlat);
                        $insertGamePlats = array_diff($gamePlat, $carrierGamePlat);
                        if ($insertGamePlats) {
                            foreach ($insertGamePlats as $insertGamePlat) {
                                $carrierGamePlat = new CarrierGamePlat();
                                $carrierGamePlat->carrier_id = $carrierId;
                                $carrierGamePlat->game_plat_id = $insertGamePlat;
                                $carrierGamePlat->save();
                            }
                        }
                        $deleteGamePlats && CarrierGamePlat::byCarrierId($carrierId)->byGamePlats($deleteGamePlats)->delete();
                    }
                }
            });
            return $this->sendSuccessResponse();
        } catch (\Exception $e) {
            throw $e;
            return $this->sendErrorResponse($e->getMessage());
        }
        // dd($oldGameCarriers,$carrierIds,$deleteGameCarrierIds,$insertGameCarrierIds);
    }

    /**
     * Remove the specified Game from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $game = $this->gameRepository->findWithoutFail($id);
        
        if (empty($game)) {
            Flash::error('Game not found');
            
            return redirect(route('Admin.games.index'));
        }
        
        $this->gameRepository->delete($id);
        
        Flash::success('Game deleted successfully.');
        
        return redirect(route('Admin.games.index'));
    }
}
