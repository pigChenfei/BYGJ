@section('css')
    @include('Admin.layouts.datatables_css')
    <link rel="stylesheet" href="/src/css/select2-bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">
@endsection

<div class="col-md-12">
    {!! $dataTable->table(['width' => '100%','class' => 'table table-bordered table-hover dataTable','style' => 'text-align:center']) !!}
</div>

@section('scripts')
    <script src="/src/js/jquery.validate.min.js"></script>
    <script src="/src/js/jquery.steps.min.js"></script>
    @include('Admin.layouts.datatables_js')
    @include('Components.Ajax.WinwinAjax')
    {!! $dataTable->scripts() !!}
    <script src="/src/js/bootstrap-switch.js"></script>

@endsection