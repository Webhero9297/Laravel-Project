<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/index', 'IndexController@index')->name('index');
Auth::routes();

Route::get('/changepassword', 'Auth\ChangePasswordController@index')->name('changepassword');
Route::post('/resetpassword', 'Auth\ChangePasswordController@resetpassword')->name('resetpassword');
Route::get('/changephone', 'Auth\ChangeEmailController@changephone')->name('changephone');
Route::post('/changepersonphonenumber', 'Auth\ChangeEmailController@changepersonphonenumber')->name('changepersonphonenumber');
Route::get('/change2fa', 'Auth\ChangeEmailController@change2fa')->name('change2fa');
Route::get('/check2fa', 'Auth\ChangeEmailController@check2fa')->name('check2fa');
Route::get('/registkey', 'Auth\ChangeEmailController@registeKey')->name('registkey');
Route::get('/update2fa', 'Auth\ChangeEmailController@update2fa')->name('update2fa');
Route::post('/reportuser', 'Auth\ChangeEmailController@reportuser')->name('reportuser');


Route::get('/verifyphone', 'Auth\VerifyPhoneController@index')->name('verifyphone');
Route::get('/getpincode', 'Auth\VerifyPhoneController@getpincode')->name('getpincode');
Route::get('/verifycode', 'Auth\VerifyPhoneController@verifycode')->name('verifycode');

Route::get('/verifyid', 'Auth\VerifyIDController@index')->name('verifyid');
Route::post('/uploadidimage', 'Auth\VerifyIDController@uploadidimage')->name('uploadidimage');
Route::get('/loadidimagebyuser', 'Auth\VerifyIDController@loadidimagebyuser')->name('loadidimagebyuser');

Route::get('/verifyready', 'Auth\RegisterController@verifyready')->name('verifyready');


Route::post('/chat','ChatController@sendMessage')->name('chat');
 
Route::get('/chat','ChatController@chatPage')->name('chat');
Route::get('/getwalletamountbycoin', 'WalletController@getwalletamountbycoin')->name('getwalletamountbycoin');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/trades', 'TradeController@index')->name('trades');
Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
Route::post('/buy', 'BuyerController@index')->name('buyer');
Route::get('/managelistings', 'ManageListingsController@index')->name('managelistings');
Route::get('/messagebox', 'TradeMessageController@messagebox')->name('messagebox');
Route::get('/addlistings/{listing_id}', 'ManageListingsController@addlistings')->name('addlistings/{listing_id}');
Route::post('/reportlisting', 'TradeController@reportListing')->name('reportlisting');
Route::post('/deletereport', 'TradeController@deleteReport')->name('deletereport');

Route::get('/viewlisting/{listing_id}', 'ManageListingsController@viewlisting')->name('viewlisting/{listing_id}');
Route::get('/getuserbalance', 'ManageListingsController@userbalance')->name('getuserbalance');
Route::post('/storelistings', 'ManageListingsController@storelistings')->name('storelistings');
Route::post('/changestatus', 'ManageListingsController@changestatus')->name('changestatus');
Route::post('/withdraw', 'ManageListingsController@withdraw')->name('withdraw');

Route::post('/settradestatus', 'TradeMessageController@setTradeStatus')->name('settradestatus');
Route::post('/leavefeedback', 'TradeMessageController@leaveFeedback')->name('leavefeedback');

Route::get('/sell', 'SellerController@index')->name('seller');
Route::get('/chatroom', 'ChatRoomController@index')->name('chatroom');
Route::post('/trademessage', 'TradeMessageController@index')->name('trademessage');
Route::post('/setdispute', 'TradeMessageController@setdispute')->name('setdispute');

Route::post('/createcontract', 'TradeMessageController@createcontract')->name('createcontract');
Route::post('/createcont', 'TradeMessageController@createcont')->name('createcont');
Route::post('/addmessage', 'TradeMessageController@addmessage')->name('addmessage');
Route::post('/dispute', 'TradeMessageController@dispute')->name('dispute');

Route::get('/wallet', 'WalletController@index')->name('wallet');
Route::post('/deposit', 'WalletController@deposit')->name('deposit');
Route::post('/coinwithdraw', 'WalletController@withdraw')->name('coinwithdraw');
Route::post('/generateqrcode', 'WalletController@generateqrcode')->name('generateqrcode');

Route::get('/settings', 'SettingsController@index')->name('settings');
Route::get('/userprofile', 'UserProfileController@index')->name('userprofile');
Route::get('/verify/email/{token}', 'VerifyController@email')->name('verify/email/{token}');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::get('/gettrade', 'ProfileController@gettrade')->name('gettrade');

Route::post('/getlistingdata','TradeController@getListingData')->name('getlistingdata');
Route::post('/getlistingdatabyuser','ManageListingsController@getListingDataByUser')->name('getlistingdatabyuser');
Route::post('/getalllistingdata','IndexController@getListingData')->name('getalllistingdata');
Route::post('/getlastmessagelist','IndexController@getLastMessageList')->name('getlastmessagelist');

Route::get('/opentrade','OpenTradeController@index')->name('opentrade');

Route::get('/chart','ChartController@index')->name('chart');
Route::post('/getchartdatabycoin','ChartController@getchartdatabycoin')->name('getchartdatabycoin');

Route::group(array('prefix' => 'api/v1'), function() {
    Route::get('getwalletbalance/{btc_address}/{eth_address}', 'RestAPIController@getwalletbalance');
    Route::get('dowithdraw/{transaction_id}', 'RestAPIController@dowithdraw');
});