<div class="modal-dialog modal-lg" style="min-width: 1280px" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">编辑运营商</h4>
        </div>
        <div class="modal-body" id="modalContent">
            <div class="row">
                @include('Admin.carriers.fields')
            </div>
        </div>
        <div class="modal-footer">
            <!-- Submit Submit -->
            <div class="form-group col-sm-12">
                <div class="btn-group">
                    <button id="save" type="button" class="btn btn-success"><i class="fa fa-save"></i> 保存</button>
                </div>
                {{--{!! TableScript::addFormSubmitAndCancelButtonsScript(route('carriers.store')) !!}--}}
            </div>
        </div>
    </div>
</div>
