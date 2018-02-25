@extends('Carrier.layouts.app')
@section('css')
    @include('Components.Editor.style')
    @include('Components.ImagePicker.style')
@endsection
@section('scripts')
    @include('Components.Editor.scripts')
    <script src="{{asset('js/vue.min.js')}}"></script>
    <script>
        $(function () {
            $(document).on('submit','.activitie_form',function (e) {
                e.preventDefault();
                {!! TableScript::ajaxSubmitScript(route('carrierActivities.update',$carrierActivity->id),'保存') !!}
//                window.location.href = "{!! route('carrierActivities.index') !!}";
            })
        })
    </script>
@endsection
@section('content')
    <section class="content-header">
        <div class="left">
            <ol class="breadcrumb">
                <li><a href="{!! route('carrierActivities.index') !!}">优惠活动</a></li>
                <li class="active">编辑优惠活动</li>
            </ol>
        </div>
    </section>
    <div class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">活动基本信息</a></li>
                <li><a href="#tab_2" data-toggle="tab">活动规则设置</a></li>
            </ul>
            {!! Form::model($carrierActivity, ['route' => ['carrierActivities.update', $carrierActivity->id], 'method' => 'patch','class' => 'activitie_form']) !!}
            <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <div class="box-body">
                            @include('Carrier.carrier_activities.basic_info')
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_2">
                        <div class="box-body">
                            @include('Carrier.carrier_activities.basic_info_one')
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <div class="form-group col-sm-12">
                    {!! Form::button('保存', ['class' => 'btn btn-primary','type' => 'submit']) !!}
                    <a onclick="history.back();" class="btn btn-default">取消</a>
                </div>
            </div>
        </div>
    </div>
@endsection