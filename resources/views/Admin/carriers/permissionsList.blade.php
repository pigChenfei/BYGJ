<table class="table table-bordered table-responsive table-hover" style="text-align: left">
    <tbody>
    @foreach($permissionGroups as $permissionGroup)
        <tr>
            <td><a data-id="{!! $permissionGroup->id !!}" class="permissionTopGroup btn-link" style="cursor: pointer;text-decoration: none"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;{!! $permissionGroup->group_name !!}</a></td>
            <td style="width: 100px;text-align: center"><input type="checkbox" data-id="{!! $permissionGroup->id !!}" class="minimal permissionTopGroupCheckbox" name="" id="permissionTopGroupCheckbox_{!! $permissionGroup->id  !!}"> </td>
        </tr>
        @foreach($permissionGroup->groups as $group)
            <tr style="display: none"  class="permissionGroup permissionTopGroup_{!! $permissionGroup->id !!}" data-id="{!! $group->id !!}">
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a data-id="{!! $group->id !!}" class="btn-link" style="cursor: pointer;text-decoration: none"><i class="fa fa-plus-circle"></i>&nbsp;&nbsp;{!! $group->group_name !!}</a></td>
                <td style="text-align: center"><input type="checkbox" data-top-group-id="{!! $permissionGroup->id !!}" data-id="{!! $group->id !!}" class="minimal permissionGroupCheckbox permissionGroupCheckbox_{!! $permissionGroup->id !!}" name="" id="permissionGroupCheckbox_{!! $group->id !!}"> </td>
            </tr>
            @foreach($group->permissions as $permission)
                <tr class="permissions_{!! $group->id !!}" style="display: none">
                    <td>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{!! $permission->description !!}
                    </td>
                    <td style="text-align: center">
                        <input {!! in_array($permission->id,$teamPermissions) ? 'checked' : '' !!} type="checkbox" data-group-id="{!! $group->id !!}" data-id="{!! $permission->id !!}" class="minimal permissionCheckbox permissionCheckbox_{!! $group->id !!}" name="permission[]" value="{!! $permission->id !!}" id="{!! $permission->id !!}">
                    </td>
                </tr>
            @endforeach
        @endforeach
    @endforeach
    </tbody>
