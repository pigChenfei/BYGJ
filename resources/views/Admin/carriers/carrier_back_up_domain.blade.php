<table class="table table-bordered table-responsive table-hover" style="text-align: left">
    <thead>
        <tr>
            <th>域名</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($carrierBackUpDomain as $carrierBackUpDomain)
        <tr>
            <td style="text-align: center">
                {!! $carrierBackUpDomain->domain !!}
            </td>
            <td style="text-align: center">
                <div class='btn-group'>
                    <button onclick="var _me = this;$.fn.winwinAjax.buttonActionSendAjax(_me,
                            '{!! route('carriers.showEditDomainModal',$carrierBackUpDomain->id) !!}',{},function(html){
                            var modal = $('#carrierUserAddEditModal');
                            modal.html(html).modal('show');
                            },function() {

                            },'GET',{
                            dataType:'html'
                            }
                            )" class='btn btn-default btn-xs'>
                        <i class="fa fa-edit">编辑</i>
                    </button>
                    <button onclick="
                            var _me = this;
                            $.fn.winwinAjax.buttonActionSendAjax(
                            _me,
                            '{!! route('carriers.toggleCarrierBackUpDomainStatus',$carrierBackUpDomain->id) !!}',
                            {},
                            function(resp){
                                _me.disabled = true;
                                $('#step4_button').click();
                            },
                            function() {

                            },
                            'PATCH'
                            )
                            " class='btn {!!  $carrierBackUpDomain->status ? 'btn-danger':'btn-success' !!}  btn-xs'>
                        <i class="fa fa-close">{!! $carrierBackUpDomain->status ? '禁用' : '开启' !!}</i>
                    </button>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="col-sm-12">
    <div class="btn-group">
        <button class="btn btn-success" onclick="var _me = this;$.fn.winwinAjax.buttonActionSendAjax(_me,
                '{!! route('carriers.showCreateBackUpDomainModal',$carrierId) !!}',{},function(html){
                    var modal = $('#carrierUserAddEditModal');
                    modal.html(html).modal('show');
                },function() {

                },'GET',{
                    dataType:'html'
                }
        )">新增域名</button>
    </div>
</div>

<div class="clearfix"></div>