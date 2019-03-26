<?php
$list_s = get_esps_info();
for($i=0;$i< count($list_s);$i++){
    $esp_id = $list_s[$i][0];
    $list = esp_data($esp_id,1000);
?>

<canvas class="my-4 w-100 chartjs-render-monitor" id="myChart<?php echo($esp_id);?>" width="1490" height="629"
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
          label: 'ESP - PID output signal',
          data: [
          <?php
              for($j=0;$j< count($list);$j++){
                if($j > 0) echo ", ";
                  echo ("{ x: new Date('" . str_replace("-","/",$list[$j][0]) . "'), y: ". $list[$j][3] . "}");
              }
          ?>
          ],

          borderColor: '#f00',
          fill: false
        },{
          label: 'ESP - gold signal',
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