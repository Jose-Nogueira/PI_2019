<script src="jquery-3.3.1.slim.min.js"></script>
<link href="dashboard.css" rel="stylesheet">
<script src="bootstrap-4.3.1-dist/js/bootstrap.bundle.min.js"></script>
<script src="feather.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<script src="Chart.min.js"></script>


<div class="card" style="width: 22rem;">
  <div class="card-body">
    <h5 class="card-title">Consumo total</h5>
    <p class="card-title">xxxx KWh</p>
  </div>
</div>

<?php
$list_s = get_setors_mode();
for($i=0;$i< count($list_s);$i++){
    $setor_id = $list_s[$i][0];
?>

<canvas class="my-4 w-100 chartjs-render-monitor" id="myChart" width="1490" height="629"
  style="display: block; width: 1490px; height: 629px;"></canvas>

<script>
  (function () {
    'use strict'

    feather.replace()

    // Graphs
    var ctx = document.getElementById('myChart')
    // eslint-disable-next-line no-unused-vars
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        datasets: [{
          label: 'Dataset from setor <?php echo($setor_id);?>',
          data: [
          <?php
              $list = w_setor_data($id=0,$data_size=1000);
              for($j=0;$j< count($list);$j++){
                if($j > 0) echo ", ";
                  echo ("{ x: " . $list[$j][1] . ", y: ". $list[$j][0] . "}");
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
            ticks: { source: 'data' },
            time: { displayFormats: { day: 'MMM DD' } }
          }]
        },
        title: {
          display: true,
          text: 'Student Assessment Cluster Scores'
        },


      }
    })
  }())


</script>
<?php
}
?>