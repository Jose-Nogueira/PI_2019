<div class="bg-light d-flex flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">

  <?php
  if(isset($_POST['id'])){
      $mode = "";
      if(isset($_POST['on'])) $mode = "on";
      else if(isset($_POST['off'])) $mode = "off";
      else if(isset($_POST['auto'])) $mode = "auto";
      set_setor_mode(@$_POST['id'], $mode);
  }
  $list = get_setors_mode();
  for($i=0;$i< count($list);$i++){
      $setor = $list[$i];
?>
    <form method="post" enctype="multipart/form-data" action="#">
    <input name="id" hidden="" value="<?php echo @$setor[0]; ?>" />
          
          <h5 class="card-title" style="padding-left: 5px; padding-right: 250px; padding-top: 15px;">Setor: <?php echo @$setor[0]; ?></h5>
          <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <label class="btn btn-secondary <?php echo(($setor[1] == "auto") ? "active":" "); ?>">
                  <input type="radio" name="auto" id="option1" autocomplete="off"> Auto
              </label>
              <label class="btn btn-secondary <?php echo(($setor[1] == "on") ? "active":" "); ?>">
                  <input type="radio" name="on" id="option2" autocomplete="off"> ON
              </label>
              <label class="btn btn-secondary <?php echo(($setor[1] == "off") ? "active":" "); ?>">
                  <input type="radio" name="off" id="option3" autocomplete="off"> OFF
              </label>
          </div>
    </form>
    <?php
  }
  ?>
</div>
<script>
    $('input[type=radio]').on('change', function() {
    $(this).closest("form").submit();
});
</script>