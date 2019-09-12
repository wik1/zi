<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\UsersService;

class ApiUniToolsController extends Controller
{	
    public function sessionPut($id, $value)
    {	
        session()->put($id, $value);
        return $value;    
    }	
	
    public function sessionGet($id)
    {	
        return session()->get($id);		
    }
    
    public function sessionPull($id)
    {	
        return session()->pull($id);
    }
	
    public function userAdd(Request $request)
    {
        $data= $request->id;
        $x= $request->input('Login');

        session()->put('debug', 'Login='.$x.' -->'.$data);
		//return UsersService::getUserAdd($data);
    }

}

