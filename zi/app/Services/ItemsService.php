<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\RtfReader;
use Illuminate\Support\Facades\Cache;

class ItemsService {

    const ITEMS_TABLE = 'GM_ASORTMAG AS asort_mag';

    public static function getItemsFromGroup($group, $ktrh, $arePricesNet) {
//        $asorts = DB::table('GM_ASORTMAG')
//            ->select('ASORT_N AS name')
//            ->get();
//        return $asorts->toArray();
//            "IDMAG":"6575",
//            "ASORT":"2763",
//            "NAZWA":"Pieprz czarny",
//            "INDEKS":"ID0000000108",
//            "KOD_KRESK":null,
//            "ILOSC":"0.0000001",
//            "CENA":"0.0001",
//            "ILOSC_ZAM":"0.0000001",
//            "NETTO":"0.01",
//            "BRUTTO":"0.01",
//            "PROM":"1",
//            "WYPRZ":"0",
//            "NOWOSC":"0",
//            "PROD":null,
//            "PROD_N":"????",
//            "IS_PICTURE":"0",
//            "IS_OPIS":"0"}

        if ($group !== 'GRP88' || !Cache::has("itemsFromGroupGRP88Ktrh{$ktrh}")) {
            $asorts = DB::select("select 
              IDMAG as id,
              NAZWA as name,
              INDEKS,
              ILOSC as quantity,
              JM_N  as QUANTITY_UNIT,
              DOKL as QUANTITY_PRECISION,
              CENA as price,
              IS_PICTURE as is_picture,
              IS_OPIS as is_description,
              PROD_N as producer_code,
              PROM as is_discount,
              WYPRZ as is_sale,
              NOWOSC as is_news
              from ZI_ASORT('{$group}', '{$ktrh}', '')");
        }

        if ($group == 'GRP88') {
            $asorts = Cache::remember("itemsFromGroupGRP88Ktrh{$ktrh}", 10, function () use( &$asorts) {
                        return $asorts;
                    });
        }
//            ->select(
//                'asort_mag.ASORT_N AS name',
//                'asort_mag.INDEKS AS index',
//                'asort_mag.PRODUCENT_CODE AS producer_code',
//                DB::raw('(SELECT vcena FROM gm_find_cena_ktrh(\'222\',"asort_mag"."KOD",\'TODAY\',NULL,NULL,NULL,NULL,1)) AS net_price'),
//                DB::raw('("asort_mag"."ILOSC" - "asort_mag"."REZ") AS quantity_1'),
//                'asort_mag.KOD AS code',
////                'asort_mag.PICTURE AS picture',
//                'asort_mag.OPIS AS description',
//                'asort_mag.ILOSC_MAX_INET AS quantity_2'
//                )
//            ->where('IS_HIDEN', 0)
//            ->when($skip, function ($query) use ($skip) {
//                return $query->skip($skip);
//            })
//            ->when($take, function ($query) use ($take) {
//                return $query->take($take);
//            });



        $count = 10;

        $asorts = ItemsService::addCartValues($asorts);

        $result['data'] = $asorts;
        $result['totalCount'] = 10;
        $result['arePricesNet'] = $arePricesNet;
        $result['priceFilterOff'] = session('priceFilterOff');

        return $result;
    }

    private static function addCartValues($asorts) {
        $addCartValueForItem = function ($product) {
            $product->CART_QUANTITY = CartService::getCurrentQuantityForProduct($product->ID);
            $product->PRICE = (float) $product->PRICE;
            $product->QUANTITY = (float) $product->QUANTITY;
            return $product;
        };

        return array_map($addCartValueForItem, $asorts);
    }

    public static function getItem($id, $ktrh, $arePricesNet) {
        $itemFromDb = DB::select("select 
              IDMAG as id,
              NAZWA as name,
              INDEKS,
              ILOSC as quantity,
              DOKL as QUANTITY_PRECISION,
              NETTO as net_price,
              BRUTTO as gross_price,
              PROD_N as producer_code,
              PROM as is_discount,
              WYPRZ as is_sale,
              PICTURE as is_picture,
              PROM as is_discount,
              NOWOSC as is_news,
              WYPRZ as is_sale,
              OPIS as DESCRIPTION,
              WAGA as WEIGHT,
              WAGA_JM as WEIGHT_UNIT,
              ILOSC_ZB as QUANTITY_IN_UNIT,
              DOKL_ZB as QUANTITY_IN_UNIT_PRECISION
              from ZI_ASORT_ONE('{$id}', '{$ktrh}')")[0];
        if (strlen($itemFromDb->IS_PICTURE) > 0) {
            $itemFromDb->IS_PICTURE = true;
        } else {
            $itemFromDb->IS_PICTURE = false;
        }

        if (strlen($itemFromDb->DESCRIPTION) > 0) {
            $itemFromDb->DESCRIPTION = str_replace("\n", " ", $itemFromDb->DESCRIPTION);
            // $reader = new RtfReader();
            // $parsingResult = $reader->Parse("{\rtf1\ansi\ansicpg1250\deff0\deflang1045{\fonttbl{\f0\fnil\fcharset238 Tahoma;}} \viewkind4\uc1\pard\f0\fs16 Moc \tab 1200 W\par \'8crednica tarczy \tab 185 mm\par \'8crednica otworu tarczy \tab 20 mm\par G\'b3\'eaboko\'9c\'e6 ci\'eacia \tab pod k\'b9tem 90\'b0: 64 mm\par Wyposa\'bfenie \tab dodatkowa tarcza 185 mm (48 z\'eab\'f3w), klucz do montowania tarczy, walizka\par Napi\'eacie zasilaj\'b9ce \tab 230 V - 50 Hz\par Informacje dodatkowe \tab Regulacja k\'b9ta ci\'eacia\par }");
            // if ($parsingResult) {
            //     $phpWord = \PhpOffice\PhpWord\IOFactory::load($itemFromDb->DESCRIPTION, 'RTF');
            //     $formatter = new RtfHtml();
            //     $itemFromDb->DESCRIPTION = $formatter->Format($reader->root);
            // }
        }

        ItemsService::convertIntToBoolean($itemFromDb, 'IS_DISCOUNT');
        ItemsService::convertIntToBoolean($itemFromDb, 'IS_SALE');
        ItemsService::convertIntToBoolean($itemFromDb, 'IS_NEWS');

        $itemFromDb->arePricesNet = $arePricesNet;

//		['arePricesNet'] = true;
        //$arePricesNet;

        return $itemFromDb;
    }

    public static function convertIntToBoolean($object, $property) {
        if ($object->{$property} == "1") {
            $object->{$property} = true;
        } else {
            $object->{$property} = false;
        }
    }

    public static function getPicture($id) {
        $itemFromDb = DB::select("select 
              PICTURE as picture
              from ZI_ASORT_ONE('{$id}', NULL)")[0];
        return $itemFromDb->PICTURE;
    }

    public static function getProductCategories($productCategoryId) {
        return DB::table('ZI_ASORT_GRP')
                        ->select(
                                'NAZWA as name', 'ID as id'
                        )
                        ->where('KIND', 'GRP')
                        ->when($productCategoryId == 'NULL', function ($query) {
                            return $query->whereNull('PREV');
                        })
                        ->when($productCategoryId != 'NULL', function ($query) use ($productCategoryId) {
                            return $query->where('PREV', $productCategoryId);
                        })
                        ->orderBy('NAZWA')
                        ->get()->toArray();
    }

    public static function getProductGroupDetails($productCategoryId) {
        return DB::table('ZI_ASORT_GRP')
                        ->select(
                                'NAZWA as name', 'ID as id', 'PREV as parentGroup'
                        )
                        ->where('KIND', 'GRP')
                        ->where('ID', $productCategoryId)
                        ->get()->first();
    }

    public static function getItemsByQuery($query, $category, $ktrh, $arePricesNet) {
        $query = strtolower($query);

        if ($category == '') {
            $category = 'ALL';
        }

        $asorts = DB::select("select 
              IDMAG as id,
              NAZWA as name,
              INDEKS,
              ILOSC as quantity,
              JM_N  as QUANTITY_UNIT,
              DOKL as QUANTITY_PRECISION,
              CENA as PRICE,
              IS_PICTURE as is_picture,
              IS_OPIS as is_description,
              PROD_N as producer_code,
              PROM as is_discount,
              WYPRZ as is_sale,
              NOWOSC as is_news
              from ZI_ASORT('{$category}', '{$ktrh}', '$query')
              ");

        $asorts = ItemsService::addCartValues($asorts);

        $result['data'] = $asorts;
        $result['totalCount'] = 0;
        $result['arePricesNet'] = $arePricesNet;
        $result['priceFilterOff'] = session('priceFilterOff');

        return $result;
    }

    private static function getBreadcrumbsForGroupInner($groupDetails, $resultArray) {
        if ($groupDetails->parentGroup == NULL) {
            return $resultArray;
        } else {
            $parentGroupDetails = ItemsService::getProductGroupDetails($groupDetails->parentGroup);
            array_unshift($resultArray, $parentGroupDetails);
            return ItemsService::getBreadcrumbsForGroupInner($parentGroupDetails, $resultArray);
        }
    }

    public static function getBreadcrumbsForGroup($groupDetails) {
        return ItemsService::getBreadcrumbsForGroupInner($groupDetails, [$groupDetails]);
    }

    public static function getProductTree() {
        $allProductGroups = ItemsService::getAllProductGroups();
        $groupIdToChildrenMap = ItemsService::createGroupIdToChildrenMap($allProductGroups);
        $treeInFrontendFormat = ItemsService::convertGroupIdToChildrenMapToFrontendFormat($groupIdToChildrenMap);
        $sortedTree = ItemsService::sortTreeByNumberOfItemsDesc($treeInFrontendFormat);
        return $sortedTree;
    }

    private static function getAllProductGroups() {
        return DB::table('ZI_ASORT_GRP')
                        ->select(
                                'NAZWA as name', 'ID as id', 'PREV as parentGroup'
                        )
                        ->where('KIND', 'GRP')
                        ->orderBy('name', 'desc')
                        ->get()->toArray();
    }

    private static function createGroupIdToChildrenMap($allProductGroups) {
        $result = [];
        foreach ($allProductGroups as $productGroup) {
            if (isset($result[$productGroup->parentGroup])) {
                array_push($result[$productGroup->parentGroup], $productGroup);
            } else {
                $result[$productGroup->parentGroup] = [$productGroup];
            }
        }
        return $result;
    }

    private static function convertGroupIdToChildrenMapToFrontendFormat($groupIdToChildrenMap) {
        if ($groupIdToChildrenMap) 
            $parentGroups = $groupIdToChildrenMap[""];
        
        $result = [];
        if ($groupIdToChildrenMap) 
            foreach ($parentGroups as $group) {
                array_push($result, ItemsService::getTreeObjectForGroup($group, $groupIdToChildrenMap));
            }
        return $result;
    }

    private static function sortTreeByNumberOfItemsDesc($treeInFrontendFormat) {
        usort($treeInFrontendFormat, function($a, $b) {
            return count($b['items']) - count($a['items']);
        });

        usort($treeInFrontendFormat, function($a, $b) {
            return strcmp($a['text'], $b['text']);
        });

        return $treeInFrontendFormat;
    }

    private static function getTreeObjectForGroup($group, $groupIdToChildrenMap) {
        if (isset($groupIdToChildrenMap[$group->id])) {
            return [
                'id' => $group->id,
                'text' => trim($group->name),
                'items' => array_map(
                        function($group) use ($groupIdToChildrenMap) {
                            return ItemsService::getTreeObjectForGroup($group, $groupIdToChildrenMap);
                        }, $groupIdToChildrenMap[$group->id]
                )
            ];
        } else {
            return [
                'id' => $group->id,
                'text' => trim($group->name),
                'items' => []
            ];
        }
    }

	public static function getKtrh($user)	

	{

		if (Cache::has('itemsKtrh'))
		{
			$items = Cache::get('itemsKtrh');
		} else {
			$items = Cache::remember('itemsKtrh', 5, function() use ($user)
							{	$x = DB::select("SELECT 
												KOD as KOD,
												KTRH_N as KONTRAHENT,
												KTRH_NIP as NIP
												FROM m_ktrh('{$user}')");
								return $x;
							});
			
		};

		$result['data'] = $items;		
		$result['user'] = $user;
		
        return $result;
    }		
}    