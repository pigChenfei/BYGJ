<?php
namespace App\Http\Controllers\Carrier;

use App\Http\Controllers\AppBaseController;
use App\DataTables\Carrier\PlayerTransferUnknownProcessDataTable;
use App\Repositories\Carrier\PlayerTransferUnknownProcessRepository;
use App\Models\PlayerTransfer;
use App\Vendor\Game\BBin;
use App\Vendor\Game\Sunbet;
use App\Vendor\Game\MG;
use App\Vendor\Game\OneWorks;
use App\Models\PlayerGameAccount;
use Illuminate\Container\Container;
use App\Facades\GameServiceFacade;

class PlayerTransferUnknownProcessController extends AppBaseController
{

    private $playerTransferUnknownProcessRepository;

    public function __construct(PlayerTransferUnknownProcessRepository $playerTransferUnknownProcessRepo)
    {
        $this->playerTransferUnknownProcessRepository = $playerTransferUnknownProcessRepo;
    }

    /**
     * Display a listing of the PlayerWithdrawFlowLimitLog.
     *
     * @param PlayerWithdrawFlowLimitLogDataTable $playerWithdrawFlowLimitLogDataTable
     * @return Response
     */
    public function index(PlayerTransferUnknownProcessDataTable $playerTransferUnknownProcessDataTable)
    {
        return $playerTransferUnknownProcessDataTable->render('Carrier.player_transfer_unknown_process.index');
    }

    /**
     * 自动检查
     *
     * @param int $id
     */
    public function check($id)
    {
        if (empty($id)) {
            return $this->sendNotFoundResponse();
        }
        $playTransfer = PlayerTransfer::with([
            'player',
            'mainGamePlat',
            'carrier',
            'carrierOperator'
        ])->transferUnknown()
            ->where('id', $id)
            ->first();
        if (empty($playTransfer)) {
            return $this->sendNotFoundResponse();
        }
        $message = '';
        if (is_null($playTransfer->mainGamePlat)) {
            $message = '<span style="color:red">请将上述信息发给博赢国际客服</span>';
        }
        $account = PlayerGameAccount::where([
            'player_id' => $playTransfer->player_id,
            'main_game_plat_id' => $playTransfer->main_game_plats_id
        ])->first();
        $container = GameServiceFacade::getFacadeApplication();
        $game = $container->make('GameService', [
            $playTransfer->mainGamePlat->main_game_plat_name
        ]);
        if (empty($playTransfer) || empty($account)) {
            $result = 0;
        } else {
            $result = $game->checkTransfer($playTransfer, $account);
        }
        if ($result == 1) {
            $message = '<span style="color:green">转账成功</span>';
        } elseif ($result == - 1) {
            $message = '<span style="color:">转账失败</span>';
        } else {
            $message = '<span style="color:red">请将上述信息发给博赢国际客服</span>';
        }
        
        return view('Carrier.player_transfer_unknown_process.checkAuto')->with('playTransfer', $playTransfer)->with('resMessage', $message);
    }

    public function transferSuccess($id)
    {
        if (empty($id)) {
            return $this->sendNotFoundResponse();
        }
        $playTransfer = PlayerTransfer::with([
            'player',
            'mainGamePlat',
            'carrier',
            'carrierOperator'
        ])->transferUnknown()
            ->where('id', $id)
            ->first();
        if (empty($playTransfer)) {
            return $this->sendNotFoundResponse();
        }
        $playTransfer->state = 1;
        $playTransfer->save();
        return $this->sendResponse($playTransfer, '操作成功！');
    }

    public function transferFail($id)
    {
        if (empty($id)) {
            return $this->sendNotFoundResponse();
        }
        $playTransfer = PlayerTransfer::with([
            'player',
            'mainGamePlat',
            'carrier',
            'carrierOperator'
        ])->transferUnknown()
            ->where('id', $id)
            ->first();
        if (empty($playTransfer)) {
            return $this->sendNotFoundResponse();
        }
        if ($playTransfer->direction == 1) {
            $playTransfer->player->main_account_amount += $playTransfer->money;
        } else {
            $playTransfer->player->main_account_amount -= $playTransfer->money;
        }
        $playTransfer->state = 2;
        \DB::beginTransaction();
        try {
            $playTransfer->save();
            $playTransfer->player->save();
            \DB::commit();
        } catch (\PDOException $e) {
            \DB::rollBack();
            return $this->sendError('操作失败！');
        }
        
        return $this->sendResponse($playTransfer, '操作成功');
    }
}
