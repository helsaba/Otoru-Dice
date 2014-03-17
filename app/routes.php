<?php

Route::group(array('before' => 'auth'), function()
{
    Route::any('global/update', 'AdminController@Settings');

    Route::any('api/rollhi', 'ApiController@RollHi');
    Route::any('api/rolllo', 'ApiController@RollLo');

    Route::any('api/createwallet', 'ApiController@CreateWallet');
    Route::any('api/withdraw', 'ApiController@Withdraw');

    Route::any('api/user', 'ApiController@User');

    Route::any('api/chat/connect', 'ChatController@Connect');
    Route::any('api/chat/poll', 'ChatController@Poll');
    Route::any('api/chat/send', 'ChatController@Send');
});

    Route::any('/', 'HomeController@index');
    Route::any('login', 'UserController@Login');
    Route::any('signup', 'UserController@Signup');

Route::group(array('before' => 'maint'), function()
{
    Route::any('aur', 'HomeController@AURDice');
    Route::any('btc', 'HomeController@BTCDice');
    
    Route::any('submissions/{coin}', 'HomeController@submissions');
    Route::any('{coin}/newsubmission', 'HomeController@newSubmission');
    
    Route::any('account', 'UserController@Account');
    Route::any('logout', 'UserController@Logout');
    
    Route::any('submissions', 'ApiController@submissions');
});

    //Route::any('install', 'HomeController@Install');