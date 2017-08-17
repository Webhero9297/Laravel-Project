<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TradeMessageModel;
use App\Models\Listings;
use Illuminate\Foundation\Auth\User;
use DB;
use App\Models\ContractModel;
use App\Models\TransactionHistory;
use App\Models\UserWallet;
use App\Models\WalletManage;
use App\Models\BlockchainWalletMng;
use App\Models\DisputeHistory;

class TradeMessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $arr = explode('-', $request->param);
        $contract_id    = $arr[0];
        $listing_id     = $arr[1];
        $sender_id      = $arr[2];
        $receiver_id    = $arr[3];
        $user_type      = $arr[4];
        $back           = $arr[5];


        $listing = listings::all()->where('id', '=', $listing_id)->first();
        $coin_type = $listing->coin_type;

        $user = \Auth::user();

        if($user->id == $listing->user_id){
            if(!$listing->user_type){
                $coin_sender_id = $user->id;
                if($user->id == $sender_id)
                    $coin_receiver_id = $receiver_id;
                else
                    $coin_receiver_id = $sender_id;
            }else{
                $coin_receiver_id = $user->id;
                if($user->id == $sender_id)
                    $coin_sender_id = $receiver_id;
                else
                    $coin_sender_id = $sender_id;
            }
        }else{
            if(!$listing->user_type){
                $coin_sender_id = $listing->user_id;
                $coin_receiver_id = $user->id;
            }else{
                $coin_sender_id = $user->id;
                $coin_receiver_id = $listing->user_id;
            }
        }

        $data = $this->getMsgListByContractId($contract_id);
        
        $row = DB::table('transaction_history')->where('contract_id','=',$contract_id)->where('coin_sender_id','=',$coin_sender_id)->where('coin_receiver_id','=', $coin_receiver_id)->first();
        $transaction_id = $row->transaction_id;
        $coin_amount = $row->coin_amount;

        $flag = true;
        $request_amount = $coin_amount;
        // if ( $coin_type == 'btc' ) {
        //     $model = new UserWallet();
        //     $sender_info = $model->getWalletInfo($coin_sender_id, 'btc');
        //     $sender_wallet = $sender_info->wallet_address;
        //     $receiver_info = $model->getWalletInfo($coin_receiver_id, 'btc');
        //     $receiver_wallet = $receiver_info->wallet_address;
        //     $model = new WalletManage();  
        //     $tmp = $model->getWalletBalanceByAddress($sender_wallet);           
        //     $balance = $tmp->data->available_balance;
        //     if ( $balance > $coin_amount ) {
        //         $tmpdata = $model->getTransFee($coin_amount, $receiver_wallet);
        //         $request_amount = $tmpdata['total'];

        //     }
        // }
        // if ( $coin_type == 'eth' ) {
        //     $model = new UserWallet();
        //     $sender_info = $model->getWalletInfo($coin_sender_id, 'eth');
        //     $from_address = array('address'=>$sender_info->wallet_address, 'private'=>$sender_info->private, 'public'=>$sender_info->public);
        //     $receiver_info = $model->getWalletInfo($coin_receiver_id, 'eth');
        //     $to_address = array('address'=>$receiver_info->wallet_address, 'private'=>$receiver_info->private, 'public'=>$receiver_info->public);
        //     $wModel = new BlockchainWalletMng();
        //     $wModel->setWalletType( $coin_type );
        //     $Skelton = $wModel->createTransaction($from_address, $to_address, $coin_amount);
        //     $request_amount = floatval(($Skelton->tx->total+$Skelton->tx->fees)/1000000000000000000);
        //     $tmp = $wModel->getAddressBalance($sender_info->wallet_address);
        //     $balance = floatval($tmp['final_balance']/1000000000000000000);
        // }
        $balance = $request_amount;
        
        $dispModel = new DisputeHistory();
        $disput_status = $dispModel->getDisputeStatus($transaction_id);
        $is_success = $this->isSuccess($contract_id, $user->id);
        $is_feedback = $this->isFeedback($contract_id, $user->id);

        //Isread = 1;
        DB::table('trade_message')->where('contract_id', $contract_id)->update(['is_read' => 1]);

        if(!$back)
            return view('trademessage.index')->with('data', $data)->with('transaction_id', $transaction_id)->with('listing', $listing)->with('status_col', $row->status_col)
            ->with('listing_id', $listing_id)->with('contract_id', $contract_id)->with('sender_id', $sender_id)->with('receiver_id', $receiver_id)
            ->with('request_amount', $request_amount)->with('balance', $balance)->with('disput_status', $disput_status)->with('is_success', $is_success)->with('is_feedback', $is_feedback);
        else
            return view('trademessage.index')->with('data', $data)->with('transaction_id', $transaction_id)->with('listing', $listing)->with('status_col', $row->status_col)
            ->with('listing_id', $listing_id)->with('contract_id', $contract_id)->with('sender_id', $receiver_id)->with('receiver_id', $sender_id)
            ->with('request_amount', $request_amount)->with('balance', $balance)->with('disput_status', $disput_status)->with('is_success', $is_success)->with('is_feedback', $is_feedback);
    }

    public function setdispute(Request $request) {
        $transaction_id = $request->transaction_id;
        $content = $request->content;
        $newRow = new DisputeHistory();
        $newRow->transaction_id = $transaction_id;
        $newRow->dispute_reason = $content;
        $newRow->save();

        $listing_data = DB::select("SELECT l.id 
                            FROM transaction_history th
                            join contract c
                            on th.contract_id = c.id
                            join listings l
                            on l.id = c.listing_id
                            where th.transaction_id=$transaction_id");
        $tmp = $listing_data[0];
        $listing_id = $tmp->id;
        DB::table('listings')->where('id','=', $listing_id)->update(['is_closed'=>4]);
        echo 'ok';
        exit;
    }

    public function addmessage(Request $request) {
        header('Content-type:application/json');
        
        $contract_id = $request->contract_id;
        $sender_id = $request->sender_id;
        $receiver_id = $request->receiver_id;
        $message_content = $request->message_content;

        if ( $message_content != 'NULL' ) {  
            $newRow = new TradeMessageModel();
            $newRow->contract_id = $contract_id;
            $newRow->sender_id = $sender_id;
            $newRow->receiver_id = $receiver_id;
            $newRow->message_content = htmlentities($message_content);
            $newId = $newRow->save();
        }
        $arr = $this->getMsgListByContractId($contract_id);
        $fee = 0;
//        try{
//            $userWalletModel = new UserWallet();
//            $receiver_address = $userWalletModel->getUserWallet($receiver_id);
//
//            $transModel = new TransactionHistory();
//            $row = $transModel->getDataByContractId($contract_id);
//            $coin_amount = $row->coin_amount;
//
//            $walletModel = new WalletManage();
//            $fee = $walletModel->getTransFee($coin_amount, $receiver_address);
//
//            $senderBalance = $walletModel->getWalletBalanceByAddress($userWalletModel->getUserWallet($sender_id));
//            $amount = $senderBalance->data->available_balance;
//            ( $amount*1 > $fee['total'] ) ? $fee['status'] = 'enable' : $fee['status'] = 'disable';
//        }catch(Exception $e) {
//            $fee = 0;
//        }


        $ret_arr['fee'] = $fee;
        $ret_arr['content'] = $arr;
        echo json_encode($ret_arr);
        exit;
    }

    public function createcontract(Request $request){
        $user = \Auth::user();
        $listing_id = $request->listing_id;
        $contract_id = $request->contract_id;
        $sender_id = $user->id;
        $receiver_id = $request->receiver_id;
        $coin_amount = $request->coin_amount;
        $coin_type = $request->coin_type;
        $price = $request->price;
        $message_content = strtoupper($coin_type) . " : " . $coin_amount;

        $newRow = new TradeMessageModel();
        $newRow->contract_id = $contract_id;
        $newRow->sender_id = $sender_id;
        $newRow->receiver_id = $receiver_id;
        $newRow->message_content = htmlentities($message_content);
        $newId = $newRow->save();

        $listing = Listings::find($listing_id);
        if($user->id == $listing->user_id){
            if(!$listing->user_type){
                $coin_sender_id = $user->id;
                if($user->id == $sender_id)
                    $coin_receiver_id = $receiver_id;
                else
                    $coin_receiver_id = $sender_id;
            }else{
                $coin_receiver_id = $user->id;
                if($user->id == $sender_id)
                    $coin_sender_id = $receiver_id;
                else
                    $coin_sender_id = $sender_id;
            }
        }else{
            if(!$listing->user_type){
                $coin_sender_id = $listing->user_id;
                $coin_receiver_id = $user->id;
            }else{
                $coin_sender_id = $user->id;
                $coin_receiver_id = $listing->user_id;
            }
        }

        $trans_listing = DB::table('transaction_history')->where(['contract_id'=>$contract_id, 'coin_sender_id'=>$coin_sender_id, 'coin_receiver_id'=>$coin_receiver_id])->first();
        if(!isset($trans_listing->transaction_id)){
            $newRow = new TransactionHistory();
            $newRow->contract_id = $contract_id;
            $newRow->coin_amount = $coin_amount;
            $newRow->coin_sender_id = $coin_sender_id;
            $newRow->coin_receiver_id = $coin_receiver_id;
            $newRow->save();
            $transaction_id = $newRow->id;
        }else{
            $transaction_id = $trans_listing->transaction_id;
        }

        $listing = listings::all()->where('id', '=', $listing_id)->first();
        $data = $this->getMsgListByContractId($contract_id);
        $is_success = $this->isSuccess($contract_id, $user->id);
        $is_feedback = $this->isFeedback($contract_id, $user->id);

        return view('trademessage.index')->with('data', $data)->with('transaction_id', $transaction_id)->with('listing', $listing)->with('listing_id', $listing_id)->with('contract_id', $contract_id)->with('sender_id', $sender_id)->with('receiver_id', $receiver_id)->with('is_success', $is_success)->with('is_feedback', $is_feedback);
    }

    public function dispute(Request $request){
        DB::table('transaction_history')->where('transaction_id', '=', $request->transaction_id)->update(['status_col'=>1]);
        echo "ok";
        exit;
    }

    public function messagebox(Request $request){

        //User list
        $user = \Auth::user();
        $user_id = $user->id;
        $sql = "select c.id contract_id, c.sender_id id, u.name name from contract c, users u where c.sender_id = u.id and receiver_id = {$user_id} order by c.id desc";
        $user_list = DB::select($sql);
// dd($sql);      
        //Messages for first user
        $msg_content = array();
        foreach($user_list as $user){
            $msg_content = $this->getMsgListByContractId($user->contract_id);
            break;
        }

// dd($fee);        
        return view('trademessage.messagebox')->with('user_list', $user_list)->with('msg_content', $msg_content);
    }

    private function getMsgListByContractId( $contract_id ) {
        $datas = DB::table('trade_message')
                    ->leftjoin('users', 'users.id', '=', 'trade_message.sender_id')
                    ->select('trade_message.*', 'users.name')
                    ->where('contract_id', '=', $contract_id)
                    ->orderBy('created_at', 'asc')
                    ->get();
        $arr = array();
        $user = \Auth::user();
        $current_id = $user->id;
        foreach( $datas as $data ) {
            $user_state = 'success left-content';
            if($current_id == $data->sender_id)
                $user_state = 'info right-content';
            if(($current_id != $data->sender_id) && ($current_id != $data->receiver_id))    
                continue;
            $user_name = (is_null($data->name)) ? 'P2Coin' : $data->name;
            $arr[] = array( 'contract_id'       => $data->contract_id,
                            'sender_id'         => $data->sender_id,
                            'receiver_id'       => $data->receiver_id,
                            'message_content'   => $data->message_content,
                            'user_state'        => $user_state,
                            'name'              => $user_name,
                            'created_at'        => date('H:m:s M j,Y',strtotime($data->created_at)));
        }
        return $arr;
    }

    public function setTradeStatus( Request $request ) {
        $contract_id = $request->contract_id;
        $status = $request->status;
        $user = \Auth::user();
        $user_id = $user->id;
        DB::table('trade_message')->insert(['contract_id'=>$contract_id, 'sender_id'=>-3000, 'receiver_id'=>$user_id, 'message_content'=>'Please leave feedback', 'created_at'=>Date('Y-m-d H:i:s'), 'updated_at'=>Date('Y-m-d H:i:s')]);
        $flag = 'fail';
        $row = TransactionHistory::all()->where('contract_id','=',$contract_id)->first();

        try {
            if($row->coin_sender_id == $user_id)
                DB::table('transaction_history')->where('contract_id', '=', $contract_id)->update(['seller_feedback'=>$status]);
            if($row->coin_receiver_id == $user_id)
                DB::table('transaction_history')->where('contract_id', '=', $contract_id)->update(['buyer_feedback'=>$status]);
            $flag = 'success';
            echo $flag;
        }
        catch( Exception $e ) {
            echo $flag;
        }
        exit;
    }

    public function leaveFeedback(Request $request){
        $user = \Auth::user();
        $user_id = $user->id;
        $contract_id = $request->contract_id;
        $feedback = $request->feedback;
        $outcome = $request->outcome;

        $row = DB::table('transaction_history')->where('contract_id','=',$contract_id)->first();
        if($row->coin_sender_id == $user_id){
            DB::table('trade_feedback')->insert(['contract_id' => $contract_id, 'seller_id' => $user_id, 'buyer_id' => $row->coin_receiver_id, 'feedback' => $feedback, 'outcome' => $outcome, 'created_at' => Date('Y-m-d H:i:s'), 'updated_at' => Date('Y-m-d H:i:s')]);
        }
        if($row->coin_receiver_id == $user_id)
            DB::table('trade_feedback')->insert(['contract_id' => $contract_id, 'seller_id' => $user_id, 'buyer_id' => $row->coin_sender_id, 'feedback' => $feedback, 'outcome' => $outcome, 'created_at' => Date('Y-m-d H:i:s'), 'updated_at' => Date('Y-m-d H:i:s')]);
        echo "ok";
        exit;
    }

    public function isFeedback($contract_id, $user_id){
        return DB::table('trade_feedback')->select('trade_feedback.*')->where('contract_id', '=', $contract_id)->where('seller_id', '=', $user_id)->get()->count();
    }
    public function isSuccess($contract_id, $user_id){
        $row = DB::table('transaction_history')->select('transaction_history.*')->where('contract_id', '=', $contract_id)->first();
        $coin_sender_id = $row->coin_sender_id;
        $coin_receiver_id = $row->coin_receiver_id;
        $seller_feedback = $row->seller_feedback;
        $buyer_feedback = $row->buyer_feedback;
        $success = 0;
        if($user_id == $coin_sender_id){
            if($seller_feedback != -1)
                $success = 1;
        }
        if($user_id == $coin_receiver_id){
            if($buyer_feedback != -1)
                $success = 1;
        }
        return $success;
    }
}
