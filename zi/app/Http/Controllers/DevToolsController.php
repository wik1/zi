<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DevToolsController extends BaseViewController
{
    public function tableViewer()
    {
        return view('devtools.tableViewer');
    }



    public function tableData($tableName)
    {
//        select rdb$field_name from rdb$relation_fields
//where rdb$relation_name='YOUR-TABLE_NAME';

//        var_dump(DB::table('RDB$RELATION_FIELDS')
//            ->select('RDB$FIELD_NAME')
//            ->where('RDB$RELATION_NAME', $tableName)
//            ->get()->toArray()[0]);

        $columnNameExtractor = function ($columnObject) {
            return trim($columnObject->columnName);
        };


        $arrayFromDb =  DB::table('RDB$RELATION_FIELDS')
            ->select('RDB$FIELD_NAME as columnName')
            ->where('RDB$RELATION_NAME', $tableName)
            ->get()->toArray();

        $columnNames = array_map($columnNameExtractor, $arrayFromDb);

        sort($columnNames);

        return $columnNames;
    }
}
