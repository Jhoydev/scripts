<?php

$link = mysqli_connect("127.0.0.1", "root", "root", "fitcrm");

$res = getTablesWithFields($link,'fitcrm',['name','email']);

print_r($res);


/**
 * Funcion que nos ayuda a obtener las tablas de una base de datos
 * que continen las columnas indicadas
 * @param $link
 * @param $database
 * @param $columns
 * @return array
 */
function getTablesWithFields($link,$database,$columns){
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

        for ($i=0; $i < count($columns); $i++) {
            $arr_aux[$columns[$i]] = false;
        }
        

        foreach ($columns_name as $column_name) {
            
            for ($i=0; $i < count($columns); $i++) {
                if (!$arr_aux[$columns[$i]] && $column_name['Field'] == $columns[$i]) {
                    $arr_aux[$columns[$i]] = true;
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

