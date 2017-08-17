<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndexModel;
use App\Models\WalletManage;
use App\Models\UserWallet;
use App\Models\BlockchainWalletMng;
use DB;


class IndexController extends Controller
{
    //
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }
    public $country_info = array("AF"=>"Afghanistan","AL"=>"Albania","DZ"=>"Algeria","AS"=>"American Samoa","AD"=>"Andorra","AO"=>"Angola","AI"=>"Anguilla",
        "AQ"=>"Antarctica",
        "AG"=>"Antigua and Barbuda",
        "AR"=>"Argentina",
        "AM"=>"Armenia",
        "AW"=>"Aruba",
        "AU"=>"Australia",
        "AT"=>"Austria",
        "AZ"=>"Azerbaijan",
        "BS"=>"Bahamas",
        "BH"=>"Bahrain",
        "BD"=>"Bangladesh",
        "BB"=>"Barbados",
        "BY"=>"Belarus",
        "BE"=>"Belgium",
        "BZ"=>"Belize",
        "BJ"=>"Benin",
        "BM"=>"Bermuda",
        "BT"=>"Bhutan",
        "BO"=>"Bolivia",
        "BA"=>"Bosnia and Herzegowina",
        "BW"=>"Botswana",
        "BV"=>"Bouvet Island",
        "BR"=>"Brazil",
        "IO"=>"British Indian Ocean Territory",
        "BN"=>"Brunei Darussalam",
        "BG"=>"Bulgaria",
        "BF"=>"Burkina Faso",
        "BI"=>"Burundi",
        "KH"=>"Cambodia",
        "CM"=>"Cameroon",
        "CA"=>"Canada",
        "CV"=>"Cape Verde",
        "KY"=>"Cayman Islands",
        "CF"=>"Central African Republic",
        "TD"=>"Chad",
        "CL"=>"Chile",
        "CN"=>"China",
        "CX"=>"Christmas Island",
        "CC"=>"Cocos (Keeling) Islands",
        "CO"=>"Colombia",
        "KM"=>"Comoros",
        "CG"=>"Congo",
        "CD"=>"Congo, the Democratic Republic of the",
        "CK"=>"Cook Islands",
        "CR"=>"Costa Rica",
        "CI"=>"Cote d'Ivoire",
        "HR"=>"Croatia (Hrvatska)",
        "CU"=>"Cuba",
        "CY"=>"Cyprus",
        "CZ"=>"Czech Republic",
        "DK"=>"Denmark",
        "DJ"=>"Djibouti",
        "DM"=>"Dominica",
        "DO"=>"Dominican Republic",
        "TP"=>"East Timor",
        "EC"=>"Ecuador",
        "EG"=>"Egypt",
        "SV"=>"El Salvador",
        "GQ"=>"Equatorial Guinea",
        "ER"=>"Eritrea",
        "EE"=>"Estonia",
        "ET"=>"Ethiopia",
        "FK"=>"Falkland Islands (Malvinas)",
        "FO"=>"Faroe Islands",
        "FJ"=>"Fiji",
        "FI"=>"Finland",
        "FR"=>"France",
        "FX"=>"France, Metropolitan",
        "GF"=>"French Guiana",
        "PF"=>"French Polynesia",
        "TF"=>"French Southern Territories",
        "GA"=>"Gabon",
        "GM"=>"Gambia",
        "GE"=>"Georgia",
        "DE"=>"Germany",
        "GH"=>"Ghana",
        "GI"=>"Gibraltar",
        "GR"=>"Greece",
        "GL"=>"Greenland",
        "GD"=>"Grenada",
        "GP"=>"Guadeloupe",
        "GU"=>"Guam",
        "GT"=>"Guatemala",
        "GN"=>"Guinea",
        "GW"=>"Guinea-Bissau",
        "GY"=>"Guyana",
        "HT"=>"Haiti",
        "HM"=>"Heard and Mc Donald Islands",
        "VA"=>"Holy See (Vatican City State)",
        "HN"=>"Honduras",
        "HK"=>"Hong Kong",
        "HU"=>"Hungary",
        "IS"=>"Iceland",
        "IN"=>"India",
        "ID"=>"Indonesia",
        "IR"=>"Iran (Islamic Republic of)",
        "IQ"=>"Iraq",
        "IE"=>"Ireland",
        "IL"=>"Israel",
        "IT"=>"Italy",
        "JM"=>"Jamaica",
        "JP"=>"Japan",
        "JO"=>"Jordan",
        "KZ"=>"Kazakhstan",
        "KE"=>"Kenya",
        "KI"=>"Kiribati",
        "KP"=>"Korea, Democratic People's Republic of",
        "KR"=>"Korea, Republic of",
        "KW"=>"Kuwait",
        "KG"=>"Kyrgyzstan",
        "LA"=>"Lao People's Democratic Republic",
        "LV"=>"Latvia",
        "LB"=>"Lebanon",
        "LS"=>"Lesotho",
        "LR"=>"Liberia",
        "LY"=>"Libyan Arab Jamahiriya",
        "LI"=>"Liechtenstein",
        "LT"=>"Lithuania",
        "LU"=>"Luxembourg",
        "MO"=>"Macau",
        "MK"=>"Macedonia, The Former Yugoslav Republic of",
        "MG"=>"Madagascar",
        "MW"=>"Malawi",
        "MY"=>"Malaysia",
        "MV"=>"Maldives",
        "ML"=>"Mali",
        "MT"=>"Malta",
        "MH"=>"Marshall Islands",
        "MQ"=>"Martinique",
        "MR"=>"Mauritania",
        "MU"=>"Mauritius",
        "YT"=>"Mayotte",
        "MX"=>"Mexico",
        "FM"=>"Micronesia, Federated States of",
        "MD"=>"Moldova, Republic of",
        "MC"=>"Monaco",
        "MN"=>"Mongolia",
        "MS"=>"Montserrat",
        "MA"=>"Morocco",
        "MZ"=>"Mozambique",
        "MM"=>"Myanmar",
        "NA"=>"Namibia",
        "NR"=>"Nauru",
        "NP"=>"Nepal",
        "NL"=>"Netherlands",
        "AN"=>"Netherlands Antilles",
        "NC"=>"New Caledonia",
        "NZ"=>"New Zealand",
        "NI"=>"Nicaragua",
        "NE"=>"Niger",
        "NG"=>"Nigeria",
        "NU"=>"Niue",
        "NF"=>"Norfolk Island",
        "MP"=>"Northern Mariana Islands",
        "NO"=>"Norway",
        "OM"=>"Oman",
        "PK"=>"Pakistan",
        "PW"=>"Palau",
        "PA"=>"Panama",
        "PG"=>"Papua New Guinea",
        "PY"=>"Paraguay",
        "PE"=>"Peru",
        "PH"=>"Philippines",
        "PN"=>"Pitcairn",
        "PL"=>"Poland",
        "PT"=>"Portugal",
        "PR"=>"Puerto Rico",
        "QA"=>"Qatar",
        "RE"=>"Reunion",
        "RO"=>"Romania",
        "RU"=>"Russian Federation",
        "RW"=>"Rwanda",
        "KN"=>"Saint Kitts and Nevis", 
        "LC"=>"Saint LUCIA",
        "VC"=>"Saint Vincent and the Grenadines",
        "WS"=>"Samoa",
        "SM"=>"San Marino",
        "ST"=>"Sao Tome and Principe", 
        "SA"=>"Saudi Arabia",
        "SN"=>"Senegal",
        "SC"=>"Seychelles",
        "SL"=>"Sierra Leone",
        "SG"=>"Singapore",
        "SK"=>"Slovakia (Slovak Republic)",
        "SI"=>"Slovenia",
        "SB"=>"Solomon Islands",
        "SO"=>"Somalia",
        "ZA"=>"South Africa",
        "GS"=>"South Georgia and the South Sandwich Islands",
        "ES"=>"Spain",
        "LK"=>"Sri Lanka",
        "SH"=>"St. Helena",
        "PM"=>"St. Pierre and Miquelon",
        "SD"=>"Sudan",
        "SR"=>"Suriname",
        "SJ"=>"Svalbard and Jan Mayen Islands",
        "SZ"=>"Swaziland",
        "SE"=>"Sweden",
        "CH"=>"Switzerland",
        "SY"=>"Syrian Arab Republic",
        "TW"=>"Taiwan, Province of China",
        "TJ"=>"Tajikistan",
        "TZ"=>"Tanzania, United Republic of",
        "TH"=>"Thailand",
        "TG"=>"Togo",
        "TK"=>"Tokelau",
        "TO"=>"Tonga",
        "TT"=>"Trinidad and Tobago",
        "TN"=>"Tunisia",
        "TR"=>"Turkey",
        "TM"=>"Turkmenistan",
        "TC"=>"Turks and Caicos Islands",
        "TV"=>"Tuvalu",
        "UG"=>"Uganda",
        "UA"=>"Ukraine",
        "AE"=>"United Arab Emirates",
        "GB"=>"United Kingdom",
        "US"=>"United States",
        "UM"=>"United States Minor Outlying Islands",
        "UY"=>"Uruguay",
        "UZ"=>"Uzbekistan",
        "VU"=>"Vanuatu",
        "VE"=>"Venezuela",
        "VN"=>"Viet Nam",
        "VG"=>"Virgin Islands (British)",
        "VI"=>"Virgin Islands (U.S.)",
        "WF"=>"Wallis and Futuna Islands",
        "EH"=>"Western Sahara",
        "YE"=>"Yemen",
        "YU"=>"Yugoslavia",
        "ZM"=>"Zambia",
        "ZW"=>"Zimbabwe");

    public function index(Request $request){
        session()->reflash();
        try{

            $user = \Auth::user();
            if ( $user ) {
                try {
                    // $userWalletRow = UserWallet::all()->where('user_id', '=', $user->id)->first();
                    // $model = new WalletManage();
                    // $wallet_info = $model->getWalletBalanceByAddress($userWalletRow->wallet_address);
                    // $coin_balance= floatval($wallet_info->data->available_balance);

                    $model = new UserWallet();
                    // $ethAddress = $model->getUserWallet($user->id, 'eth');
                    // $blockchain = new BlockchainWalletMng();
                    // $blockchain->setWalletType('eth');
                    // $balanceInfo = $blockchain->getAddressBalance($ethAddress);
                    session()->put('btc_amount', 0 /*$coin_balance*/);
                    session()->put('eth_amount', 0 /* $balanceInfo['balance']*/);
                }
                catch(Exception $exp) {
                    session()->put('btc_amount', 0);
                    session()->put('eth_amount', 0);

                }

            }

            $request_location_info = json_decode($request->location_info);
            $locationInfo = array('ip'=>$request_location_info->ip,
                                'city'=>$request_location_info->city,
                                'region'=>$request_location_info->region,
                                'country'=>$request_location_info->country,
                                'country_full_name'=>$this->country_info[$request_location_info->country],
                                'loc'=>$request_location_info->loc,
                                'org'=>$request_location_info->org,
                                /*'postal'=>$request_location_info->postal*/);
                                
            session()->put('locationinfo',$locationInfo);
            $wmodel = new UserWallet();
            $local_currency = $wmodel->getLocalCurrencyRate("USD");

            return view('index.index')->with('country', $this->country_info[$locationInfo['country']])->with('usd_currency', $local_currency);
        }
        catch( Exception $e ){
            return redirect()->route("/");
        }
        
    }    

    public function getListingData(Request $request) {

        $flag = $request->flag;
        $localinfo = session()->get('locationinfo');
        
        $user = \Auth::user();
        $lModel = new IndexModel();
//        $wmodel = new UserWallet();

        $crypto_name = "Cryptocurrencies";
        if($request->coin_type == "btc")
            $crypto_name = "Bitcoin";
        if($request->coin_type == "eth")
            $crypto_name = "Ethereum";

        if($request->flag == true){
            $buy_listings = $lModel->getListingsData($user->id, 1, 1, array('coin_amount'=>0, 'coin_type'=>$request->coin_type, 'payment_method'=>$request->payment_method, 'location'=>$this->country_info[$localinfo['country']]));
          
            $sell_listings = $lModel->getListingsData($user->id, 0, 1, array('coin_amount'=>0, 'coin_type'=>$request->coin_type, 'payment_method'=>$request->payment_method, 'location'=>$this->country_info[$localinfo['country']]));

            $location = $crypto_name . " in " . $this->country_info[$localinfo['country']];
        }else{
            $buy_listings = $lModel->getListingsData($user->id, 1, 0, $request);
            $sell_listings = $lModel->getListingsData($user->id, 0, 0, $request);
            if($request->location != "")
                $location = $crypto_name . " in " . $request->location;
            else
                $location = $crypto_name . " in " . $this->country_info[$localinfo['country']];
        }

        $buy_list = "";
        foreach($buy_listings as $listing){
//            $local_currency = $wmodel->getLocalCurrencyRate($listing->currency);
            $data = DB::table("contract")
                        ->join('transaction_history', 'transaction_history.contract_id', '=', 'contract.id')
                        ->select('id')
                        ->where('sender_id', '=', $user->id)->where('receiver_id', '=', $listing->user_id)->where('listing_id', '=', $listing->id)
                        ->get();

            $buy_list .= "<tr>";
            $buy_list .= "<td>" . $listing->name . "</td>";
            $buy_list .= "<td>" . $listing->payment_method . ":" . $listing->payment_name . "</td>";
            $buy_list .= "<td>" . round($listing->coin_amount, 2) . " " . $listing->currency . "</td>";
            $buy_list .= "<td>" . $listing->min_transaction_limit . "-" . $listing->max_transaction_limit . " " . $listing->currency . "</td>";
            $buy_list .= "<td>";
            if ( !count($data) )
                $buy_list .= "<button type='button' onclick=\"j_obj.doCreateContractAndGoTransaction('".$listing->id . "-" . $listing->user_id . "-" . $listing->coin_type."')\" class='btn btn-success btn-green buy'>BUY</button>";
            else
                $buy_list .= "<button type='button' onclick=\"j_obj.doViewMessages('" . $data[0]->id . "-" . $listing->id . "-" . $user->id . "-" . $listing->user_id . "-1-0')\" class='btn btn-success btn-green view'>View/Message</button>";
            $buy_list .= "</td>";                       
            $buy_list .= "</tr>";
        }  

        $sell_list = "";
        foreach($sell_listings as $listing){
//            $local_currency = $wmodel->getLocalCurrencyRate($listing->currency);
            $data = DB::table("contract")
                        ->join('transaction_history', 'transaction_history.contract_id', '=', 'contract.id')
                        ->select('id')
                        ->where('sender_id', '=', $user->id)->where('receiver_id', '=', $listing->user_id)->where('listing_id', '=', $listing->id)
                        ->get();

            $sell_list .= "<tr>";
            $sell_list .= "<td>" . $listing->name . "</td>";
            $sell_list .= "<td>" . $listing->payment_method . ":" . $listing->payment_name . "</td>";
            $sell_list .= "<td>" . round($listing->coin_amount, 5) . " " . strtoupper($listing->coin_type) . "</td>";
            $sell_list .= "<td>" . $listing->min_transaction_limit . "-" . $listing->max_transaction_limit . " " . $listing->currency . "</td>";
            $sell_list .= "<td>";
            if ( !count($data) )
                $sell_list .= "<button type='button' onclick=\"j_obj.doCreateContractAndGoTransaction('".$listing->id . "-" . $listing->user_id . "-" . $listing->coin_type."')\" class='btn btn-success btn-green buy'>BUY</button>";
            else
                $sell_list .= "<button type='button' onclick=\"j_obj.doViewMessages('" . $data[0]->id . "-" . $listing->id . "-" . $user->id . "-" . $listing->user_id . "-0-0')\" class='btn btn-success btn-green view'>View/Message</button>";
            $sell_list .= "</td>";
            $sell_list .= "</tr>";
        }

        echo $location . "@@@" . $buy_list . "@@@" . $sell_list;
        exit;
    } 

    public function getLastMessageList(){
        $user = \Auth::user();

        $lModel = new IndexModel();
        $msg_listings = $lModel->getLastMessageList($user->id); 
        // dd($msg_listings);

        $msg_list = "";
        $flag = 0;
        foreach($msg_listings as $listing){
            if($user->id == $listing->user_id){
                if(!$listing->user_type)
                    $flag = 0;
                else
                    $flag = 1;
            }else{
                if(!$listing->user_type)
                    $flag = 1;
                else
                    $flag = 0;
            }
            $msg_list .= "<li style='height: 20px;border:1px dotted lightgrey;'><a href='#' class='view-message' style='height: 100%;padding: 0px!important;' onclick=\"doViewMessages('" . $listing->contract_id . "-" . $listing->listing_id . "-" . $listing->sender_id . "-" . $user->id . "-".$flag."-1')\">";
            $msg_list .= "<div class='col-sm-4'><strong>" . $listing->name . "</strong></div>";
            $msg_list .= "<div class='col-sm-8 msg-content'>" . $listing->message_content . "</div>";
            $msg_list .= "</a></li>";
        }

        echo $msg_list;
        exit;
    }
}
