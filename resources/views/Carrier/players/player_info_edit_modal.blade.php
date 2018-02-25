<div class="modal-dialog modal-lg" style="min-width: 1280px" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">编辑{!! $player->user_name !!}</h4>
        </div>
        <div class="modal-body" id="modalContent">
            <div class="row">
                @include('Carrier.players.show')
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
            </div>
        </div>
    </div>
</div>