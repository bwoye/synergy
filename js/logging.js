/*
Author Samuel Bwoye
Title Script for creating reports and setting page paginations
Languge: Javascript
Date : 27-02-2019
*/

/*function to create all the divs 
the div of class reportman should be appended to maindiv.
this div will hold all report details for a particular student
*/

$(document).ready(function () {
    $('#mymenus').load("menus.html");

    $.post('php/logging.php', function (some) {
        var k = 1;
        //alert(some);
        var mines = JSON.parse(some);
        var tab = '';

        tab += "<table class='table table-bordered table-hover table-sm' id='logtab'>";
        tab += "<thead>";
        tab += "<tr><th colspan='3'><button class='btn btn-primary btn-sm gothere'>Print</button></th>";
        tab += "</tr>";
        tab += "<tr>";
        tab += "<th colspan='2'><select id='suserid'></select></th><th>From <input type='date' id='fdate'> to <input type='date' id='tdate'> <button class='btn mybuts btn-sm btn-primary findit'>Go</button></th><th colspan='2'><span id='ownrecord'></span<</th></tr>";
        tab += "</tr>";
        tab += "<tr>";
        tab += "<th style='width:70px;'>#</th><th>Name</th><th style='width:500px;'>Action</th><th>Date</th><th>Time</th>";
        tab += "</tr>";
        tab += "<tbody>";
        for (s in mines.logs) {
            tab += "<tr>";
            tab += "<td style='width:70px;' align='right'>" + k + "</td>";
            tab += "<td>" + mines.logs[s].fulname + "</td>";
            tab += "<td style='width:500px;'>" + mines.logs[s].action + "</td>";
            tab += "<td gval='" + mines.logs[s].refdate + "'>" + returndate(mines.logs[s].date) + "</td>";
            tab += "<td>" + getLocaltime(mines.logs[s].date) + "</td>";
            tab += "</tr>";
            k += 1;
        }
        tab += "</tbody>";
        tab += "</thead>";
        tab += "</table>";

        $(document).find('#listing').html(tab);
        $('#myuser').val(mines.userid);

        $('#suserid').append("<option value='all'>All</option>");
        for (s in mines.names) {
            $('#suserid').append("<option value='" + mines.names[s].fulname + "'>" + mines.names[s].fulname + "</option>");
        }

        $('#ownrecord').html("All transactions " + $('#logtab tbody tr').length);

        $(document).find('#prints').html('').css({ 'display': 'none' });

        var xl = "All transactions " + $('#logtab tbody tr').length;
        var ql = "<h3>" + xl + "</h3>";
        ql += "<table><thead><tr>";
        ql += "<th style='width:70px;'>#</th><th>Name</th><th style='width:500px;'>Action</th><th>Date</th><th>Time</th>";
        ql += "</tr></thead><tbody>";
        $('#logtab tbody tr').each(function () {
            ql += "<tr>";
            $(this).find('td').each(function () {
                ql += "<td>" + $(this).text() + "</td>";
            });
            ql += "</tr>";
        });
        ql += "</tbody></table>";

        $(document).find('#prints').html(ql);
    });

    $(document).on('click', '.gothere', function (ev) {
        ev.preventDefault();
        printme('prints');
    });

    $(document).on('click', '.findit', function (ev) {
        ev.preventDefault();
        var fx = $('#suserid').val();
        var m = 0;
        if (fx == 'all') {
            $('#logtab tbody tr').each(function () {
                var cpdate = $(this).find('td:eq(3)').attr('gval');
                if (cpdate >= $('#fdate').val() && cpdate <= $('#tdate').val()) {

                    $(this).css({ 'display': '' });
                    m += 1;
                    $(this).find('td:eq(0)').text(m);
                } else {
                    $(this).css({ 'display': 'none' });
                }
            });
            $('#ownrecord').html("All transactions " + m);
            makeRecords(m);
        } else {
            $('#logtab tbody tr').each(function () {
                var cpdate = $(this).find('td:eq(3)').attr('gval');
                var nams = $(this).find('td:eq(1)').text();
                if (cpdate >= $('#fdate').val() && cpdate <= $('#tdate').val() && fx == nams) {
                    $(this).css({ 'display': '' });
                    m += 1;
                    $(this).find('td:eq(0)').text(m);
                } else {
                    $(this).css({ 'display': 'none' });
                }
            });
            $('#ownrecord').html("Transactions for " + $('#suserid').val() + ' ' + m);
            makeRecords(m);
        }
    });

    $(document).on('change', '#suserid', function () {
        var tm = $(this).val();
        //alert("MY tm is "+tm);
        var m = 0;
        if (tm == 'all') {
            $('#logtab tbody tr').each(function () {
                $(this).css({ 'display': '' });
                m += 1;
                $(this).find('td:eq(0)').text(m);
            });
            $('#ownrecord').html("All transactions " + $('#logtab tbody tr').length);
            makeRecords(m);

        } else {
            $('#logtab tbody tr').each(function () {
                if ($(this).find('td:eq(1)').text() == tm) {
                    $(this).css({ 'display': '' });
                    m += 1;
                    $(this).find('td:eq(0)').text(m);
                } else {
                    $(this).css({ 'display': 'none' });
                }
            });
            $('#ownrecord').html("Transactions for " + $('#suserid').val() + ' ' + m);
            makeRecords(m);
        }
    });

    function makeRecords(m) {
        $(document).find('#prints').html('').css({ 'display': 'none' });

        var xl = "Transactions for " + $('#suserid').val() + ' ' + m;
        var ql = "<h3>" + xl + "</h3>";
        ql += "<table><thead><tr>";
        ql += "<th style='width:70px;'>#</th><th>Name</th><th style='width:500px;'>Action</th><th>Date</th><th>Time</th>";
        ql += "</tr></thead><tbody>";
        $('#logtab tbody tr').each(function () {
            if ($(this).css('display') != 'none') {
                ql += "<tr>";
                $(this).find('td').each(function () {
                    ql += "<td>" + $(this).text() + "</td>";
                });
                ql += "</tr>";
            }
        });
        ql += "</tbody></table>";

        $(document).find('#prints').html(ql);
    }
});

function returndate(sm) {
    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var x = new Date(sm);
    var p = x.getDate() + "-" + months[x.getMonth()] + "-" + x.getFullYear();
    return p;
}

function getLocaltime(string) {
    var date = new Date(string);
    return date.toLocaleTimeString();
}

function printme(kam) {
    var restorpage = document.body.innerHTML;
    var printcontent = document.getElementById(kam).innerHTML;
    document.body.innerHTML = printcontent;
    window.print();
    document.body.innerHTML = restorpage;
}

//$('#addmodal').modal('hide');
// $('.control').prop('readOnly', true);