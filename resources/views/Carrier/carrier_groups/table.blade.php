<table class="table table-responsive" id="carrierGroups-table">
    <thead>
        <th>Title</th>
        <th>Status</th>
        <th>Rules</th>
        <th>Operator Id</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($carrierGroups as $carrierGroup)
        <tr>
            <td>{!! $carrierGroup->title !!}</td>
            <td>{!! $carrierGroup->status !!}</td>
            <td>{!! $carrierGroup->rules !!}</td>
            <td>{!! $carrierGroup->operator_id !!}</td>
            <td>
                {!! Form::open(['route' => ['carrierGroups.destroy', $carrierGroup->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('carrierGroups.show', [$carrierGroup->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('carrierGroups.edit', [$carrierGroup->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>