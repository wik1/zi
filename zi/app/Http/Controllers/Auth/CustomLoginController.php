<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use App\Services\UsersService;

class CustomLoginController extends Controller
{    
	protected function silentAuthenticate()
	{	
		$enabled = config('uni.guest_login') and Auth::guest();
		if ($enabled) {			
			Auth::attempt(['email' => config('uni.guest_email'), 'password' =>config('uni.guest_password')]);	
			session()->put('user_kind', 'guest');			
		};
		return $enabled;
		
	}
	public function guestLogin(Request $request)
    {        
		$this->logout($request);
		$this->silentAuthenticate();		
			
        return redirect("/discounts");
    }
		
	public function userRedirect(Request $request)
    {   $x =	$request->input('urlPrevious');
		$contains = str_contains( $x, 'm/choice');
		if ($contains) {
			$x="/discounts";
		};
		return redirect ( $x );
    }
	
    public function userUpdate(Request $request)
    {        			
		$x=session()->getId();

		$email = $request->input('email');
		$password = $request->input('password');
		// bcrypt($request->input('password'));

		Auth::attempt(['email' => $email, 'password' =>$password]);	
		session()->put('user_kind', 'user');			
		
		return $this->userRedirect($request);
    }
	
	public function userAdd(Request $request)
    {
		$data=	'email='.$request->input('email').';'
				.'password='.$request->input('password').';'
				.'Naz='.$request->input('Naz').';'
				.'Im='.$request->input('Im').';'
				.'IsCompany='.$request->input('IsCompany').';'				
				.'Name='.$request->input('Name').';'
				.'Nip='.$request->input('Nip').';'
				.'Address='.$request->input('Address').';'
				.'ZipCode='.$request->input('ZipCode').';'
				.'PostOffice='.$request->input('PostOffice').';';
	
		// session()->put('debug', $data);
		
		$item=UsersService::getUserAdd($data);			
					
		if ($item->ERROR==0){
			$this->userUpdate($request);
			session()->put('error_msg', 'Brak blędów');
			return $this->userRedirect($request);
		} else {		
			session()->put('error_msg', $item->ERROR_MSG);
			return redirect("/user/reregister");
		};
    }
	
	protected function logout(Request $request)
    {
        $request->session()->flush();

        $request->session()->regenerate();

//        return redirect('/');
    }
	
}
