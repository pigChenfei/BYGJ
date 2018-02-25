<table class="table table-responsive" id="carrierImageCategories-table">
    <thead>
        <th>Category Name</th>
        <th>Carrier Id</th>
        <th>Parent Category Id</th>
        <th>Created User Id</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($carrierImageCategories as $carrierImageCategory)
        <tr>
            <td>{!! $carrierImageCategory->category_name !!}</td>
            <td>{!! $carrierImageCategory->carrier_id !!}</td>
            <td>{!! $carrierImageCategory->parent_category_id !!}</td>
            <td>{!! $carrierImageCategory->created_user_id !!}</td>
            <td>
                {!! Form::open(['route' => ['carrierImageCategories.destroy', $carrierImageCategory->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('carrierImageCategories.show', [$carrierImageCategory->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('carrierImageCategories.edit', [$carrierImageCategory->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>