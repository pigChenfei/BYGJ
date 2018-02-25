
<div class="row">
    <div class="col-sm-12">
        <div class="col-sm-6">
            <div class="box-header">
                <h3 class="box-title">系统存款设置</h3>
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
                <?php $statusDic = \App\Models\Conf\CarrierDepositConf::depositStatus() ?>
                @foreach($statusDic as $key=>$value)
                    <tr role="row">
                        <td>{{$value}}</td>
                        @foreach([1,0] as $status)
                            @if(isset($deposit[$key]))
                                <td>
                                    <input type="radio" {!! $deposit[$key]  == $status  ? 'checked' : '' !!} name="{!! $key !!}" value="{!! $status !!}"  class="square-blue">
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
    <div class="col-sm-12">
        <!-- Site Title Field -->
        <div class="form-group col-sm-6">
            {!! Form::label('unreview_deposit_record_limit', '允许未审核存款条数').Form::required_pin() !!}
            {!! Form::number('unreview_deposit_record_limit', null, ['class' => 'form-control']) !!}
        </div>
    </div>

</div>




<div class="form-group col-sm-12">
    {!! Form::button('保存当前设置', ['class' => 'btn btn-primary','type' => 'submit']) !!}
</div>

<script>
    $(function () {
        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].square-blue, input[type="radio"].square-blue').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
    })
</script>

