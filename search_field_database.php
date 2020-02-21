<?php

$link = mysqli_connect("127.0.0.1", "root", "root", "fitcrm");

$res = getTablesWithFields($link,'fitcrm',['name']);

print_r($res);

function getTablesWithFields($link,$database,$fields){
    $sql = "SHOW FULL TABLES FROM $database";

    $rs = mysqli_query($link, $sql);

    $tables_name = mysqli_fetch_all($rs, MYSQLI_NUM);

    $tablas_target = [];


    foreach ($tables_name as $table_name) {

        $arr_aux = [];

        $sql = "SHOW columns from $table_name[0]";
        $rs = mysqli_query($link, $sql);
        $columns_name = mysqli_fetch_all($rs, 1);

        $arr_aux = [];

        for ($i=0; $i < count($fields); $i++) {
            $arr_aux[$fields[$i]] = false;
        }
        

        foreach ($columns_name as $column_name) {
            
            for ($i=0; $i < count($fields); $i++) {
                if (!$arr_aux[$fields[$i]] && $column_name['Field'] == $fields[$i]) {
                    $arr_aux[$fields[$i]] = true;
                }  
            }

        }

        $arr_aux_filter = array_filter($arr_aux,function($li){
            return $li == true;
        });

        if (count($arr_aux_filter) == count($arr_aux)) {
            array_push($tablas_target, $table_name[0]);
        }

    }

    return $tablas_target;

}

