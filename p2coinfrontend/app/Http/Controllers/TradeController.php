<?php

namespace App\Http\Controllers;

use App\Models\Listings;

use Illuminate\Http\Request;
use DB;
use App\Models\UserWallet;
use App\Models\BlockchainWalletMng;
use App\Models\WalletManage;

class TradeController extends Controller
{
    //
    public $country_info = array("AF"=>"Afghanistan","AL"=>"Albania","DZ"=>"Algeria","AS"=>"American Samoa","AD"=>"Andorra","AO"=>"Angola","AI"=>"Anguilla",
                    "AQ"=>"Antarctica","AG"=>"Antigua and Barbuda","AR"=>"Argentina","AM"=>"Armenia","AW"=>"Aruba","AU"=>"Australia","AT"=>"Austria","AZ"=>"Azerbaijan",
                    "BS"=>"Bahamas","BH"=>"Bahrain","BD"=>"Bangladesh","BB"=>"Barbados","BY"=>"Belarus","BE"=>"Belgium","BZ"=>"Belize","BJ"=>"Benin","BM"=>"Bermuda",
                    "BT"=>"Bhutan","BO"=>"Bolivia","BA"=>"Bosnia and Herzegowina","BW"=>"Botswana","BV"=>"Bouvet Island","BR"=>"Brazil","IO"=>"British Indian Ocean Territory",
                    "BN"=>"Brunei Darussalam","BG"=>"Bulgaria","BF"=>"Burkina Faso","BI"=>"Burundi","KH"=>"Cambodia","CM"=>"Cameroon","CA"=>"Canada",
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
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        $user = \Auth::user();
        $userWalletRow = UserWallet::all()->where('user_id', '=', $user->id)->first();
        // $model = new WalletManage();
        // $wallet_info = $model->getWalletBalanceByAddress($userWalletRow->wallet_address);
        // $coin_balance= floatval($wallet_info->data->available_balance);
        
        $localinfo = session()->get('locationinfo');
        // // dd($localinfo);

        // $model = new UserWallet();
        // $ethAddress = $model->getUserWallet($user->id, 'eth');
        // $blockchain = new BlockchainWalletMng();
        // $blockchain->setWalletType('eth');
        // $balanceInfo = $blockchain->getAddressBalance($ethAddress);
        // session()->put('btc_amount', $coin_balance);
        // session()->put('eth_amount', $balanceInfo['balance']);

        session()->put('btc_amount', 0);
        session()->put('eth_amount', 0);

        $wmodel = new UserWallet();
        $local_currency = $wmodel->getLocalCurrencyRate("USD");
        return view('trade.screen')->with('real_location', $this->country_info[$localinfo['country']])->with('usd_currency', $local_currency);
    }

    public function getListingData(Request $request) {

        $flag = $request->flag;
        $localinfo = session()->get('locationinfo');
        $user = \Auth::user();
        $lModel = new Listings();
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
        $report_listing = $this->reportListingByUser();

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
            $buy_list .= "<td>" . round($listing->coin_amount, 2) . " " . strtoupper($listing->coin_type) . "</td>";
            $buy_list .= "<td>" . $listing->min_transaction_limit . "-" . $listing->max_transaction_limit . " " . $listing->currency . "</td>";
            $buy_list .= "<td>";
            if ( !count($data) )
                $buy_list .= "<button type='button' onclick=\"j_obj.doCreateContractAndGoTransaction('".$listing->id . "-" . $listing->user_id . "-" . $listing->coin_type."')\" class='btn btn-success btn-green buy'>BUY</button>";
            else
                $buy_list .= "<button type='button' onclick=\"j_obj.doViewMessages('" . $data[0]->id . "-" . $listing->id . "-" . $user->id . "-" . $listing->user_id . "-1-0')\" class='btn btn-success btn-green view'>View/Message</button>";
            $buy_list .= "</td>";
            $checked = in_array($listing->id, $report_listing) ? "checked" : "";
            $buy_list .= "<td><input type='checkbox' id=\"" . $user->id . "-" . $listing->id . "\" onchange=\"report_user(this);\" " . $checked . "/></td>";                       
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
            $checked = in_array($listing->id, $report_listing) ? "checked" : "";
            $sell_list .= "<td><input type='checkbox' id=\"" . $user->id . "-" . $listing->id . "\" onchange=\"report_user(this);\" " . $checked . "/></td>";                       
            $sell_list .= "</tr>";
        }

