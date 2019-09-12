<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;

class ConfigService
{
    public static function getPredefinedSalesGroup()
    {
        //return 'sd';
        $query = "SELECT ID,NAZWA FROM ZI_ASORT_GRP_PREDEF WHERE EXT = 'WYPRZ'";
        $result = DB::select($query);
        return $result[0];
    }

    public static function getPredefinedDiscountsGroup()
    {
        //return 'sd';
        $query = "SELECT ID,NAZWA FROM ZI_ASORT_GRP_PREDEF WHERE EXT = 'PROM'";
        $result = DB::select($query);
        return $result[0];
    }
}