<div id="{!! isset($id) ? $id : 'wangEditor' !!}" style="{!! isset($style) ? $style : 'height:400px;' !!}">
    {!! isset($defaultContent) ? $defaultContent : ''  !!}
</div>
@if(isset($name))
    <input type="hidden" name="{!! $name !!}" id="{!! $name !!}_editor_hidden_input">
@endif
<!--这里引用jquery和wangEditor.js-->
<script type="text/javascript">
    $(function () {
        var editor = new wangEditor('{!! isset($id) ? $id : 'wangEditor' !!}');
        editor.config.menus = $.map(wangEditor.config.menus, function(item, key) {
                 if (item === 'location') {
                     return null;
                 }
                 return item;
             });

        @if(isset($name))
            editor.onchange = function () {
                $("#{!! $name !!}_editor_hidden_input").val(this.$txt.html());
            };
        @endif
        editor.create();
    })
</script>