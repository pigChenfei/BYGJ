<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">部门权限设置</h4>
        </div>

        <form id="team_permissions_select_form">
            <input type="hidden" name="_method" value="post">
            <input type="hidden" name="team_id" value="{{$team_id}}">
        <div class="modal-body" id="modalContent">

            <div class="row">
                <div class="col-sm-12">
                <!-- Submit Field -->
                        <div id="main">
                            <ul id='browser' class = 'filetree'>
                                @foreach($permissionGroups as $permissionGroup)
                                    <li>
                                        <span class='folder'><input type="checkbox"  id="t{{$permissionGroup->id}}" pId="t{{$permissionGroup->parent_id}}" value="{{$permissionGroup->id}}" onchange="child(this)"/>{{$permissionGroup->group_name}}</span>
                                        @foreach($permissionGroup->groups as $group)
                                                <ul>
                                                    <li><span class='folder'><input class="second_children_node" type="checkbox"  id='t{{$group->id}}' pId="t{{$group->parent_id}}" value="{{$group->id}}" onchange="child(this)"/>{{$group->group_name}}</span>
                                                        @foreach($group->permissions as $permission)
                                                            <ul>
                                                                <li><span class='file'><input type="checkbox" name="ids[]" id='t{{$permission->id}}' pId="t{{$group->id}}" value="{{$permission->id}}" onchange="child(this)" {{in_array($permission->id,$hasPermissions) ? 'checked="checked"' : ''}}/>{{$permission->description}}</span>
                                                                </li>
                                                            </ul>
                                                        @endforeach
                                                    </li>
                                                </ul>
                                        @endforeach
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="form-group col-sm-12">
                {!! TableScript::editFormSubmitAndCancelButtonsScript(Route('CarrierServiceTeams.permissionSave')) !!}
            </div>
        </div>
        </form>
    </div>
</div>


<script src="{{asset('js/vue.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/jquery.treeview.css') }}">
<script type="text/javascript" src="{{ asset('js/jquery.cookie.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.treeview.js') }}"></script>
<script>
    $(document).ready(function(){
        $("#browser").treeview({
            collapsed: true,
            control: "#sidetreecontrol",
            persist: "cookie",
            unique: true,
            finished:function(tree){
                $('.second_children_node').filter(function (index,element) {
                    return $(element).parents('li:eq(0)').find('input:checked').length > 0;
                }).each(function (index,element) {
                    var selectedCheckBox = $(element).parents('li:eq(0)').find('input:checked').eq(0);
                    var p = $(selectedCheckBox).attr("pId");
                    findParent(p);
                });
            }
        });
    });

//    function findParent1(p,checked) {
//        $("#"+p).prop("indeterminate",checked);//indeterminate:checkbox属性
//    }

//
    //   function findParent(p,checked) {
    //       $("#"+p).prop("indeterminate",checked);//indeterminate:checkbox属性
    //       var parentId =  $("#"+p).attr('pId');
    //       findParent1(parentId,checked);
    //
    //    }
    function findParent(p) {
           var dom = $('#'+p);
           if(dom.length > 0){
               var checkLevelLength = $("input[pId='"+p+"']:checked").length;
               var indeterminateLength = $("input[pId='"+p+"']:indeterminate").length;
               var levelLength = $("input[pId='"+p+"']").length;
               dom.prop("indeterminate",indeterminateLength > 0 || checkLevelLength > 0 && checkLevelLength != levelLength);
               dom.prop("checked",checkLevelLength == levelLength);
               //dom.prop("indeterminate",checked);//indeterminate:checkbox属性
               var parentId = dom.attr('pId');
               findParent(parentId);
           }
    }

    function findChildren(pId,checked){
        var children = $("input[pId='"+pId+"']");
        if(children.length > 0){
            for(var i = 0; i < children.length; i++){
                $(children[i]).prop("checked", checked);
                $(children[i]).prop("indeterminate", false);
                findChildren(children[i].id,checked)
            }
        }
    }

    function child(e) {
        var pId = e.id;//获取父ID
        var checked = e.checked;//获取状态
        var p = $(e).attr("pId");
        findParent(p);
        findChildren(pId,checked);
    }

</script>