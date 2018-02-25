<script>
    $(function(){
        if(window.LaravelDataTables){
            var dataTable = window.LaravelDataTables["dataTableBuilder"];
            var params = dataTable.ajax.params();
            $('#searchForm').on('submit', function (e) {
                e.preventDefault();
                dataTable.ajax.reload();
            })
        }
        $('.select2').select2();
        $('.disable_search_select2').select2({
            minimumResultsForSearch: Infinity
        });
    })
</script>