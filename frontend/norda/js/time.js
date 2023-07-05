function updateClock ( ){
    var currentTime = new Date ( );
    var currentHours = currentTime.getHours ( );
    var currentMinutes = currentTime.getMinutes ( );
    var currentSeconds = currentTime.getSeconds ( );
    // Pad the minutes and seconds with leading zeros, if required
    currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
    currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

    var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

    currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;
 
    currentHours = ( currentHours == 0 ) ? 12 : currentHours;

    var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
    $(".time-now").html(currentTimeString);
}

$(document).ready(function()
{
    setInterval('updateClock()', 1000);
});
