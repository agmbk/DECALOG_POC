<?php
/**
 * Symphony import
 */
require_once(__DIR__ . '/vendor/autoload.php');

use Symfony\Component\Dotenv\Dotenv;

/**
 * DotEnv load
 */
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');

if (!isset($_ENV['ONE_CALL_API'])) exit('Missing API key');

/**
 * Set locale to Paris
 * Not working ??
 */
setlocale(LC_TIME, 'fr_FR');
date_default_timezone_set('Europe/Paris');

/**
 * Manual weekdays conversion since locales aren't working
 */
const weekdays = ["Mon" => "Lun", "Tue" => "Mar", "Wed" => "Mer", "Thu" => "Jeu", "Fri" => "Ven", "Sat" => "Sam", "Sun" => "Dim"];

/**
 * JSON body parser
 */
try{
    $body = file_get_contents('php://input');
    $json = json_decode($body);
} catch(Exception $e){
    echo "Invalid body. Please use {location: {lat: float, lng: float, address?: string}}";
    return;
}


/**
 * Check the validity of the payload
 */
if (!property_exists($json, 'location')) {
    echo "Missing body. Please use {location: {lat: float, lng: float, address?: string}}";
    return;
}

$location = $json->location;

if (!property_exists($location, 'lat')
    || !property_exists($location, 'lng')
    || !is_numeric($location->lat)
    || !is_numeric($location->lng)) {
    echo "Invalid body. Please use {location: {lat: float, lng: float, address?: string}}";
    return;
}

/**
 * DotEnv init
 */
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');

/**
 * Fetch the open weather map API
 * Documentation at: https://openweathermap.org/api/one-call-3
 */
$url = "https://api.openweathermap.org/data/3.0/onecall";

$queryString = http_build_query([
    'appid' => $_ENV['ONE_CALL_API'],
    'units' => 'metric',
    'exclude' => 'minutely,hourly',
    'lang' => 'fr',
    'lat' => $location->lat,
    'lon' => $location->lng,
]);

$fp = fopen("$url?$queryString", 'r');

$data = stream_get_contents($fp);

$data = json_decode($data);

/**
 * Display one day and its weather
 * strftime deprecated ...
 * @param $weatherInfo
 * @return void
 */
function oneDay($weatherInfo): void
{
    echo "<div class=\"card bg-light mb-3\">
    <div class=\"card-header\">
        <img src=\"http://openweathermap.org/img/wn/" . $weatherInfo->weather[0]->icon . "@4x.png\"
            alt=\"weather icon\">
    </div>
    <div class=\"card-body\">
        <span>" . (strftime("%D", $weatherInfo->dt) === strftime("%D") ? "Aujourd'hui" : weekdays[strftime("%a", $weatherInfo->dt)] . '. ' . strftime("%d", $weatherInfo->dt)) . "
            <br/>
            " . strtoupper($weatherInfo->weather[0]->description[0]) . substr($weatherInfo->weather[0]->description, 1) . "
            <br/>
            Jour: <strong>{$weatherInfo->temp->day} °C</strong>
            <br/>
            Nuit: <strong>{$weatherInfo->temp->night} °C</strong>
            <br/>
            Vent : " . (int) $weatherInfo->wind_speed * 3.6 . " km/h
            <br/>
            Humidité : {$weatherInfo->humidity} %
            <br/>
        </span>
    </div>
</div>";
}

?>

<div onclick="this.nextElementSibling.style.display='flex'" class="card bg-light mb-3">
    <div class="card-header">
        <img src="http://openweathermap.org/img/wn/<?php echo $data->current->weather[0]->icon; ?>@4x.png"
             alt="weather icon">
    </div>
    <div class="card-body">
        <span>
            <strong><?php echo $data->current->temp; ?> °C</strong><br/>
            <?php echo strtoupper($data->current->weather[0]->description[0]) . substr($data->current->weather[0]->description, 1); ?>
            <br/>
            Vent : <?php echo (int) $data->current->wind_speed * 3.6; ?> km/h<br/>
            Humidité : <?php echo $data->current->humidity; ?> %<br/>
        </span>
    </div>
</div>

<article style="display: none" id="weather-info" onclick="this.style.display='none'">
    <section onclick="event.stopPropagation()">
        <button type="button" class="btn btn-light" onclick="this.parentElement.parentElement.style.display='none'">X
        </button>

        <div class="container">

            <div id="daily" class="card bg-light mb-3">

                <div class="card-header">
                    <h1><?php echo $json->location->address; ?></h1>
                </div>

                <div class="card-body">
                    <?php foreach ($data->daily as $current) oneDay($current); ?>
                </div>

            </div>
        </div>

    </section>
</article>