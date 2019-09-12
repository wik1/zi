<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class UniOrdersService
{
    public static function getOrdersForUser($ktrh)
    {

//        SELECT * FROM zi_zam (null, :ktrh);
        $orders = DB::select("select 
              KOD as id,
              NAZWA as DOCUMENT,            
                NR_TXT as NR,
                DATA_WYST as CREATE_DATE,
                DATA_REAL as REALIZATION_DATE,
                WARTOSC as VALUE1,                
                STATUS as STATUS,
                NETTO as NET_PRICE,
                BRUTTO as GROSS_PRICE
              from ZI_ZAM(NULL, '{$ktrh}') 
              ORDER BY DATA_WYST DESC");
        return $orders;

    }

    public static function getOrder($id)
    {
        /*["KOD"]=> string(3) "580"
    ["NRDOK"]=> string(1) "8"
    ["SOURCE"]=> string(1) "0"
    ["DATAZAM"]=> string(19) "2017-01-20 00:00:00"
    ["DATAREAL"]=> string(19) "2017-01-20 00:00:00"
    ["KTRH"]=> string(4) "7392"
    ["SRTRANS"]=> string(1) "1"
    ["UWTRANS"]=> NULL
    ["WALUTA"]=> string(1) "1"
    ["ROUND"]=> string(1) "2"
    ["STATE"]=> string(1) "0"
    ["REALPOZ"]=> string(1) "0"
    ["NREALPOZ"]=> string(1) "1"
    ["ALLPOZ"]=> string(1) "1"
    ["REZ"]=> string(1) "0"
    ["TYP_PLAT"]=> string(1) "6"
    ["TERM_PLAT"]=> string(2) "21"
    ["ISSUM"]=> string(1) "0"
    ["WALUTA_DATE"]=> string(19) "2017-01-19 00:00:00"
    ["AKW"]=> NULL
    ["NETTO_WALUTA"]=> string(4) "0.01"
    ["BRUTTO_WALUTA"]=> string(4) "0.01"
    ["VAT_WALUTA"]=> string(4) "0.01"
    ["NETTO_EURO"]=> string(4) "0.01"
    ["BRUTTO_EURO"]=> string(4) "0.01"
    ["VAT_EURO"]=> string(4) "0.01"
    ["NETTO_DEFAULT"]=> string(4) "0.01"
    ["BRUTTO_DEFAULT"]=> string(4) "0.01"
    ["VAT_DEFAULT"]=> string(4) "0.01"
    ["DOCSTATE"]=> string(1) "2"
    ["OWNER"]=> string(8) "SPRZEDAZ"
    ["FIRMA"]=> string(1) "1"
    ["SYSNR"]=> string(1) "2"
    ["BINDDOC"]=> string(16) "OFE 225= 1/2017;"
    ["TYPE_RAB"]=> string(1) "1"
    ["RAB_TRANS"]=> string(4) "0.00"
    ["RAB_PLAT"]=> string(4) "0.00"
    ["WART_BASE_W"]=> string(4) "0.01"
    ["WART_BASE_D"]=> string(4) "0.01"
    ["WART_BASE_E"]=> string(4) "0.01"
    ["WART_BASE_B_W"]=> string(4) "0.01"
    ["WART_BASE_B_D"]=> string(4) "0.01"
    ["WART_BASE_B_E"]=> string(4) "0.01"
    ["LG_DOST_CNT"]=> string(1) "0"
    ["LG_DOST_REAL_CNT"]=> string(1) "0"
    ["DATA_PLAT"]=> string(19) "2017-02-10 00:00:00"
    ["PUSER"]=> string(8) "SPRZEDAZ"
    ["PCHECK"]=> NULL
    ["PCLOSE"]=> NULL
    ["RABAT"]=> string(4) "0.01"
    ["CURR_YEAR"]=> string(4) "2017"
    ["TO_CLEAR"]=> NULL
    ["TYPE_RAB_KTRH"]=> string(1) "1"
    ["OSW_USE"]=> string(1) "0"
    ["GEN_POKW_POBR"]=> string(1) "0"
    ["USE_KURIER"]=> string(1) "0"
    ["LIST_PRZEW"]=> NULL
    ["KURIER"]=> NULL
    ["KWOTA_POBR"]=> NULL
    ["OSOBA_ZAM"]=> NULL
    ["OSOBA_ODB"]=> NULL
    ["OSOBA_FAKT"]=> NULL
    ["ZAM_PROD"]=> NULL
    ["TXT_BEFORE_DOC"]=> string(0) ""
    ["TXT_AFTER_DOC"]=> string(0) ""
    ["DOC_ORG"]=> string(0) ""
    ["KONTRAKT"]=> NULL
    ["WART_FROM_JM"]=> string(1) "1"
    ["POTW_CNT"]=> string(1) "0"
    ["WAGA_JM"]=> string(1) "1"
    ["WAGA_N"]=> string(5) "0.001"
    ["WAGA_B"]=> string(5) "0.001"
    ["FROMEDI"]=> NULL
    ["BANK_FAKT"]=> string(2) "19"
    ["MIEJSCE_ZALADUNKU"]=> NULL
    ["WART_ZREA_W"]=> string(4) "0.01"
    ["WART_ZREA_D"]=> string(4) "0.01"
    ["WART_ZREA_E"]=> string(4) "0.01"
    ["WART_ZREA_B_W"]=> string(4) "0.01"
    ["WART_ZREA_B_D"]=> string(4) "0.01"
    ["WART_ZREA_B_E"]=> string(4) "0.01"
    ["WART_ZARE_W"]=> string(4) "0.01"
    ["WART_ZARE_D"]=> string(4) "0.01"
    ["WART_ZARE_E"]=> string(4) "0.01"
    ["WART_ZARE_B_W"]=> string(4) "0.01"
    ["WART_ZARE_B_D"]=> string(4) "0.01"
    ["WART_ZARE_B_E"]=> string(4) "0.01"
    ["WART_ANUL_W"]=> string(4) "0.01"
    ["WART_ANUL_D"]=> string(4) "0.01"
    ["WART_ANUL_E"]=> string(4) "0.01"
    ["WART_ANUL_B_W"]=> string(4) "0.01"
    ["WART_ANUL_B_D"]=> string(4) "0.01"
    ["WART_ANUL_B_E"]=> string(4) "0.01"
    ["WART_DORE_W"]=> string(4) "0.01"
    ["WART_DORE_D"]=> string(4) "0.01"
    ["WART_DORE_E"]=> string(4) "0.01"
    ["WART_DORE_B_W"]=> string(4) "0.01"
    ["WART_DORE_B_D"]=> string(4) "0.01"
    ["WART_DORE_B_E"]=> string(4) "0.01"
    ["TRASA"]=> NULL
    ["NETTO_WALUTA_S"]=> string(4) "0.01"
    ["BRUTTO_WALUTA_S"]=> string(4) "0.01"
    ["VAT_WALUTA_S"]=> string(4) "0.01"
    ["NETTO_EURO_S"]=> string(4) "0.01"
    ["BRUTTO_EURO_S"]=> string(4) "0.01"
    ["VAT_EURO_S"]=> string(4) "0.01"
    ["NETTO_DEFAULT_S"]=> string(4) "0.01"
    ["BRUTTO_DEFAULT_S"]=> string(4) "0.01"
    ["VAT_DEFAULT_S"]=> string(4) "0.01"
    ["DATADOST"]=> string(19) "2017-01-20 00:00:00"
    ["ROLA"]=> string(1) "9"
    ["RAB_SOURCE"]=> string(1) "2"
    ["RAB_SOURCE_INS"]=> string(1) "2"
    ["UTYP"]=> string(4) "1001"
    ["DATA_WYS"]=> NULL
    ["REZ_REAL_MODE"]=> string(1) "1"
    ["DOCSTATE_EXT"]=> NULL
    ["KTRH_WZ"]=> string(4) "7392"
    ["DBD"]=> NULL
    ["DBD_KOD"]=> NULL
    ["DBD_TXT"]=> NULL
    ["DBS"]=> NULL
    ["DBS_KOD"]=> NULL
    ["DBS_TXT"]=> NULL
    ["V$KTRH"]=> string(5) "-1614"
    ["V$DOCSTATE"]=> NULL
    ["UMOWA"]=> NULL
    ["DOC_OD"]=> NULL
    ["DOC_OD_SOURCE"]=> string(1) "0"
    ["LOGPUMP_DOK"]=> NULL
    ["IMPRAW_IMPKOD"]=> NULL
    ["SELLFRU_MADE"]=> NULL
    ["CONTRACT"]=> NULL
    ["PROFORMA_USE"]=> NULL
    ["PROFORMA_NR"]=> NULL
    ["PROFORMA_DOCKIND"]=> NULL
    ["XML_IMP"]=> NULL
    ["KIND"]=> string(1) "1"
    ["RTF_BEFORE"]=> NULL
    ["RTF_AFTER"]=> NULL
    ["RTF_JOIN_CNT"]=> NULL
    ["RTF_BEFORE_P"]=> NULL
    ["RTF_AFTER_P"]=> NULL
    ["RTF_JOIN_CNT_P"]=> NULL
    ["RTF_BEFORE_F"]=> NULL
    ["RTF_AFTER_F"]=> NULL
    ["RTF_JOIN_CNT_F"]=> NULL
    ["PW_ETY"]=> NULL
    ["KTRH_ONE_TIME"]=> NULL
    ["KTRH_NAZWA"]=> NULL
    ["KTRH_ULICA"]=> NULL
    ["KTRH_KODPOCZT"]=> NULL
    ["KTRH_POCZTA"]=> NULL
    ["KTRH_TYPNIP"]=> NULL
    ["KTRH_NIP"]=> NULL
    ["DOST"]=> NULL
    ["DOST_NAZWA"]=> NULL
    ["DOST_ULICA"]=> NULL
    ["DOST_KODPOCZT"]=> NULL
    ["DOST_POCZTA"]=> NULL
    ["NR_TXT"]=> string(7) " 8/2017"
    ["KTRH_VER"]=> string(3) "101"
    ["KTRH_ODB"]=> string(4) "7392"
    ["KTRH_ODB_VER"]=> string(3) "101"
    ["DBS_SRC"]=> NULL
    ["DBD_DST"]=> NULL */

        $result = DB::select("select 
              KOD as id,
                NR_TXT as nr,
                DATA_WYST as create_date,
                DATA_REAL as realization_date,
                DATA_DOST as delivery_date,
                STATUS as status,
                NETTO as net_price,
                BRUTTO as gross_price,
                VAT as vat,
                IS_NETTO as IS_NETTO
              from ZI_ZAM('{$id}', NULL)")[0];

//        $result = DB::table('GM_NAGZAM')
//            ->select(
//                '*'
//            )
//            ->where('KOD', $id)
//            ->get()->first();


        $result2 = DB::select("select 
              NR as nr,
                INDEKS as indeks,
                NAZWA as name,
                ILOSC as quantity,
                CENA as price,
                RABAT as discount,
                DOKL as QUANTITY_PRECISION,
                JM_N as quantity_unit,
                WYDANO as issued,
                DO_WYDANIA as to_issue,
                MAGAZYN as warehouse,
                REZ as reserved,
                DO_REZ as to_reserve,
                NETTO as net_price,
                ST_VAT as vat,
                WARTOSC  as VALUE1,
                STATUS as status
              from ZI_ZAM_POZ('{$id}')");


//        $result2 = DB::table('GM_POZZAM')
//            ->join('GM_ASORTMAG', 'GM_POZZAM.IDMAG', '=', 'GM_ASORTMAG.KOD')
//            ->select(
//                'GM_POZZAM.ILOSC as QUANTITY',
//                'GM_POZZAM.NETTO_WALUTA as NET_PRICE',
//                'GM_ASORTMAG.ASORT_N as NAME'
//            )
//            ->where('NAG', $id)
//            ->where('ROLA', 1)
//            ->get();

        return [ 'order' => $result, 'positions' => $result2 ];
    }
}