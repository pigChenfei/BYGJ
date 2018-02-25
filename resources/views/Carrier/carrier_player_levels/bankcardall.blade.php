<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">选择银行卡</h4>
        </div>

        <form id="bank_cards_select_form">
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="player_level_id" value="{!! $carrierPlayerLevel !!}">
        <div class="modal-body" id="modalContent">

            <div class="row">
                <div class="col-sm-12">
                <!-- Submit Field -->
                <table class="table table-bordered no-footer table-responsive">
                    <thead>
                        <tr role="row">
                            <th style="width: 50px"><a class="btn-link" id="select_all" style="cursor: pointer;text-underline: none">全选</a></th>
                            <th>名称</th>
                            <th>卡号</th>
                            <th>开户人</th>
                            <th>开户行</th>
                            <th>用途</th>
                        </tr>
                    </thead>
                    <tbody>

                       
                        @foreach($bankList as $carrierBankCard)
                            <tr role="row">
                                <td style="text-align: center">

                                    <input class="checkbox selected_bank_checkbox" {!! in_array($carrierBankCard->id,$playerLevelBankCardIds) ? 'checked' : '' !!} name="selected_bank[]" value="{!! $carrierBankCard->id !!}" type="checkbox">
                                </td>
                                <td>
                                    {!! $carrierBankCard->display_name !!}
                                </td>
                                <td>
                                    {!! $carrierBankCard->account !!}
                                </td>
                                <td>
                                    {!! $carrierBankCard->owner_name !!}
                                </td>
                                <td>
                                    {!! $carrierBankCard->card_origin_place !!}
                                </td>
                                <td>
                                    {!! \App\Models\CarrierPayChannel::usedForPurposeMeta()[$carrierBankCard->use_purpose] !!}
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('carrierPlayerLevels.bank')) !!}
            </div>
        </div>
        </form>
    </div>
</div>
<script>
    $(function(){
        var bankCheckboxDom = $('input.selected_bank_checkbox'),bankCardsSelectForm = $('#bank_cards_select_form');
        bankCheckboxDom.iCheck({
            checkboxClass: 'icheckbox_square-blue'
        });
        bankCardsSelectForm.on('click','#select_all',function(){
            var selectedCheckboxCount = $('.selected_bank_checkbox:checked').length;
            if (selectedCheckboxCount != bankCheckboxDom.length){
                bankCheckboxDom.each(function(index,dom){
                    $(dom).iCheck('check');
                });
            }else{
                bankCheckboxDom.each(function(index,dom){
                    $(dom).iCheck('uncheck');
                });
            }
        })
    })
</script>