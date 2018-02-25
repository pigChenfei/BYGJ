<script>
    $(function(){
        var dataTable = window.LaravelDataTables["dataTableBuilder"];
        var params = dataTable.ajax.params();
        $('.select2').select2();
        $('#searchForm').on('submit', function (e) {
            e.preventDefault();
            dataTable.ajax.reload();
        })
    })
</script>