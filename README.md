# DECALOG_POC

DECALOG internship proof of competences

## Instructions

Récupérer les données de cette api
https://openweathermap.org/api/one-call-3
en PHP et faire un petit affichage sympatique avec
https://bootswatch.com/flatly/

## Feedback

Nice weekend project (~10hrs)
PHP dates are probably messed up  
~~Responsive until 370px width, should be 320px~~ Fixed   
I should add the input autocomplete but ...  
Plan and satellite view added  
Google street is blocked by cors ?? Idk how to fix that

## How to run it

Open a shell in the project root.  
Install composer if needed: https://getcomposer.org/download  
Install the requirements with: `composer install`  
Launch the web server: `php -S localhost:8000`.  
Open the link http://localhost:8000/index.php in your browser.  
Add the following environments variables:
`GEOCODE_API_KEY`  
From: https://developers.google.com/maps/documentation/javascript/examples/geocoding-reverse  
`ONE_CALL_API`  
From: https://openweathermap.org/api/one-call-3

## How to use it

Search a location with the help of the Google map.  
The weather will be automatically synced with the current displayed location.  
A click on the weather shows a pop-up with the daily weather  
Enjoy !  
