var chart;
var pointInterval = 60000; // 1 minutes
var waitForReady = 300;



function __drawChart(data, options, addMiliseconds) {
	$('.addedContainer').remove(); //remove newly added container
	var chartData = data.data;
	if (!chartData) { // no data found
		$('h1.page_title').html('No data found!');
		$('#container.chart_container').html('');
		return; 
	}
	
	
	if (data.minuteStep) pointInterval = data.minuteStep;
	
	var i=data.total;

	// remove existed title
	$('h1.page_title').remove();
	$('#container').before('<h1 class="page_title">'+data['title']+'</h1>');

	while (i > 1) { // create more container for new chart
		$('#container').after('<div id="container'+i+'" class="addedContainer chart_container"></div>');
		i--;
	}
	var w = $(window).width();
	if (w < 1000) {
		$('.chart_container').css({
			'width': '80%',
			'min-width': 'auto',
			'margin-left' : '6%'
		});
	}

	for (i=0; i<data.total; i++) {
		_options = chartData[parseInt(i+1)];

		options.chart.renderTo = 'container';
		if (i>0) options.chart.renderTo += (parseInt(i+1));


		options.series = [{
			'name': _options['info']['DataCol'],
			'data': _options.data,
			'showInLegend': false,
			'pointInterval': pointInterval,
			//pointStart: Date.UTC(2012, 9, 22, 02, 03, 22)
			'pointStart': parseInt(_options['info']['StartDate']*1000 - addMiliseconds),
			'marker': {
				enabled: false
			}
		}];

		options.title.text = _options['info']['Title'];
		options.subtitle.text = _options['info']['SubTitle'];
		options.subtitle.text = '';
		options.yAxis.title.text = _options['info']['YAxisLabel'];
		options.chart.type = _options['info']['Type'];
		
		if (_options['info']['MinYValue']) {
			options.yAxis.min = _options['info']['MinYValue'];
		}else {
			options.yAxis.min = _options['info']['yAxis_min'];
		}
		if (_options['info']['MaxYValue']) {
			options.yAxis.max = _options['info']['MaxYValue'];
		}

		chart = new Highcharts.Chart(options);
	}
}
$(document).ready(function() {
    var myDate = new Date();
    var addMiliseconds = myDate.getTimezoneOffset() * 60000;

    var options = {
        chart: {
            renderTo: 'container',
            type: 'line'
        },
        exporting: {
            url: '../export/index.php'
        },
        title: {
            text: 'Chart by TamNM',
            x: -20
        },
        subtitle: {
            text: 'for demo only',
            x: -20
        },
        yAxis: {
            title: {
                text: 'Temperature (C)'
            }
        },
        xAxis: {
            type: 'datetime',
            title: {
                text: null
            }
        },
        plotOptions: {
            line: {
                lineWidth: 1
            }
        }
    };

    if (action == 'chart') {
		$('.device').click(function(e){
			var _id = $(this).attr('data-id');
			$.get(
				'show_render.php',
				{
					id: _id
				},
				function(data){
					__drawChart(data, options, addMiliseconds);
					
					$('#redrawChart').attr('data-id', _id);
					$('#redrawContainer').show();
				}, 'json'
			);
	
			var h = $(window).height();
			h = h - 150;
			$('#chart_wrapper').css({
				'height': h+'px',
				'overflow': 'auto'
			})
	
			e.preventDefault();
		});
		
		$('#redrawChart').click(function(e){
			var start = $('#rd_startdate').val();
			var end   = $('#rd_enddate').val();
			
			if (start == '' || end == '') {
				alert('Please select start date and end date');
				return false;
			} else {
				
				$.get(
					'show_render.php',
					{
						id: $(this).attr('data-id'),
						s : start,
						e : end
					},
					function(data){
						__drawChart(data, options, addMiliseconds);
					}, 'json'
				);
		
				var h = $(window).height();
				h = h - 150;
				$('#chart_wrapper').css({
					'height': h+'px',
					'overflow': 'auto'
				})
		
				e.preventDefault();
			}
		});
	}
    

    if (action == 'table')
    $('.device').click(function(e){
        $.get(
            'table_render.php',
            {
                id: $(this).attr('data-id')
            },
            function(data){
                // remove existed title
                $('h1.page_title').remove();

                var w = $(window).width();
                var h = $(window).height();
                w = w - 400;
                h = h - 300;
                $('#container').css({
                    'width': w+'px',
                    'height': h+'px',
					'max-height': h+'px',
                    'margin': '0 0 0 60px',
                    'overflow': 'auto'
                }).html(data['html']).before('<h1 class="page_title">'+data['title']+' &nbsp; <a title="Download CSV file" href="../download.php?p='+data['csv_link']+'"><img src="images/download.png" ></a></h1>');
            } , 'json'
        );

        e.preventDefault();
    });
	
	setTimeout(function(){
        if (typeof DeviceID != 'undefined') {
			$('#'+DeviceID).trigger('click');
			
        }
    }, waitForReady);
	
	$('#nav > li > a').click(function(){
        if ($(this).attr('class') != 'active'){
            $('#nav li ul').slideUp();
            $(this).next().slideToggle();
            $('#nav li a').removeClass('active');
            $(this).addClass('active');
        }
    });
	
});
