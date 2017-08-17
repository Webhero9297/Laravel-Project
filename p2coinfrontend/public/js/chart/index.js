/**
 * Created by administrator on 7/28/17.
 */
$(document).ready(function(){
    $('.a_view_chart').click(function() {

        $(".modal-title").html($(this).attr('title')+" Chart");
        var coin = $(this).attr('id');
        var _token = $('meta[name=csrf-token]').attr('content');
        ( coin == 'eth' ) ? url = 'https://graphs.coinmarketcap.com/currencies/ethereum/' : url = 'https://graphs.coinmarketcap.com/currencies/bitcoin/';
        // $.get(url, function(resp){
        $.post('getchartdatabycoin',{coin:coin,_token:_token}, function(resp){

            // var tmp_market_data = resp.market_cap_by_available_supply;
            
            var tmp_price_usd = resp.price_usd;
            var tmp_price_btc = resp.price_btc;
            var volume_usd = [];
            var price_usd = [];
            var price_btc = [];
            var tmp_market_data_resp = resp.market_cap_by_available_supply;
            var market_data = [];
            $.each(tmp_market_data_resp, function(){
                market_data.push([this[0], this[1]]);
            });
            $.each(resp.volume_usd, function(){
                volume_usd.push([this[0], this[1]]);
            });
            $.each(resp.price_btc, function(){
                price_btc.push([this[0], this[1]]);
            });
            $.each(resp.price_usd, function(){
                price_usd.push([this[0], this[1]*10000000]);
            });
            // $.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=usdeur.json&callback=?', function (data) {
                var detailChart;
console.log(resp);
                // $(document).ready(function () {

                    // create the detail chart
                    function createDetail(masterChart) {
console.log(masterChart.axes[1].max);
                        // prepare the detail chart
                        var detailData = [],
                            detailStart = price_usd[0][0];
                        $.each(masterChart.series[0].data, function () {
                            if (this.x >= detailStart) {
                                detailData.push(this.y);
                            }
                        });
console.log(detailData);
                        // create a detail chart referenced by a global variable
                        detailChart = Highcharts.chart('detail-container', {
                            chart: {
                                marginBottom: 120,
                                reflow: false,
                                marginLeft: 50,
                                marginRight: 20,
                                style: {
                                    position: 'absolute'
                                }
                            },
                            credits: {
                                enabled: false
                            },
                            title: {
                                text: $('.a_view_chart').attr('title')+' Historical Data'
                            },
                            subtitle: {
                                text: ''
                            },
                            xAxis: {
                                type: 'datetime'
                            },
                            yAxis: {
                                title: {
                                    text: null
                                },
                                maxZoom: 0.1
                            },
                            tooltip: {
                                formatter: function () {
console.log(this.points[0].color);
                                    return Highcharts.dateFormat('%A %B %e %Y', this.x) + ':<br/>' +
                                        "<div style='width:10px;height:10px;background-color:"+this.points[3].color+";'></div><span style='font-weight:bold;'><strong>"+ this.points[3].series.name + ": " + this.points[3].y + 'USD</strong></span><br/>' +
                                        "<div style='width:10px;height:10px;background-color:"+this.points[0].color+";'></div><span style='font-weight:bold;'><strong>"+ this.points[0].series.name + ": " + this.points[0].y/10000000 + '</strong></span><br/>' +
                                        // "<div style='width:10px;height:10px;background-color:"+this.points[1].color+";'></div><span style='font-weight:bold;'><strong>"+ this.points[1].series.name + ": " + this.points[1].y + '</strong></span><br/>' +
                                        "<div style='width:10px;height:10px;background-color:"+this.points[2].color+";'></div><span style='font-weight:bold;'><strong>"+ this.points[2].series.name + ": " + this.points[2].y + 'USD</strong></span><br/>'
                                        ;
                                },
                                shared: true
                            },
                            legend: {
                                enabled: false
                            },
                            plotOptions: {
                                series: {
                                    marker: {
                                        enabled: false,
                                        states: {
                                            hover: {
                                                enabled: true,
                                                radius: 3
                                            }
                                        }
                                    }
                                }
                            },
                            series: [{
                                type: 'line',
                                name: 'Price(USD)',
                                pointStart: price_usd[0][0],
                                pointInterval: 24 * 3600 * 1000,
                                data: price_usd
                            },{
                                type: 'line',
                                name: 'Price(BTC)',
                                pointInterval: 24 * 3600 * 1000,
                                pointStart: price_btc[0][0],
                                data: price_btc
                            },{
                                type: 'line',
                                name: '24h Volume(USD)',
                                pointInterval: 24 * 3600 * 1000,
                                pointStart: volume_usd[0][0],
                                data: volume_usd
                            },{
                                type: 'line',
                                name: 'Market Cap',
                                pointInterval: 24 * 3600 * 1000,
                                pointStart: market_data[0][0],
                                data: market_data
                            }],

                            exporting: {
                                enabled: false
                            }

                        }); // return chart
                    }

                    // create the master chart
                    function createMaster() {
                        Highcharts.chart('master-container', {
                            chart: {
                                reflow: false,
                                borderWidth: 0,
                                backgroundColor: null,
                                marginLeft: 50,
                                marginRight: 20,
                                zoomType: 'x',
                                events: {

                                    // listen to the selection event on the master chart to update the
                                    // extremes of the detail chart
                                    selection: function (event) {
                                        var extremesObject = event.xAxis[0],
                                            min = extremesObject.min,
                                            max = extremesObject.max,
                                            detailData = [],
                                            xAxis = this.xAxis[0];

                                        // reverse engineer the last part of the data
// console.log(this.series[3]);
                                        var chart_content = this.series[3];
                                        $.each(chart_content.data, function () {
                                            if (this.x > min && this.x < max) {
                                                detailData.push([this.x, this.y]);
                                            }
                                        });

                                        // move the plot bands to reflect the new detail span
                                        xAxis.removePlotBand('mask-before');
                                        xAxis.addPlotBand({
                                            id: 'mask-before',
                                            from: chart_content.data[0],
                                            to: min,
                                            color: 'rgba(0, 0, 0, 0.2)'
                                        });

                                        xAxis.removePlotBand('mask-after');
                                        xAxis.addPlotBand({
                                            id: 'mask-after',
                                            from: max,
                                            to: chart_content.data[chart_content.data.length - 1][0],
                                            color: 'rgba(0, 0, 0, 0.2)'
                                        });


                                        detailChart.series[0].setData(detailData);

                                        return false;
                                    }
                                }
                            },
                            title: {
                                text: null
                            },
                            xAxis: {
                                type: 'datetime',
                                showLastTickLabel: true,
                                maxZoom: 14 * 24 * 3600000, // fourteen days
                                plotBands: [{
                                    id: 'mask-before',
                                    from: market_data[0][0],
                                    to: market_data[market_data.length - 1][0],
                                    color: 'rgba(0, 0, 0, 0.2)'
                                }],
                                title: {
                                    text: null
                                }
                            },
                            yAxis: {
                                gridLineWidth: 0,
                                labels: {
                                    enabled: false
                                },
                                title: {
                                    text: null
                                },
                                min: 0.6,
                                showFirstLabel: false
                            },
                            tooltip: {
                                formatter: function () {
                                    return false;
                                }
                            },
                            legend: {
                                enabled: false
                            },
                            credits: {
                                enabled: false
                            },
                            plotOptions: {
                                series: {
                                    fillColor: {
                                        linearGradient: [0, 0, 0, 70],
                                        stops: [
                                            [0, Highcharts.getOptions().colors[0]],
                                            [1, 'rgba(255,255,255,0)']
                                        ]
                                    },
                                    lineWidth: 1,
                                    marker: {
                                        enabled: false
                                    },
                                    shadow: false,
                                    states: {
                                        hover: {
                                            lineWidth: 1
                                        }
                                    },
                                    enableMouseTracking: false
                                }
                            },

                            series: [{
                                type: 'area',
                                name: 'Price(USD)',
                                pointInterval: 24 * 3600 * 1000,
                                pointStart: price_usd[0][0],
                                data: price_usd
                            },{
                                type: 'area',
                                name: 'Price(BTC)',
                                pointInterval: 24 * 3600 * 1000,
                                pointStart: price_btc[0][0],
                                data: price_btc
                            },{
                                type: 'area',
                                name: '24h Volume(USD)',
                                pointInterval: 24 * 3600 * 1000,
                                pointStart: volume_usd[0][0],
                                data: volume_usd
                            },{
                                type: 'area',
                                name: 'Market Cap',
                                pointInterval: 24 * 3600 * 1000,
                                pointStart: market_data[0][0],
                                data: market_data
                            }],

                            exporting: {
                                enabled: false
                            }

                        }, function (masterChart) {
                            createDetail(masterChart);
                        }); // return chart instance
                    }

                    // make the container smaller and add a second container for the master chart
                    var $container = $('#container')
                        .css('position', 'relative');

                    $('<div id="detail-container">')
                        .appendTo($container);

                    $('<div id="master-container">')
                        .css({
                            position: 'absolute',
                            top: 300,
                            height: 100,
                            width: '100%'
                        })
                        .appendTo($container);

                    // create master and in its callback, create the detail chart
                    createMaster();
                // });
            // });
            // console.log(resp);
        });
        // $('#chart_dialog').modal('show');
    });
});