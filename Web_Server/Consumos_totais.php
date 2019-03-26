<?php
    $setor_id = 0;
    $list = total_w_setor_data(1000);
    $last = count($list)-1;
?>
<div class="card" style="width: 22rem;">
  <div class="card-body">
    <h5 class="card-title">Consumo total</h5>
    <p class="card-title"><?php echo($list[$last][0]/1000);?> KWh</p>
  </div>
</div>

<canvas class="my-4 w-100 chartjs-render-monitor" id="myChart<?php echo($setor_id);?>" width="1490" height="629"
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
          label: 'Consumos Totais',
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