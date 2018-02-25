@section('css')
    @include('Components.Editor.style')
    @include('Carrier.layouts.datatables_css')
    <link rel="stylesheet" href="/src/css/bootstrap-switch.css">
    <link rel="stylesheet" href="/src/css/select2-bootstrap.min.css">
@endsection



<div class="col-md-12">
</div>

<div class="col-md-12">
    {!! $dataTable->table(['width' => '100%','class' => 'table table-bordered table-hover dataTable']) !!}
</div>

@section('scripts')
    @parent
    <script src="{{asset('js/vue.min.js')}}"></script>
    @include('Carrier.layouts.datatables_js')
    @include('Components.Editor.scripts')
    <script src="/src/js/bootstrap-switch.js"></script>
    {!! $dataTable->scripts() !!}
    @include('vendor.datatable.datatables_template')
@endsection

@section('footer')
    @include('vendor.alert.default')
@endsection