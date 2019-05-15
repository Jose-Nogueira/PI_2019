<form method="get" enctype="multipart/form-data" action="?pg=sensorval#">
<input type="hidden" name="pg" value="sensorval">
<input type="date" name="start">
<input type="date" name="stop">
<input type="submit" name="submit" value="Go">
</form>
<?php
$list_s = get_esps_info();
for($i=0;$i< count($list_s);$i++){
    $esp_id = $list_s[$i][0];
    $now = (isset($_GET['stop']) && isset($_GET['start'])) ? $_GET['stop'] : date("Y-m-d H:i:s");
    $lastday = (isset($_GET['stop']) && isset($_GET['start'])) ? $_GET['start'] : date('Y-m-d H:i:s',strtotime("-2 days"));
    $list = esp_data($esp_id,$lastday, $now);
    $list2= setor_full_status($list_s[$i][3],$lastday, $now);
?>
<canvas class="my-4 w-100 chartjs-render-monitor" id="myChart<?php echo($esp_id);?>" width="1490" height="<?php echo((@$_GET['vr'] == "app") ? "1629" : "629");?>"
  style="display: block; width: 1490px; height: 629px;"></canvas>

<script>
  (function () {
    'use strict'

    feather.replace()

    // Graphs
    var ctx = document.getElementById('myChart<?php echo($esp_id);?>')
    // eslint-disable-next-line no-unused-vars
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        datasets: [{
          label: 'ESP - LDR input signal',
          data: [
          <?php
              for($j=0;$j< count($list);$j++){
                if($j > 0) echo ", ";
                  echo ("{ x: new Date('" . str_replace("-","/",$list[$j][0]) . "'), y: ". $list[$j][2] . "}");
              }
          ?>
          ],

          borderColor: '#3e95cd',
          fill: false
        },{
          label: 'ESP - PID output',
          hidden: true,
          data: [
          <?php
              for($j=0;$j< count($list);$j++){
                if($j > 0) echo ", ";
                  echo ("{ x: new Date('" . str_replace("-","/",$list[$j][0]) . "'), y: ". $list[$j][3] . "}");
              }
          ?>
          ],

          borderColor: '#000',
          fill: false
        },{
          label: 'On / Off',
          lineTension:0,
          data: [
          <?php
              for($j=0;$j< count($list2);$j++){
                if($j > 0) echo ", ";
                  echo ("{ x: new Date('" . str_replace("-","/",$list2[$j][0]) . "'), y: ". ($list2[$j][1] == "on" ? 0 : 500) . "}");
              }
          ?>
          ],

          borderColor: '#f00',
          fill: false
        },{
          label: 'Mode - auto',
          lineTension:0,
          data: [
          <?php
              for($j=0;$j< count($list2);$j++){
                if($j > 0) echo ", ";
                  echo ("{ x: new Date('" . str_replace("-","/",$list2[$j][0]) . "'), y: ". ($list2[$j][2] == "auto" ? 600 : 0) . "}");
              }
          ?>
          ],

          borderColor: '#0f0',
          fill: false
        },{
          label: 'ESP - gold signal',
          hidden: true,
          data: [
          <?php
              for($j=0;$j< count($list);$j++){
                if($j > 0) echo ", ";
                  echo ("{ x: new Date('" . str_replace("-","/",$list[$j][0]) . "'), y: ". $list[$j][1] . "}");
              }
          ?>
          ],

          borderColor: '#ffd700',
          fill: false
        }]
      },

      options: {
        scales: {
          xAxes: [{
            type: 'time',
            unit: 'day',
            distribution: 'linear',
            ticks: { source: 'auto' },
            time: { displayFormats: { day: 'MMM DD' } }
          }],
          yAxes: [{
            display: true,
            ticks: {
                                beginAtZero: true,
                                max: 1024
                            }
        }]
        },
        title: {
          display: true,
          text: 'ESP  <?php echo(get_esp_id($esp_id)[1]);?>'
        },


      }
    })
  }())


</script>
<?php
}
?>