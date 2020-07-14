/*
Author Samuel Bwoye
Title Script for creating reports and setting page paginations
Languge: Javascript
Date : 27-02-2019
*/

/*function to create all the divs 
the div of class reportman should be appended to maindiv.
this div will hold all report details for a particular student*/

$(document).ready(function () {
    $.post('php/mmslogin.php', function (some) {
        //alert(some);
        var mines = JSON.parse(some);
        var tt = "<span><strong>Number of Grantees " + mines.entcounts + "</strong></span>";
        //$(document).find(".budgetsummaries").html(tt);
        var myfinal = tt;

        tt = '';
       
        var ml = mines.budget.sdf;
        var mlo = mines.budget.own;
        var ff = parseFloat(mlo) + parseFloat(ml);
        var tfs = ff;
        tt += "<span><strong>SDF Grant</strong>  " + numberWithCommas(ml) + "</span><span><strong>Own Budget </strong>" + numberWithCommas(mlo) + "</span> <span><strong>Total</strong> " + numberWithCommas(ff) + "</span>";
        //$(document).find(".budgetsummaries").append(tt);        
        myfinal += tt;
        $(document).find(".budgetsummaries").append(myfinal);
        //Payments 
        $(document).find(".budgetsummaries").append("<br><h3>Payments</h3>");
        tt = '';
        ml = mines.payment.sdf;
        mo = mines.payment.own;
        ff = parseFloat(mo) + parseFloat(ml);
        tt += "<span>SDF Payments " + numberWithCommas(ml) + "</span><span>Own Budget " + numberWithCommas(mo) + "</span>";
        $(document).find(".budgetsummaries").append(tt);
        $(document).find(".budgetsummaries").append("Total SDF Payment " + numberWithCommas(ff));

       
        tt = mines.skills.counts;
        //$(document).find(".contentx").append("Total Registrants "+numberWithCommas(tt));
        var mv = "Total Registrants " + numberWithCommas(tt);

        tt = '';

        tt += "<div style='display:flex;justify-content: space-between'><div id='registrant'><strong>Enrollment</strong><br>Female: " + numberWithCommas(mines.skills.f);
        tt += "<br>Male : " + numberWithCommas(mines.skills.m);
        tt += "<br>Uncategorised: " + numberWithCommas(mines.skills.uncategorised);
        // tt += "<br><strong>Enrollment</strong><br>Female :"+numberWithCommas(mines.skills.f)+"  Male :"+numberWithCommas(mines.skills.m)+"  Uncategorised :"+numberWithCommas(mines.skills.uncategorised);
        
        // $(document).find(".contentx").append(tt + "</div><div id='bydist' style='max-height:250px;overflow-y:auto;overflow-x:hidden;'></div></div>");

        tt = '';
        tt += "<table class='table-bordered table-sm table-hover' id='regis' style='font-size:12px;'>";       
        tt += "<thead style='position:sticky;top:0'><tr><th colspan='5'>Number or Registrants per District</th></tr>";
        tt += "<tr><th colspan='5'><input type='text' placeholder='search' id='sksearch'></th></tr>";
        tt += "<tr><th>#</th><th>District</th><th>Enrolement</th><th>Males</th><th>Females</th><th></tr></thead>";
        tt += "<tbody>";
        var v = 1
        for (s in mines.sktable) {
            tt += "<tr>";
            tt += "<td align='right'>" + v + "</td>";
            tt += "<td>" + mines.sktable[s].name + "</td>";
            tt += "<td align='right'>" + numberWithCommas(mines.sktable[s].Total) + "</td>";
            tt += "<td align='right'>" + numberWithCommas(mines.sktable[s].male) + "</td>";
            tt += "<td align='right'>" + numberWithCommas(mines.sktable[s].female) + "</td>";
            tt += "</tr>";
            v += 1;
        }

        tt += "</tbody></table>";
        //$(document).find('#bydist').html(mv);
        $(document).find('.onlytable').html(tt);


        tt = '';
        tt += "<table class='table table-sm table-bordered' style='font-size:12px;'>";
        tt +="<thead><tr><th colspan='4'><h3>Skilling by Sector</h3></th></tr>";
        tt +="<tr><th>Sector</th><th>Female</th><th>Male</th><th>Total</th></tr></thead>";
        tt += "<tbody>";
        for(s in mines.secskill){
            tt += "<tr>";
            tt += "<td>"+mines.secskill[s].Sectordescription+"</td>";
            tt += "<td align='right'>"+numberWithCommas(mines.secskill[s].Female)+"</td>";
            tt += "<td align='right'>"+numberWithCommas(mines.secskill[s].Male)+"</td>";
            tt += "<td align='right'>"+numberWithCommas(mines.secskill[s].Total)+"</td>";
            tt += "</tr>";
        }

        tt += "</tbody></table>";

        $(document).find('.budgetsummaries').append(tt);
        
        tt = '';
        tt += "<table class='table table-sm table-bordered' style='font-size:12px;'>";
        tt +="<thead><tr><th colspan='4'><h3>Skilling by Window</h3></th></tr>";
        tt += "<tr><th>Window</th><th>Male</th><th>Female</th><th>Total</th></tr></thead>";
        tt += "<tbody>";
        for(s in mines.pwindow){
            tt += "<tr>";
            tt += "<td align='right'>"+mines.pwindow[s].pwindow+"</td>";
            tt += "<td align='right'>"+numberWithCommas(mines.pwindow[s].Male)+"</td>";
            tt += "<td align='right'>"+numberWithCommas(mines.pwindow[s].Female)+"</td>";
            tt += "<td align='right'>"+numberWithCommas(mines.pwindow[s].Total)+"</td>";
            tt += "</tr>";
        }
        tt += "</tbody></table>";
        $(document).find('.budgetsummaries').append(tt);

    });

    $(document).on('keyup', '#sksearch', function () {

        var value = $(this).val().toLowerCase().trim();

        var numrows = $("#regis tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            return $(this).css('display') !== 'none';
        });
    });

    function numberWithCommas(number) {
        var parts = number.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }    
});