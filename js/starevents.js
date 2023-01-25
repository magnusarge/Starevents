
async function loadEventList(eventsApiUrl, eventContainer) {
    eventContainer.innerHTML = `<div style="text-align:center;padding-top:100px;">Getting clear sky events. Few seconds more and we are done...</div>`;
	const response = await fetch(
		eventsApiUrl,
		{
			method: 'GET'
		}
	);
	if (!response.ok) {
        eventContainer.innerText = "Error loading data!";
		throw new Error(`HTTP error! status: ${response.status}`);
	}
	const eventList = await response.json();
    buildAllEventsList(eventList, eventContainer);
}

function buildAllEventsList(eventList, eventContainer) {

    /** Init data. */
    let cityCount = 0;
    let allEventsCount = 0;
    const maxClouds = eventList["max_clouds"];
    const countries = eventList.list;

    /** This container will hold all events. */
    const allEvents = document.createElement("section");

    /** Menu bar for city names. */
    const cityMenu = document.createElement("ul");
    cityMenu.classList.add("city-links", "noselect");

    /** Iterate all countries. */
    for ( country in countries ) {

        const cities = countries[country];
        /** Iterate this country's cities. */
        for ( city in cities ) {
            if ( countries[country][city]["city_name"] ) {

                /** Get data for this city. */
                const cityName = countries[country][city]["city_name"];
                const events  = countries[country][city]["events"];
                let thisCityEventCount = 0;

                /** Create container for this city's events. */
                const cityEvents = document.createElement("div");
                cityEvents.classList.add("city-event-container");
                if ( cityCount == 0 ) {
                     cityEvents.classList.add("visible");
                }

                /** Iterate this city's events */
                for ( cityevent in events ) {
                    allEventsCount++;
                    thisCityEventCount++;

                    /** Get data for event. */
                    const timestamp = countries[country][city]["events"][cityevent]["timestamp"];
                    const dtText = countries[country][city]["events"][cityevent]["dt_text"];
                    const clouds = countries[country][city]["events"][cityevent]["clouds"];
                    const visibility = getVisibility(clouds, maxClouds);

                    /** Create cell for individual event and append it to this city's container. */
                    const eventCell = document.createElement("a");
                    eventCell.classList.add("popupper");
                    eventCell.setAttribute("href", "#register");
                    eventCell.innerHTML = buildEventCell(dtText, clouds, maxClouds);
                    eventCell.addEventListener("click", function() {
                        fillForm(cityName, dtText, timestamp, clouds, visibility);
                    }); 
                    cityEvents.appendChild(eventCell);

                }
                /** Add events and city menu item only if this city has events. */
                if ( thisCityEventCount > 0 ) {
                    cityCount++;
                    /** Assign id to container and append events to it. */
                    cityEvents.setAttribute("id", `city-${cityCount}`);
                    allEvents.appendChild(cityEvents);

                    /** Create menu item for city and append it to the menu. */
                    const thisCityMenuItem = document.createElement("li");
                    thisCityMenuItem.classList.add("city-link");
                    if ( cityCount == 1 ) {
                        thisCityMenuItem.classList.add("active");
                    }
                    thisCityMenuItem.setAttribute("onClick", `showEventsForCity(this, 'city-${cityCount}')`)
                    thisCityMenuItem.innerText = cityName;
                    cityMenu.appendChild(thisCityMenuItem);
                }
            }
        }

        /** Compose message text. */
        let messageText = "Unfortunately, there is no clear sky in next 5 days.";
        if ( cityCount > 0 ) {
            let s1 = allEventsCount == 1 ? "is 1 star observation event" : `are ${allEventsCount} star observing events`;
            let s2 = cityCount == 1 ? "1 Estonian city" : `${cityCount} Estonian cities`;
            messageText = `There ${s1} available in ${s2}.`;
        }

        /** Create container for message. */
        let eventCountMessage = document.createElement("div");
        eventCountMessage.classList.add("message");
        eventCountMessage.innerText = messageText;

        /** Append prepared elements to DOM */
        eventContainer.innerHTML = "";
        eventContainer.appendChild(eventCountMessage);
        eventContainer.appendChild(cityMenu);
        eventContainer.appendChild(allEvents);

        /** Display max clouds */
        displayMaxClouds(maxClouds);
    }
}

function fillForm(cityName, dtText, timestamp, clouds, visibility) {

    message("", true); // Remove previous messages.
    success(false); // Remove success message and make form visible if it's not.

    /** Targets. */
    const cityNameId = document.getElementById("form-details-city");
    const dateId = document.getElementById("form-details-time");
    const timestampId = document.getElementById("timestamp");
    const weatherId = document.getElementById("form-details-weather");

    const nameId = document.getElementById("register-name");
    const emailId = document.getElementById("register-email");
    const commentId = document.getElementById("register-comment");

    nameId.classList.remove("error");
    emailId.classList.remove("error");
    commentId.classList.remove("error");

    /** Formatted datetime. */
    const eventTime = new Date(dtText);
    const eventHour = formattedHours(eventTime, false);
    const eventWeekDay = formattedWeekDay(eventTime, false);
    const eventDate = formattedDate(eventTime, false);

    const dateTime = `${eventWeekDay}, ${eventDate} ${eventHour}`;

    /** Fill targets with data. */
    cityNameId.innerText = cityName;
    dateId.innerText = dateTime;
    timestampId.value = timestamp;
    weatherId.innerText = `Clouds ${clouds}%, visibility ${visibility}%`;

    /** Assign behaviour to button */
    const button = document.getElementById("register-button");
    button.setAttribute("onClick", `validateForm()`);

    registrationModal = document.getElementById("register");
    registrationModal.setAttribute("style", "");
}

function getVisibility(clouds, maxClouds) {
    let visibility = 0;
    if ( (clouds > 0) && (maxClouds > 0) && (maxClouds >= clouds) ) {
        visibility = Math.ceil(100 - (clouds / maxClouds * 100))
    }
    return visibility;
}

function displayMaxClouds(maxClouds) {
    const cloudDisplay = document.getElementById("cloud-display");
    cloudDisplay.innerText = maxClouds + "%";
}

function buildEventCell(dtText, clouds, maxClouds) {
    const visibility = getVisibility(clouds, maxClouds);
    let progressBarWidth = visibility;
    if ( visibility < 5 ) progressBarWidth = 5;
    const progressColor = visibilityColor(visibility);

    const eventTime = new Date(dtText);
    const eventHour = formattedHours(eventTime);
    const eventWeekDay = formattedWeekDay(eventTime);
    const eventDate = formattedDate(eventTime);

    const eventTemplate = `
    <div class="event-row">
        <div class="event-content">
            <div class="event-hour">${eventHour}</div>
            <div class="event-date">
                <div class="day">${eventWeekDay}</div>
                <div class="date">${eventDate}</div>
            </div>
        </div>
        <div class="event-clouds">
            <div class="icon"><i class="fa-solid fa-eye-slash"></i></div>
            <div class="progress-frame">
                <div class="progress-bar" style="width:${progressBarWidth}%; background-color:${progressColor}"></div>
            </div>
            <div class="icon"><i class="fa-solid fa-eye"></i></div>
            <div class="clouds-value">
                Clouds ${clouds}%<br>
                Visibility ${visibility}%
            </div>
        </div>
    </div>
    `;
    return eventTemplate;
}

function visibilityColor(visibility) {

    if (visibility <= 25) { return "crimson"; } else
    if (visibility <= 50) { return "tomato"; } else
    if (visibility <= 75) { return "orange"; } else
    { return "yellowgreen"; }

}


