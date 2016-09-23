<?php die("Access Denied"); ?>#x#a:2:{s:6:"output";s:0:"";s:6:"result";a:2:{s:6:"report";a:0:{}s:2:"js";s:1432:"
  google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'Orders', 'Total Items sold', 'Revenue net'], ['2016-04-28', 0,0,0], ['2016-04-29', 0,0,0], ['2016-04-30', 0,0,0], ['2016-05-01', 0,0,0], ['2016-05-02', 0,0,0], ['2016-05-03', 0,0,0], ['2016-05-04', 0,0,0], ['2016-05-05', 0,0,0], ['2016-05-06', 0,0,0], ['2016-05-07', 0,0,0], ['2016-05-08', 0,0,0], ['2016-05-09', 0,0,0], ['2016-05-10', 0,0,0], ['2016-05-11', 0,0,0], ['2016-05-12', 0,0,0], ['2016-05-13', 0,0,0], ['2016-05-14', 0,0,0], ['2016-05-15', 0,0,0], ['2016-05-16', 0,0,0], ['2016-05-17', 0,0,0], ['2016-05-18', 0,0,0], ['2016-05-19', 0,0,0], ['2016-05-20', 0,0,0], ['2016-05-21', 0,0,0], ['2016-05-22', 0,0,0], ['2016-05-23', 0,0,0], ['2016-05-24', 0,0,0], ['2016-05-25', 0,0,0], ['2016-05-26', 0,0,0]  ]);
        var options = {
          title: 'Report for the period from Thursday, 28 April 2016 to Friday, 27 May 2016',
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