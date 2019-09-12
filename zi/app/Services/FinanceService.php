<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class FinanceService
{

    public static function getHeadline($ktrh)
    {
        return DB::select("select 
              SALDO as SALDO,
                PRZETERM as OVERDUE_PAYMENTS,
                KREDYT as LIMIT,
                KREDYT_SALDO as LIMIT_LEFT
              from ZI_KTRH_FIN('{$ktrh}')")[0];
    }

    public static function getOverdueDocuments($ktrh)
    {
        $documents = DB::select("select 
              DATA as DATE1,
                TRESC as DOCUMENT,
                WART as VALUE1,
                SPL as PAYED,
                DO_ZAPL as TO_PAY,
                PRZETERM as OVERDUE,
                DATA_PLAT as PAYMENT_DATE
              from ZI_KTRH_ROZRACH('{$ktrh}')");
        return $documents;
    }

    public static function getInvoices($ktrh)
    {
        $invoices = DB::select("select 
              ID as ID,
              NAZWA as DOC_NAME,
              NR_TXT as NR,
              DATA_WYST as CREATE_DATE,
              DATA_PLAT as PAYMENT_DATE,
              Netto as NETTO,
              VAT as VAT,
              BRUTTO as BRUTTO,
              STATUS_PLAT as STATUS
              from ZI_FAKT(NULL, '{$ktrh}')");
        return $invoices;
    }

    public static function getInvoice($id, $ktrh)
    {
        $headline = DB::select("select 
              ID as ID,
              NAZWA as DOC_NAME,
              NR_TXT as NR,
              DATA_WYST as CREATE_DATE,
              DATA_PLAT as PAYMENT_DATE,
              Netto as NET_PRICE,
              VAT as VAT,
              BRUTTO as GROSS_PRICE,
              STATUS_PLAT as STATUS,
              IS_NETTO as IS_NETTO
              from ZI_FAKT(NULL, '{$ktrh}')
              where ID = '{$id}'
              ")[0];
        $positions = DB::select("select 
              NR as NR,
              NAZWA as NAME,
              INDEKS  as INDEKS,
              ILOSC  as QUANTITY,
              DOKL as QUANTITY_PRECISION,
              JM_N  as QUANTITY_UNIT,
              CENA  as PRICE,
              RABAT  as DISCOUNT,
              WARTOSC  as VALUE1
              from ZI_FAKT_POZ('{$id}')");
        $result['headline'] = $headline;
        $result['positions'] = $positions;
        return $result;
    }
}