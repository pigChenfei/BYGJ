@section('css')
    @include('Carrier.layouts.datatables_css')
@endsection

{!! $dataTable->table(['width' => '100%']) !!}

@section('scripts')
    @include('Carrier.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
@endsection