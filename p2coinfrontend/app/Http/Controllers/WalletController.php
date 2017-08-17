<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BlockchainWalletMng;
use App\Models\UserWallet;
use App\Models\WalletManage;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Symfony\Component\HttpFoundation\Response;


class WalletController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        $wallet_info = array();
        $user = Auth::user();
        $model = new UserWallet();
        $price_data = $model->getCurrentPrice();
        $btcAddress = $model->getUserWallet($user->id, 'btc');
        $btcWallet = new WalletManage();
        $btcWData = $btcWallet->getWalletBalanceByAddress($btcAddress);

        $btc_amount = $btcWData->data->available_balance;
        $btc_price_usd = floatval(number_format($btc_amount*$price_data['btc'],2,'.',','));


        $wallet_info[] = array('coin'=>'Bitcoin', 'abbrev'=>'BTC', 'address'=>$btcAddress, 'amount'=>floatval($btc_amount), 'price_usd'=>$btc_price_usd);
        $ethAddress = $model->getUserWallet($user->id, 'eth');
        $blockchain = new BlockchainWalletMng();
        $blockchain->setWalletType('eth');
        $balanceInfo = $blockchain->getAddressBalance($ethAddress);

        $eth_price_usd = floatval(number_format( $balanceInfo['balance']*$price_data['eth'],2,'.',','));
        $wallet_info[] = array('coin'=>'Ethereum', 'abbrev'=>'ETH', 'address'=>$ethAddress, 'amount'=>floatval($balanceInfo['balance']),'price_usd'=>$eth_price_usd );

        return view('wallet.index')->with('wallet_info', $wallet_info)->with('total_worth', ($btc_price_usd+$eth_price_usd))->with('price_data', $price_data);
    }
    public function deposit(Request $request) {
        $src_address = $request->src_address;
        $deposit_amount = $request->deposit_amount;
        $private_key = $request->private_key;
        $coin_type = strtolower($request->coin_type);

        $user = \Auth::user();
        $model = new UserWallet();
        $destAddressInfo = $model->getWalletInfo($user->id, $coin_type);
        $destAddress = array('address'=>$destAddressInfo->wallet_address,'private'=>$destAddressInfo->private);
        $wModel = new BlockchainWalletMng();
        $wModel->setWalletType( $coin_type );

        $from_address = array('address'=>$src_address, 'private'=>$private_key);
        $Skelton = $wModel->createTransaction($from_address, $destAddress, $deposit_amount);
        $ret = $wModel->sendTransaction($Skelton, $from_address['private']);
        echo $ret;
        exit;
    }
    public function withdraw(Request $request) {
        $address = $request->address;
        $coin_type = strtolower($request->coin_type);
        $coin_amount = $request->coin_amount;
        $user = \Auth::user();
        $model = new UserWallet();
        $fromAddressInfo = $model->getWalletInfo($user->id, $coin_type);
        $fromAddress = array('address'=>$fromAddressInfo->wallet_address,'private'=>$fromAddressInfo->private);
        $wModel = new BlockchainWalletMng();
        $wModel->setWalletType( $coin_type );

        $destAddress = array('address'=>$address, 'private'=>'');
        $Skelton = $wModel->createTransaction($fromAddress, $destAddress, $coin_amount);
        $ret = $wModel->sendTransaction($Skelton, $fromAddress['private']);
        echo $ret;
        exit;
    }
    public function generateqrcode( Request $request ) {

        $qrCode = new QrCode($request->address);
        $qrCode->setSize(300);
// Directly output the QR code
        header('Content-Type: '.$qrCode->getContentType());
//        echo $qrCode->writeString();
// Save it to a file
        $url = public_path()."/assets/qrcode/{$request->address}.png";
        $qrCode->writeFile($url);
// Create a response object
        echo "/assets/qrcode/{$request->address}.png";
        exit;
    }
    public function getwalletamountbycoin() {
        $user = Auth::user();
        // $model = new UserWallet();
        // $price_data = $model->getCurrentPrice();
        // $btcAddress = $model->getUserWallet($user->id, 'btc');
        // $btcWallet = new WalletManage();
        // $btcWData = $btcWallet->getWalletBalanceByAddress($btcAddress);

        // $btc_amount = $btcWData->data->available_balance;
        // $btc_price_usd = floatval(number_format($btc_amount*$price_data['btc'],2,'.',','));


        // $wallet_info['btc'] = floatval($btc_amount);
        // $ethAddress = $model->getUserWallet($user->id, 'eth');
        // $blockchain = new BlockchainWalletMng();
        // $blockchain->setWalletType('eth');
        // $balanceInfo = $blockchain->getAddressBalance($ethAddress);

        // $eth_price_usd = floatval(number_format( $balanceInfo['balance']*$price_data['eth'],2,'.',','));
        // $wallet_info['eth'] = floatval($balanceInfo['balance']);
        
        $wallet_info['eth']=$wallet_info['btc']=0;
        
        echo json_encode($wallet_info);
        exit;
    }
}
