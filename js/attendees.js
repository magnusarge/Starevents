async function loadAttendeeList(attendeesApiUrl, attendeeContainer) {
    attendeeContainer.innerHTML = `<div style="text-align:center;padding-top:100px;">Getting attendees. Few seconds more and we are done...</div>`;
	const response = await fetch(
		attendeesApiUrl,
		{
			method: 'GET'
		}
	);
	if (!response.ok) {
        attendeeContainer.innerText = "Error loading data!";
		throw new Error(`HTTP error! status: ${response.status}`);
	}
	const attendeeList = await response.json();
    buildAttendeeList(attendeeList, attendeeContainer);
}

function buildAttendeeList(attendeeList, attendeeContainer) {
    attendeeContainer.innerHTML = "";
    const attendees = attendeeList.list;
    for ( i in attendees ) {
        attendeeContainer.appendChild(attendeeTemplate(attendees[i]));
    }

}

function attendeeTemplate(attendee) {
    const attendeeRow = document.createElement("div");
    attendeeRow.classList.add("attendee-row");
    for ( i in attendee ) {
        const column = document.createElement("div");
        let content = attendee[i];
        if ( i == "timestamp" ) {
            const dateTime = new Date(attendee[i]);
            content = formattedDate(dateTime, false) + " " + formattedHours(dateTime, false);
        }
        column.innerText = content;
        attendeeRow.appendChild(column);
    }
    return attendeeRow;
}