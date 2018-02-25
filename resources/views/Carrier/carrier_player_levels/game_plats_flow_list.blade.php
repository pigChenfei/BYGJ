

@extends('Carrier.layouts.app')
@section('content')
    <section class="content-header">
        <div class="left">
            {!! Breadcrumbs::render(Route::current()->getAction()['as'],$carrierPlayerLevel) !!}
        </div>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary color-palette-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-tag"></i> {!! $carrierPlayerLevel->level_name !!}洗码设置</h3>
                <div class="box-tools">
                    <ul class="pull-right pagination-sm pagination">
                    </ul>
                </div>
            </div>
            <div class="box-body">
                <div class="col-sm-12">
                    <table class="table table-bordered table-hover dataTable text-center">
                        <thead>
                            <tr role="row">
                                <th>序号</th>
                                <th>游戏平台</th>
                                <th>洗码周期</th>
                                <th>单次限额</th>
                                <th>总洗码比例</th>
                                <th>洗码阶梯比例</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carrierPlayerLevel->rebateFinancialFlow as $rebateFinancialFlow)
                                <tr role="row">
                                    <td>
                                        {!! $rebateFinancialFlow->id  !!}
                                    </td>
                                    <td>
                                        {!! $rebateFinancialFlow->carrierGamePlat->gamePlat->game_plat_name !!}
                                    </td>
                                    <td>
                                        {!! $rebateFinancialFlow->isAutoRebateFinancialByPlayer() ? '自动洗码' : ($rebateFinancialFlow->rebate_manual_period_hours / 24).'天' !!}
                                    </td>
                                    <td>
                                        {!! $rebateFinancialFlow->limit_amount_per_flow == 0 ? '不限额' : $rebateFinancialFlow->limit_amount_per_flow !!}
                                    </td>
                                    <td>
                                        {!! $rebateFinancialFlow->rebate_financial_flow_rate !!}
                                    </td>
                                    <td>
                                        {!! $rebateFinancialFlow->flowStepRateFormatString() !!}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a class='btn btn-default btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('CarrierPlayerLevels.rebateFlowEdit', $rebateFinancialFlow->id)) !!}">
                                                <i class="fa fa-edit">{!! Lang::get('common.edit') !!}</i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="overlay" id="overlay" style="display: none">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>

    {!! TableScript::createEditOrAddModal() !!}

@endsection


@section('scripts')
    @parent
    <script src="{{asset('js/vue.min.js')}}"></script>
@endsection
