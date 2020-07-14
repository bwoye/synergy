/*
Author Samuel Bwoye
Title Script foe creating table for all assessment types and periods;
Languge: Javascript
Date : 27-02-2019
*/

/*function to create all the divs 
the div of class reportman should be appended to maindiv.
this div will hold all report details for a particular student
*/

$(document).ready(function () {
    $(document).on('click', '.bline', function (ev) {
        ev.preventDefault();
        $(document).find('.modal_rating').html("Base Line Rating");
        $(document).find('#ratingfind').val('baseline');
        $(document).find('#whatlooking').html("Base Line");

        var snd = {
            baseline: $(document).find('#usefile').val(),
        }

        //document.write(JSON.stringify(snd));

        $.post('php/rating.php', snd, function (some) {
            //var tab = makTable();
            var k = 1;

            var mines = JSON.parse(some);

            var tab = makTable(mines);
        });
    });

    // end of baseline

    $(document).on('click', '.radclass', function () {
        var row = $(this).closest('tr');
        var rnum = row.attr('rowid');
        var pl = 0;
        var setval = $(this).val();

        var rline = $(this).attr('tell');

        row.find(".radclass").each(function () {
            if ($(this).prop('checked') == true) {
                //pl +=  
                pl += parseInt($(this).val());
            }
        });



        if ($('#ratingfind').val() == "baseline") {

            var snd = {
                baselineup: rnum,
                tots: pl,
                myline: rline,
                selval: setval
            }


            $.post('php/rating.php', snd, function (some) {
                var mines = JSON.parse(some);

                var ele = $("#enrolx tbody tr[enrid='" + mines.recno + "']");
                ele.find('td').eq(8).attr('nval', mines.tots);
                ele.find('td').eq(8).text(mines.texttot);

            });
        } else if ($('#ratingfind').val() == 'midline') {

            var snd = {
                midtermup: rnum,
                tots: pl,
                myline: rline,
                selval: setval
            }


            $.post('php/rating.php', snd, function (some) {
                var mines = JSON.parse(some);

                var ele = $("#enrolx tbody tr[enrid='" + mines.recno + "']");
                ele.find('td').eq(9).attr('nval', mines.tots);
                ele.find('td').eq(9).text(mines.texttot);

            });

        } else {
            var snd = {
                endtermup: rnum,
                tots: pl,
                myline: rline,
                selval: setval
            }


            $.post('php/rating.php', snd, function (some) {
                var mines = JSON.parse(some);

                var ele = $("#enrolx tbody tr[enrid='" + mines.recno + "']");
                ele.find('td').eq(10).attr('nval', mines.tots);
                ele.find('td').eq(10).text(mines.texttot);

            });
        }
    });

    $(document).on('click', '.saverating', function () {
        $(document).find('#ratemodal').hide('slow');
    });


    // ==========================================================================================
    // begin midterm

    $(document).on('click', '.mline', function (ev) {
        ev.preventDefault();
        $(document).find('.modal_rating').html("Mid Term Rating");
        $(document).find('#ratingfind').val('midline');
        $(document).find('#whatlooking').html("Mid Term");

        var snd = {
            midline: $(document).find('#usefile').val(),
        }

        //document.write(JSON.stringify(snd));

        $.post('php/rating.php', snd, function (some) {
            //var tab = makTable();
            var k = 1;

            var mines = JSON.parse(some);

            var tab = makTable(mines);

        });
    });

    // end of midterm

    // =======================================================================================================

    // begin of endterm 
    $(document).on('click', '.eline', function (ev) {
        ev.preventDefault();
        $(document).find('.modal_rating').html("End Term Rating");
        $(document).find('#ratingfind').val('endline');
        $(document).find('#whatlooking').html("End Term");

        var snd = {
            endline: $(document).find('#usefile').val(),
        }

        //document.write(JSON.stringify(snd));

        $.post('php/rating.php', snd, function (some) {
            //var tab = makTable();           

            var mines = JSON.parse(some);
            //alert("We shall bring assessement div");
            var tab = makTable(mines);

        });
    });

    // end of end term

    // ========================================================================================



    // $('#somerating').click(function (event) {
    //     event.preventDefault();
    //     $('#somerating').scrollView();
    // });

    // Detecting a click outside div

    // $(function() {
    //     $(document).on('click', function(e) {
    //         if (e.target.id === 'somerating') {
    //             alert('Div Clicked !!');
    //         } else {
    //             $('#somerating').hide();
    //         }    
    //     })
    // });​


   $(document).mouseup(function(e){
       mcon =$('#somerating');
       container = $('#placement');
       // if the target of the click isn't the container nor a descendant of the container
       if(!container.is(e.target) && container.has(e.target).length === 0){
           container.hide();
       }
   });
});


