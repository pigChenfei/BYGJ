
<div class="row">
    <div class="col-sm-12">
        <div class="col-sm-6">
            <div class="box-header">
                <h3 class="box-title">会员取款设置</h3>
            </div>
            <table class="table table-bordered table-hover table-responsive">
                <thead>
                <tr role="row">
                    <th>信息描述</th>
                    <th>是</th>
                    <th>否</th>
                </tr>
                </thead>
                <tbody>
                <?php $statusDic = \App\Models\Conf\CarrierWithdrawConf::playerStatus() ?>
                @foreach($statusDic as $key=>$value)
                    <tr role="row">
                        <td>{{$value}}</td>
                        @foreach([1,0] as $status)
                            @if(isset($playerWithdraw[$key]))
                                <td>
                                    <input type="radio" {!! $playerWithdraw[$key]  == $status  ? 'checked' : '' !!} name="{!! $key !!}" value="{!! $status !!}"  class="square-blue">
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
    </div>
    <div class="col-sm-12 adjust">
        <!-- Site Description Field -->
        <div class="form-group col-sm-3">
            {!! Form::label('player_once_withdraw_min_sum', '会员单次取款最小金额') !!}
            {!! Form::text('player_once_withdraw_min_sum', null, ['class' => 'form-control']) !!}
        </div>
        <!-- Site Description Field -->
        <div class="form-group col-sm-3">
            {!! Form::label('player_once_withdraw_max_sum', '会员单次取款最大金额') !!}
            {!! Form::text('player_once_withdraw_max_sum', null, ['class' => 'form-control']) !!}
        </div>
        <!-- Site Description Field -->
        <div class="form-group col-sm-3">
            {!! Form::label('player_day_withdraw_max_sum', '会员单日取款最大金额') !!}
            {!! Form::text('player_day_withdraw_max_sum', null, ['class' => 'form-control']) !!}
        </div>
        <!-- Site Description Field -->
        <div class="form-group col-sm-3">
            {!! Form::label('player_day_withdraw_success_limit_count', '会员单日取款成功限制次数') !!}
            {!! Form::number('player_day_withdraw_success_limit_count', null, ['class' => 'form-control']) !!}
        </div>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-6">
        <div class="box-header">
            <h3 class="box-title">代理取款设置</h3>
        </div>
        <table class="table table-bordered table-hover table-responsive">
            <thead>
            <tr role="row">
                <th>信息描述</th>
                <th>是</th>
                <th>否</th>
            </tr>
            </thead>
            <tbody>
            <?php $statusDic = \App\Models\Conf\CarrierWithdrawConf::agentStatus() ?>
            @foreach($statusDic as $key=>$value)
                <tr role="row">
                    <td>{{$value}}</td>
                    @foreach([1,0] as $status)
                        @if(isset($agentWithdraw[$key]))
                            <td>
                                <input type="radio" {!! $agentWithdraw[$key]  == $status  ? 'checked' : '' !!} name="{!! $key !!}" value="{!! $status !!}"  class="square-blue">
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
    </div>
    <div class="col-sm-12 adjust">
        <!-- Site Description Field -->
        <div class="form-group col-sm-3">
            {!! Form::label('agent_once_withdraw_min_sum', '代理单次取款最小金额') !!}
            {!! Form::text('agent_once_withdraw_min_sum', null, ['class' => 'form-control']) !!}
        </div>
        <!-- Site Description Field -->
        <div class="form-group col-sm-3">
            {!! Form::label('agent_once_withdraw_max_sum', '代理单次取款最大金额') !!}
            {!! Form::text('agent_once_withdraw_max_sum', null, ['class' => 'form-control']) !!}
        </div>
        <!-- Site Description Field -->
        <div class="form-group col-sm-3">
            {!! Form::label('agent_day_withdraw_max_sum', '代理单日取款最大金额') !!}
            {!! Form::text('agent_day_withdraw_max_sum', null, ['class' => 'form-control']) !!}
        </div>
        <!-- Site Description Field -->
        <div class="form-group col-sm-3">
            {!! Form::label('agent_day_withdraw_success_limit_count', '代理单日取款成功限制次数') !!}
            {!! Form::number('agent_day_withdraw_success_limit_count', null, ['class' => 'form-control']) !!}
        </div>
    </div>

</div>




<div class="form-group col-sm-12">
    {!! Form::button('保存当前设置', ['class' => 'btn btn-primary','type' => 'submit']) !!}
</div>


<style type="text/css">
    .col-sm-12.adjust{
        padding-bottom: 25px;
    }

</style>
<script>
    $(function () {
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].square-blue, input[type="radio"].square-blue').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
    })

</script>


