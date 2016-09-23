<?php die("Access Denied"); ?>#x#a:2:{s:6:"output";s:0:"";s:6:"result";a:2:{s:6:"report";a:0:{}s:2:"js";s:1439:"
  google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'Orders', 'Total Items sold', 'Revenue net'], ['2016-02-16', 0,0,0], ['2016-02-17', 0,0,0], ['2016-02-18', 0,0,0], ['2016-02-19', 0,0,0], ['2016-02-20', 0,0,0], ['2016-02-21', 0,0,0], ['2016-02-22', 0,0,0], ['2016-02-23', 0,0,0], ['2016-02-24', 0,0,0], ['2016-02-25', 0,0,0], ['2016-02-26', 0,0,0], ['2016-02-27', 0,0,0], ['2016-02-28', 0,0,0], ['2016-02-29', 0,0,0], ['2016-03-01', 0,0,0], ['2016-03-02', 0,0,0], ['2016-03-03', 0,0,0], ['2016-03-04', 0,0,0], ['2016-03-05', 0,0,0], ['2016-03-06', 0,0,0], ['2016-03-07', 0,0,0], ['2016-03-08', 0,0,0], ['2016-03-09', 0,0,0], ['2016-03-10', 0,0,0], ['2016-03-11', 0,0,0], ['2016-03-12', 0,0,0], ['2016-03-13', 0,0,0], ['2016-03-14', 0,0,0], ['2016-03-15', 0,0,0]  ]);
        var options = {
          title: 'Report for the period from Tuesday, 16 February 2016 to Wednesday, 16 March 2016',
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