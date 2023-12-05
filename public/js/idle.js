var idleTime = 0;
 
$(document).ready(function () {
    // Increment the idle time every second
    var idleInterval = setInterval(timerIncrement, 1000);
 
    // Reset the idle time on user activity
    $(this).mousemove(function (e) {
        idleTime = 0;
    });
 
    $(this).keypress(function (e) {
        idleTime = 0;
    });
});
 
function timerIncrement() {
    idleTime = idleTime + 1;
    if (idleTime > 29) { // 30 seconds
        // Trigger logout action or redirect to logout route
        window.location.href = '/logout';
    }
}