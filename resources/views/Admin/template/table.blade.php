@section('css')
    @include('Admin.layouts.datatables_css')
    <link rel="stylesheet" href="/src/css/bootstrap-switch.css">
    <link rel="stylesheet" href="/src/css/select2-bootstrap.min.css">
    <link rel="stylesheet" href="{!! asset('duallist/bootstrap-duallistbox.css') !!}">
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">
@endsection

<div class="col-md-12">
    {!! $dataTable->table(['width' => '100%','class' => 'table table-bordered table-hover dataTable','style' => 'text-align:center']) !!}
    <h5 class="pull-left">
        <div class='btn-group'>
        <button class="btn btn-primary" style="margin-top: -10px;margin-bottom: 5px" onclick="
        var selectCheckbox = $('input[type=checkbox].log_check_box:checked');
        var selectedIds = selectCheckbox.map(function(index,element){ return element.value }).toArray();
        var _me = this;
        $.fn.winwinAjax.buttonActionSendAjax(
        _me,
        '{!! route('templates.showAssignCarriersModal')!!}',
        {
            template_ids : selectedIds
        },function(html){
            $('#editAddModal').html(html).modal('show');
        },function(){

        },'POST',{dataType:'html'});
        ">批量分配运营商</button>

        <a class="btn btn-primary pull-left" style="margin-top: -10px;margin-bottom: 5px;margin-left: 10px" onclick="{!! TableScript::addOrEditModalShowEventScript(route('templates.create')) !!}">新增模板</a>
    </div>
    </h5>
</div>

@section('scripts')
	<script src="/src/js/jquery.validate.min.js"></script>
    <script src="/src/js/jquery.steps.min.js"></script>
    @include('Admin.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    @include('vendor.datatable.datatables_template')
    @include('Components.Ajax.WinwinAjax')
    <script src="{!! asset('duallist/jquery.bootstrap-duallistbox.js') !!}"></script>
    <script>
        $(function(){
            //多选控件处理
            $('table').on('ifClicked','.selectCheckbox', function(){
                var allCheckbox = $('input[type=checkbox].log_check_box');
                if (this.checked == false){
                    allCheckbox.each(function(index,element){
                        $(element).iCheck('check');
                    })
                }else{
                    allCheckbox.each(function(index,element){
                        $(element).iCheck('uncheck');
                    })
                }
            }).on('ifChanged','.log_check_box',function(){
                var selectedCheckboxLength = $('input[type=checkbox].log_check_box:checked').length;
                var allCheckboxLength = $('input[type=checkbox].log_check_box').length;
                if(allCheckboxLength == selectedCheckboxLength){
                    $('.selectCheckbox').iCheck('check');
                }else{
                    $('.selectCheckbox').iCheck('uncheck');
                }
            });
        })
    </script>
@endsection