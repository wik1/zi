<?php

return [
/*
    |--------------------------------------------------------------------------
    | Guest access 
    |--------------------------------------------------------------------------
    | if guest_login=true used guest login like in a internet shop 
	| otherwise working as application b2b.
    |
    */
    
    'guest_login' => env('UNI_GUEST_LOGIN', false),
	'guest_email' => env('UNI_GUEST_EMAIL', 'xxguest@ziuni@xyz'),
	'guest_password' => env('UNI_GUEST_PASSWORD', 'XXuni()zi321ad2018'),
    'main_file_title' => env('UNI_MAIN_FILE_TITLE', 'Sample HTML'),
	'use_odbiorca' => env('UNI_USE_ODBIORCA', false),
];
