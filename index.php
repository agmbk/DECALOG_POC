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

if (!isset($_ENV['GEOCODE_API_KEY'])) exit('Missing API key');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Weather</title>
    <meta charset="utf-8">
    <meta name="weather" content="Weather"/>
    <meta name="theme-color" content="#fff"/>
    <meta name="apple-mobile-web-app-status-bar" content="#fff"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/normalize.css">
    <link rel="stylesheet" href="styles/map.css">
    <link rel="stylesheet" href="styles/weather-info.css">
    <link rel="stylesheet" href="styles/bootstrap.min.css">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default" defer></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $_ENV['GEOCODE_API_KEY'] ?>&callback=initMap&v=weekly"
            defer></script>
    <script src="js/map.js"></script>
    <script src="js/colorScheme.js" defer></script>
</head>

<body>

<article>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Weather app</a>
            <button id="color-scheme" type="button"
                    onclick="handleClick()" class="btn btn-dark">&nbsp;
            </button>
        </div>
    </nav>

    <section id="map"></section id="map">
</article>
</body>
</html>
