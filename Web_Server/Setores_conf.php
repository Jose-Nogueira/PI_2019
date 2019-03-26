<div class="my-3 p-1 bg-white"></div>

<div class="btn-group">
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownSectorButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Sector
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownSectorButton">
            <a class="dropdown-item" href="#">1</a>
            <a class="dropdown-item" href="#">2</a>
        </div>
    </div>
    <div class="dropdown" style="padding-left: 5px">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownSensorButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Sensor
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownSensorButton">
            <a class="dropdown-item" href="#">3</a>
            <a class="dropdown-item" href="#">4</a>
        </div>
    </div>
</div>

<h5 class="card-title" style="padding-top: 15px;">Target value</h5>

<form class="range-field my-4 w-50">
    <input type="range" class="custom-range" min="0" max="1024" id="customRange2">
    <button type="button" class="btn btn-secondary">Set</button>
</form>
