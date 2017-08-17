                <div class="form-group">
                    <label class="control-label col-sm-3" for="terms_of_trade">Price equation</label>
                    <div class="col-sm-3">
                        <div class="controls"> 
                            <input class="textinput textInput form-control" id="price_equation" name="price_equation" type="text" value="btc_in_usd"> 
                        </div>
                        <div class="dynamic-info">
                            <span class="price-info-text">Trade price with current market value
                                <label>BTC: ${{ $price_data['btc'] }}</label>/<label>ETH: ${{ $price_data['eth'] }}</label>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="label-caption-title">How the trade price is determined from the hourly market price. For more information about equations how to determine your trading price see  pricing FAQ. Please note that the advertiser is always responsible for all payment processing fees.</label>
                    </div>
                </div>
