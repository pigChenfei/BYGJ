<div class="modal-dialog modal-lg" style=" width: 1000px;" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">成本分摊</h4>
        </div>
        <div class="modal-body" id="modalContent">
            <div class="row">
                <table class="table table-bordered table-hover dataTable text-center">
                    <thead>
                        <tr role="row">
                            <th>项目</th>
                            <th>合计值</th>
                            <th>比例%</th>
                            <th>结果</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr role="row">
                            <td>存款优惠</td>
                            <td>{!! $totalDepositAmount !!}</td>
                            <td>{!! $depositRatio !!}%</td>
                            <td>{!! $depositAmount !!}</td>
                        </tr>
                        <tr role="row">
                            <td>红利</td>
                            <td>{!! $totalBonusAmount !!}</td>
                            <td>{!! $bonusRatio !!}%</td>
                            <td>{!! $bonusAmount !!}</td>
                        </tr>
                        <tr role="row">
                            <td>洗码</td>
                            <td>{!! $totalRebateAmount !!}</td>
                            <td>{!! $rebateRatio !!}%</td>
                            <td>{!! $rebateAmount !!}</td>
                        </tr>
                        <tr role="row">
                            <td>存款手续费</td>
                            <td>{!! $totalInFeeAmount !!}</td>
                            <td></td>
                            <td>{!! $outFeeAmount !!}</td>
                        </tr>
                        <tr role="row">
                            <td>取款手续费</td>
                            <td>{!! $totalOutFeeAmount !!}</td>
                            <td></td>
                            <td>{!! $inFeeAmount !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>