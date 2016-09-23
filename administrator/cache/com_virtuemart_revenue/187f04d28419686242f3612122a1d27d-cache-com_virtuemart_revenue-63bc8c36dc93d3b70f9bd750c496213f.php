<?php die("Access Denied"); ?>#x#a:2:{s:6:"output";s:0:"";s:6:"result";a:2:{s:6:"report";a:0:{}s:2:"js";s:1434:"
  google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'Orders', 'Total Items sold', 'Revenue net'], ['2016-03-24', 0,0,0], ['2016-03-25', 0,0,0], ['2016-03-26', 0,0,0], ['2016-03-27', 0,0,0], ['2016-03-28', 0,0,0], ['2016-03-29', 0,0,0], ['2016-03-30', 0,0,0], ['2016-03-31', 0,0,0], ['2016-04-01', 0,0,0], ['2016-04-02', 0,0,0], ['2016-04-03', 0,0,0], ['2016-04-04', 0,0,0], ['2016-04-05', 0,0,0], ['2016-04-06', 0,0,0], ['2016-04-07', 0,0,0], ['2016-04-08', 0,0,0], ['2016-04-09', 0,0,0], ['2016-04-10', 0,0,0], ['2016-04-11', 0,0,0], ['2016-04-12', 0,0,0], ['2016-04-13', 0,0,0], ['2016-04-14', 0,0,0], ['2016-04-15', 0,0,0], ['2016-04-16', 0,0,0], ['2016-04-17', 0,0,0], ['2016-04-18', 0,0,0], ['2016-04-19', 0,0,0], ['2016-04-20', 0,0,0], ['2016-04-21', 0,0,0]  ]);
        var options = {
          title: 'Report for the period from Thursday, 24 March 2016 to Friday, 22 April 2016',
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