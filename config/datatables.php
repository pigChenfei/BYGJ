<?php

return [
    /**
     * DataTables search options.
     */
    'search'          => [
        /**
         * Smart search will enclose search keyword with wildcard string "%keyword%".
         * SQL: column LIKE "%keyword%"
         */
        'smart'            => true,

        /**
         * Case insensitive will search the keyword in lower case format.
         * SQL: LOWER(column) LIKE LOWER(keyword)
         */
        'case_insensitive' => true,

        /**
         * Wild card will add "%" in between every characters of the keyword.
         * SQL: column LIKE "%k%e%y%w%o%r%d%"
         */
        'use_wildcards'    => false,
    ],

    /**
     * DataTables fractal configurations.
     */
    'fractal'         => [
        /**
         * Request key name to parse includes on fractal.
         */
        'includes'   => 'include',

        /**
         * Default fractal serializer.
         */
        'serializer' => 'League\Fractal\Serializer\DataArraySerializer',
    ],

    /**
     * DataTables script view template.
     */
    'script_template' => 'datatables::script',

    /**
     * DataTables internal index id response column name.
     */
    'index_column'    => 'DT_Row_Index',

    /**
     * Namespaces used by the generator.
     */
    'namespace'       => [
        /**
         * Base namespace/directory to create the new file.
         * This is appended on default Laravel namespace.
         * Usage: php artisan datatables:make User
         * Output: App\DataTables\UserDataTable
         * With Model: App\User (default model)
         * Export filename: users_timestamp
         */
        'base'  => 'DataTables',

        /**
         * Base namespace/directory where your model's are located.
         * This is appended on default Laravel namespace.
         * Usage: php artisan datatables:make Post --model
         * Output: App\DataTables\PostDataTable
         * With Model: App\Post
         * Export filename: posts_timestamp
         */
        'model' => '',
    ],

    /**
     * PDF generator to be used when converting the table to pdf.
     * Available generators: excel, snappy
     * Snappy package: barryvdh/laravel-snappy
     * Excel package: maatwebsite/excel
     */
    'pdf_generator'   => 'excel',

    /**
     * Snappy PDF options.
     */
    'snappy'          => [
        'options'     => [
            'no-outline'    => true,
            'margin-left'   => '0',
            'margin-right'  => '0',
            'margin-top'    => '10mm',
            'margin-bottom' => '10mm',
        ],
        'orientation' => 'landscape',
    ],

    'language' => [
        "sProcessing" => "查询中...",
        "sLengthMenu"=> "显示 _MENU_ 项结果",
        "sZeroRecords"=> "没有匹配结果",
        "sInfo"=> "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty"=> "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered"=> "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix"=> "",
        "sSearch"=> "搜索:",
        "sUrl"=> "",
        "sEmptyTable"=> "表中数据为空",
        "sLoadingRecords"=> "载入中...",
        "sInfoThousands"=> ",",
        "oPaginate"=> [
            "sFirst"=> "首页",
            "sPrevious"=> "上页",
            "sNext"=> "下页",
            "sLast"=> "末页",
        ],
        "oAria"=> [
            "sSortAscending"=> ": 以升序排列此列",
            "sSortDescending"=> ": 以降序排列此列"
        ]
    ],

    'ajax' => ['data' => 'function(data){
                    var formData = $(\'#searchForm\').serializeJson();
                    for(index in data.columns){
                        for(dataName in formData){
                             if(data.columns[index].name == dataName){
                                data.columns[index].search.value = formData[dataName];
                                delete formData[dataName]
                             }
                             if(dataName == \'search[value]\' && formData[dataName]){
                                data.search.value = formData[dataName];
                                delete formData[dataName]
                             }
                             if(dataName == \'search[regex]\' && formData[dataName]){
                                data.search.regex = formData[dataName];
                                delete formData[dataName]
                             }
                        }
                    }
                    for(dataName in formData){
                        data[dataName] = formData[dataName];
                    }
                }'
    ]

];
