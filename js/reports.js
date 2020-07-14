/*
Author Samuel Bwoye
Title Script for showing list of entites which have ever submitted reports, reports due and making 
submitted reports downloadable. Accepted formats will be excel, word and pdf formats   
Languge: Javascript
Date : 28-08-2019
*/
// http://localhost/synergy/php/reports.php



var jq = $.noConflict();
jq(document).ready(function () {
    var myents;

    jq('#mymenus').load("menus.html");

    const entrows = [{ "fileno": "File No.", "Granteename": "Name", "district": "District", "sector": "Sector", "pwindow": "Window", "sdate": "Start Date", "edate": "End Date" }];
    //const entrows = [{ "Granteename": "Name", "Beneficiaryno": "Enrollment", "numroll": "Enrollment Allowed", "district": "District", "sector": "Sector", "pwindow": "Window", "sdate": "Start Date", "edate": "End Date"}];


    jq(document).on('click', '.mgrantees', function () {
        myentities();
    });

    jq(document).on('click', '.mskilling', function () {
        myskills();
    });

    function myskills() {

        jq.post('php/reports.php', { mskilling: "yes" }, function (some) {
            var mines = JSON.parse(some);
            //alert(some);
            var gg = mines.overall;
            var bywin = mines.bywindow;
            var dsector = mines.mysectors;
            var gdist = mines.mydists;
            var tt = '';
            tt += "<div id='divoverall'><h3>Overall Skilling Status</h3>";
            tt += "<table id='ovstatus'>";
            tt += "<thead><tr><th>#</th><th>Status</th><th>Female</th><th>Male</th></tr></thead>";
            tt += "<tbody>";
            let k = 0;
            gg.forEach(g => tt += `<tr><td align='right'>${++k}</td><td>${g.Status}</td><td align='right'>${numberWithCommas(g.Female)}</td><td align='right'>${numberWithCommas(g.Male)}</td></tr>`);
            // gg.forEach( g => )
            tt += "</tbody></table></div>";
            jq('.results').html(tt);


            tt = '';
            tt = "<div id='windiv' style='padding-top:30px'><h3>Skilling by Window</h3>";
            tt += "<table id='bywindow'>";
            tt += "<thead><tr><th>Window</th><th>Female</th><th>Male</th></tr>";
            tt += "<tbody>";
            bywin.forEach(mh => tt += `<tr><td align='right'>${mh.pwindow}</td><td align='right'>${numberWithCommas(mh.Female)}</td>
                <td align='right'>${numberWithCommas(mh.Male)}</td></tr>`);
            tt += "</tbody></table></div>";
            jq('.results').append(tt);


            tt = '';
            tt += "<div id='msect' style='padding-top:30px;'>";
            tt += "<table>";
            tt += "<thead><tr><th>Sector</th><th>Completed</th><th>Training</th><th>Droped out</th></tr>";
            tt += "<tbody>";
            dsector.forEach(mm => tt += `<tr><td>${mm.Sectordescription}</td><td align='right'>${numberWithCommas(mm.Completed)}</td>
            <td align='right'>${numberWithCommas(mm.Trainig)}</td><td align='right'>${numberWithCommas(mm['Droped Out'])}</td></tr>`);
            tt += "</tbody></table>";

            tt += "</div>";
            jq('.results').append(tt);

            tt = '';
            tt += "<div id='mydists' style='padding-top:30px;'>";
            tt += "<h3>Skilling by District</h3>";
            tt += "<table>";

            tt += "<thead><tr><th></th><th>District</th><th>Completed</th><th>Training</th><th>Droped out</th></tr></thead>";
            tt += "<tbody>";
            k = 0;
            gdist.forEach(pp => tt += `<tr><td align='right'>${++k}</td><td>${pp.District}</td><td align='right'>${numberWithCommas(pp.Completed)}</td><td align='right'>${numberWithCommas(pp.Trainig)}</td><td align='right'>${numberWithCommas(pp['Droped Out'])}</td></tr>`);
            tt += "</tbody></table></div>";

            jq('.results').append(tt);

            let ff = "<h3>Select Items for Printing</h3>";
            ff += "<div><input type='checkbox' checked='checked' id='oprint' class='forprinting'>Overall skilling Status</div>";
            ff += "<div><input type='checkbox' checked='checked' id='dprint' class='forprinting'>District</div>";
            ff += "<div><input type='checkbox' checked='checked' id='dsector' class='forprinting'>Sector</div>";
            ff += "<div><input type='checkbox' checked='checked' id='dwindow' class='forprinting'>Window</div>";
            jq('.sidebar').html(ff);
        })
    }



    function numberWithCommas(number) {
        var parts = number.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }

    jq(document).on('change', '.forprinting', function () {
        var theClick = jq(this).attr('id');
        if (theClick == 'oprint') {
            if (jq(this).is(':checked')) {
                jq('#divoverall').removeClass('noprint');
            } else {
                //jq(this).attr('checked', true);
                jq('#divoverall').addClass('noprint');
            }
        } else if (theClick == 'dprint') {
            if (jq(this).is(':checked')) {
                jq('#mydists').removeClass('noprint');
            } else {
                //jq(this).attr('checked', true);
                jq('#mydists').addClass('noprint');
            }
        } else if (theClick == 'dsector') {
            if (jq(this).is(':checked')) {
                jq('#msect').removeClass('noprint');
            } else {
                //jq(this).attr('checked', true);
                jq('#msect').addClass('noprint');
            }

        } else if (theClick == 'dwindow') {
            if (jq(this).is(':checked')) {
                jq('#windiv').removeClass('noprint');
            } else {
                //jq(this).attr('checked', true);
                jq('#windiv').addClass('noprint');
            }
        }
    });

    function myentities() {
        jq.post('php/reports.php', { grantees: "yes" }, function (some) {
            var mines = JSON.parse(some);
            myents = mines.entities;
            var mtskill = mines.skillsums;
            var tt = '';
            tt += "<table id='grantees' border-bottom='1px solid'>";
            tt += "<thead><tr>";
            // tt += entrows.forEach(trow => console.log(trow));
            //var trows = entrows[0];
            let trows = { "Granteename": "Name", "sector": "Sector", "pwindow": "Window", "sdate": "Start Date", "edate": "End Date" };
            trows = entrows[0];
            tt += "<th>#</th>";
            for (s in trows) {
                tt += "<th>" + trows[s] + "</th>";
            }
            tt += "</tr></thead>";
            tt + "<tbody>";
            var obj = trows;
            var k = 1;
            for (s in myents) {
                tt += "<tr>";
                tt += "<td align='right'>" + k + "</td>";
                for (i = 0; i < Object.keys(obj).length; i++) {
                    tt += "<td>" + myents[s][Object.keys(obj)[i]] + "</td>";
                }
                tt += "</tr>";
                k += 1;
            }
            tt += "</tbody></table>";
            jq('.results').html(tt);


            //Prepare array for making table of skills 
            var bs = '';
            bs +="<div id='skillsum'>";
            bs += "<table>";
            bs +="<thead><tr><th></th><th></th><th colspan=4>Female</th><th colspan='4'>Male</th></tr>";
            bs += "<tr><th>#</th><th>Name</th><th>Enrolment</th><th>Completed</th><th>Training</th><th>Droped Out</th>";
            bs += "<th class='mymales'>Enrolment</th><th class='mymales'>Completed</th><th class='mymales'>Training</th><th class='mymales'>Droped Out</th></tr></thead>";
            bs += "<tbody>";
            var kk =0;
            mtskill.forEach(vv => bs += `<tr><td align='right'>${++kk}</td><td>${vv.Granteename}</td><td align='right'>${numberWithCommas(vv.Female)}</td>
            <td align='right'>${numberWithCommas(vv.Femcompleted)}</td><td align='right'>${numberWithCommas(vv.Femtrain)}</td><td align='right'>${numberWithCommas(vv.Femdrop)}</td><td align='right'>${numberWithCommas(vv.Male)}</td><td align='right'>${numberWithCommas(vv.Mco)}</td>
            <td align='right'>${numberWithCommas(vv.Mtr)}</td><td align='right'>${numberWithCommas(vv.Mdropped)}</td></tr>`);
            bs += "</tbody></table></div>";

            jq('.results').append(bs);


            ///////////
            jq('.sidebar').empty();
            var mm = entrows[0];
            //var ff = Object.keys(mm)+"<br/>";
            var tt = '';
            tt += "<h4>Select columns for printing</h4>";
            //tt += "<input type='checkbox' class='contrls'> Invert<br>";
            for (i = 0; i < Object.keys(mm).length; i++) {
                tt += "<input type='checkbox' class='mytable' checked='checked' id='" + Object.keys(mm)[i] + "'>&nbsp;" + mm[Object.keys(mm)[i]] + "<br>";
            }
            jq('.sidebar').html(tt);
        });

        jq(document).on('change', '.contrls', function () {
            jq('.mytable').each(function () {
                if (jq(this).is(':checked')) {
                    jq(this).attr('checked', false);
                } else {
                    jq(this).attr('checked', true);
                }
            });
        })

        jq(document).on('change', '.mytable', function () {
            myrow = entrows[0][jq(this).attr('id')];

            //if (jq(this).is(':checked')) {
            // var f = 0;
            jq('#grantees thead tr th').each(function () {

                if (jq(this).text() === myrow) {
                    f = jq(this).parent().children().index(jq(this));;
                    //jq(this).parent().children(f).toggle('hide');
                    jq(this).toggleClass('noprint');
                }
            });
            //alert("Toggling "+f);
            jq('#grantees tbody tr').each(function () {
                jq(this).find('td').eq(f).toggleClass('noprint');
            });
        });
    }

    function numberWithCommas(number) {
        var parts = number.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }
});


