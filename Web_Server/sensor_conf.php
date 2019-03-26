<script type="text/javascript" src="bootstrap-slider.js"></script>
<?php
if(isset($_POST['submit'])){
    set_esp_gold($_POST['id'], $_POST['new_gold']);
}

$list = get_esps_info();
for($i=0;$i< count($list);$i++){
    $esp_id = $list[$i][0];
    $esp_name = $list[$i][1];
    $esp_gold = $list[$i][2];
?>
<form class="range-field my-4 col-md-11" method="post" enctype="multipart/form-data" action="#">
<h5 class="card-title" style="padding-top: 15px;">Esp <?php echo $esp_name . ", atual gold: " . $esp_gold;?></h5>

    <input name ="new_gold" type="range" class="custom-range" min="0" max="1024" id="customRange<?php echo $esp_id;?>" value="<?php echo $esp_gold;?>">
    <!-- <span id="CurrentVAL">Current Value: <span id="SliderVal<?php echo $esp_id;?>">3</span></span> -->

<input name="id" hidden value="<?php echo $esp_id;?>">
    <input id="SliderVal<?php echo $esp_id;?>" type="submit" class="btn btn-secondary" name="submit">
<script>

// With JQuery
$("#SliderVal<?php echo $esp_id;?>").val("Set gold = " + $("#customRange<?php echo $esp_id;?>").val());
$("#customRange<?php echo $esp_id;?>").on("input change", function(){$("#SliderVal<?php echo $esp_id;?>").val("Set gold = " + $("#customRange<?php echo $esp_id;?>").val() + "");});

</script>

</form>
<hr>

<?php
}
?>
