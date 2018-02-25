<table class="table table-bordered table-responsive table-hover" style="text-align: left">
    <thead>
        <tr>
            <th>用户名</th>
            <th>所属部门</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($carrier->carrierUsers as $carrierUser)
        <tr>
            <td style="text-align: center">
                {!! $carrierUser->username !!}
            </td>
            <td style="text-align: center;">
                {!! $carrierUser->serviceTeam ? $carrierUser->serviceTeam->team_name : null !!}
            </td>
            <td style="text-align: center">
                <div class='btn-group'>
                    <button onclick="var _me = this;$.fn.winwinAjax.buttonActionSendAjax(_me,
                            '{!! route('carriers.showEditUserModal',$carrierUser->id) !!}',{},function(html){
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
                            '{!! route('carriers.toggleCarrierUserStatus',$carrierUser->id) !!}',
                            {},
                            function(resp){
                                _me.disabled = true;
                                $('#step3_button').click();
                            },
                            function() {

                            },
                            'PATCH'
                            )
                            " class='btn {!!  $carrierUser->isForbidden() ? 'btn-success' : 'btn-danger' !!}  btn-xs'>
                        <i class="fa fa-close">{!! $carrierUser->isForbidden() ? '开启' : '禁用' !!}</i>
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
                '{!! route('carriers.showCreateUserModal',$carrier->id) !!}',{},function(html){
                    var modal = $('#carrierUserAddEditModal');
                    modal.html(html).modal('show');
                },function() {

                },'GET',{
                    dataType:'html'
                }
        )">新增用户</button>
    </div>
</div>

<div class="clearfix"></div>