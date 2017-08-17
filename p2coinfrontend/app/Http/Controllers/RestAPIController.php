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

class RestAPIController extends Controller
{
    //
    public function getwalletbalance( $btcAddress, $ethAddress ) {
        // $btcAddress = $request->btc_address;
        // $ethAddress = $request->eth_address;
// dd($btcAddress, $ethAddress);

        $model = new UserWallet();
        $price_data = $model->getCurrentPrice();
        $btcWallet = new WalletManage();
        $btcWData = $btcWallet->getWalletBalanceByAddress($btcAddress);

        $btc_amount = $btcWData->data->available_balance;
        $btc_price_usd = floatval(number_format($btc_amount*$price_data['btc'],2,'.',','));

        $wallet_info['btc'] = array('coin'=>'Bitcoin', 'abbrev'=>'BTC', 'address'=>$btcAddress, 'amount'=>floatval($btc_amount), 'price_usd'=>$btc_price_usd);
        $blockchain = new BlockchainWalletMng();
        $blockchain->setWalletType('eth');
        $balanceInfo = $blockchain->getAddressBalance($ethAddress);

        $eth_price_usd = floatval(number_format( $balanceInfo['balance']*$price_data['eth'],2,'.',','));
        $wallet_info['eth'] = array('coin'=>'Ethereum', 'abbrev'=>'ETH', 'address'=>$ethAddress, 'amount'=>floatval($balanceInfo['balance']),'price_usd'=>$eth_price_usd );
        
        echo json_encode($wallet_info);
        exit;
    }
    public function dowithdraw( $transaction_id ){
        $row = TransactionHistory::all()->where('transaction_id','=',$transaction_id)->first();
        $coin_amount = $row->coin_amount;
        $sender_id = $row->coin_sender_id;
        $receiver_id = $row->coin_receiver_id;

        $tmp_data = DB::select("select id, coin_type from listings where id in ( select c.listing_id from contract c join transaction_history th on th.contract_id=c.id where th.transaction_id={$transaction_id})");
        $tmp = $tmp_data[0];
        $coin_type = $tmp->coin_type;
        $listing_id = $tmp->id;

        try{
            if ( $coin_type == 'btc' ) {
                $temp_row = UserWallet::all()->where('user_id', '=', $sender_id)->first();
                $sender_wallet = $temp_row->wallet_address;
                $temp_row = UserWallet::all()->where('user_id', '=', $receiver_id)->first();
                $receiver_wallet = $temp_row->wallet_address;

                $model = new WalletManage();
                $data = $model->getTransFee($coin_amount, $receiver_wallet);
                $model->withdrawExt($data['amount'], $data['site_fee'], $sender_wallet, $receiver_wallet);
            }
            if ( $coin_type == 'eth' ) {
                $model = new UserWallet();
                $sender_info = $model->getWalletInfo($sender_id, 'eth');
                $from_address = array('address'=>$sender_info->wallet_address, 'private'=>$sender_info->private, 'public'=>$sender_info->public);
                $receiver_info = $model->getWalletInfo($receiver_id, 'eth');
                $to_address = array('address'=>$receiver_info->wallet_address, 'private'=>$receiver_info->private, 'public'=>$receiver_info->public);
                $wModel = new BlockchainWalletMng();
                $wModel->setWalletType( $coin_type );
                $Skelton = $wModel->createTransaction($from_address, $to_address, $coin_amount);
                $ret = $wModel->sendTransaction($Skelton, $from_address['private']);

//                echo json_encode(array('from'=>$wModel->getAddressBalance($from_address['address']),'to'=>$wModel->getAddressBalance($to_address['address'])));
//                exit;
            }
            $listing = Listings::find($listing_id);
            $listing->is_closed = 1;
            $listing->save();

            echo 'ok';
        }
        catch(Exception $e) {
            echo 'fail';
        }

        exit;
    }
}
