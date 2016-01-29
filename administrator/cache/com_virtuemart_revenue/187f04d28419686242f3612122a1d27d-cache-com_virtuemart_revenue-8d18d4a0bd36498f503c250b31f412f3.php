<?php die("Access Denied"); ?>#x#a:2:{s:6:"output";s:0:"";s:6:"result";a:2:{s:6:"report";a:0:{}s:2:"js";s:1420:"
  google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'Orders', 'Total Items sold', 'Revenue net'], ['2015-12-11', 0,0,0], ['2015-12-12', 0,0,0], ['2015-12-13', 0,0,0], ['2015-12-14', 0,0,0], ['2015-12-15', 0,0,0], ['2015-12-16', 0,0,0], ['2015-12-17', 0,0,0], ['2015-12-18', 0,0,0], ['2015-12-19', 0,0,0], ['2015-12-20', 0,0,0], ['2015-12-21', 0,0,0], ['2015-12-22', 0,0,0], ['2015-12-23', 0,0,0], ['2015-12-24', 0,0,0], ['2015-12-25', 0,0,0], ['2015-12-26', 0,0,0], ['2015-12-27', 0,0,0], ['2015-12-28', 0,0,0], ['2015-12-29', 0,0,0], ['2015-12-30', 0,0,0], ['2015-12-31', 0,0,0], ['2016-01-01', 0,0,0], ['2016-01-02', 0,0,0], ['2016-01-03', 0,0,0], ['2016-01-04', 0,0,0], ['2016-01-05', 0,0,0], ['2016-01-06', 0,0,0], ['2016-01-07', 0,0,0], ['2016-01-08', 0,0,0]  ]);
        var options = {
          title: 'Report for the period from Friday, 11 December 2015 to Saturday, 09 January 2016',
            series: {0: {targetAxisIndex:0},
                   1:{targetAxisIndex:0},
                   2:{targetAxisIndex:1},
                  },
                  colors: ["#00A1DF", "#A4CA37","#E66A0A"],
        };

        var chart = new google.visualization.LineChart(document.getElementById('vm_stats_chart'));

        chart.draw(data, options);
      }
";}}