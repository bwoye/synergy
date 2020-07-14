/*
Author Samuel Bwoye
Title Script foe creating table for all assessment types and periods;
Languge: Javascript
Date : 27-02-2019
*/

/*function to handle all finances
the div of class reportman should be appended to maindiv.
this div will hold all report details for a particular student
*/

$(document).ready(function () {

    $(document).on('click','.newpay',function(ev){
        ev.preventDefault();
        //alert("We are entering new pay");

        var snd = {
            nupay:$(document).find('#usefile').val(),
        }

        //document.write(JSON.stringify(snd));

        $.post('php/finances.php',snd,function(some){
            var mines = JSON.parse(some);
            var k = 1;
            var tab = '';

            tab += "<table class='table table-xm table-bordered table-hover' id='payments'>";
            tab += "<thead>";
            tab += "<tr><th colspan='5'><button class='btn-xs btn-primary addnu mybuts' data-toggle='modal' data-target='#nupay'>Add New</button></th></tr>";
            tab += "<tr><th>#</th><th>Date</th><th>SDF Budget</th><th>Own Budget</th><th>Options</th>";
            tab += "</tr>";
            tab += "</thead>";
            tab += "<tbody>";
            for(s in mines.payments){
                tab += "<tr rowid='"+mines.payments[s].recno+"'>";
                tab += "<td align='right'>"+k+"</td>";
                tab += "<td align='center'>"+mines.payments[s].datepaid+"</td>";
                tab += "<td align='right'>"+mines.payments[s].amount+"</td>";
                tab += "<td align='right'>"+mines.payments[s].ownamount+"</td>";
                tab += "<td><span class='editfinance'><a href='#' class='btn btn-xs btn-success mybuts' data-toggle='modal' data-target='#nupay'>Edit</a></span>";
                tab += " | <span class='delfinance'><a href='#' class='btn btn-xs btn-danger mybuts'>Delete</a></span></td>";
                tab += "</tr>";
                k +=  1;
            }
            tab += "</tbody>";
            tab += "</table>";

            $(document).find('#listing').html(tab);
        });
    });

    $(document).on('click','.addnu',function(ev){
        ev.preventDefault();
        $(document).find('#filenopay').val($(document).find('#usefile').val());
        $(document).find('#nupay .modal_title').html("Add New Payment");
    });

    $(document).on('submit','#nupay_form',function(ev){
        ev.preventDefault();
        if($(document).find('#editxpay').val()==''){
            //alert("We are entring new Payment");
            var snd = {
                amount:$(document).find('#nuamount').val(),
                ownamount:$(document).find('#ownamount').val(),
                datepaid:$(document).find('#nupaydate').val(),
                nupayfile:$(document).find('#filenopay').val()                
            }
            $('#nupay_form')[0].reset();
            $('#nupay').modal('hide');
            //document.write(JSON.stringify(snd));
            $.post('php/finances.php',snd,function(some){
                var mines = JSON.parse(some);
                var trow = $('#payments tbody tr').eq(0);
                var tab = '';
                tab += "<tr rowid='"+mines.recno+"' style='background-color:black;color:white;'>";
                tab += "<td align='right'>"+mines.counts+"</td>";
                tab += "<td align='center'>"+mines.datepaid+"</td>";
                tab += "<td align='right'>"+mines.amount+"</td>";
                tab += "<td align='right'>"+mines.ownamount+"</td>";
                tab += "<td><span class='editfinance'><a href='#' class='btn btn-xs btn-success mybuts' data-toggle='modal' data-target='#nupay'>Edit</a></span>";
                tab += " | <span class='delfinance'><a href='#' class='btn btn-xs btn-danger mybuts'>Delete</a></span></td>";
                tab += "</tr>";

                if($('#payments tbody tr').length == 0){
                    $('#payments tbody').append(tab);
                }else{
                    trow.before(tab);
                }
            });
            
        }else{
            alert("We are editing existing payment");
        }
    });

    $(document).on('submit', '#pay_insert_form', function (ev) {
        ev.preventDefault();

        var snd = {
            fileno: $(document).find('#filenox').val(),
            ApprovedbudgetMlnUgx: $(document).find('#ApprovedbudgetMlnUgx').val(),
            GranteecontrbnMlnUgx: $(document).find('#GranteecontrbnMlnUgx').val(),
            startdate: $(document).find('#startdate').val(),
            duration: $(document).find('#duration').val(),
            dform: $(document).find('#dform').val(),
            mydform: $(document).find('#dform option:selected').text()
        }


        $('#pay_insert_form')[0].reset();
        $('#paymodal').modal('hide');

        $.post('php/finances.php', snd, function (some) {
            var mines = JSON.parse(some);

            //update hidden boxes

            $(document).find('#approvxin').val(mines.ApprovedbudgetMlnUgx);
            $(document).find('#startxin').val(mines.startdate);
            $(document).find('#contrxin').val(mines.GranteecontrbnMlnUgx);
            $(document).find('#durationxin').val(mines.duration);
            $(document).find('#duetype').val(mines.dform);

            $('#approvx').html("<strong>SDF Budget</strong> " + numberWithCommas(mines.ApprovedbudgetMlnUgx));
            $('#contrx').html("<strong>Own contribution</strong> " + numberWithCommas(mines.GranteecontrbnMlnUgx));
            $('#startx').html("<strong>Start</strong> " + mines.startdate);
            $('#endx').html("<strong>Duration </strong>" + mines.duration + " " + mines.mydform);


            function numberWithCommas(number) {
                var parts = number.toString().split(".");
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return parts.join(".");
            }
        });


        // start her man
        $(document).find('#allrecsdiv').html('');
        $.post('php/details.php', function (some) {
            var mines = JSON.parse(some);

            var tab = '';
            tab += "<table class='table-sm table-hover table-bordered' id='recstable'>";
            tab += "<thead>";
            tab += "<tr>";
            tab += "<th>File No</th><th>Entity</th><th class='dontshow'>Type</th>";
            tab += "</tr>";
            tab += "</thead>";
            tab += "<tbody id='mybody'>";
            for (s in mines.ents) {
                tab += "<tr>";
                tab += "<td>" + mines.ents[s].fileno + "</td>";
                tab += "<td>" + mines.ents[s].Granteename + "</td>";
                tab += "<td class='dontshow'>" + mines.ents[s].Entitytype + "</td>";
                tab += "<td class='dontshow'>" + mines.ents[s].Primarycontactphone + "</td>";
                tab += "<td class='dontshow'>" + mines.ents[s].Contactperson + "</td>";
                tab += "<td class='dontshow'>" + mines.ents[s].Districtcode + "</td>";
                tab += "<td class='dontshow'>" + mines.ents[s].Sectorcode + "</td>";
                tab += "<td class='dontshow'>" + mines.ents[s].window + "</td>";
                tab += "<td class='dontshow'>" + mines.ents[s].address + "</td>";
                tab += "<td class='dontshow'>" + mines.ents[s].ApprovedbudgetMlnUgx + "</td>";
                tab += "<td class='dontshow'>" + mines.ents[s].GranteecontrbnMlnUgx + "</td>";
                tab += "<td class='dontshow'>" + mines.ents[s].startdate + "</td>";
                tab += "<td class='dontshow'>" + mines.ents[s].enddate + "</td>";
                tab += "<td class='dontshow'>" + mines.ents[s].duration + "</td>";
                tab += "<td class='dontshow'>" + mines.ents[s].dform + "</td>";
                tab += "<td class='dontshow'>" + mines.ents[s].duform + "</td>";

                tab += "</tr>";
            }
            tab += "</tbody>";
            tab += "</table>";

            $(document).find('#allrecsdiv').html(tab);
            $(document).find('.dontshow').hide();
            function numberWithCommas(number) {
                var parts = number.toString().split(".");
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return parts.join(".");
            }

        });

        // stops here mane

    });

    $(document).on('click','.delfinance',function(ev){
        ev.preventDefault();
        alert("We are deleting finances");
    });


    $(document).on('click','editfinance',function(ev){
        ev.preventDefault();
        var myrow = $(this).closest('tr');
        var id = myrow.attr('rowid');
        alert("We are editing payments");
    });

    var timer, closingtime = 2000, mdal = $('#paymodal');

    mdal.on('hide.bs.modal', function (e) {
        if (timer) {
            timer = false;
        } else {
            e.preventDefault();
            timer = true;
            mdal.animate({
                opacity: 0
            }, closingtime - 150, function () {
                mdal.modal('hide');
            })
        }
    })
        .on('hidden.bs.modal', function () {
            mdal.css({
                opacity: 1
            })
        })
});