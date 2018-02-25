{!! Form::open(['route' => ['carrierBankCards.destroy', $bank_card_id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a class='btn btn-default btn-xs' onclick="{!! TableScript::addOrEditModalShowEventScript(route('carrierBankCards.edit', $bank_card_id)) !!}">
        <i class="glyphicon glyphicon-edit">{!! Lang::get('common.edit') !!}</i>
    </a>
    {!! TableScript::createDeleteButtonScript(Route('carrierBankCards.destroy',$bank_card_id)) !!}
</div>
{!! Form::close() !!}
