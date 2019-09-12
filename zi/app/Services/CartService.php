<?php
/**
 * Created by PhpStorm.
 * User: M&P
 * Date: 22.04.2017
 * Time: 19:19
 */

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class CartService
{
    const CART_ITEMS_WITH_PRICES = 'cartItemsWithPrices';

    const BRUTTO_SUM_OF_CART = 'bruttoSumOfCart';

    const NETTO_SUM_OF_CART = 'nettoSumOfCart';

    const NUMBER_OF_ITEMS_IN_CART = 'numberOfItemsInCart';

    const CART_ITEMS = 'cartItems';

    const CART_TRANSPORT_TYPE = 'cartTransportType';

    const CART_PAYMENT_TYPE = 'cartPaymentType';
	
	const CART_COMMENTS = 'cartComments';
	
	const CART_ODBIORCA = 'cartOdbiorca';

	/*
			DB::beginTransaction();
			$user = DB::table('KTRH_PERSONS')
					->select(
						'KOD as id',
						'IM as name',
						'NAZ as surname',				
						'MAIL as email',
						'KTRH as ktrh',
						'KTRH as is_netto'
					)
					->where('KOD', $identifier)
					->get()->first();					
			
			$user->is_netto = DB::select("SELECT is_netto FROM ZI_PRMS('{$user->ktrh}')")[0]->IS_NETTO;
		DB::commit();
	*/
    public static function addProductToCart($product, $quantity, $session, $ktrh)
    {
		
		$currentValue = CartService::getCurrentQuantityForProduct($product);
		CartService::removeProductFromCart($product, $currentValue, $session, $ktrh);
		$session->push(self::CART_ITEMS, [
			'product_id' => (int)$product,
			'quantity' => (float)$quantity + $currentValue
		]);
		CartService::updateItemsWithPrices($session, $ktrh);
		
        return CartService::getReturnCartObject();
    }

    public static function getReturnCartObject()
    {
        return [
            'cartSize' => CartService::getNumberOfItemsInCart(),
            'cartValue' => CartService::getSumOfItemsInCart()
        ];
    }

    public static function getCurrentQuantityForProduct($productId)
    {
        $cart = CartService::getCartItems();
        if ($cart != NULL) {
            foreach ($cart as $cartItem) {
                if ($cartItem['product_id'] == $productId) {
                    return (float)$cartItem['quantity'];
                }
            }
        }
        return 0;
    }

    public static function removeProductFromCart($product, $quantity, $session, $ktrh)
    {
        $array = session(self::CART_ITEMS);
        if ($array != NULL) {
            if (($key = array_search([
                    'product_id' => (float)$product,
                    'quantity' => (float)$quantity
                ], $array, false)) !== FALSE
            ) {
                unset($array[$key]);
            }
            $session->put(self::CART_ITEMS, $array);
        }
    }

    public static function submitCart($session, $ktrh)
    {
        // SELECT zam FROM zi_basket2zam_as (:ibasket,  :ktrh, :datareal, :srtrans, :uwagi);
        $ibasket = CartService::getCartInStoredProcedureFormat();
        $ibasket = CartService::getCartInStoredProcedureFormat();
        $datareal = 'NULL';
        $srtrans = self::getTransportType();
        $typ_plat = self::getPaymentType();
        $uwagi = self::getCommentsText();
		$odbiorca = self::getOdbiorca();

        $query = "select * from ZI_BASKET2ZAM_AS('{$ibasket}',  '{$ktrh}', {$datareal}, '{$srtrans}', '{$uwagi}');";

	session('retData', 'retData=xnehjnx');
		$orderNumber = 0;
                // errorTxt
        
		try {
                    DB::transaction(
                            function () use ($query, &$orderNumber) {

                                $orderNumber=   DB::select($query)[0];

                            }, 30
                    );				
					
                    CartService::clearCart();

                    return $orderNumber->ZAM;
                } catch (QueryException $e) {
                    $x=	$e->getMessage();
                    $x= iconv('windows-1250', 'UTF-8', $x);
                    session()->put('errorTxt', $x);
                    
                    return 0;
                }

    }
	

    private static function getCartInStoredProcedureFormat()
    {
        $cart = CartService::getCartItems();
        $result = '';
        foreach ($cart as $cartItem) {
            $result .= $cartItem['product_id'] . '=' . $cartItem['quantity'] . ';';
        }
        return $result;
    }

    public static function getCartItems()
    {
        return session(self::CART_ITEMS);
    }

    public static function clearCart()
    {
        session([self::CART_ITEMS => []]);
        session([self::CART_ITEMS_WITH_PRICES => []]);
        self::updateNumberAndSumOfItemsInCart();
    }

    public static function getCartWithItemsInfo()
    {
        return [
            'items' => session(self::CART_ITEMS_WITH_PRICES, []),
            'sum' => self::getSumOfItemsInCart(),
            'selectedPaymentType' => self::getPaymentType(),
            'selectedTransportType' => self::getTransportType(),
			'addedCommentsText' => (string)self::getCommentsText(),
			'selectedOdbiorca' => self::getOdbiorca()
        ];
    }

    public static function getNumberOfItemsInCart()
    {
        return session(self::NUMBER_OF_ITEMS_IN_CART, 0);
    }

    public static function getSumOfItemsInCart()
    {
        return session(self::BRUTTO_SUM_OF_CART, 0);
    }

    public static function getNettoSumOfItemsInCart()
    {
        return session(self::NETTO_SUM_OF_CART, 0);
    }

    private static function updateItemsWithPrices($session, $ktrh)
    {
        $ibasket = CartService::getCartInStoredProcedureFormat();
        $datareal = 'NULL';
        $srtrans = '1';
        $typ_plat = 'NULL';

        /*
        ["CENA"]=> string(6) "0.0001"
        ["CENA_N"]=> string(6) "0.0001"
        ["CENA_B"]=> string(6) "0.0001"
        ["JM_N"]=> string(5) "opak."
        ["ILOSC"]=> string(9) "0.0000001"
        ["IDMAG"]=> string(4) "5678"
        ["NAZWA"]=> string(23) "Majonez tradycyjny 260g"
        ["INDEKS"]=> string(12) "ID0000000104"
        ["CENA_MAG"]=> string(6) "0.0001"
        ["CENA_BASE"]=> string(6) "0.0001"
        ["RABAT"]=> string(6) "0.0001"
        ["WARTOSC"]=> string(4) "0.01"
        ["RABAT_TXT"]=> string(0) ""
        ["NETTO"]=> string(4) "0.01"
        ["BRUTTO"]=> string(4) "0.01"
        ["MAG_ILOSC"]=> string(9) "0.0000001"*/
        $query = "select 
            CENA,
            CENA_N,
            CENA_B,
            JM_N,
            ILOSC,
            IDMAG,
            NAZWA,
            INDEKS,
            CENA_MAG,
            CENA_BASE,
            RABAT,
            WARTOSC,
            RABAT_TXT,
            NETTO,
            BRUTTO,
            MAG_ILOSC,
            DOKL
        from ZI_BASKET_V('{$ibasket}',  '{$ktrh}', {$datareal}, '{$srtrans}', {$typ_plat});";
		
/*		DB::transaction(
			function () use ($query, &$result) {
				
				$result=DB::select($query);
				
			}, 10
		);						
*/
        $result = DB::select($query);
		
        $session->put(self::CART_ITEMS_WITH_PRICES, $result);
        self::updateNumberAndSumOfItemsInCart();
        return $result;
    }

    private static function updateNumberAndSumOfItemsInCart()
    {
        $bruttoSum = 0;
        $nettoSum = 0;
        $numberOfItems = 0;
        if (session(self::CART_ITEMS_WITH_PRICES) != NULL) {
            foreach (session(self::CART_ITEMS_WITH_PRICES) as $cartItemWithPrice) {
                $bruttoSum = $bruttoSum + $cartItemWithPrice->BRUTTO;
                $nettoSum = $nettoSum + $cartItemWithPrice->NETTO;
                $numberOfItems = $numberOfItems + 1;
            }
        }
        session([self::BRUTTO_SUM_OF_CART => $bruttoSum]);
        session([self::NETTO_SUM_OF_CART => $nettoSum]);
        session([self::NUMBER_OF_ITEMS_IN_CART => $numberOfItems]);
    }

    public static function removeAll()
    {
        session([
            self::CART_ITEMS_WITH_PRICES => [],
            self::CART_ITEMS => [],
        ]);
        self::updateNumberAndSumOfItemsInCart();
    }

    public static function updateCartWithRequest($request, $session, $ktrh)
    {
        self::clearCart();
        foreach ($request as $requestItemKey => $requestItemValue) {
            if (self::startsWith($requestItemKey, 'item')) {
                $product = substr($requestItemKey, 4);
                $session->push(self::CART_ITEMS, [
                    'product_id' => (int)$product,
                    'quantity' => (float)$requestItemValue
                ]);
            }
        }
        session([
            self::CART_PAYMENT_TYPE => $request['paymentType'],
            self::CART_TRANSPORT_TYPE => $request['transportType'],
			self::CART_COMMENTS => $request['commentsText'],
        ]);
        CartService::updateItemsWithPrices($session, $ktrh);
    }

    static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public static function getCartSummary($session, $ktrh)
    {
        $ibasket = CartService::getCartInStoredProcedureFormat();
        $datareal = 'NULL';
        $srtrans = self::getTransportType();
        $typ_plat = self::getPaymentType();

        /**
        ["ID"]=> string(4) "WART"
        ["NAZWA"]=> string(6) "Koszyk"
        ["TRESC"]=> string(8) "Wartosć"
        ["KOD"]=> NULL
        ["WARTOSC"]=> string(6) "100.00"
         **/
        $query = "select 
            ID as ID,
            NAZWA as NAZWA,
            TRESC as TRESC,
            KOD as KOD,
            WARTOSC as WARTOSC
        from zi_basket_v_sum('{$ibasket}',  '{$ktrh}', {$datareal}, '{$srtrans}', {$typ_plat});";

        $result = DB::select($query);

        return $result;
    }

    private static function getTransportType()
    {
        $result = session(self::CART_TRANSPORT_TYPE);

        if (is_null($result) ) {
            return '1';
        } else {
            return $result;
        }
    }

    private static function getPaymentType()
    {
        $result = session(self::CART_PAYMENT_TYPE);

        if (is_null($result) ) {
            return 'NULL';
        } else {
            return $result;
        }
    }
	
	private static function getCommentsText()
    {
        $result = session(self::CART_COMMENTS);

        if (is_null($result) ) {
            return '';
        } else {
            return $result;
        }
    }
	
	private static function getOdbiorca()
    {
        $result = session(self::CART_ODBIORCA);

        if (is_null($result) ) {
            return Auth::user()->ktrh;
        } else {
            return $result;
        }
    }

    public static function getPaymentTypes($ktrh) {
        /**
        ["KOD"]=> string(1) "1"
        ["NAZWA"]=> string(8) "Gotówka"
        ["TERMIN"]=> string(1) "0"
        ["NAZWA_TERMIN"]=> string(14) "Gotówka 0 dni"
         **/
        $query = "select 
            KOD as KOD,
            NAZWA_TERMIN as NAZWA_TERMIN            
        from zi_typplat('{$ktrh}', NULL);";

        return DB::select($query);
    }

    public static function getTransportTypes($ktrh) {

        /**
        ["KOD"]=> string(1) "1"
        ["NAZWA"]=> string(7) "Własny"
         **/
        $query = "select 
            KOD as KOD,
            NAZWA as NAZWA
        from zi_srtrans('{$ktrh}', NULL);";

        return DB::select($query);
    }

    public static function updateCartSummaryWithRequest($all, $session, $ktrh)
    {
        session([
            self::CART_PAYMENT_TYPE => $all['paymentType'],
            self::CART_TRANSPORT_TYPE => $all['transportType'],
			self::CART_COMMENTS => $all['commentsText'],
        ]);
    }
}