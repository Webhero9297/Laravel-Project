<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listings;

use App\Models\WalletManage;
use App\Models\UserWallet;
use App\Models\TransactionHistory;
use App\Models\ContractModel;
use App\Models\BlockchainWalletMng;
//use App\Models\Listings;
use DB;

class ManageListingsController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {

        
        $user = \Auth::user();
        $listings = Listings::all()->where('user_id', '=', $user->id)->sortByDesc('created_at');
        $wmodel = new UserWallet();
        $user = \Auth::user();
        //$btcaddress =$wmodel->getUserWallet($user->id, 'btc');
        $btc_balance = 0;
        // $model = new WalletManage();
        // $wallet_info = $model->getWalletBalanceByAddress($btcaddress);
        // $btc_balance= floatval($wallet_info->data->available_balance);


        // $ethAddress = $wmodel->getUserWallet($user->id, 'eth');
        // $blockchain = new BlockchainWalletMng();
        // $blockchain->setWalletType('eth');
        // $balanceInfo = $blockchain->getAddressBalance($ethAddress);
        // $eth_balance = $balanceInfo['balance'];

        $btc_balance = $eth_balance = 0;

        $btc_disabled = "";
        if ( $btc_balance == 0 ) $btc_disabled = " disabled";
        $eth_disabled = "";
        if ( $eth_balance == 0 ) $eth_disabled = " disabled";

// dd($wmodel->getLocalCurrencyRate('CNY'));   
        session()->put('btc_amount', $btc_balance);
        session()->put('eth_amount', $eth_balance);
        

