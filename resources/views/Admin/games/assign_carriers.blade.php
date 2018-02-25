<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">分配运营商</h4>
        </div>
        <form action="" id="assign_carrier_games_form">
        <div class="modal-body" id="modalContent">
            <div class="row">
                <div class="col-sm-12">
                    <select id="duallist" multiple="multiple" size="20">
                        @foreach($allCarriers as $carrier)
                            @if($selectedCarriers->contains(function($value,$key) use ($carrier){
                                return $value->id == $carrier->id;
                            }))
                                <option selected value="{!! $carrier->id !!}">{!! $carrier->name !!}</option>
                            @else
                                <option value="{!! $carrier->id !!}">{!! $carrier->name !!}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
            <select name="game_ids[]" multiple style="display: none" id="game_id_select">
            @foreach($games as $game)
                <option selected value="{!! $game->game_id !!}">
            @endforeach
            </select>
        <div class="modal-footer">
            <!-- Submit Submit -->
            <div class="form-group col-sm-12">
                <div class="btn-group">
                    <button id="save" type="button" onclick="var _me = this;
                            var data = {
                                game_ids:  $('#game_id_select').val(),
                                carrier_ids : $('#duallist').val()
                            };
                            $.fn.winwinAjax.buttonActionSendAjax(_me,
                            '{!! route('games.updateCarriersGames') !!}',
                            data,
                            function() {
                                $.fn.alertSuccess('操作成功');
                                $('#editAddModal').modal('hide');
                            },null,'POST')" class="btn btn-success"><i class="fa fa-save"></i> 保存</button>
                </div>
                {{--{!! TableScript::addFormSubmitAndCancelButtonsScript(route('carriers.store')) !!}--}}
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    $("#duallist").bootstrapDualListbox({
        showFilterInputs:false,
        infoText:'',
        selectorMinimalHeight:300,
        selectedListLabel:'已分配的运营商',
        nonSelectedListLabel:'未分配的运营商'
    });
</script>
