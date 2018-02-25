<?php

namespace App\DataTables\Agent;


use Form;
use Yajra\Datatables\Services\DataTable;
use App\Models\CarrierAgentLevel;
use App\Models\Log\PlayerDepositPayLog;
use App\Models\Log\PlayerBetFlowLog;
use App\Models\Log\PlayerAccountLog;
use App\Models\Player;
use Carbon\Carbon;
use App\Models\CarrierAgentUser;

class AgentSubPlayerDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $DataTables = $this->datatables
            ->eloquent($this->query())
            ->addColumn('type_name',function(CarrierAgentUser $agentlevel){
                return CarrierAgentLevel::typeMeta()[$agentlevel['agentLevel']->type];
            });
            
        return $DataTables->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query($id)
    {
        $carrierAgentUsers = CarrierAgentUser::select("*")->where('parent_id','=',\WinwinAuth::agentUser()->id);
        return $this->applyScopes($carrierAgentUsers);
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
            ->parameters([
                    'paging' => true,
                    'searching' => false,
                    'ordering' => false,
                    'info' => true,
                    'dom' => 'Bfrtipl',
                    'scrollX' => false,
                    'buttons' => [
                    ],
                    'language' => \Config::get('datatables.language'),
                    'drawCallback' => 'function(){
                    var api = this.api();
            　　    var startIndex= api.context[0]._iDisplayStart;
                　　api.column(0).nodes().each(function(cell, i) {
                　　　　cell.innerHTML = startIndex + i + 1;
                　　});
                }']
            );
    }


    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            '序号' => ['name' => 'id', 'data' => 'id'],
            '子代理账号' => ['name' => 'username','data' => 'username','defaultContent' => ''],
            '存款总额' => ['name' => 'deposit_total', 'data' => 'deposit_total','defaultContent' => ''],
            '取款总额' => ['name' => 'id', 'data' => 'withdraw_total','defaultContent' => ''],
            '有效投注额' => ['name' => 'id', 'data' => 'availableBetAmount','defaultContent' => ''],
            '存款优惠' => ['name' => 'id', 'data' => 'depositBenefitAmount','defaultContent' => ''],
            '红利' => ['name' => 'id', 'data' => 'bonusAmount','defaultContent' => ''],
            '洗码' => ['name' => 'id', 'data' => 'rebateFinancialFlowAmount','defaultContent' => ''],
            '子代理公司输赢' => ['name' => 'id', 'data' => 'winlose_total','defaultContent' => ''],
            '子代理佣金' => ['name' => 'id', 'data' => 'winlose_total','defaultContent' => ''],
            '获得子代理佣金提成' => ['name' => 'id', 'data' => 'sub_agent_commission_total','defaultContent' => ''],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'carrierAgentUsers';
    }
}
