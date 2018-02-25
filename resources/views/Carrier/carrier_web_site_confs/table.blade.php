<table class="table table-responsive" id="carrierWebSiteConfs-table">
    <thead>
        <th>Carrier Id</th>
        <th>Site Title</th>
        <th>Site Key Words</th>
        <th>Site Description</th>
        <th>Site Javascript</th>
        <th>Site Notice</th>
        <th>Site Footer Comment</th>
        <th>Common Question File Path</th>
        <th>Contact Us</th>
        <th>Privacy Policy File Path</th>
        <th>Rule Clause File Path</th>
        <th>With Draw Comment File Path</th>
        <th>Net Bank Deposit Comment</th>
        <th>Atm Deposit Comment</th>
        <th>Third Part Deposit Comment</th>
        <th>Commission Policy File Path</th>
        <th>Jointly Operated Agreement File Path</th>
        <th colspan="3">Action</th>
    </thead>
    <tbody>
    @foreach($carrierWebSiteConfs as $carrierWebSiteConf)
        <tr>
            <td>{!! $carrierWebSiteConf->carrier_id !!}</td>
            <td>{!! $carrierWebSiteConf->site_title !!}</td>
            <td>{!! $carrierWebSiteConf->site_key_words !!}</td>
            <td>{!! $carrierWebSiteConf->site_description !!}</td>
            <td>{!! $carrierWebSiteConf->site_javascript !!}</td>
            <td>{!! $carrierWebSiteConf->site_notice !!}</td>
            <td>{!! $carrierWebSiteConf->site_footer_comment !!}</td>
            <td>{!! $carrierWebSiteConf->common_question_file_path !!}</td>
            <td>{!! $carrierWebSiteConf->contact_us !!}</td>
            <td>{!! $carrierWebSiteConf->privacy_policy_file_path !!}</td>
            <td>{!! $carrierWebSiteConf->rule_clause_file_path !!}</td>
            <td>{!! $carrierWebSiteConf->with_draw_comment_file_path !!}</td>
            <td>{!! $carrierWebSiteConf->net_bank_deposit_comment !!}</td>
            <td>{!! $carrierWebSiteConf->atm_deposit_comment !!}</td>
            <td>{!! $carrierWebSiteConf->third_part_deposit_comment !!}</td>
            <td>{!! $carrierWebSiteConf->commission_policy_file_path !!}</td>
            <td>{!! $carrierWebSiteConf->jointly_operated_agreement_file_path !!}</td>
            <td>
                {!! Form::open(['route' => ['carrierWebSiteConfs.destroy', $carrierWebSiteConf->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{!! route('carrierWebSiteConfs.show', [$carrierWebSiteConf->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="{!! route('carrierWebSiteConfs.edit', [$carrierWebSiteConf->id]) !!}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>