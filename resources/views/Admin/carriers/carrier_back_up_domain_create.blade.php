<div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">新增域名</h4>
        </div>
        <div class="modal-body" id="modalContent">
            <div class="row">
                <form id="carrier_back_up_domain_create_form">
                    <div class="form-group col-sm-12">
                        <label for="">域名</label>
                        <input name="domain" type="text" class="form-control">
                        <input name="carrier_id" type="hidden" value="{!! $carrier_id !!}" class="form-control">
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
                            '{!! route('carriers.createDomain',$carrier_id) !!}',
                            $('#carrier_back_up_domain_create_form').serializeJson(),
                            function() {
                                $.fn.alertSuccess('操作成功');
                                $('#carrierUserAddEditModal').modal('hide');
                                $('#step4_button').click();
                            },
                            function() {

                            },'POST')" class="btn btn-success"><i class="fa fa-save"></i> 保存</button>
                </div>
            </div>
        </div>
    </div>
</div>
