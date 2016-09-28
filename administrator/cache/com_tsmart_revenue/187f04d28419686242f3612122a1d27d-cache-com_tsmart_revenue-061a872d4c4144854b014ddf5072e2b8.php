<?php die("Access Denied"); ?>#x#a:2:{s:6:"output";s:0:"";s:6:"result";a:2:{s:6:"report";N;s:2:"js";s:1462:"
  google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['com_tsmart_DAY', 'com_tsmart_REPORT_BASIC_ORDERS', 'com_tsmart_REPORT_BASIC_TOTAL_ITEMS', 'com_tsmart_REPORT_BASIC_REVENUE_NETTO'], ['2016-08-29', 0,0,0], ['2016-08-30', 0,0,0], ['2016-08-31', 0,0,0], ['2016-09-01', 0,0,0], ['2016-09-02', 0,0,0], ['2016-09-03', 0,0,0], ['2016-09-04', 0,0,0], ['2016-09-05', 0,0,0], ['2016-09-06', 0,0,0], ['2016-09-07', 0,0,0], ['2016-09-08', 0,0,0], ['2016-09-09', 0,0,0], ['2016-09-10', 0,0,0], ['2016-09-11', 0,0,0], ['2016-09-12', 0,0,0], ['2016-09-13', 0,0,0], ['2016-09-14', 0,0,0], ['2016-09-15', 0,0,0], ['2016-09-16', 0,0,0], ['2016-09-17', 0,0,0], ['2016-09-18', 0,0,0], ['2016-09-19', 0,0,0], ['2016-09-20', 0,0,0], ['2016-09-21', 0,0,0], ['2016-09-22', 0,0,0], ['2016-09-23', 0,0,0], ['2016-09-24', 0,0,0], ['2016-09-25', 0,0,0], ['2016-09-26', 0,0,0]  ]);
        var options = {
          title: 'com_tsmart_REPORT_TITLE',
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