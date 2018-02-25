<div class="modal-dialog modal-lg" style=" width: 1200px;" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">佣金报表</h4>
        </div>
        <div class="box-body">
            <table class="table table-responsive  table-bordered table-hover dataTable">
                <tbody>
                    <tr>
                        <th rowspan="2">游戏平台</th>
                        <th rowspan="2">有效会员</th>
                        <th colspan="6">
                            佣金收入计算
                        </th>
                        <th colspan="3">
                            洗吗收入计算
                        </th>
                    </tr>
                    <tr>
                        <th>公司输赢</th>
                        <th>存款优惠</th>
                        <th>红利</th>
                        <th>洗码</th>
                        <th>平台费</th>
                        <th>公司实际输赢</th>
                        <th>有效投注额</th>
                        <th>洗码比例</th>
                        <th>洗码金额</th>
                    </tr>
                    <tr>
                        @foreach($gamePlat as $key => $value)
                            <td>{!! $value->game_plat_name !!}</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                            <td>6</td>
                            <td>7</td>
                            <td>8</td>
                            <td>9</td>
                            <td>10</td>
                            <td>11</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>小计</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                        <td>0.00</td>
                    </tr>
                    <tr>
                        <td>平台合计</td>
                        <td colspan="2">0.00</td>
                        <td>佣金比例</td>
                        <td>30%</td>
                        <td>佣金收入</td>
                        <td>0.00</td>
                        <td>洗码收入</td>
                        <td>0.00</td>
                        <td>子代理提成</td>
                        <td>0.00</td>
                    </tr>
                    <tr>
                        <td>总佣金</td>
                        <td colspan="10">0.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="clearfix"></div>
    </div>
</div>