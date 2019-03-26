<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <title>Scient</title>

  <!-- <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/dashboard/"> -->

  <!-- Bootstrap core CSS -->
  <link href="bootstrap-4.3.1-dist/css/bootstrap.min.css" rel="stylesheet">


  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }
  </style>
  <!-- Custom styles for this template -->
  <!-- <link href="bootstrap-4.3.1-dist/dashboard.css" rel="stylesheet"> -->
</head>

<body>
  <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="?pg=home"> Scient </a>
    <ul class="navbar-nav px-3">
      <li class="nav-item text-nowrap">
        <a class="nav-link" href="?logout=true">Sign out</a>
      </li>
    </ul>
  </nav>

  <div class="container-fluid" id="containerFluid">
    <div class="row">
      <nav class="col-md-2 d-none d-md-block bg-dark sidebar">
        <div class="sidebar-sticky">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link text-light bg-dark" href="#" type="button" data-toggle="collapse"
                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <span data-feather="zap"></span>
                Consumos <span class="sr-only">(current)</span>
              </a>
              <div id="collapseOne" class="<?php echo ((@$_GET['pg'] == "consumossetor")||(@$_GET['pg'] == "consumostotal") ? "collapse show":"collapse hide");?>" data-parent="#containerFluid">
                <a class="dropdown-item <?php echo ((@$_GET['pg'] == "consumossetor") ? "text-primary":"text-light");?>" href="?pg=consumossetor">No setor</a>
                <a class="dropdown-item <?php echo ((@$_GET['pg'] == "consumostotal") ? "text-primary":"text-light");?>" href="?pg=consumostotal">Totais</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link text-light bg-dark" href="#" type="button" data-toggle="collapse"
                data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
                <span data-feather="home"></span>
                Setores
              </a>
              <div id="collapseTwo" class="<?php echo ((@$_GET['pg'] == "setorstatus")||(@$_GET['pg'] == "setorconf") ? "collapse show":"collapse hide");?>" data-parent="#containerFluid">
                <a class="dropdown-item <?php echo ((@$_GET['pg'] == "setorstatus") ? "text-primary":"text-light");?>" href="?pg=setorstatus">Estado</a>
                <a class="dropdown-item <?php echo ((@$_GET['pg'] == "setorconf") ? "text-primary":"text-light");?>" href="?pg=setorconf">Configuração</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link text-light bg-dark" href="#" type="button" data-toggle="collapse"
                data-target="#collapseThree" aria-expanded="true" aria-controls="collapseOne">
                <span data-feather="wifi"></span>
                Sensores
              </a>
              <div id="collapseThree" class="<?php echo ((@$_GET['pg'] == "sensorval")||(@$_GET['pg'] == "sensorstatus") ? "collapse show":"collapse hide");?>" data-parent="#containerFluid">
                <a class="dropdown-item <?php echo ((@$_GET['pg'] == "sensorval") ? "text-primary":"text-light");?>" href="?pg=sensorval">Valores lidos</a>
                <a class="dropdown-item <?php echo ((@$_GET['pg'] == "sensorstatus") ? "text-primary":"text-light");?>" href="?pg=sensorstatus">Estado</a>
              </div>
            </li>

          </ul>

        </div>
      </nav>
      <script src="jquery-3.3.1.slim.min.js"></script>
      <link href="dashboard.css" rel="stylesheet">
      <script src="bootstrap-4.3.1-dist/js/bootstrap.bundle.min.js"></script>
      <script src="feather.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
      <script src="Chart.min.js"></script>
      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
        <?php
        if(@$_GET['pg'] == "consumossetor") require("Consumos_porsetor.php");
        else if(@$_GET['pg'] == "consumostotal") require("Consumos_totais.php");
        //else if(@$_GET['pg'] == "setorstatus") require("Setores_estado.html");
        else if(@$_GET['pg'] == "setorconf") require("Setores_conf.php");
        //else if(@$_GET['pg'] == "sensorval") require("Sensor_values.html");
        //else if(@$_GET['pg'] == "sensorstatus") require("sensor_estado.html");
        ?>
      </main>
    </div>
  </div>
</body>

<script>
  (function () {
    'use strict'

    feather.replace()
  }())
</script>

</html>