        echo $location . "@@@" . $buy_list . "@@@" . $sell_list;
        exit;
    } 

    public function getOpenTrades(){
        $user = \Auth::user();
        $user_id = $user->id;

        //
        $sell_listings = DB::table('listings l')
                        ->join('users u', 'l.user_id', '=', 'u.id')
                        ->join('contract c', 'l.id', '=', 'c.listing_id')
                        ->join('transaction_history t', 't.contract_id', '=', 'c.id')
                        ->select('c.*', 'u.name', 't.coin_amount', 'l.payment_method')
                        ->where('l.is_closed', '=', '1')->where('c.sender_id', '=', $user->id)->where('u.user_type', '=', 0)
                        ->where('l.user_id', '=', 'c.receiver_id')
                        ->orderBy('c.created_at', 'desc')
                        ->get();
        $sell_list = "";
        foreach($sell_listings as $listing){
            $sell_list .= "<tr>";
            $sell_list .= "<td>" . $listing->id . "</td>";
            $sell_list .= "<td>" . $listing->name . "</td>";
            $sell_list .= "<td>" . $listing->coin_amount . "</td>";
            $sell_list .= "<td>" . $listing->coin_amount . "</td>";
            $sell_list .= "<td>" . $listing->payment_method . "</td>";
            $sell_list .= "<td>-</td>";
            $sell_list .= "<td>" . $listing->created_at . "</td>";
            $sell_list .= "</tr>";
        }

        //
        $buy_listings = DB::table('listings l')
                        ->join('users u', 'l.user_id', '=', 'u.id')
                        ->join('contract c', 'l.id', '=', 'c.listing_id')
                        ->join('transaction_history t', 't.contract_id', '=', 'c.id')
                        ->select('c.*', 'u.name', 't.coin_amount', 'l.payment_method')
                        ->where('l.is_closed', '=', '1')->where('c.receiver_id', '=', $user->id)->where('u.user_type', '=', 1)
                        ->where('l.user_id', '=', 'c.sender_id')
                        ->orderBy('c.created_at', 'desc')
                        ->get();
        $buy_list = "";
        foreach($buy_listings as $listing){
            $buy_list .= "<tr>";
            $buy_list .= "<td>" . $listing->id . "</td>";
            $buy_list .= "<td>" . $listing->name . "</td>";
            $buy_list .= "<td>" . $listing->coin_amount . "</td>";
            $buy_list .= "<td>" . $listing->coin_amount . "</td>";
            $buy_list .= "<td>" . $listing->payment_method . "</td>";
            $buy_list .= "<td>-</td>";
            $buy_list .= "<td>" . $listing->created_at . "</td>";
            $buy_list .= "</tr>";
        }

        echo $sell_list . "@@@" . $buy_list;
        exit;
    }

    public function reportListing(Request $request){
        $user = \Auth::user();
        $listing_id = $request->listing_id;
        $report_reason = $request->report_reason;
        DB::table('listing_report')->insert(['listing_id'=>$listing_id, 'report_user_id'=>$user->id, 'report_reason'=>$report_reason, 'created_at'=>Date('Y-m-d H:i:s'), 'updated_at'=>Date('Y-m-d H:i:s')]);
        echo "ok";
        exit;
    }

    public function deleteReport(){
        $user = \Auth::user();
        $listing_id = request()->get('listing_id');
        DB::table('listing_report')->where(['listing_id'=>$listing_id, 'report_user_id'=>$user->id])->delete();
        echo "ok";
        exit;
    }

    public function reportListingByUser(){
        $user = \Auth::user();
        $data = DB::table('listing_report')->select('listing_id')->where('report_user_id', '=', $user->id)->get();
        $report_list = array();
        foreach($data as $row){
            $report_list[] = $row->listing_id;
        }
        return $report_list;
    }
}