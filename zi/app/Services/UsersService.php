<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;


class UsersService
{

    public function testMethod()
    {
    }
	
	public function setPriceOff()
    {
		$agent = new Agent();	
		if ($agent->isMobile()) {
			$x=	session()->get('priceFilterOff');
			if($x=="null" or $x=="NaN" or $x=="") {
				session()->put( 'priceFilterOff', 1);
			};		
		};
    }


    public function getUsersByEmailAndPassword($email, $password)
    {
        $query = "SELECT ID as KOD, NAME, SURNAME, MAIL, KTRH, IS_NETTO FROM ZI_LOGIN (NULL, '{$email}', '{$password}')";
        $result = DB::select($query);
        return $result;
    }

    public function getUserById($identifier)
    {
		DB::beginTransaction();		
			$user = DB::table('KTRH_PERSONS')
				->select(
					'KOD as id',
					'IM as name',
					'NAZ as surname',
					'MAIL as mail',
					'KTRH as ktrh',
					'KTRH as is_netto'
				)
				->where('KOD', $identifier)
				->get()->first();
			$user->is_netto = DB::select("SELECT is_netto FROM ZI_PRMS('{$user->ktrh}')")[0]->IS_NETTO;
			
		DB::commit();		
		$this->setPriceOff();
        ItemsService::convertIntToBoolean($user, 'is_netto');
        return $user;
    }
	
	public static function getUserAdd($data)
    {
		session()->put('debug', '---- getUserAdd===>'.$data);
		
		DB::beginTransaction();
			$Item = DB::select("SELECT 
									ERROR,
									ERROR_MSG,
									EMAIL,
									PASSWD as PASSWORD
								FROM zi_user_add('{$data}')")[0];
		DB::commit();	
//		session()->put('debug', '---- select===>'.$Item->EMAIL.$Item->PASSWORD);
        $result = $Item;
        return $result;
    }  			
}