<link rel="stylesheet" href="{!! asset('iCheck/square/blue.css') !!}">
<div class="modal-dialog" role="document" style="width: 1000px;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">多级代理佣金方案--{{$carrierAgentLevel->level_name}}</h4>
        </div>
        {!! Form::model($carrierAgentLevel, ['route' => ['carrierAgentLevels.saveAgentLevelRebate', $carrierAgentLevel->id], 'method' => 'patch','id'=>'carrierAgentForm']) !!}
        <div class="modal-body" id="modalContent">
            <div class="row">
                <div class="box-body">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-hover dataTable text-center">
                            <thead>
                                <tr role="row">
                                    <th>代理层级</th>
                                    <th>
                                        抽佣比例%
                                        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="抽佣比例给上一级" ></i>
                                    </th>
                                    <th>
                                        抽佣上限
                                        <i class="fa fa-question-circle" style="color: #f44336"  data-toggle="tooltip" data-original-title="抽佣上限，填0为不设上限" ></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="agent_level_tbody">
                                @forelse($agentpa as $key=>$rebateFinancialFlow)
                                    <tr role="row">
                                        <td>
                                            {!! $rebateFinancialFlow->level !!}
                                            <input type="hidden" name="level[{!! $key !!}]" value="{!! $rebateFinancialFlow->level !!}">
                                        </td>
                                        <td>
                                            <input type="number" name="commission_ratio[{!! $key !!}]" value="{!! $rebateFinancialFlow->commission_ratio !!}" class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" name="commission_max[{!! $key !!}]" value="{!! $rebateFinancialFlow->commission_max !!}" class="form-control">
                                        </td>
                                    </tr>
                                    @empty
                                    <tr role="row">
                                        <td>
                                            1
                                            <input type="hidden" name="level[0]" value="1">
                                        </td>
                                        <td>
                                            <input type="number" name="commission_ratio[0]" value="0.00" class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" name="commission_max[0]" value="0.00" class="form-control">
                                        </td>
                                    </tr>
                                    <tr role="row">
                                        <td>
                                            2
                                            <input type="hidden" name="level[1]" value="2">
                                        </td>
                                        <td>
                                            <input type="number" name="commission_ratio[1]" value="0.00" class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" name="commission_max[1]" value="0.00" class="form-control">
                                        </td>
                                    </tr>
                                    <tr role="row">
                                        <td>
                                            3
                                            <input type="hidden" name="level[2]" value="3">
                                        </td>
                                        <td>
                                            <input type="number" name="commission_ratio[2]" value="0.00" class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" name="commission_max[2]" value="0.00" class="form-control">
                                        </td>
                                    </tr>
                                    <tr role="row">
                                        <td>
                                            4
                                            <input type="hidden" name="level[3]" value="4">
                                        </td>
                                        <td>
                                            <input type="number" name="commission_ratio[3]" value="0.00" class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" name="commission_max[3]" value="0.00" class="form-control">
                                        </td>
                                    </tr>
                                    <tr role="row">
                                        <td>
                                            5
                                            <input type="hidden" name="level[4]" value="5">
                                        </td>
                                        <td>
                                            <input type="number" name="commission_ratio[4]" value="0.00" class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" name="commission_max[4]" value="0.00" class="form-control">
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
       <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('carrierAgentLevels.saveAgentLevelRebate',$carrierAgentLevel->id)) !!}
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>
<script>
$(function () {
    $('.disable_search_select2').select2({
        minimumResultsForSearch: Infinity
    });
    $('input[type="checkbox"].square-blue, input[type="radio"].square-blue').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue'
    });
    var form  =  $('#carrierAgentForm');
    form.on('click','#select_yj_all',function(){
        var selectedCheckboxCount = $('.select_yj_box:checked').length;
        var yjboxDom = form.find('.select_yj_box');
        if (selectedCheckboxCount != yjboxDom.length){
        	yjboxDom.each(function(index,dom){
                $(dom).iCheck('check');
            });
        }else{
        	yjboxDom.each(function(index,dom){
                $(dom).iCheck('uncheck');
            });
        }
    });
    form.on('click','#select_tze_all',function(){
        var selectedCheckboxCount = $('.select_tze_box:checked').length;
        var tzeboxDom = form.find('.select_tze_box');
        if (selectedCheckboxCount != tzeboxDom.length){
        	tzeboxDom.each(function(index,dom){
                $(dom).iCheck('check');
            });
        }else{
        	tzeboxDom.each(function(index,dom){
                $(dom).iCheck('uncheck');
            });
        }
    })
})
</script>