function makTable(mines) {
    var k = 1;
    var tab = '';
    // tab += "<div class='table-cont' id='table-cont'>";
    tab += "<table class='table table-hover table-xs' id='myratingtable'>";
    // tab += "<table class='table table-hover' id='myratingtable'>";
    tab += "<thead>";
    tab += "<tr ><th colspan='2' style='width:475px;' ></th><th colspan='3' >Knowledge of Tool</th>";
    tab += "<th colspan='3'>Explanation of Tool</th>";
    tab += "<th colspan='3'>Use of Tool</th></tr>";
    tab += "<tr>";
    tab += "<tr><th colspan='2' style='text-align:center;'?Name</th>";
    tab += "<th >Not Satisfied</th><th >Fairly Satisfied</th><th >Satisfied</th>";
    tab += "<th >Not Satisfied</th><th >Fairly Satisfied</th><th >Satisfied</th>";
    tab += "<th >Not Satisfied</th ><th >Fairly Satisfied</th><th >Satisfied</th>";
    // tab += "<tr ><th colspan='2' class='border border-info' style='width:475px;' ></th><th colspan='3' class='border border-danger'>Knowledge of Tool</th>";
    // tab += "<th colspan='3' class='border border-danger'>Explanation of Tool</th>";
    // tab += "<th colspan='3' class='border border-danger'>Use of Tool</th></tr>";
    // tab += "<tr>";
    // tab += "<tr><th colspan='2' style='text-align:center;' class='border border-primary'>Name</th>";
    // tab += "<th class='border border-primary'>Not Satisfied</th><th class='border border-primary'>Fairly Satisfied</th><th class='border border-primary'>Satisfied</th>";
    // tab += "<th class='border border-primary'>Not Satisfied</th><th class='border border-primary'>Fairly Satisfied</th><th class='border border-primary'>Satisfied</th>";
    // tab += "<th class='border border-primary'>Not Satisfied</th ><th class='border border-primary'>Fairly Satisfied</th><th class='border border-primary'>Satisfied</th>";
    tab += "</tr>";
    tab += "</thead>";
    tab+= "<tbody</tbody></table>";

    
    var tab2='';
    tab2 += "<table class='table table-borderd table-hover' class='myratingtable' ><thead></thead>"
    tab2 += "<tbody id='cmbody' style='color:white;' >";

    for (s in mines.sample) {
        nv = mines.sample[s].recno;
        var know = 'know' + nv;
        var expl = 'expl' + nv;
        var appt = 'appt' + nv;


        tab2 += "<tr rowid='" + mines.sample[s].recno + "'>";
        tab2 += "<td align='right'>" + k + "</td>";
        tab2 += "<td>" + mines.sample[s].fulname + "</td>";


        if (mines.sample[s].bknowtool == 1) {

            tab2 += "<td align='center' ><input type='radio' id='a" + nv + "' tell='know' name='" + know + "' value='0' class='radclass'></td>";
            tab2 += "<td align='center' style='border-right:none;'><input type='radio' id='b" + nv + "' tell='know' name='" + know + "' value='1' class='radclass' checked></td>";
            tab2 += "<td align='center'><input type='radio' id='c" + nv + "' tell='know' name='" + know + "' value='2' class='radclass'></td>";
        } else if (mines.sample[s].bknowtool == 2) {
            tab2 += "<td align='center' ><input type='radio' id='a" + nv + "' tell='know' name='" + know + "' value='0' class='radclass' ></td>";
            tab2 += "<td align='center' style='border-right:none;'><input type='radio' id='b" + nv + "' tell='know' name='" + know + "' value='1' class='radclass' ></td>";
            tab2 += "<td align='center'><input type='radio' id='c" + nv + "' tell='know' name='" + know + "' value='2' class='radclass' checked></td>";
        } else {
            tab2 += "<td align='center' ><input type='radio' id='a" + nv + "' tell='know' name='" + know + "' value='0' class='radclass' checked></td>";
            tab2 += "<td align='center' style='border-right:none;'><input type='radio' id='b" + nv + "' tell='know' name='" + know + "' value='1' class='radclass' ></td>";
            tab2 += "<td align='center'><input type='radio' id='c" + nv + "' tell='know' name='" + know + "' value='2' class='radclass' ></td>";
        }

        if (mines.sample[s].busetool == 2) {
            tab2 += "<td align='center' ><input type='radio' id='d" + nv + "' tell='expl' name='" + expl + "' value='0' class='radclass' ></td>";
            tab2 += "<td align='center' style='border-right:none;'><input type='radio' id='e" + nv + "' tell='expl' name='" + expl + "' value='1' class='radclass' ></td>";
            tab2 += "<td align='center'><input type='radio' id='f" + nv + "' tell='expl' name='" + expl + "' value='2' class='radclass' checked></td>";
        } else if (mines.sample[s].busetool == 1) {
            tab2 += "<td align='center' ><input type='radio' id='d" + nv + "' tell='expl' name='" + expl + "' value='0' class='radclass'></td>";
            tab2 += "<td align='center' style='border-right:none;'><input type='radio' id='e" + nv + "' tell='expl' name='" + expl + "' value='1' class='radclass' checked></td>";
            tab2 += "<td align='center'><input type='radio' id='f" + nv + "' tell='expl' name='" + expl + "' value='2' class='radclass' ></td>";
        } else {
            tab2 += "<td align='center' ><input type='radio' id='d" + nv + "' tell='expl' name='" + expl + "' value='0' class='radclass' checked></td>";
            tab2 += "<td align='center' style='border-right:none;'><input type='radio' id='e" + nv + "' tell='expl' name='" + expl + "' value='1' class='radclass' ></td>";
            tab2 += "<td align='center'><input type='radio' id='f" + nv + "' tell='expl' name='" + expl + "' value='2' class='radclass' ></td>";
        }


        if (mines.sample[s].bapptool == 2) {
            tab2 += "<td align='center' ><input type='radio' id='g" + nv + "' tell='appt' name='" + appt + "' value='0' class='radclass'></td>";
            tab2 += "<td align='center' style='border-right:none;'><input type='radio' id='h" + nv + "' tell='appt' name='" + appt + "' value='1' class='radclass' ></td>";
            tab2 += "<td align='center'><input type='radio' id='i" + nv + "' tell='appt' name='" + appt + "' value='2' class='radclass' checked></td>";
        } else if (mines.sample[s].bapptool == 1) {
            tab2 += "<td align='center' ><input type='radio' id='g" + nv + "' tell='appt' name='" + appt + "' value='0' class='radclass' ></td>";
            tab2 += "<td align='center' style='border-right:none;'><input type='radio' id='h" + nv + "' tell='appt' name='" + appt + "' value='1' class='radclass' checked></td>";
            tab2 += "<td align='center'><input type='radio' id='i" + nv + "' tell='appt' name='" + appt + "' value='2' class='radclass' ></td>";
        } else {
            tab2 += "<td align='center' ><input type='radio' id='g" + nv + "' tell='appt' name='" + appt + "' value='0' class='radclass' checked></td>";
            tab2 += "<td align='center' style='border-right:none;'><input type='radio' id='h" + nv + "' tell='appt' name='" + appt + "' value='1' class='radclass' ></td>";
            tab2 += "<td align='center'><input type='radio' id='i" + nv + "' tell='appt' name='" + appt + "' value='2' class='radclass' ></td>";
        }
        k += 1;
        tab2 += "</tr>";
    }
    tab2 += "</tbody>";
    tab2 += "</table>";

    

    // tab +="</div>";
    
    $(document).find('#dorating').html(tab2);
    $(document).find('#ratinghead').html(tab);
    
    // $(document).find('#ratingdiv').html(tab);
    // $(document).find('#ratemodal').css({ 'display': 'block', 'top': '50px', 'bottom': '50px', 'left': '127px', 'margin': '4px 4px', 'padding': '4px' });
    //return tab; 
}

function SelectRadioButton(name, value) {

    $("input[name='" + name + "'][value='" + value + "']").prop('checked', true);

    return false; // Returning false would not submit the form

}

$.fn.scrollView = function () {
    return this.each(function () {
        $('html, body').animate({
            scrollTop: $(this).offset().top
        }, 1000);
    });
}
