@section('css')
    @include('Carrier.layouts.datatables_css')
    <style>
        .table>tbody>tr>td{
            vertical-align: middle;
        }
    </style>
@endsection


<div class="col-md-12">
    <form action="" id="searchForm">
        <div class="col-md-3">
            <div class="input-group">
                <div class="input-group-addon">
                    <span>分类</span>
                </div>
                <select title="分类" name="image_category" class="form-control disable_search_select2" style="width: 100%;">
                    <option value="">不限</option>
                    @foreach($categories as $category)
                        <option value="{!! $category->id !!}">{!! $category->category_name !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="input-group input-group-sm">
                <button class="btn btn-primary btn-md" type="submit">搜索</button>
            </div>
        </div>
    </form>
</div>

<div class="col-md-12">
    {!! $dataTable->table(['width' => '100%','class' => 'table table-bordered table-hover dataTable']) !!}
</div>


@section('scripts')
    @parent
    @include('Carrier.layouts.datatables_js')
    <script src="{{asset('js/vue.min.js')}}"></script>
    <script src="/src/js/bootstrap-switch.js"></script>
    {!! $dataTable->scripts() !!}
    @include('vendor.datatable.datatables_template')
@endsection

@section('footer')
    @include('vendor.alert.default')
@endsection