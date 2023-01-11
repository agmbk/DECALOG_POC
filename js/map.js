/**
 * @license
 * Copyright 2019 Google LLC. All Rights Reserved.
 * SPDX-License-Identifier: Apache-2.0
 * Documentation at: https://developers.google.com/maps/documentation/javascript/examples/geocoding-reverse
 */
let map, marker, geocoder, weatherDiv, feedback, infowindow, isFirstSearch = true;

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 6.5,
        center: {lat: 46.6, lng: 2.2},
    });
    geocoder = new google.maps.Geocoder();
    infowindow = new google.maps.InfoWindow();

    const inputText = document.createElement("input");

    inputText.type = "text";
    inputText.placeholder = "Enter a location";

    const submitButton = document.createElement("input");

    submitButton.type = "button";
    submitButton.value = "Chercher";
    submitButton.classList.add("button", "button-primary");

    const clearButton = document.createElement("input");

    clearButton.type = "button";
    clearButton.value = "Clear";
    clearButton.classList.add("button", "button-secondary");
    `<div class="card bg-light mb-3" style="max-width: 20rem;"></div>`
    
    weatherDiv = document.createElement("div");
    weatherDiv.id = "weather-container";
    feedback = document.createElement("p");
    feedback.id = "instructions";
    showFeedback({Instructions: "Cherchez un lieu, sa localisation s'affichera."});

    map.controls[google.maps.ControlPosition.TOP_LEFT].push(inputText);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(submitButton);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(clearButton);
    map.controls[google.maps.ControlPosition.LEFT_TOP].push(feedback);
    map.controls[google.maps.ControlPosition.LEFT_TOP].push(weatherDiv);
    marker = new google.maps.Marker({
        map,
    });
    map.addListener("click", (e) => {
        geocode({location: e.latLng});
    });
    submitButton.addEventListener("click", () =>
        geocode({address: inputText.value})
    );
    clearButton.addEventListener("click", () => {
        clear();
    });
    clear();
}

function clear() {
    marker.setMap(null);
    weatherDiv.style.display = "none";
}

/**
 * Displays the weather div
 * @param html
 */
function displayWeather(html) {
    weatherDiv.innerHTML = html
    weatherDiv.style.display = "flex";
}

function geocode(request) {
    clear();
    geocoder
        .geocode(request)
        .then((result) => {
            const {results} = result;

            const location = results[0].geometry.location

            map.setCenter(location);
            marker.setPosition(location);
            marker.setMap(map);

            /**
             * Extract the location
             */
            let address = results[0].formatted_address
            if (results[0].address_components[0].types.includes("plus_code"))
                address = address.replace(results[0].address_components[0].long_name, '')

            /**
             * Fetch the weather from the php page and display it
             */
            fetch('/weather.php', {
                method: 'POST',
                body: JSON.stringify({
                    location: {
                        lat: location.lat(),
                        lng: location.lng(),
                        address
                    }
                })
            }).then(async res => {
                if (!res.ok) return alert('Something went wrong')
                const html = await res.text()
                displayWeather(html)
                if (isFirstSearch) {
                    showFeedback({Information: 'Cliquez sur la météo pour voir les détails'})
                    isFirstSearch = false
                    setTimeout(() => hideFeedback(), 3000)
                }
            })

            hideFeedback()

            return results;
        })
        .catch((e) => {
            if (typeof e === "object") {
                if (e.code === "ZERO_RESULTS")
                    return showFeedback({Erreur: 'Il n\'y pas de résultat pour cette requête'})
            }
            showFeedback(`Une erreur est survenue. ${e.toString()}`)
        });
}

/**
 * Hide the user feedback message
 */
function hideFeedback() {
    feedback.style.display = 'none'
}

document.getElementById('weather-info').firstChild

/**
 * Show the user feedback message
 * @param payload {string | Object}
 */
function showFeedback(payload) {
    if (typeof payload === 'string')
        feedback.innerText = payload
    else {
        const [key, value] = Object.entries(payload)[0]
        feedback.innerHTML = `<strong>${key}: </strong>${value}`
    }
    feedback.style.display = 'block'
}

window.initMap = initMap;
