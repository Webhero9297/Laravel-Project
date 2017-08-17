<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use BlockCypher\Client\BlockchainClient;
use BlockCypher\Api\Wallet;
use BlockCypher\Client\WalletClient;
use BlockCypher\Api\AddressList;
use BlockCypher\Client\AddressClient;
use BlockCypher\Client\TXClient;
use BlockCypher\Rest\ApiContext;
use BlockCypher\Auth\SimpleTokenCredential;
use BlockCypher\Api\TXInput;
use BlockCypher\Api\TXOutput;
use BlockCypher\Api\TX;
use JsonRPC\Client;

class BlockchainWalletMng
{
    //
    private $token;
    private $wallet_type = 'eth';
    private $apiContext;
    public function __construct() {
        $this->token = env('CYPHER_TOKEN');
        $this->apiContext = new ApiContext(new SimpleTokenCredential($this->token));
//        $this->apiContext->setCoin($this->wallet_type);
    }
    public function setWalletType( $wallet_type ) {
        $this->wallet_type = $wallet_type;
        $this->apiContext->setCoin($this->wallet_type);
    }
    public function createWallet( $wallet_name=null ) {
        if (!is_null($wallet_name)) {
            $walletName = $wallet_name;
        } else {
            $walletName = self::generateWalletName(); // Default wallet name for samples
        }

        $wallet = new Wallet();
        $wallet->setName($walletName);
        $walletClient = new WalletClient($this->apiContext);

        try {
            $output = $walletClient->create($wallet);
        } catch (Exception $ex) {
            ResultPrinter::printError("Created Wallet", "Wallet", null, $ex);
            exit(1);
        }
        $ret_arr = array('wallet_name'=>$output->getName(), 'token'=>$output->getToken());
        dd($ret_arr);

        return $output;
    }
    public function generateAddress() {
        $addressClient = new AddressClient($this->apiContext);
        try {
            $addressKeyChain = $addressClient->generateAddress();
        } catch (Exception $ex) {
            ResultPrinter::printError("Generate Address", "AddressKeyChain", null, null, $ex);
            exit(1);
        }
        $ret_arr = array( 'private'=>$addressKeyChain->getPrivate(), 'public'=>$addressKeyChain->getPublic(), 'address'=>$addressKeyChain->getAddress() );

        return $ret_arr;
    }
    public function getAddressBalance( $address ) {
        $addressClient = new AddressClient($this->apiContext);
        try {
//            $url = "https://api.blockcypher.com/v1/eth/main/addrs/".$address."/balance?token=".$this->token;
//            $ch = curl_init("https://api.blockcypher.com/v1/eth/main/txs/new?token={$this->token}");
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            $addressBalance = curl_exec($ch);
//            curl_close($ch);
//dd($addressBalance);
            $addressBalance = $addressClient->getBalance($address);
        } catch (Exception $ex) {
            ResultPrinter::printError("Get Only Address Balance", "Address Balance", $address, null, $ex);
            exit(1);
        }
        $ret_arr = array('address'=>$addressBalance->getAddress(),
                         'total_received'=>$addressBalance->getTotalReceived(),
                         'total_sent'=>$addressBalance->getTotalSent(),
                         'balance'=>floatval($addressBalance->getBalance()/1000000000000000000),
                         'unconfirmed_balance'=>$addressBalance->getUnconfirmedBalance(),
                         'final_balance'=>$addressBalance->getFinalBalance(),
                         'n_tx'=>$addressBalance->getNTx(),
                         'unconfirmed_n_tx'=>$addressBalance->getUnconfirmedNTx(),
                         'final_n_tx'=>$addressBalance->getFinalNTx());
        return $ret_arr;
    }
    public function Transaction($from_address, $to_address, $amount) {
        $input = new TXInput();
        $input->addAddress($from_address['address']);
        $output = new TXOutput();
        $output->addAddress($to_address['address']);
        $output->setValue($amount); // Satoshis
        $tx = new TX();
        $tx->addInput($input);
        $tx->addOutput($output);
        $txClient = new TXClient($this->apiContext);
        try {
            $output = $txClient->create($tx);
        } catch (Exception $ex) {
            ResultPrinter::printError("Created TX", "TXSkeleton", null, null, $ex);
            exit(1);
        }
        return $output;
    }
    public function createTransaction($from_address, $to_address, $amount) {
        $txClient = new TXClient($this->apiContext);
        $privateKeys = array( $from_address['private'] );
        $param_arr = array('inputs'=>array(array('addresses'=>array($from_address['address']))),
            'outputs'=>array(array('addresses'=>array($to_address['address']), 'value'=>(intval($amount*1000000000000000000)))));
        $ch = curl_init("https://api.blockcypher.com/v1/eth/main/txs/new?token={$this->token}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($param_arr));
        $tx_object = curl_exec($ch);
        curl_close($ch);
        $Skelton = json_decode($tx_object);
        return $Skelton;
    }
    public function getFeeOfTransaction( $Skelton ) {
        return floatval($Skelton->tx->fees/1000000000000000000);
    }
    public function sendTransaction( $Skelton,$privatehex) {
        $datahex =$Skelton->tosign[0];
        $ch = curl_init("http://138.68.73.6?datahex={$datahex}&privatehex={$privatehex}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $signer = trim(curl_exec($ch));
        curl_close($ch);
        $txObj = $Skelton;
        $txObj->signatures = array($signer);
        $ch = curl_init("https://api.blockcypher.com/v1/eth/main/txs/send?token={$this->token}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($txObj));
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
    private function generateWalletName() {
        $length = env('WALLET_NAME_LENGTH');
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
