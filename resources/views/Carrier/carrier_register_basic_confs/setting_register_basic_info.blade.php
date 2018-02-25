
<div class="row">
    <div class="col-sm-6">
        <div class="box-header">
            <h3 class="box-title">会员注册信息设置</h3>
        </div>
        <table class="table table-bordered table-hover table-responsive">
            <thead>
            <tr role="row">
                <th>信息描述</th>
                <th>是否显示</th>
                <th>是否必填</th>
            </tr>
            </thead>
            <tbody>
            <?php $statusDic = \App\Models\Conf\CarrierRegisterBasicConf::playerFieldAlias() ?>
                @foreach($statusDic as $key=>$value)
                <tr role="row">
                    <td>{{$value}}</td>
                    @foreach([1,2] as $status)
                @if(isset($players[$key]))
                    <td><input type="checkbox" name="{!! $key !!}[]" class="square-blue" {!! ($players[$key] & $status) == $status  ? 'checked' : '' !!} value="{!! $status !!}"></td>
                    @endif
                        @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="box-header">
            <h3 class="box-title">代理注册信息设置</h3>
        </div>
        <table class="table table-bordered table-hover table-responsive">
            <thead>
            <tr role="row">
                <th>信息描述</th>
                <th>是否显示</th>
                <th>是否必填</th>
            </tr>
            </thead>
            <tbody>
            <?php $statusDic = \App\Models\Conf\CarrierRegisterBasicConf::agentFieldAlias() ?>
            @foreach($statusDic as $key=>$value)
                <tr role="row">
                    <td>{{$value}}</td>
                    @foreach([1,2] as $status)
                        @if(isset($agents[$key]))
                            <td><input type="checkbox" name="{!! $key !!}[]" class="square-blue" {!! ($agents[$key] & $status) == $status  ? 'checked' : '' !!} value="{!! $status !!}"></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    </div>
    <div class="col-sm-6">

</div>

<div class="form-group col-sm-12">
    {!! Form::button('保存当前页', ['class' => 'btn btn-primary','type' => 'submit']) !!}
</div>

<script src="{{asset('js/icheck.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/all.css') }}">

<script>
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

</script>


