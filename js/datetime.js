function formattedHours(eventTime, addLeadingZero= true) {
    let eventHour = eventTime.getHours();
    let eventMinutes = eventTime.getMinutes();
    if ( addLeadingZero ) {
        eventHour = `${eventHour}`.length == 1 ? `0${eventHour}` : eventHour;
    }
    eventMinutes = `${eventMinutes}`.length == 1 ? `0${eventMinutes}` : eventMinutes;
    return `${eventHour}:${eventMinutes}`;
}

function formattedDate(eventTime, addSpan=true) {
    const eventDate = eventTime.getDate();
    const eventMonth = eventTime.getMonth();
    return `${monthShortName(eventMonth)} ${dateWithSuffix(eventDate, addSpan)}`;
}

function formattedWeekDay(eventTime, short=true) {
    let eventWeekDay = eventTime.getDay();

    const today = new Date().setHours(0,0,0,0);
    const tomorrow = new Date(new Date().getTime() + (24 * 60 * 60 * 1000)).setHours(0,0,0,0);
    const eventDay = eventTime.setHours(0,0,0,0);

    if ( eventDay == today ) {
        return "Today";
    } else if ( eventDay == tomorrow ) {
        return "Tomorrow";
    } else {
        return weekDay(eventWeekDay, short);
    }
}

function dateWithSuffix(dateNum, addSpan=true) {
    const suffixes = ["th", "st", "nd", "rd"];
    const specialNums = [1, 2, 3, 21, 22, 23, 31];
    const spanStartTag = addSpan ? "<span>" : "";
    const spanEndTag = addSpan ? "</span>" : "";
    if ( specialNums.indexOf(dateNum) >= 0 ) {
        return dateNum+spanStartTag+suffixes[`${dateNum}`.slice(-1)]+spanEndTag;
    } else {
        return dateNum+spanStartTag+suffixes[0]+spanEndTag;
    }
}

function monthShortName(monthNum) {
    const date = new Date();
    date.setMonth(monthNum);
    return date.toLocaleString("en-US", { month: "short" });
}

function weekDay(weekDayNum, short) {
    let weekDays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    if ( !short ) {
        weekDays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    }
    return weekDays[weekDayNum];
}