@section('css')
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
    <script src="{{asset('js/vue.min.js')}}"></script>
    @include('Carrier.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    @include('vendor.datatable.datatables_template')
    <script>
        $('#dataTableBuilder').on('click', 'td a.main_account_amount_edit', function () {
            var _me = this;
            var tr = $(this).closest('tr');
            var row = window.LaravelDataTables['dataTableBuilder'].row( tr );
            var data = row.data();
        });
    </script>
@endsection