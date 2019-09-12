<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	
    use Notifiable;
    protected $table = 'ktrh_persons';
    protected $connection = 'firebird';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'ktrh', 'is_netto', 'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	
	/*
	* unifactor custom functions
	
	*/
	public function guestEnabled()
	{    
		return config('uni.guest_login');
	}

	public function kind()
	{    
		return session()->get('user_kind');
	}
	
	public function guestLoged()
	{   
		if( $this->kind()=='guest' ){
			return true;
		} else {
			return false;
		};
	}
	
	public function loged()
	{    
		if($this->kind()=='user'){
			return true;
		} else {
			return false;
		};
	}	
}
