/*
Author Samuel Bwoye
Title Script for automatically logging out user after 10 minutes of inactivuty
Languge: Javascript
Date : 27-02-2019
*/
$(function () {

    function timeChecker() {

        setInterval(function () {
            var lastTime = sessionStorage.getItem("synergylastTimeStamp");
            compareTime(lastTime);
        },15000);
    }

    function compareTime(timeString){
        var currtime = new Date();
        var pastTime = new Date(timeString);
        var timeDiff = currtime - pastTime;
        var minPast = Math.floor(timeDiff/60000);

        if(minPast > 10){
            sessionStorage.removeItem("synergylastTimeStamp");
            window.location = 'php/logout.php';
            return false;
        }
    }
    $(document).on('mousemove keyup',function () {
        var timeStamp = new Date();
        sessionStorage.setItem("synergylastTimeStamp", timeStamp);
    });

    timeChecker();
}); 