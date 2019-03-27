<?php
$list_s = get_setors_mode();
for($i=0;$i< count($list_s);$i++){
    $setor_id = $list_s[$i][0];
    $list = w_setor_data($setor_id,1000);
?>

<canvas class="my-4 w-100 chartjs-render-monitor" id="myChart<?php echo($setor_id);?>" width="1490" height="<?php echo((@$_GET['vr'] == "app") ? "1629" : "629");?>"
  style="display: block; width: 1490px; height: 629px;"></canvas>

<script>
  (function () {
    'use strict'

    feather.replace()

    // Graphs
    var ctx = document.getElementById('myChart<?php echo($setor_id);?>')
    // eslint-disable-next-line no-unused-vars
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        datasets: [{
          label: 'Consumos do setor <?php echo(get_setor_id($setor_id)[2]);?>',
          data: [
          <?php
              for($j=0;$j< count($list);$j++){
                if($j > 0) echo ", ";
                  echo ("{ x: new Date('" . str_replace("-","/",$list[$j][1]) . "'), y: ". $list[$j][0] . "}");
              }
          ?>
          ],

          borderColor: '#3e95cd',
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
          text: 'Consumos'
        },


      }
    })
  }())


</script>
<?php
}
?>