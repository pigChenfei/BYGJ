
    <div class="col-sm-12">
        <div class="col-sm-6">
            <div class="box-header">
                <h3 class="box-title">会员设置</h3>
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
                <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::playerStatus() ?>
                @foreach($statusDic as $key=>$value)
                    <tr role="row">
                        <td>{{$value}}</td>
                        @foreach([1,0] as $status)
                            @if(isset($playerstatus[$key]))
                                <td>
                                    <input type="radio" {!! $playerstatus[$key]  == $status  ? 'checked' : '' !!} name="{!! $key !!}" value="{!! $status !!}" class="square-blue">
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

        <div class="col-sm-6">
            <div class="box-header">
                <h3 class="box-title">会员信息设置</h3>
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
                <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::playerFieldAlias() ?>
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

        </div>
    </div>

    <div class="col-sm-12">
        <div class="col-sm-6">
            <div class="box-header">
                <h3 class="box-title">代理设置</h3>
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
                <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::agentStatus() ?>
                @foreach($statusDic as $key=>$value)
                    <tr role="row">
                        <td>{{$value}}</td>
                        @foreach([1,0] as $status)
                            @if(isset($agentstatus[$key]))
                                <td>
                                    <input type="radio" {!! $agentstatus[$key]  == $status  ? 'checked' : '' !!} name="{!! $key !!}" value="{!! $status !!}"  class="square-blue">
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-sm-6">
            <div class="box-header">
                <h3 class="box-title">代理注册设置</h3>
            </div>
            <table class="table table-bordered table-hover table-responsive" id="tab">
                <thead>
                <tr role="row">
                    <th>信息描述</th>
                    <th>是否显示</th>
                    <th>是否必填</th>
                </tr>
                </thead>
                <tbody>
                <?php $statusDic = \App\Models\Conf\CarrierDashLoginConf::agentFieldAlias() ?>
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
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].square-blue, input[type="radio"].square-blue').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
        //checkbox点击事件
        $(document).on('ifChanged','input[type=checkbox]', function(){
            var name = this.name;
            var value = this.value;
            if(this.checked && value == 2){
                $('input[name="'+name+'"]').each(function(index,element){
                    if(element.value != 2){
                        $(element).iCheck('check')
                    }
                });
            }
            if (this.checked == false && value == 1){
                $('input[name="'+name+'"]').each(function(index,element){
                    if(element.value != 1){
                        $(element).iCheck('uncheck')
                    }
                });
            }
        });
    });

</script>


