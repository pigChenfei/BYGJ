{{--账目详情--}}
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 class="modal-title" id="myModalLabel">结算详情</h3>
        </div>
        <div class="modal-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>会员账号</th>
                    <th>存款额</th>
                    <th>投注总额</th>
                    <th>有效投注额</th>
                    <th>结算类型</th>
                    <th>结算金额</th>
                    <th>结算时间</th>
                </tr>
                </thead>
                <tbody id="detailTableBody">
                @include('Web.default.player_centers.friend_recommend.account_statistic.detail_lists')
                </tbody>
            </table>
        </div>
    </div>
</div>
