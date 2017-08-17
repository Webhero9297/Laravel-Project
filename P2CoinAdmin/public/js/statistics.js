var ChartsAmcharts = function() {
    var getVolume = function() {
        var _token = $('meta[name=csrf-token]').attr('content');
        $('#volume_bar').html("Loading...");
        $.post('getvolume', {year:'2017', month:'07', _token: _token}, function(resp){
            $('#volume_bar').html("");
            var days = resp.day;
            var btc_list = resp.btc;
            var eth_list = resp.eth;
            
            require.config({
                paths: {
                    echarts: '../assets/global/plugins/echarts/'
                }
            });
            require(
            [
                'echarts',
                'echarts/chart/bar',
                'echarts/chart/line',
            ],
            function(ec) {
                //--- BAR ---
                var myChart = ec.init(document.getElementById('volume_bar'));
                myChart.setOption({
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        data: ['Bitcoin', 'Ethereum']
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            dataView: {
                                show: true,
                                readOnly: false
                            },
                            magicType: {
                                show: true,
                                type: ['line', 'bar']
                            },
                            restore: {
                                show: true
                            },
                            saveAsImage: {
                                show: true
                            }
                        }
                    },
                    calculable: true,
                    xAxis: [{
                        type: 'category',
                        data: days
                    }],
                    yAxis: [{
                        type: 'value',
                        splitArea: {
                            show: true
                        }
                    }],
                    series: [{
                        name: 'Bitcoin',
                        type: 'bar',
                        data: btc_list
                    }, {
                        name: 'Ethereum',
                        type: 'bar',
                        data: eth_list
                    }]
                });
            }
            );
        });
    }

    
    var getRevenue = function() {
        var _token = $('meta[name=csrf-token]').attr('content');
        $('#echarts_bar').html("Loading...");
        $.post('getrevenu', {year:'2017', month:'07', _token: _token}, function(resp){
            $('#echarts_bar').html("");
            var days = resp.day;
            var btc_list = resp.btc;
            var eth_list = resp.eth;
            
            require.config({
                paths: {
                    echarts: '../assets/global/plugins/echarts/'
                }
            });
            require(
            [
                'echarts',
                'echarts/chart/bar',
                'echarts/chart/line',
            ],
            function(ec) {
                //--- BAR ---
                var myChart = ec.init(document.getElementById('echarts_bar'));
                myChart.setOption({
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        data: ['Bitcoin', 'Ethereum']
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            dataView: {
                                show: true,
                                readOnly: false
                            },
                            magicType: {
                                show: true,
                                type: ['line', 'bar']
                            },
                            restore: {
                                show: true
                            },
                            saveAsImage: {
                                show: true
                            }
                        }
                    },
                    calculable: true,
                    xAxis: [{
                        type: 'category',
                        data: days
                    }],
                    yAxis: [{
                        type: 'value',
                        splitArea: {
                            show: true
                        }
                    }],
                    series: [{
                        name: 'Bitcoin',
                        type: 'bar',
                        data: btc_list
                    }, {
                        name: 'Ethereum',
                        type: 'bar',
                        data: eth_list
                    }]
                });
            }
            );
        });
    }    
    var getTrades = function() {
        var _token = $('meta[name=csrf-token]').attr('content');
        $('#trades_bar').html("Loading...");
        $.post('gettrades', {year:'2017', month:'07', _token: _token}, function(resp){
            $('#trades_bar').html("");
            var days = resp.day;
            var btc_list = resp.btc;
            var eth_list = resp.eth;
            
            require.config({
                paths: {
                    echarts: '../assets/global/plugins/echarts/'
                }
            });
            require(
            [
                'echarts',
                'echarts/chart/bar',
                'echarts/chart/line',
            ],
            function(ec) {
                //--- BAR ---
                var myChart = ec.init(document.getElementById('trades_bar'));
                myChart.setOption({
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        data: ['Bitcoin', 'Ethereum']
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            dataView: {
                                show: true,
                                readOnly: false
                            },
                            magicType: {
                                show: true,
                                type: ['line', 'bar']
                            },
                            restore: {
                                show: true
                            },
                            saveAsImage: {
                                show: true
                            }
                        }
                    },
                    calculable: true,
                    xAxis: [{
                        type: 'category',
                        data: days
                    }],
                    yAxis: [{
                        type: 'value',
                        splitArea: {
                            show: true
                        }
                    }],
                    series: [{
                        name: 'Bitcoin',
                        type: 'bar',
                        data: btc_list
                    }, {
                        name: 'Ethereum',
                        type: 'bar',
                        data: eth_list
                    }]
                });
            }
            );
        });
    }    
    
    var getListings = function() {
        var _token = $('meta[name=csrf-token]').attr('content');
        $('#listings_bar').html("Loading...");
        $.post('getlistings', {year:'2017', month:'07', _token: _token}, function(resp){
            $('#listings_bar').html("");
            var days = resp.day;
            var btc_list = resp.btc;
            var eth_list = resp.eth;
            
            require.config({
                paths: {
                    echarts: '../assets/global/plugins/echarts/'
                }
            });
            require(
            [
                'echarts',
                'echarts/chart/bar',
                'echarts/chart/line',
            ],
            function(ec) {
                //--- BAR ---
                var myChart = ec.init(document.getElementById('listings_bar'));
                myChart.setOption({
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        data: ['Bitcoin', 'Ethereum']
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            dataView: {
                                show: true,
                                readOnly: false
                            },
                            magicType: {
                                show: true,
                                type: ['line', 'bar']
                            },
                            restore: {
                                show: true
                            },
                            saveAsImage: {
                                show: true
                            }
                        }
                    },
                    calculable: true,
                    xAxis: [{
                        type: 'category',
                        data: days
                    }],
                    yAxis: [{
                        type: 'value',
                        splitArea: {
                            show: true
                        }
                    }],
                    series: [{
                        name: 'Bitcoin',
                        type: 'bar',
                        data: btc_list
                    }, {
                        name: 'Ethereum',
                        type: 'bar',
                        data: eth_list
                    }]
                });
            }
            );
        });
    }

    var getSignUpUsers = function() {
        var _token = $('meta[name=csrf-token]').attr('content');
        $('#signupusers_bar').html("Loading...");
        $.post('getsignupusers', {year:'2017', month:'07', _token: _token}, function(resp){
            $('#signupusers_bar').html("");
            var days = resp.day;
            var signupusers_list = resp.signupusers;
            
            require.config({
                paths: {
                    echarts: '../assets/global/plugins/echarts/'
                }
            });
            require(
            [
                'echarts',
                'echarts/chart/bar',
                'echarts/chart/line',
            ],
            function(ec) {
                //--- BAR ---
                var myChart = ec.init(document.getElementById('signupusers_bar'));
                myChart.setOption({
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        data: ['Users']
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            dataView: {
                                show: true,
                                readOnly: false
                            },
                            magicType: {
                                show: true,
                                type: ['line', 'bar']
                            },
                            restore: {
                                show: true
                            },
                            saveAsImage: {
                                show: true
                            }
                        }
                    },
                    calculable: true,
                    xAxis: [{
                        type: 'category',
                        data: days
                    }],
                    yAxis: [{
                        type: 'value',
                        splitArea: {
                            show: true
                        }
                    }],
                    series: [{
                        name: 'SignUp Users',
                        type: 'bar',
                        data: signupusers_list
                    }]
                });
            }
            );
        });
    }

    return {
        init: function() {
            getVolume();
            getRevenue();
            getTrades();
            getListings();
            getSignUpUsers();
        }
    };
}();

jQuery(document).ready(function() {
   ChartsAmcharts.init(); 
});