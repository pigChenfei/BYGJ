<?php
namespace App\DataTables\Carrier;

use App\Models\Log\CarrierWinLoseStastics;
use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\Log\AgentWinLoseStastics;

class AgentWinLoseStasticsDataTable extends DataTable
{

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $dataTables = $this->datatables->eloquent($this->query());
        if ($date_time_range = $this->request()->get('date_time_range')) {
            $time = explode(' - ', $date_time_range);
            if (count($time) == 2) {
                $dataTables->where('created_at', '>=', $time[0]);
                $dataTables->where('created_at', '<=', $time[1]);
            }
        } else {
            $dataTables->where('created_at', '>=', date('Y-m-01 00:00:00', time()));
            $dataTables->where('created_at', '<=', date('Y-m-d H:i:s', time()));
        }
        return $dataTables->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $agentWinLoseStastics = AgentWinLoseStastics::with([
            'agent'
        ])->whereHas('agent', function ($query){
            $query->where('is_default', 0);
        })->orderBy('updated_at', 'desc');
        
        return $this->applyScopes($agentWinLoseStastics);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
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
                'language' => \Config::get('datatables.language')
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
            '代理' => [
                'name' => 'agent.username',
                'data' => 'agent.username'
            ],
            '注册数' => [
                'name' => 'register_count',
                'data' => 'register_count'
            ],
            '登录数' => [
                'name' => 'login_count',
                'data' => 'login_count'
            ],
            '存款额' => [
                'name' => 'deposit_amount',
                'data' => 'deposit_amount'
            ],
            '首存额' => [
                'name' => 'first_deposit_amount',
                'data' => 'first_deposit_amount'
            ],
            '存款数' => [
                'name' => 'deposit_count',
                'data' => 'deposit_count'
            ],
            '首存数' => [
                'name' => 'first_deposit_count',
                'data' => 'first_deposit_count'
            ],
            '取款额' => [
                'name' => 'withdraw_amount',
                'data' => 'withdraw_amount'
            ],
            '公司输赢' => [
                'name' => 'winlose_amount',
                'data' => 'winlose_amount'
            ],
            '红利' => [
                'name' => 'bonus_amount',
                'data' => 'bonus_amount'
            ],
            '洗码' => [
                'name' => 'rebate_financial_flow_amount',
                'data' => 'rebate_financial_flow_amount'
            ],
            '存款优惠' => [
                'name' => 'deposit_benefit_amount',
                'data' => 'deposit_benefit_amount'
            ],
            '公司收入' => [
                'name' => 'carrier_income',
                'data' => 'carrier_income'
            ],
            '更新时间' => [
                'name' => 'updated_at',
                'data' => 'updated_at'
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
        return 'agentWinLoseStastics';
    }
}