        return view('manage.index')->with("btc_disabled", $btc_disabled)->with("eth_disabled", $eth_disabled)/*->with('price_data', $price_data)*/;
    }

    //Managelistings Pages
    public function getListingDataByUser(Request $request){
        $user = \Auth::user();
        $flag = $request->flag;
        $lModel = new Listings();
        $wmodel = new UserWallet();

        $btc_listings = $lModel->getListingsDataByUser($user->id, 'btc', $flag);
//        dd($user->id);
        
        $btc_list = "";
        foreach($btc_listings as $listing){
//            dd($listing['id']);
            $local_currency = $wmodel->getLocalCurrencyRate($listing->currency);

            $btc_list .= "<tr>";
            $btc_list .= "<td class='text-center'>" . $listing->id . "</td>";
            $btc_list .= "<td class='text-center'><a class='btn btn-success btn-green' href='addlistings/" . $listing->id . "'>Edit</a></td>";
            $btc_list .= "<td class='text-center'><a href='viewlisting/" . $listing->id . "'>" . $listing->payment_method . ":" . $listing->payment_name . "</a></td>";
            $btc_list .= "<td class='text-center'>" . round(($listing->coin_amount * $local_currency[$listing->coin_type]), 2) . " " . $listing->currency . "</td>";
            $btc_list .= "<td class='text-center'><label class='switch'>";
            if($listing->status)
                $btc_list .= "<input type='checkbox' class='status' id='" . $listing->id . "' name='status' onclick=\"j_obj.updateStatus(".$listing->id.")\" checked>";
            else
                $btc_list .= "<input type='checkbox' class='status' id='" . $listing->id . "' onclick=\"j_obj.updateStatus(".$listing->id.")\" name='status'>";
            $btc_list .= "<span class='slider round'></span></label></td>";                       
            $btc_list .= "</tr>";
        }

        $eth_listings = $lModel->getListingsDataByUser($user->id, 'eth', $flag);
        $eth_list = "";
        foreach($eth_listings as $listing){
            $local_currency = $wmodel->getLocalCurrencyRate($listing->currency);

            $eth_list .= "<tr>";
            $eth_list .= "<td class='text-center'>" . $listing->id . "</td>";
            $eth_list .= "<td class='text-center'><a class='btn btn-success btn-green' href='addlistings/" . $listing->id . "'>Edit</a></td>";
            $eth_list .= "<td class='text-center'><a href='viewlisting/" . $listing->id . "'>" . $listing->payment_method . ":" . $listing->payment_name . "</a></td>";
            $eth_list .= "<td class='text-center'>" . round(($listing->coin_amount * $local_currency[$listing->coin_type]), 2) . " " . $listing->currency . "</td>";
            $eth_list .= "<td class='text-center'><label class='switch'>";
            if($listing->status)
                $eth_list .= "<input type='checkbox' class='status' id='" . $listing->id . "' name='status' onclick=\"j_obj.updateStatus(".$listing->id.")\" checked>";
            else
                $eth_list .= "<input type='checkbox' class='status' id='" . $listing->id . "' onclick=\"j_obj.updateStatus(".$listing->id.")\" name='status'>";
            $eth_list .= "<span class='slider round'></span></label></td>";                       
            $eth_list .= "</tr>";
        }

        echo $btc_list . "@@@" . $eth_list;
        //exit;
   }

    //07-26 updated
    public function addlistings($listing_id) {
        $user = \Auth::user();
        $wmodel = new UserWallet();
        // $userWalletInfo = UserWallet::where('user_id', '=', $user->id)->first(); 
        // if ( $listing_id == -1 ) {
        //     $btcaddress =$wmodel->getUserWallet($user->id, 'btc');      
        //     $model = new WalletManage();
        //     $wallet_info = $model->getWalletBalanceByAddress($btcaddress);
        //     $coin_balance= floatval($wallet_info->data->available_balance);
        // }
        // if ( $listing_id == -2 ) {
        //     $ethAddress = $wmodel->getUserWallet($user->id, 'eth');
        //     $blockchain = new BlockchainWalletMng();
        //     $blockchain->setWalletType('eth');
        //     $balanceInfo = $blockchain->getAddressBalance($ethAddress);
        //     $coin_balance = $balanceInfo['balance'];
        // }
$coin_balance = 0;
        $price_data = $wmodel->getCurrentPrice();
        if ($listing_id < 0 ) {
            return view('manage.listings')->with('coin_balance', $coin_balance)->with('listing', 'NULL')->with('price_data', $price_data);
        }
        if ( $listing_id > 0 ) {
            $listing = Listings::all()->where('id', '=', $listing_id )->first()->toArray();
            // if ( $listing['coin_type'] == 'btc' ) {
            //     $btcaddress =$wmodel->getUserWallet($user->id, 'btc');      
            //     $model = new WalletManage();
            //     $wallet_info = $model->getWalletBalanceByAddress($btcaddress);
            //     $coin_balance= floatval($wallet_info->data->available_balance);
            // }
            // if ( $listing['coin_type'] == 'eth' ) {
            //     $ethAddress = $wmodel->getUserWallet($user->id, 'eth');
            //     $blockchain = new BlockchainWalletMng();
            //     $blockchain->setWalletType('eth');
            //     $balanceInfo = $blockchain->getAddressBalance($ethAddress);
            //     $coin_balance = $balanceInfo['balance'];
            // }
$coin_balance = 0;
            return view('manage.listings')->with('coin_balance', $coin_balance)->with('listing', $listing)->with('price_data', $price_data);
        }
    }

    //07-26 created
    public function viewlisting($listing_id) {
        $listings = Listings::all()->where('id', '=', $listing_id );  
        foreach($listings as $arr){
            $listing = $arr;
            break;
        }

        return view('manage.viewlisting')->with('listing', $listing);
    }

    //
    public function changestatus(Request $request) {
        $listing_id = $request->listing_id;
        $status = $request->status;
        $listing_row = Listings::find($listing_id);
        $listing_row->status = $status;
        $listing_row->save();
        echo 'ok';
        exit;
    }
    public function storelistings(Request $request) {
        $listing_id = $request->listing_id;
        $user_type = $request->user_type;
        $coin_type = $request->coin_type;
        $coin_amount = $request->coin_amount;
        $location = $request->location;
        $payment_method = $request->payment_method;
        $payment_name = $request->payment_name;
        $currency = $request->currency;
        $min_transaction_limit = $request->min_transaction_limit;
        $max_transaction_limit = $request->max_transaction_limit;
        $price_equation = $request->price_equation;
        $terms_of_trade = $request->terms_of_trade;
        $payment_details = $request->payment_details;
        $user = \Auth::user();
        
        if($listing_id > 0)
            $listing = Listings::find($listing_id);
        else
            $listing = new Listings();

        $listing->user_id=$user->id;
        $listing->user_type=$user_type;
        $listing->coin_type=$coin_type;
        $listing->coin_amount=$coin_amount;
        $listing->location = $location;
        $listing->payment_method = $payment_method;
        $listing->payment_name = $payment_name;
        $listing->currency = $currency;
        $listing->min_transaction_limit = $min_transaction_limit;
        $listing->max_transaction_limit = $max_transaction_limit;
        $listing->price_equation = $price_equation;
        $listing->terms_of_trade = $terms_of_trade;
        $listing->payment_details = $payment_details;
        $listing->save();

        /**  
            @TODO *** Here is deposit operation procedure

        **/
/*
        $model = new WalletManage();
        $user = \Auth::user();
        $userWalletInfo = UserWallet::where('user_id', '=', $user->id)->first();
        $model = new WalletManage();
        $userWallet = $userWalletInfo->wallet_address;
        $model->deposit($coin_amount, $userWallet);
*/
        /** ************************** ******************************* */

        return redirect()->action('ManageListingsController@index');
    }
    public function withdraw( Request $request ) {
        $transaction_id = $request->transaction_id;
        $tmp = DB::select("select id from listings where id in ( select c.listing_id from contract c join transaction_history th on th.contract_id=c.id where th.transaction_id={$transaction_id})");
        $tmp_listing = $tmp[0];
        DB::table('listings')->where('id', '=', $tmp_listing->id)->update(['is_closed'=>1]);
        echo '1';
        exit;
        // $contract_data = 
//         $row = TransactionHistory::all()->where('transaction_id','=',$transaction_id)->first();
//         $coin_amount = $row->coin_amount;
//         $sender_id = $row->coin_sender_id;
//         $receiver_id = $row->coin_receiver_id;

//         $tmp_data = DB::select("select id, coin_type from listings where id in ( select c.listing_id from contract c join transaction_history th on th.contract_id=c.id where th.transaction_id={$transaction_id})");
//         $tmp = $tmp_data[0];
//         $coin_type = $tmp->coin_type;
//         $listing_id = $tmp->id;

//         try{
//             if ( $coin_type == 'btc' ) {
//                 $temp_row = UserWallet::all()->where('user_id', '=', $sender_id)->first();
//                 $sender_wallet = $temp_row->wallet_address;
//                 $temp_row = UserWallet::all()->where('user_id', '=', $receiver_id)->first();
//                 $receiver_wallet = $temp_row->wallet_address;

//                 $model = new WalletManage();
//                 $data = $model->getTransFee($coin_amount, $receiver_wallet);
//                 $model->withdrawExt($data['amount'], $data['site_fee'], $sender_wallet, $receiver_wallet);
//             }
//             if ( $coin_type == 'eth' ) {
//                 $model = new UserWallet();
//                 $sender_info = $model->getWalletInfo($sender_id, 'eth');
//                 $from_address = array('address'=>$sender_info->wallet_address, 'private'=>$sender_info->private, 'public'=>$sender_info->public);
//                 $receiver_info = $model->getWalletInfo($receiver_id, 'eth');
//                 $to_address = array('address'=>$receiver_info->wallet_address, 'private'=>$receiver_info->private, 'public'=>$receiver_info->public);
//                 $wModel = new BlockchainWalletMng();
//                 $wModel->setWalletType( $coin_type );
//                 $Skelton = $wModel->createTransaction($from_address, $to_address, $coin_amount);
//                 $ret = $wModel->sendTransaction($Skelton, $from_address['private']);

// //                echo json_encode(array('from'=>$wModel->getAddressBalance($from_address['address']),'to'=>$wModel->getAddressBalance($to_address['address'])));
// //                exit;
//             }
//             $listing = Listings::find($listing_id);
//             $listing->is_closed = 1;
//             $listing->save();

//             echo 'ok';
//         }
//         catch(Exception $e) {
//             echo 'fail';
//         }

//         exit;
    }
    public function userbalance() {
        $user = \Auth::user();
        $userWalletInfo = UserWallet::where('user_id', '=', $user->id)->first();
        // $model = new WalletManage();
        // $wallet_info = $model->getWalletBalanceByAddress($userWalletInfo->wallet_address);
        // $coin_balance= floatval($wallet_info->data->available_balance);

        $coin_balance = 0;
        echo $coin_balance;
        exit;
    }
    public function releaseyes() {
        $transaction_id = request()->get('transaction_id');
        $data_row = TransactionHistory::all()->where('transaction_id', '=',$transaction_id)->first();
        $seller_id = $data_row->coin_sender_id;
        $buyer_id = $data_row->coin_receiver_id;
        $user_id = \Auth::user()->id;

        if ( $user_id == $seller_id ) 
            DB::table('transaction_history')->where('transaction_id','=',$transaction_id)
                     ->update(['seller_release'=>1, 'updated_at'=>date('Y-m-d H:i:s')]);
        if ( $user_id == $buyer_id )
            DB::table('transaction_history')->where('transaction_id','=',$transaction_id)
                     ->update(['buyer_release'=>1, 'updated_at'=>date('Y-m-d H:i:s')]);
        exit;
    }
    public function releaseno() {
        $transaction_id = request()->get('transaction_id');
        $data_row = TransactionHistory::all()->where('transaction_id', '=',$transaction_id)->first();
        $seller_id = $data_row->coin_sender_id;
        $buyer_id = $data_row->coin_receiver_id;
        $user_id = \Auth::user()->id;

        if ( $user_id == $seller_id ) 
            DB::table('transaction_history')->where('transaction_id','=',$transaction_id)
                     ->update(['seller_release'=>0, 'updated_at'=>date('Y-m-d H:i:s')]);
        if ( $user_id == $buyer_id )
            DB::table('transaction_history')->where('transaction_id','=',$transaction_id)
                     ->update(['buyer_release'=>0, 'updated_at'=>date('Y-m-d H:i:s')]);
        exit;
    }
}
