		$.ajax({
				method : "GET",
				url: "../BackEnd.php",
				success: function(data) {
				}
			});
		// start loading the graph after things have some time to set up
		setTimeout(start, 4000);
    	// function to start the loading of the graph
    	function start() {
	    	// load the core chart package that includes the line graph
			google.charts.load('44', { 'packages' : ['corechart'] } );
			// set drawChart() to run once the package has loaded
			google.charts.setOnLoadCallback(drawChart);
    	}

    	// function to start the drawing of the chart
		function drawChart() {
			// data table to hold the data that will be graphed
			var dataTable = new google.visualization.DataTable();
			// first column is just the dates
			dataTable.addColumn('date', 'Date');
			// request the stock info from the server for the columns
			// using ajax get JSON
			$.getJSON("checkColumns.php",
				function(data) {
					for(var i = 0; i < data.length; ++i)
					{ // data[i] is the stock ticker that needs to be added
						// we need to add a new column for each ticker
						dataTable.addColumn('number', data[i]);
					}
					addRows(dataTable);
				}
			);
		}
		
		// this function adds the rows of the data
		function addRows(dataTable) {
			// ask the server for the rows in the form of the historical data
			$.getJSON("checkRows.php",
					function(data) {
						console.log(data.length);
						console.log(data);
						for(var i = 0; i < data.length; ++i)
						{
							var row = new Array();
							// get the date that these prices go with
							var newDate = new Date(data[i][0]);
							row.push(newDate);
							for(var j = 1; j < data[i].length; ++j)
							{
								// if the price isn't null
								if(data[i][j] != null)
								{ // add it to the row
									row.push(Number(data[i][j]));
								}
								else
								{ // otherwise add null
									row.push(null);
								}
							}
							dataTable.addRow(row);
						}
						finishGraph(dataTable);
					}
				);
			}
			
			// this function sets the options and actually draws the chart
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
					title: 'Stock Graph',
					legend: { position : 'right' },
					explorer: {},
					trendlines: null
				};
	
				// the chart object itself
				// attached to the div that holds the chart
				var chart = new google.visualization.LineChart(document.getElementById('graph_here'));
				
				function selectionHandler() {
					var selectedItem = chart.getSelection()[0];
					if(selectedItem) {
						var ticker = dataTable.getColumnLabel(selectedItem.column);
						var date = new Date(dataTable.getValue(selectedItem.row, 0));
						var price = Math.round(100 * dataTable.getValue(selectedItem.row, selectedItem.column)) / 100;
						document.getElementById("details").innerHTML = ticker + "<br>" + date.toDateString() + "<br>$" + price;
					}
				}
				
				google.visualization.events.addListener(chart, 'select', selectionHandler);
	
				// draw the chart with the options
				chart.draw(dataTable, options);
			}

		// function to add a stock to the graph
		function addStock() {
			var tickerText = document.getElementById('ticker_text').value;
			$.ajax({
				method : "POST",
				url: "addStock.php",
				data: { ticker : tickerText },
				success: function(data) {
					alert("Added " + data + " to graph.");
					setTimeout(drawChart, 1000);
				}
			});
		}
