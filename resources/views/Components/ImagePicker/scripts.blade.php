<script>
    $(function () {
        $('.banner_select2').select2({
            templateResult: function (state) {
                if (!state.id) { return state.text; }
                var imageSplit = state.text.split('/');
                var imageName = imageSplit[imageSplit.length - 1];
                var $state = $(
                        '<div>' +
                            '<div style="width: 80px;height:50px;background: url('+state.text+') no-repeat center;background-size: cover" class="pull-left" />' +
                            '<div> ' +
                            '<div class="pull-left" style="line-height: 50px;margin-left: 20px;">' + imageName+ '</div>' +
                            '<div class="clearfix"></div>' +
                        '</div>'
                );
                return $state;
            },
            //minimumResultsForSearch: Infinity,
            placeholder: "请选择图片"
        });
    })
</script>