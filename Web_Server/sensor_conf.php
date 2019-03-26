<script type="text/javascript" src="bootstrap-slider.js"></script>
<?php
$list = get_esps_info();
for($i=0;$i< count($list);$i++){
    $esp_id = $list[$i][0];
    $esp_name = $list[$i][1];
    $esp_gold = $list[$i][2];
?>
<form class="range-field my-4 w-50" method="post" enctype="multipart/form-data" action="#">
<h5 class="card-title" style="padding-top: 15px;">Esp <?php echo $esp_name . ", atual gold: " . $esp_gold;?></h5>

    <input name ="new_gold" type="range" class="custom-range" min="0" max="1024" id="customRange2", value="<?php echo $esp_gold;?>">
    <input id="ex<?php echo $esp_id;?>" data-slider-id='ex1Slider' type="text" data-slider-min="0" data-slider-max="1024" data-slider-step="1" data-slider-value="14"/>
    <span id="CurrentVAL">Current Value: <span id="SliderVal">3</span></span>


    <button type="button" class="btn btn-secondary" name="submit" value="<?php echo $esp_id;?>">Set</button>
<script>

// With JQuery
$("#ex<?php echo $esp_id;?>").slider({
	tooltip: 'always'
});

</script>

</form>

<?php
}
?>
