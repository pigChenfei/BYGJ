<table class="table table-bordered table-hover table-responsive">
    <thead>
    <tr>
        <th>银行名称</th>
        <th>持卡人</th>
        <th>卡号</th>
        <th>分行</th>
        <th>状态</th>
        <th colspan="2">
            操作
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($bankCards as $bankCard)
    <tr role="row">
        <td>{!! $bankCard->bankType->bank_name !!}</td>
        <td>{!! $bankCard->card_owner_name !!}</td>
        <td>{!! $bankCard->card_account !!}</td>
        <td>{!! $bankCard->card_birth_place !!}</td>
        <td>{!! \App\Models\PlayerBankCard::statusMeta()[$bankCard->status] !!}</td>
        <td><button onclick="{!! TableScript::addOrEditModalShowEventScript(route('players.tabBankEdit',$bankCard->card_id)) !!}" class='btn btn-xs btn-primary'>
                <i class="fa fa-edit"></i>
                编辑
            </button>
        </td>
    </tr>
    @endforeach

    </tbody>
</table>
