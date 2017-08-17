<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletManage;
use App\Models\UserWallet;
use App\Models\BlockchainWalletMng;

class DashboardController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        
//        $wModel = new BlockchainWalletMng();
//        $wModel->setWalletType("eth");
////dd($wModel->getAddressBalance('0x9fb92762495705f73371e150b448e3229a07aa88'));
//        $to_address = $wModel->generateAddress();
//        echo (strlen($to_address['private']))."\n";
//        echo (strlen($to_address['public']));
//        dd($to_address);
//        $to_address = array('address'=>'4a5cab5a86a3baec6cdeccb7be2e0ebceb444898',
//                            'private'=>''
//                            'public'=>'asdfasdfajsweqiuweq9267198234');
//        $from_address = array('private'=>'6ebe224d245c7c30d2ff23c8d5ea6d3c50e56a4b70028f633ac2f13f0d6637ee',
//                            'public'=>'c6eaabb74607d1cf557aff8fb63494b271b001fc',
//                            'address'=>'c6eaabb74607d1cf557aff8fb63494b271b001fc');
//        $Skelton = $wModel->createTransaction($from_address, $to_address, 0.001);
//
//        $ret = $wModel->sendTransaction($Skelton, $from_address['private']);
//dd($ret);
        $user = \Auth::user();
        $userWalletRow = UserWallet::all()->where('user_id', '=', $user->id)->first();
        $model = new WalletManage();
        $wallet_info = $model->getWalletBalanceByAddress($userWalletRow->wallet_address);
        $coin_balance= floatval($wallet_info->data->available_balance);

        $model = new UserWallet();
        $ethAddress = $model->getUserWallet($user->id, 'eth');
        $blockchain = new BlockchainWalletMng();
        $blockchain->setWalletType('eth');
        $balanceInfo = $blockchain->getAddressBalance($ethAddress);
        session()->put('btc_amount', $coin_balance);
        session()->put('eth_amount', $balanceInfo['balance']);

        return redirect()->action('TradeController@index');
        // return view('index.dashboard');
    }
}
