<?php
namespace App\DataTables\Admin;

use App\Models\Def\Game;
use Form;
use Yajra\Datatables\Services\DataTable;

class GameDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $dataTable = $this->datatables->eloquent($this->query())
            ->addColumn('action', 'Admin.games.datatables_actions')
            ->addColumn('game_mcategory_arr', 
            function (Game $game) {
                if ($game->game_mcategory) {
                    return config('constants.game_mcategory')[$game->game_mcategory];
                }
                return '暂无添加';
            })
            ->addColumn('status_name', 
            function (Game $game) {
                if ($game->status == Game::STATUS_AVAILABLE) {
                    return '<span class="text-default">正常</span>';
                }
                if ($game->status == Game::STATUS_CLOSED) {
                    return '<span class="text-danger">已关闭</span>';
                }
                return '未知';
            })
            ->addColumn('selectCheckbox', 
            function (Game $game) {
                return "<input type=\"checkbox\" value='" . $game->game_id . "' class=\"square-blue log_check_box\">";
            });
        if ($gamePlatId = $this->request()->get('game_plat_value')) {
            $dataTable->where('game_plat_id', $gamePlatId);
        }
        
        $statusValue = $this->request()->get('status_value');
        if ($statusValue !== '') {
            $dataTable->where('status', $statusValue);
        }
        return $dataTable->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $games = Game::with('gamePlat.mainGamePlat')->orderBy('updated_at', 'desc');
        return $this->applyScopes($games);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->addColumn(
            [
                'width' => '135px',
                'title' => '<input style="display: none" type="checkbox" class="square-blue selectCheckbox">',
                'data' => 'selectCheckbox',
                'searchable' => false
            ])
            ->columns($this->getColumns())
            ->addAction([
            'width' => '190px',
            'title' => '操作'
        ])
            ->ajax([
            'data' => \Config::get('datatables.ajax.data')
        ])
            ->parameters(
            [
                'paging' => true,
                'searching' => false,
                'ordering' => false,
                'info' => true,
                'dom' => 'Bfrtipl',
                'scrollX' => false,
                'buttons' => [],
                'language' => \Config::get('datatables.language'),
                'drawCallback' => 'function(){
                    var api = this.api();
            　　    $(\'input[type="checkbox"].square-blue, input[type="radio"].square-blue\').iCheck({
                            checkboxClass: \'icheckbox_square-blue\',
                            radioClass: \'iradio_square-blue\'
                        }).show();
                }'
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            '游戏平台' => [
                'name' => 'game_plat_id',
                'render' => 'function(){
                return this.game_plat.main_game_plat.main_game_plat_name
            }',
                'searchable' => false
            ],
            '图片' => [
                'name' => 'game_icon_path',
                'render' => 'function(){
                return "<img src=\"/"+this.game_icon_path+"\" width=\"80\" height=\"48\">"
            }'
            ],
            '游戏类型' => [
                'name' => 'game_plat_id',
                'render' => 'function(){
                return this.game_plat.game_plat_name + "(" + this.game_plat.english_game_plat_name + ")"
            }',
                'searchable' => false
            ],
            '游戏分类' => [
                'name' => 'game_mcategory',
                'data' => 'game_mcategory_arr',
                'searchable' => false
            ],
            '中文名称' => [
                'name' => 'game_name',
                'data' => 'game_name'
            ],
            '英文名称' => [
                'name' => 'english_game_name',
                'data' => 'english_game_name'
            ],
            '线数' => [
                'name' => 'game_lines',
                'data' => 'game_lines',
                'searchable' => false
            ],
            '人气' => [
                'name' => 'game_popularity',
                'data' => 'game_popularity',
                'searchable' => false
            ],
            '返奖率' => [
                'name' => 'return_award_rate',
                'data' => 'return_award_rate',
                'searchable' => false
            ],
            '状态' => [
                'name' => 'status',
                'data' => 'status_name',
                'searchable' => false
            ]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'games';
    }
}
