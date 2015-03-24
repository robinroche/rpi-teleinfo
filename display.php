<!DOCTYPE HTML>
<html>
<head>
 
<?php
 
// Connect to the database server
$dbhost = 'xxx';
$dbuser = 'xxx';
$dbpass = 'xxx';
$con = mysql_connect($dbhost, $dbuser, $dbpass);
if (!$con) { die('Could not connect: ' . mysql_error()); }
 
// Get the last entries
mysql_select_db("xxx", $con);
$sql = mysql_query('SELECT * FROM teleinfo ORDER BY id DESC LIMIT 1440;') or die($myQuery."<br/><br/>".mysql_error());
 
// For each entry
while ($row = mysql_fetch_array($sql)) {
	// Extract data	
	extract($row);
 
	// Convert dates to a format Highcharts understands
	$datetime1 = date('Y, n-1, j, G, i, s', strtotime($time));
 	$datetime2 = 'Date.UTC('.$datetime1.')';
 
	// Create data arrays
	$data[] = "[$datetime2, $papp]";
	$data2[] = "[$datetime2, $price]";
}
 
// Close connection
mysql_close($con);
?>
 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
 
<script type="text/javascript">$(function () {
	var chart;
	$(document).ready(function() {
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'container',
				type: 'line',
				marginRight: 130,
				marginBottom: 50,
				zoomType: 'x'
			},
			title: {
				text: 'Residential energy consumption',
				x: -20
			},
			subtitle: {
				text: 'Data obtained using ERDF\'s Téléinfo and a Raspberry Pi',
				x: -20
			},
			xAxis: {
				type: 'datetime',
				dateTimeLabelFormats: {
 					day: '%e %b'
    				}
			},
			yAxis: [{
				title: {
					text: 'Power (W)'
				},
				min: 0
			},{
				opposite: true,
				min: 0,
				max: 1,
				minTickInterval: 1,
				labels: {
					style: {
						color: '#FF0000'
					}
				},
				title: {	
				text: 'Off or on-peak price (0 or 1)',
					style: {
						color: '#FF0000'	
					}
				},
			}],
			tooltip: {
				enabled: true,
				crosshairs: [true,true],
				followPointer: true
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
			plotOptions: {
				series: {
					marker: {
						enabled: false
					},
					lineWidth: 1,
                    			shadow: false,
                    			states: {
                        			hover: {
                            			lineWidth: 1,
                            			enabled: false
                        			}
                    			},
					cropThreshold: 1500
				}
			},
			series: [{
				name: 'Load',
				yAxis: 0,
				data: [<?php echo join($data, ',') ?>]
			},{
				name: 'Price',
				yAxis: 1,
				dashStyle: 'Dash',
				color: '#FF0000',
				data: [<?php echo join($data2, ',') ?>]
			}]
		});
	});
 
	Highcharts.setOptions({ 
		// This is for all plots, change Date axis to local timezone
		global : {
			timezoneOffset: 0 * 60,
		}
	});
});
 
</script></head><body><div id="container" style="min-width: 800px; height: 600px; margin: 50 auto"></div>
 
<font size="1">
<?php	echo "Last modified: " . date ("F d Y H:i:s.", getlastmod());?>
</font>
 
</body>
</html>