</table>
<div class="clearfix"></div>
<script>
    $(function(){

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue'
        });
        
        var permissionTopGroupCheckboxClick = function () {
            this.checked = !this.checked;
            var dataId  = $(this).attr('data-id');
            var permissionGroupCheckBoxes = $('.permissionGroupCheckbox_' + dataId);
            if(this.checked){
                permissionGroupCheckBoxes.each(function(index,element){
                    $(element).iCheck('check');
                    element.checked = false;
                    permissionGroupCheckboxClick.call(element);
                });
            }else{
                permissionGroupCheckBoxes.each(function(index,element){
                    $(element).iCheck('uncheck');
                    element.checked = true;
                    permissionGroupCheckboxClick.call(element);
                });
            }
        };

        var permissionGroupCheckboxClick = function () {
            this.checked = !this.checked;
            console.log(this.checked);
            var groupId = $(this).attr('data-id');
            var topGroupId = $(this).attr('data-top-group-id');
            if(this.checked){
                $('.permissionCheckbox_'+groupId).each(function (index,element) {
                    $(element).iCheck('check');
                })
            }else{
                $('.permissionCheckbox_'+groupId).each(function (index,element) {
                    $(element).iCheck('uncheck');
                })
            }
            var currentGroupSelectLength = 0;
            var currentGroupAllLength = 0;
            var topGroupCheckbox = $('#permissionTopGroupCheckbox_'+topGroupId);
            $('.permissionGroupCheckbox_'+topGroupId).each(function(index,element){
                currentGroupAllLength += 1;
                currentGroupSelectLength += element.checked ? 1 : 0;
            });
            if(currentGroupSelectLength == currentGroupAllLength){
                topGroupCheckbox.iCheck('check');
            }else{
                topGroupCheckbox.iCheck('uncheck');
            }
        };

        var permissionCheckboxClick = function () {
            this.checked = !this.checked;
            var groupId = $(this).attr('data-group-id');
            var currentGroupCheckbox = $('#permissionGroupCheckbox_'+groupId);
            var topGroupId = currentGroupCheckbox.attr('data-top-group-id');
            var currentGroupChildrenSelectLength = 0;
            var currentGroupChildrenAllLength = 0;
            $('.permissionCheckbox_'+groupId).each(function(index,element){
                currentGroupChildrenAllLength += 1;
                currentGroupChildrenSelectLength += element.checked ? 1 : 0;
            });
            if(currentGroupChildrenAllLength == currentGroupChildrenSelectLength){
                currentGroupCheckbox.iCheck('check');
                var currentGroupSelectLength = 0;
                var currentGroupAllLength = 0;
                var topGroupCheckbox = $('#permissionTopGroupCheckbox_'+topGroupId);
                $('.permissionGroupCheckbox_'+topGroupId).each(function(index,element){
                    currentGroupAllLength += 1;
                    currentGroupSelectLength += element.checked ? 1 : 0;
                });
                if(currentGroupSelectLength == currentGroupAllLength){
                    topGroupCheckbox.iCheck('check');
                }else{
                    topGroupCheckbox.iCheck('uncheck');
                }
            }else{
                currentGroupCheckbox.iCheck('uncheck');
                $('#permissionTopGroupCheckbox_'+topGroupId).iCheck('uncheck');
            }
            console.log(currentGroupChildrenSelectLength,currentGroupChildrenAllLength,currentGroupCheckbox)
        };

        $('.permissionTopGroupCheckbox').on('ifClicked',permissionTopGroupCheckboxClick);
        $('.permissionGroupCheckbox').on('ifClicked',permissionGroupCheckboxClick);

        $('.permissionCheckbox').on('ifClicked',permissionCheckboxClick);

        var _tempLastCheckedBoxGroupId = null;
        $('.permissionCheckbox[checked]').each(function (index,element) {
            var groupId = $(element).attr('data-group-id');
            if(!_tempLastCheckedBoxGroupId || _tempLastCheckedBoxGroupId != groupId){
                _tempLastCheckedBoxGroupId  = groupId;
            }
            element.checked = false;
            permissionCheckboxClick.call(element);
        });


        var lastOpenTopGroup = null;
        var lastOpenGroup    = null;

        $('.permissionTopGroup').on('click',function (e) {
            var _me = this;
            if(lastOpenTopGroup && lastOpenTopGroup != _me && lastOpenTopGroup.hasOpened){
                var lastOpenTopGroupId = $(lastOpenTopGroup).attr('data-id');
                $('.permissionTopGroup_'+lastOpenTopGroupId).animate({opacity:'toggle',height:'toggle'},0);
                $(lastOpenTopGroup).find('i:eq(0)').toggleClass('fa-plus-circle',true).toggleClass('fa-minus-circle');
            }
            var id = $(_me).attr('data-id');
            $('.permissionTopGroup_'+id).animate({opacity:'toggle',height:'toggle'},'normal');
            $(_me).find('i:eq(0)').toggleClass('fa-plus-circle',true).toggleClass('fa-minus-circle');
            lastOpenTopGroup = _me;
            if($(_me).find('i:eq(0)').hasClass('fa-minus-circle')){
                lastOpenTopGroup.hasOpened = true;
            }else{
                lastOpenTopGroup.hasOpened = false;
            }
            if(lastOpenGroup && lastOpenGroup.hasOpened){
                var lastOpenGroupId = $(lastOpenGroup).attr('data-id');
                $('.permissions_'+lastOpenGroupId).animate({opacity:'toggle',height:'toggle'},0);
                $(lastOpenGroup).find('i:eq(0)').toggleClass('fa-plus-circle',true).toggleClass('fa-minus-circle');
            }
            lastOpenGroup = null;
        })


        $('.permissionGroup').on('click','a',function (e) {
            var _me = this;
            if(lastOpenGroup && lastOpenGroup != _me && lastOpenGroup.hasOpened){
                var lastOpenGroupId = $(lastOpenGroup).attr('data-id');
                $('.permissions_'+lastOpenGroupId).animate({opacity:'toggle',height:'toggle'},0);
                $(lastOpenGroup).find('i:eq(0)').toggleClass('fa-plus-circle',true).toggleClass('fa-minus-circle');
            }
            var id = $(_me).attr('data-id');
            $('.permissions_'+id).animate({opacity:'toggle',height:'toggle'},'normal');
            $(_me).find('i:eq(0)').toggleClass('fa-plus-circle',true).toggleClass('fa-minus-circle');
            lastOpenGroup = _me;
            if($(_me).find('i:eq(0)').hasClass('fa-minus-circle')){
                lastOpenGroup.hasOpened = true;
            }else{
                lastOpenGroup.hasOpened = false;
            }
        })

    })
</script>
