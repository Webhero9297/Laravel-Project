@extends('layouts.app')
<style>
.border-welcome{
    border-radius:5px;
    margin-bottom:20px;
    min-height:240px;
    background-image:url('./public/assets/images/banner.png');
    background-repeat: no-repeat;
}
h1.banner-title { color: white; font-family: Roboto Bla; color: white !important; margin-top: 50px; font-size: 56px;}
.banner .btn { width: 150px; height: 50px; line-height: 100%; }

.trade .title th {font-family: Roboto Boldd; color: #000 !important;}
.table td {font-family: Roboto Regular; color: #818181 !important; text-align: center;}
.btc-color { background-color: #00b8e6; }
.eth-color { background-color: #028840; }
.btn-grey {
    color: grey;
    font-weight: bold;
    background-color: rgba(37, 157, 109, 0.01);
    border: 2px solid grey !important;
}
</style>
@section('content')
<script> var real_location = '{{ $country }}';</script> 
<script> var btc_currency = '{{ $usd_currency["btc"] }}';</script> 
<script> var eth_currency = '{{ $usd_currency["eth"] }}';</script> 

<div class="container">
    <div class="row banner" style="margin: 0px !important;">
        <div class="col-md-12 border-welcome">
            <div class="col-md-3">
                <img src="{{ asset('./assets/images/banner_boy.png') }}" style="width: 100%;">
            </div>
            <div class="col-md-9" style="margin-top: 30px;">
                <h1 class="banner-title">Welcome to P2Coin.net</h1>
                <div class="row col-md-offset-2">
                    <button href="{{ route('register') }}" type="button" class="btn btn-white" onclick="doOnHref('register')" >REGISTER</button>
                    <button href="{{ route('login') }}" type="button" class="btn btn-white" style="margin-left: 20px;"  onclick="doOnHref('login')">LOG IN</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row text-center toggle">
        <button data-toggle="collapse" data-target="#search_form" class="btn btn-warning gradient-btn">Search</button>
    </div>
    <div class="margin-height"></div>
    <div class="row searchbox text-center collapse" id="search_form">
        <div class="row search-text text-left">
            <div class='col-sm-1'></div>
            <div class="col-sm-7" style="font-family: Roboto Light; font-size: 40px; color: #028840;">Search Listings</div>
            <div class="col-sm-3" id='usd_val' style="font-size: 14px; padding-top:35px;"></div>
            <div class='col-sm-1'></div>
        </div>
        <div class="first-input"><input type="text" id="coin_amount" name="coin_amount" placeholder="Amount"/></div>
        <div class="second-input">
            <select id="coin_type" name="coin_type">
                <option value="btc">BTH</option><option value="eth">ETH</option>
            </select>
        </div>
        <div class="second-input">
            <input id="id_ad-place" name="location" type="text" placeholder="Enter a location" autocomplete="off">
            <!-- <select id="location" name="location">
                <option value="none">Country</option>
                <option value="AF">Afghanistan</option>
                <option value="AL">Albania</option>
                <option value="DZ">Algeria</option>
                <option value="AS">American Samoa</option>
                <option value="AD">Andorra</option>
                <option value="AO">Angola</option>
                <option value="AI">Anguilla</option>
                <option value="AQ">Antarctica</option>
                <option value="AG">Antigua and Barbuda</option>
                <option value="AR">Argentina</option>
                <option value="AM">Armenia</option>
                <option value="AW">Aruba</option>
                <option value="AU">Australia</option>
                <option value="AT">Austria</option>
                <option value="AZ">Azerbaijan</option>
                <option value="BS">Bahamas</option>
                <option value="BH">Bahrain</option>
                <option value="BD">Bangladesh</option>
                <option value="BB">Barbados</option>
                <option value="BY">Belarus</option>
                <option value="BE">Belgium</option>
                <option value="BZ">Belize</option>
                <option value="BJ">Benin</option>
                <option value="BM">Bermuda</option>
                <option value="BT">Bhutan</option>
                <option value="BO">Bolivia</option>
                <option value="BA">Bosnia and Herzegowina</option>
                <option value="BW">Botswana</option>
                <option value="BV">Bouvet Island</option>
                <option value="BR">Brazil</option>
                <option value="IO">British Indian Ocean Territory</option>
                <option value="BN">Brunei Darussalam</option>
                <option value="BG">Bulgaria</option>
                <option value="BF">Burkina Faso</option>
                <option value="BI">Burundi</option>
                <option value="KH">Cambodia</option>
                <option value="CM">Cameroon</option>
                <option value="CA">Canada</option>
                <option value="CV">Cape Verde</option>
                <option value="KY">Cayman Islands</option>
                <option value="CF">Central African Republic</option>
                <option value="TD">Chad</option>
                <option value="CL">Chile</option>
                <option value="CN">China</option>
                <option value="CX">Christmas Island</option>
                <option value="CC">Cocos (Keeling) Islands</option>
                <option value="CO">Colombia</option>
                <option value="KM">Comoros</option>
                <option value="CG">Congo</option>
                <option value="CD">Congo, the Democratic Republic of the</option>
                <option value="CK">Cook Islands</option>
                <option value="CR">Costa Rica</option>
                <option value="CI">Cote d'Ivoire</option>
                <option value="HR">Croatia (Hrvatska)</option>
                <option value="CU">Cuba</option>
                <option value="CY">Cyprus</option>
                <option value="CZ">Czech Republic</option>
                <option value="DK">Denmark</option>
                <option value="DJ">Djibouti</option>
                <option value="DM">Dominica</option>
                <option value="DO">Dominican Republic</option>
                <option value="TP">East Timor</option>
                <option value="EC">Ecuador</option>
                <option value="EG">Egypt</option>
                <option value="SV">El Salvador</option>
                <option value="GQ">Equatorial Guinea</option>
                <option value="ER">Eritrea</option>
                <option value="EE">Estonia</option>
                <option value="ET">Ethiopia</option>
                <option value="FK">Falkland Islands (Malvinas)</option>
                <option value="FO">Faroe Islands</option>
                <option value="FJ">Fiji</option>
                <option value="FI">Finland</option>
                <option value="FR">France</option>
                <option value="FX">France, Metropolitan</option>
                <option value="GF">French Guiana</option>
                <option value="PF">French Polynesia</option>
                <option value="TF">French Southern Territories</option>
                <option value="GA">Gabon</option>
                <option value="GM">Gambia</option>
                <option value="GE">Georgia</option>
                <option value="DE">Germany</option>
                <option value="GH">Ghana</option>
                <option value="GI">Gibraltar</option>
                <option value="GR">Greece</option>
                <option value="GL">Greenland</option>
                <option value="GD">Grenada</option>
                <option value="GP">Guadeloupe</option>
                <option value="GU">Guam</option>
                <option value="GT">Guatemala</option>
                <option value="GN">Guinea</option>
                <option value="GW">Guinea-Bissau</option>
                <option value="GY">Guyana</option>
                <option value="HT">Haiti</option>
                <option value="HM">Heard and Mc Donald Islands</option>
                <option value="VA">Holy See (Vatican City State)</option>
                <option value="HN">Honduras</option>
                <option value="HK">Hong Kong</option>
                <option value="HU">Hungary</option>
                <option value="IS">Iceland</option>
                <option value="IN">India</option>
                <option value="ID">Indonesia</option>
                <option value="IR">Iran (Islamic Republic of)</option>
                <option value="IQ">Iraq</option>
                <option value="IE">Ireland</option>
                <option value="IL">Israel</option>
                <option value="IT">Italy</option>
                <option value="JM">Jamaica</option>
                <option value="JP">Japan</option>
                <option value="JO">Jordan</option>
                <option value="KZ">Kazakhstan</option>
                <option value="KE">Kenya</option>
                <option value="KI">Kiribati</option>
                <option value="KP">Korea, Democratic People's Republic of</option>
                <option value="KR">Korea, Republic of</option>
                <option value="KW">Kuwait</option>
                <option value="KG">Kyrgyzstan</option>
                <option value="LA">Lao People's Democratic Republic</option>
                <option value="LV">Latvia</option>
                <option value="LB">Lebanon</option>
                <option value="LS">Lesotho</option>
                <option value="LR">Liberia</option>
                <option value="LY">Libyan Arab Jamahiriya</option>
                <option value="LI">Liechtenstein</option>
                <option value="LT">Lithuania</option>
                <option value="LU">Luxembourg</option>
                <option value="MO">Macau</option>
                <option value="MK">Macedonia, The Former Yugoslav Republic of</option>
                <option value="MG">Madagascar</option>
                <option value="MW">Malawi</option>
                <option value="MY">Malaysia</option>
                <option value="MV">Maldives</option>
                <option value="ML">Mali</option>
                <option value="MT">Malta</option>
                <option value="MH">Marshall Islands</option>
                <option value="MQ">Martinique</option>
                <option value="MR">Mauritania</option>
                <option value="MU">Mauritius</option>
                <option value="YT">Mayotte</option>
                <option value="MX">Mexico</option>
                <option value="FM">Micronesia, Federated States of</option>
                <option value="MD">Moldova, Republic of</option>
                <option value="MC">Monaco</option>
                <option value="MN">Mongolia</option>
                <option value="MS">Montserrat</option>
                <option value="MA">Morocco</option>
                <option value="MZ">Mozambique</option>
                <option value="MM">Myanmar</option>
                <option value="NA">Namibia</option>
                <option value="NR">Nauru</option>
                <option value="NP">Nepal</option>
                <option value="NL">Netherlands</option>
                <option value="AN">Netherlands Antilles</option>
                <option value="NC">New Caledonia</option>
                <option value="NZ">New Zealand</option>
                <option value="NI">Nicaragua</option>
                <option value="NE">Niger</option>
                <option value="NG">Nigeria</option>
                <option value="NU">Niue</option>
                <option value="NF">Norfolk Island</option>
                <option value="MP">Northern Mariana Islands</option>
                <option value="NO">Norway</option>
                <option value="OM">Oman</option>
                <option value="PK">Pakistan</option>
                <option value="PW">Palau</option>
                <option value="PA">Panama</option>
                <option value="PG">Papua New Guinea</option>
                <option value="PY">Paraguay</option>
                <option value="PE">Peru</option>
                <option value="PH">Philippines</option>
                <option value="PN">Pitcairn</option>
                <option value="PL">Poland</option>
                <option value="PT">Portugal</option>
                <option value="PR">Puerto Rico</option>
                <option value="QA">Qatar</option>
                <option value="RE">Reunion</option>
                <option value="RO">Romania</option>
                <option value="RU">Russian Federation</option>
                <option value="RW">Rwanda</option>
                <option value="KN">Saint Kitts and Nevis</option> 
                <option value="LC">Saint LUCIA</option>
                <option value="VC">Saint Vincent and the Grenadines</option>
                <option value="WS">Samoa</option>
                <option value="SM">San Marino</option>
                <option value="ST">Sao Tome and Principe</option> 
                <option value="SA">Saudi Arabia</option>
                <option value="SN">Senegal</option>
                <option value="SC">Seychelles</option>
                <option value="SL">Sierra Leone</option>
                <option value="SG">Singapore</option>
                <option value="SK">Slovakia (Slovak Republic)</option>
                <option value="SI">Slovenia</option>
                <option value="SB">Solomon Islands</option>
                <option value="SO">Somalia</option>
                <option value="ZA">South Africa</option>
                <option value="GS">South Georgia and the South Sandwich Islands</option>
                <option value="ES">Spain</option>
                <option value="LK">Sri Lanka</option>
                <option value="SH">St. Helena</option>
                <option value="PM">St. Pierre and Miquelon</option>
                <option value="SD">Sudan</option>
                <option value="SR">Suriname</option>
                <option value="SJ">Svalbard and Jan Mayen Islands</option>
                <option value="SZ">Swaziland</option>
                <option value="SE">Sweden</option>
                <option value="CH">Switzerland</option>
                <option value="SY">Syrian Arab Republic</option>
                <option value="TW">Taiwan, Province of China</option>
                <option value="TJ">Tajikistan</option>
                <option value="TZ">Tanzania, United Republic of</option>
                <option value="TH">Thailand</option>
                <option value="TG">Togo</option>
                <option value="TK">Tokelau</option>
                <option value="TO">Tonga</option>
                <option value="TT">Trinidad and Tobago</option>
                <option value="TN">Tunisia</option>
                <option value="TR">Turkey</option>
                <option value="TM">Turkmenistan</option>
                <option value="TC">Turks and Caicos Islands</option>
                <option value="TV">Tuvalu</option>
                <option value="UG">Uganda</option>
                <option value="UA">Ukraine</option>
                <option value="AE">United Arab Emirates</option>
                <option value="GB">United Kingdom</option>
                <option value="US">United States</option>
                <option value="UM">United States Minor Outlying Islands</option>
                <option value="UY">Uruguay</option>
                <option value="UZ">Uzbekistan</option>
                <option value="VU">Vanuatu</option>
                <option value="VE">Venezuela</option>
                <option value="VN">Viet Nam</option>
                <option value="VG">Virgin Islands (British)</option>
                <option value="VI">Virgin Islands (U.S.)</option>
                <option value="WF">Wallis and Futuna Islands</option>
                <option value="EH">Western Sahara</option>
                <option value="YE">Yemen</option>
                <option value="YU">Yugoslavia</option>
                <option value="ZM">Zambia</option>
                <option value="ZW">Zimbabwe</option>
            </select> -->
        </div>
        <div class="second-input">
            <select id="payment_method" name="payment_method">
                <option value="none">Payment Method</option>
                <option value="cash_deposit">Cash Deposit</option>
                <option value="bank_transfer">Bank Transfer</option>
                <option value="cash_in_person">Cash in Person</option>
                <option value="cash_by_email">Cash by Email</option>
                <option value="moneygram">Moneygram</option>
                <option value="western_union">Western Union</option>
                <option value="other">Other...</option>
            </select>
        </div>
        <div class="margin-height"></div>
        <div class="margin-height"></div>
        <div class="row text-center">
            <button class="btn btn-warning gradient-btn" id="search-btn">Search&nbsp;&nbsp;<i class="fa fa-caret-right" aria-hidden="true"></i></button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 title-content-body">
            <table class="table table-bordered trade">
                <thead>
                    <tr class="tb-title">
                        <th class="text-center" colspan = '6' style="color: white; font-size: 24px;">
                            Buy <font id="title1"></font>
                        </th>
                    </tr>
                    <tr class="title">
                        <th class="menu-caption text-center">Vendor</th>
                        <th class="menu-caption text-center">Payment Method</th>
                        <th class="menu-caption text-center">Price</th>
                        <th class="menu-caption text-center">Limits</th>
                        <th class="menu-caption text-center">Contract</th>
                        <th class="menu-caption text-center">Report</th>
                    </tr>
                </thead>
                <tbody id="buy_list">
                </tbody>
            </table>
        </div>
        <div class="col-md-12 text-center">
            <button class="btn btn-grey see-more" prop="1">See More</button>
        </div>
    </div>
    <div class="margin-height"></div>
    <div class="row">
        <div class="col-md-12 title-content-body">
            <table class="table table-bordered trade">
                <thead>
                    <tr class="tb-title">
                        <th class="text-center" colspan = '6' style="color: white; font-size: 24px;">
                            Sell <font id="title2"></font>
                        </th>
                    </tr>
                    <tr class="title">
                        <th class="menu-caption text-center">Vendor</th>
                        <th class="menu-caption text-center">Payment Method</th>
                        <th class="menu-caption text-center">Price</th>
                        <th class="menu-caption text-center">Limits</th>
                        <th class="menu-caption text-center">Contract</th>
                        <th class="menu-caption text-center">Report</th>
                    </tr>
                </thead>
                <tbody id="sell_list">
                </tbody>
            </table>
        </div>
        <div class="col-md-12 text-center">
            <button class="btn btn-grey see-more" prop="0">See More</button>
        </div>
    </div>
</div>
<script>
    function doOnHref(route_name) {
        window.location.href=route_name;
        window.reload();
    }
</script>
<script src="{{ asset('js/home/index.js') }}"></script>
@endsection