<div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">新增用户</h4>
        </div>
        <div class="modal-body" id="modalContent">
            <div class="row">
                <form id="carrier_user_create_form">
                    <div class="form-group col-sm-12">
                        <label for="">用户名</label>
                        <input name="user_name" type="text" class="form-control">
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="">密码</label>
                        <input name="password" type="password" class="form-control">
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="">确认密码</label>
                        <input name="confirm_password" type="password" class="form-control">
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="">分配部门</label>
                        <select name="service_team" id="" class="form-control">
                            @foreach($carrier->serviceTeams as $serviceTeam)
                                <option value="{!! $serviceTeam->id  !!}">{!! $serviceTeam->team_name  !!}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

            </div>
        </div>
        <div class="modal-footer">
            <!-- Submit Submit -->
            <div class="form-group col-sm-12">
                <div class="btn-group">
                    <button id="save" type="button" onclick="var _me = this;$.fn.winwinAjax.buttonActionSendAjax(
                            _me,
                            '{!! route('carriers.createUser',$carrier->id) !!}',
                            $('#carrier_user_create_form').serializeJson(),
                            function() {
                                $.fn.alertSuccess('操作成功');
                                $('#carrierUserAddEditModal').modal('hide');
                                $('#step3_button').click();
                            },
                            function() {

                            },'POST')" class="btn btn-success"><i class="fa fa-save"></i> 保存</button>
                </div>
                {{--{!! TableScript::addFormSubmitAndCancelButtonsScript(route('carriers.store')) !!}--}}
            </div>
        </div>
    </div>
</div>
