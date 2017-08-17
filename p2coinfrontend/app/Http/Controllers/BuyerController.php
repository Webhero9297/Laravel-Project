<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listings;
use App\Models\ContractModel;
use App\Models\WalletManage;
use App\Models\BlockchainWalletMng;
use DB;
use App\Models\UserWallet;


class BuyerController extends Controller
{
    //
    public function index(Request $request) {
        $user = \Auth::user();
        $arr = explode('-', $request->param);

        $listing_id = $arr[0];
        $sender_id = $user->id;
        $receiver_id = $arr[1];
        $coin_type = $arr[2];

        $USDrate = 1;
        if ( $coin_type == 'btc' ) {
            $model = new WalletManage();
            $price_data = $model->getCurrentPrice();
            $btce_data = $price_data->data->prices[5];
            $USDrate = $btce_data->price;
        }
        if ( $coin_type == 'eth' ) {
            $bmodel = new BlockchainWalletMng();
            $bmodel->setWalletType($coin_type);

            $model = new WalletManage();
            $price_data = $model->getCurrentPrice();

            $USDrate = 202.23;
        }

        $data = DB::table('contract')->where('sender_id', '=', $sender_id)->where('receiver_id', '=', $receiver_id)->where('listing_id', '=', $listing_id)->get();

        if(!count($data)){
            $newRow = new ContractModel();
            
            $newRow->sender_id = $sender_id;
            $newRow->receiver_id = $receiver_id;
            $newRow->listing_id = $listing_id;
            $status = $newRow->save();
            $contract_id = $newRow->id;
        }else{
            $contract_id = $data[0]->id;
        }

        $listing = listings::all()->where('id', '=', $listing_id)->first();
//        foreach($listings as $list){
//            $listing = $list;
//            break;
//        }
        $model = new UserWallet();
        $price_data = $model->getCurrentPrice();
        return view('buy.index')->with('price_rate',$USDrate)->with('listing', $listing)
            ->with('price_data', $price_data)
            ->with('contract_id', $contract_id)->with('receiver_id', $receiver_id)->with('coin_type', $coin_type)->with('listing_id', $listing_id);
    }
}
