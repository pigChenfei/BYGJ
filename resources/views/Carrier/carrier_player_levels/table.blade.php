@section('css')
    @include('Carrier.layouts.datatables_css')
    <link rel="stylesheet" href="/src/css/bootstrap-switch.css">
    <link rel="stylesheet" href="/src/css/select2-bootstrap.min.css">
    <link rel="stylesheet" href="{!! asset('iCheck/square/blue.css') !!}">
@endsection


<div class="col-md-12">
    {!! $dataTable->table(['width' => '100%','class' => 'table table-bordered table-hover dataTable']) !!}
</div>

@section('scripts')
    @parent
    <script src="{{asset('js/vue.min.js')}}"></script>
    <script src="{!! asset('iCheck/icheck.min.js') !!}"></script>
    <script src="/src/js/bootstrap-switch.js"></script>
    @include('Carrier.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    @include('vendor.datatable.datatables_template')
    <script>
        $(function(){
            $(document).on('click','.group_edit_link',function(e){
                e.preventDefault();
                var id = $(this).attr('data-id');
                {!! TableScript::addOrEditModalShowEventScript(route('carrierPlayerLevels.edit',59)) !!}
            });
        })
    </script>
@endsection

@section('footer')
    @include('vendor.alert.default')
@endsection
