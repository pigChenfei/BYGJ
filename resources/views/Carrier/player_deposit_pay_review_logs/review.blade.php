<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">会员存款审核</h4>
        </div>
        <form action="" role="form">
            <div class="modal-body" id="playerAccountModalContent">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <p for="">存款人: {!! $playerDepositPayLog->player->real_name !!}&nbsp;&nbsp;&nbsp;&nbsp;
                                存款银行: {!! $playerDepositPayLog->playerBankCard ? $playerDepositPayLog->playerBankCard->bankType->bank_name : null !!}&nbsp;&nbsp;&nbsp;&nbsp;
                                存款姓名: {!! $playerDepositPayLog->playerBankCard ? $playerDepositPayLog->playerBankCard->card_owner_name : null !!}&nbsp;&nbsp;&nbsp;&nbsp;
                                银行卡号: {!! $playerDepositPayLog->playerBankCard ? $playerDepositPayLog->playerBankCard->card_account : null !!}&nbsp;&nbsp;&nbsp;&nbsp;
                                附加码: {!!  $playerDepositPayLog->credential !!}
                            </p>
                            @if($playerDepositPayLog->relatedCarrierActivity)
                            <p>
                                申请的优惠活动: {!! $playerDepositPayLog->relatedCarrierActivity->name !!}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-sm-9">
                            <label for="pay_channel" class="col-sm-3 control-label">已查到玩家存款</label>
                            <input type="radio" name="is_received_deposit_amount" value="1">
                        </div>
                        <div class="col-sm-9">
                            <label for="pay_channel" class="col-sm-3 control-label">未查到玩家存款</label>
                            <input type="radio" name="is_received_deposit_amount" value="0">
                        </div>
                    </div>
                </div>
            </div>
        <div class="clearfix"></div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(route('playerDepositLogs.reviewDepositLog',$playerDepositPayLog->id)) !!}
            </div>
        </div>
        </form>
    </div>
</div>