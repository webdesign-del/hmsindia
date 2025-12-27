/*------------------------------------------------------
    Author : www.webthemez.com
    License: Commons Attribution 3.0
    http://creativecommons.org/licenses/by/3.0/
---------------------------------------------------------  */

(function ($) {
    "use strict";
    var mainApp = {

        initFunction: function () {
            /*MENU 
            ------------------------------------*/
            $('#main-menu').metisMenu(); // Enable Metis Menu for submenu functionality
            
            // Check if Morris.js is available before initializing charts
            if (typeof Morris === 'undefined') {
                console.log('Morris.js not available, skipping chart initialization');
                return;
            }
			
            $(window).bind("load resize", function () {
                if ($(this).width() < 768) {
                    $('div.sidebar-collapse').addClass('collapse')
                } else {
                    $('div.sidebar-collapse').removeClass('collapse')
                }
            });

            /* MORRIS BAR CHART
			-----------------------------------------*/
            if ($('#morris-bar-chart').length > 0) {
                Morris.Bar({
                    element: 'morris-bar-chart',
                    data: [{
                        y: '2006',
                        a: 100,
                        b: 90
                    }, {
                        y: '2007',
                        a: 75,
                        b: 65
                    }, {
                        y: '2008',
                        a: 50,
                        b: 40
                    }, {
                        y: '2009',
                        a: 75,
                        b: 65
                    }, {
                        y: '2010',
                        a: 50,
                        b: 40
                    }, {
                        y: '2011',
                        a: 75,
                        b: 65
                    }, {
                        y: '2012',
                        a: 100,
                        b: 90
                    }],
                    xkey: 'y',
                    ykeys: ['a', 'b'],
                    labels: ['Series A', 'Series B'],
                     barColors: [
                        '#e96562','#414e63',
                        '#A8E9DC' 
                    ],
                    hideHover: 'auto',
                    resize: true
                });
            }
	 


            /* MORRIS DONUT CHART
			----------------------------------------*/
            if ($('#morris-donut-chart').length > 0) {
                Morris.Donut({
                    element: 'morris-donut-chart',
                    data: [{
                        label: "Profits",
                        value: 12
                    }, {
                        label: "Users",
                        value: 30
                    }, {
                        label: "Total Sales",
                        value: 20
                    }],
                       colors: [
                        '#A6A6A6','#414e63',
                        '#e96562' 
                    ],
                    resize: true
                });
            }

            /* MORRIS AREA CHART
			----------------------------------------*/
            if ($('#morris-area-chart').length > 0) {
                Morris.Area({
                    element: 'morris-area-chart',
                    data: [{
                        period: '2010 Q1',
                        iphone: 2666,
                        ipad: null,
                        itouch: 2647
                    }, {
                        period: '2010 Q2',
                        iphone: 2778,
                        ipad: 2294,
                        itouch: 2441
                    }, {
                        period: '2010 Q3',
                        iphone: 4912,
                        ipad: 1969,
                        itouch: 2501
                    }, {
                        period: '2010 Q4',
                        iphone: 3767,
                        ipad: 3597,
                        itouch: 5689
                    }, {
                        period: '2011 Q1',
                        iphone: 6810,
                        ipad: 1914,
                        itouch: 2293
                    }, {
                        period: '2011 Q2',
                        iphone: 5670,
                        ipad: 4293,
                        itouch: 1881
                    }, {
                        period: '2011 Q3',
                        iphone: 4820,
                        ipad: 3795,
                        itouch: 1588
                    }, {
                        period: '2011 Q4',
                        iphone: 15073,
                        ipad: 5967,
                        itouch: 5175
                    }, {
                        period: '2012 Q1',
                        iphone: 10687,
                        ipad: 4460,
                        itouch: 2028
                    }, {
                        period: '2012 Q2',
                        iphone: 8432,
                        ipad: 5713,
                        itouch: 1791
                    }],
                    xkey: 'period',
                    ykeys: ['iphone', 'ipad', 'itouch'],
                    labels: ['iPhone', 'iPad', 'iPod Touch'],
                    pointSize: 2,
                    hideHover: 'auto',
                      pointFillColors:['#ffffff'],
                      pointStrokeColors: ['black'],
                      lineColors:['#A6A6A6','#414e63'],
                    resize: true
                });
            }

            /* MORRIS LINE CHART
			----------------------------------------*/
            if ($('#morris-line-chart').length > 0) {
                Morris.Line({
                    element: 'morris-line-chart',
                    data: [
                          { y: '2014', a: 50, b: 90},
                          { y: '2015', a: 165,  b: 185},
                          { y: '2016', a: 150,  b: 130},
                          { y: '2017', a: 175,  b: 160},
                          { y: '2018', a: 80,  b: 65},
                          { y: '2019', a: 90,  b: 70},
                          { y: '2020', a: 100, b: 125},
                          { y: '2021', a: 155, b: 175},
                          { y: '2022', a: 80, b: 85},
                          { y: '2023', a: 145, b: 155},
                          { y: '2024', a: 160, b: 195}
                    ],
                    
                     
          xkey: 'y',
          ykeys: ['a', 'b'],
          labels: ['Total Income', 'Total Outcome'],
          fillOpacity: 0.6,
          hideHover: 'auto',
          behaveLikeLine: true,
          resize: true,
          pointFillColors:['#ffffff'],
          pointStrokeColors: ['black'],
          lineColors:['gray','#414e63']
          
                });
            }
           
        
            // Only initialize CSS charts if elements exist
            if ($('.bar-chart').length > 0) {
                $('.bar-chart').cssCharts({type:"bar"});
            }
            if ($('.donut-chart').length > 0) {
                $('.donut-chart').cssCharts({type:"donut"}).trigger('show-donut-chart');
            }
            if ($('.line-chart').length > 0) {
                $('.line-chart').cssCharts({type:"line"});
            }
            if ($('.pie-thychart').length > 0) {
                $('.pie-thychart').cssCharts({type:"pie"});
            }
       
	 
        },

        initialization: function () {
            mainApp.initFunction();

        }

    }
    // Initializing ///

    $(document).ready(function () {
		$(".dropdown-button").dropdown();
		$("#sideNav").click(function(){
			if($(this).hasClass('closed')){
				$('.navbar-side').animate({left: '0px'});
				$(this).removeClass('closed');
				$('#page-wrapper').animate({'margin-left' : '260px'});
				
			}
			else{
			    $(this).addClass('closed');
				$('.navbar-side').animate({left: '-260px'});
				$('#page-wrapper').animate({'margin-left' : '0px'}); 
			}
		});
		
        mainApp.initFunction(); 
    });

	$(".dropdown-button").dropdown();
	
}(jQuery));
