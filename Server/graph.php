<?php
session_start();
include_once "Stock.class.php";
include_once "Graph.class.php";

$_SESSION["graph"] = new Graph();
$_SESSION["apple"] = new Stock("AAPL", date_create('2016-02-01'));
$_SESSION["nflx"] = new Stock("NFLX", date_create('2016-02-01'));
$_SESSION["microsoft"] = new Stock("MSFT", date_create('2016-01-20'));
$_SESSION["facebook"] = new Stock("FB", date_create('2016-01-20'));

$_SESSION["graph"]->pullHistoricalData($_SESSION["apple"]);
$_SESSION["graph"]->pullHistoricalData($_SESSION["nflx"]);
$_SESSION["graph"]->pullHistoricalData($_SESSION["microsoft"]);
$_SESSION["graph"]->pullHistoricalData($_SESSION["facebook"]);
?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Graph</title>
    <!-- Load ajax -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <!-- Load google chart's source -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
		setTimeout(start, 4000);
    
    	function start() {
	    	// load the core chart package that includes the line graph
			google.charts.load('44', { 'packages' : ['corechart'] } );
			// set drawChart() to run once the package has loaded
			google.charts.setOnLoadCallback(drawChart);
    	}

		function drawChart() {
			// data table to hold the data that will be graphed
			var dataTable = new google.visualization.DataTable();
			dataTable.addColumn('date', 'Date');
			$.getJSON("checkColumns.php",
				function(data) {
					console.log(data);
					console.log(data.length);
					for(var i = 0; i < data.length; ++i)
					{
						dataTable.addColumn('number', data[i]);
					}
					addRows(dataTable);
				}
			);
		}
		
		function addRows(dataTable) {
			$.getJSON("checkRows.php",
					function(data) {
						console.log(data);
						console.log(data.length);
						for(var i = 0; i < data.length; ++i)
						{
							var row = new Array();
							var newDate = new Date(data[i][0]);
							row.push(newDate);
							for(var j = 1; j < data[i].length; ++j)
							{
								if(data[i][j] != null)
								{
									row.push(Number(data[i][j]));
								}
								else
								{
									row.push(null);
								}
							}
							dataTable.addRow(row);
						}
						finishGraph(dataTable);
					}
				);
			}
		
			function finishGraph(dataTable) {
				// options to specify the design of the graph
				var options = {
					hAxis: {
						title: 'Date',
						gridlines: {
							color: '#222222'
						}
					},
					vAxis: {
						title: 'Price',
						format: 'currency',
						gridlines: {
							color: '#222222'
						}
					},
					title: 'Stock Chart',
					legend: { position : 'right' },
					explorer: {},
					trendlines: null
				};
	
				// the chart object itself
				// attached to the div that holds the chart
				var chart = new google.visualization.LineChart(document.getElementById('stock_chart'));
	
				// draw the chart with the options
				chart.draw(dataTable, options);
			}

		function addStock() {
			var tickerText = document.getElementById('ticker_text').value;
			
			$.ajax({
				method : "POST",
				url: "addStock.php",
				data: { ticker : tickerText },
				success: function(data) {
					alert("Adding " + data + " to graph.");
					setTimeout(drawChart, 1000);
				}
			});
		}
    </script>
</head>
<body>
    <div id="stock_chart" style="width: 900px; height: 500px"></div>
    <div>
    	<input type="text" id="ticker_text">
    	<button type="submit" onclick="addStock()">Add Stock to Graph</button>
    </div>
</body>
</html>