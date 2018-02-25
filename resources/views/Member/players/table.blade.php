@section('css')
    @include('Member.layouts.datatables_css')
@endsection

{!! $dataTable->table(['width' => '100%']) !!}

@section('scripts')
    @include('Member.layouts.datatables_js')
    {!! $dataTable->scripts() !!}
@endsection