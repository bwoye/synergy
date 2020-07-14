/*
Author Samuel Bwoye
Title Script foe creating table for all assessment types and periods;
Languge: Javascript
Date : 27-05-2019


function to handle all finances
the div of class reportman should be appended to maindiv.
this div will hold all report details for a particular Grantee
*/

$(document).ready(function () {
    $('#mymenus').load('menus.html');
    var payments;
    const inputPart = document.querySelector('#findit');
    findPanel = document.querySelector('.suggestions');

    $.post('php/budget.php', function (some) {
        var mines = JSON.parse(some);
        payments = mines.entities;
        var tab = '';
        tab += "<table id='badone' class='table table-hover'>";
        tab += "<thead>";
        tab += "<tr><th>File</th><th>Name</th><th>Budget</th><th>Own Budget</th><th>Start Date</th><th>End date</th></th>";
        tab += "</thead>";
        tab += "<tbody>";
        for (s in mines.entities) {
            tab += "<tr>";
            tab += "<td>" + mines.entities[s].fileno + "</td>";
            tab += "<td>" + mines.entities[s].Granteename + "</td>";
            tab += "<td>" + mines.entities[s].Appbudget + "</td>";
            tab += "<td>" + mines.entities[s].grcont + "</td>";
            tab += "<td>" + mines.entities[s].startdate + "</td>";
            tab += "<td>" + mines.entities[s].enddate + "</td>";
            tab += "</tr>";
        }
        tab += "</tbody>";
        tab += "</table>";

        $(document).find('#allrecsdiv').html(tab);       
    });

    inputPart.addEventListener('keyup',function(){
        var input = inputPart.value.toLowerCase();
        findPanel.innerHTML = '';

        const suggestion = payments.filter(function(suggested){
            return suggested.Granteename.toLowerCase().startsWith(input);
        });

        suggestion.forEach(function(company){
            const div = document.createElement('div');
            div.append(company.Granteename);

            findPanel.appendChild(div);

            div.addEventListener('click',function(){
                fillme(company);
                findPanel.innerHTML='';
            });

            if(inputPart.value==''){
                findPanel.innerHTML='';
            }
        })
    })

    function fillme(fg) {
 
        $(document).find('#filehide').val(fg.fileno);
        $(document).find('#amthidden').val(fg.Appbudget);
        $(document).find('#ownhidden').val(fg.grcont);

        $(document).find('#divfileno').html("<strong>File</strong> " + fg.fileno);
        $(document).find('#diventity').html("<strong>Entity</strong> " + fg.Granteename);
        $(document).find('#divapp').html("<strong>Budget</strong> " + numberWithCommas(fg.Appbudget));
        $(document).find('#divown').html("<strong>Own Budget</strong> " + numberWithCommas(fg.grcont));
        $(document).find('#divstart').html("<strong>Start date</strong> " + returndate(fg.startdate)+' '+returnWeekday(fg.startdate));
        $(document).find('#divend').html("<strong>End Date</strong> " + returndate(fg.enddate)+' '+returnWeekday(fg.enddate));
     
        
        $.post('php/budget.php', { fileno: $('#filehide').val() }, function (some) {
            var k = 1;
            var mines = JSON.parse(some);
            var tab = '';
            tab += "<table class='table table-bordered table-hover' id='mypays'>";
            tab += "<thead>";
            tab += "<tr><th colspan='5'><button class='btn btn-xs btn-primary newrec mybuts' data-toggle='modal' data-target='#addmodal'>Add Payment</button>";
            tab += "<tr><th>#</th><th>Date</th><th>Approved</th><th>Own budget</th><th>Options</th></tr>";
            tab += "<tbody>";
            for (s in mines.payments) {
                tab += "<tr rowid=" + mines.payments[s].recno + ">";
                tab += "<td align='right'>" + k + "</td>";
                tab += "<td align='right' gval='" + mines.payments[s].datepaid + "'>" + returndate(mines.payments[s].datepaid) + "</td>";
                tab += "<td align='right' gval='" + mines.payments[s].amount + "'>" + numberWithCommas(mines.payments[s].amount) + "</td>";
                tab += "<td align='right' gval='" + mines.payments[s].ownamount + "'>" + numberWithCommas(mines.payments[s].ownamount) + "</td>";
                tab += "<td><span class='pedit'><a href='#' class='btn btn-xs mybuts btn-primary' data-toggle='modal' data-target='#addmodal'>Edit</a></span> | <span class='pdelete'><a href='#' class='btn btn-xs mybuts btn-danger'>Delete</a></td>";
                tab += "</tr>";
                k += 1;
            }
            tab += "</tbody>";
            tab += "</thead>";
            tab += "</table>";
            
            $(document).find('#listing').html(tab);

            //Finding totoals and balances
            var appbal = $(document).find('#amthidden').val();
            var ownbal = $(document).find('#ownhidden').val();
            $('#mypays tbody tr').each(function () {
                appbal -= $(this).find('td:eq(2)').attr('gval');
                ownbal -= $(this).find('td:eq(3)').attr('gval');
            });

            var ltots = '';
            ltots += "<tr rowid='tots' class='baltot'>";
            ltots += "<td></td><td  align='right'><strong>Balance</td>";
            ltots += "<td align='right'>" + numberWithCommas(appbal) + "</td>";
            ltots += "<td align='right'>" + numberWithCommas(ownbal) + "</td>";
            ltots += "<td colspan='2'</td></tr>";

            $('#mypays tbody').append(ltots);

            $(document).find('#perms').val(mines.perms);

            if($('#perms').val() == 'no'){
                $('#addmodal').remove();
    
                $('#mypays tbody tr').each(function(){
                    $(this).find('td:eq(4)').empty();
                });
            }
    
        });
    }    

    $(document).on('click', '.pdelete', function () {
        var myrow = $(this).closest('tr');
        var fg = myrow.attr('rowid');
        myrow.remove();
        $('#mypays tbody tr[rowid=tots').remove();

        var k = 1;
        $('#mypays tbody tr').each(function(){
            $(this).find('td:eq(0)').text(k);
            k += 1;
        });

        $.post('php/entities.php', { mydel:fg}, function (some) {

        });     

        var appbal = $(document).find('#amthidden').val();
        var ownbal = $(document).find('#ownhidden').val();
        $('#mypays tbody tr').each(function () {
            appbal -= $(this).find('td:eq(2)').attr('gval');
            ownbal -= $(this).find('td:eq(3)').attr('gval');
        });

        var ltots = '';
        ltots += "<tr rowid='tots' class='baltot'>";
        ltots += "<td></td><td align='right'><strong>Balance</strong></td>";
        ltots += "<td align='right'>" + numberWithCommas(appbal) + "</td>";
        ltots += "<td align='right'>" + numberWithCommas(ownbal) + "</td>";
        ltots += "<td colspan='2'</td></tr>";
        $('#mypays tbody').append(ltots);
    });

    $('#insert_form').on('submit', function (ev) {
        ev.preventDefault();

        if ($(document).find('#txtrecno').val() == '') {
     
            var mydate = new Date();
            var said = Date.parse($('#txtdate').val());

            if(said > mydate){
                bootbox.alert({
                    message:"Invalid Date",
                    size: 'small'
                });
                return false;
            }
            var snd = {
                fileno: $(document).find('#filehide').val(),
                adding:'yes',
                amount: $(document).find('#txtamount').val(),
                datepaid: $(document).find('#txtdate').val(),
                ownamount: $(document).find('#txtownamount').val(),
            }

            $('#mypays tbody tr[rowid=tots').remove();
  
            $.post('php/budget.php', snd, function (some) {
       
                var mines = JSON.parse(some);                

                var pp = $('#mypays tbody tr').length;
                
                var tab = '';
                tab += "<tr rowid='" + mines.recno + "' style='background-color:black;color:white'>";
                tab += "<td align='right'></td>";
                tab += "<td align='right' gval='" + mines.datepaid + "'>" + returndate(mines.datepaid) + "</td>";
                tab += "<td align='right' gval='" + mines.amount + "'>" + numberWithCommas(mines.amount) + "</td>";
                tab += "<td align='right' gval='" + mines.ownamount + "'>" + numberWithCommas(mines.ownamount) + "</td>";
                tab += "<td><span class='pedit'><a href='#' class='btn btn-xs mybuts btn-primary' data-toggle='modal' data-target='#addmodal'>Edit</a></span> | <span class='pdelete'><a href='#' class='btn btn-xs mybuts btn-danger'>Delete</a></td>";
                tab += "</tr>";

                

                if (pp < 1) {
                    $('#mypays tbody').append(tab);
                } else {
                    var trow = $('#mypays tbody tr').eq(0);
                    trow.before(tab);
                }

                var k = 1;
                $('#mypays tbody tr').each(function(){
                    $(this).find('td:eq(0)').text(k);
                    k += 1;
                });

                var appbal = $(document).find('#amthidden').val();
                var ownbal = $(document).find('#ownhidden').val();
                $('#mypays tbody tr').each(function () {
        
                    appbal -= $(this).find('td:eq(2)').attr('gval');
                    ownbal -= $(this).find('td:eq(3)').attr('gval');
                });

                var ltots = '';
                ltots += "<tr rowid='tots' class='baltot'>";
                ltots += "<td></td><td  align='right'><strong>Balance</strong></td>";
                ltots += "<td align='right'>" + numberWithCommas(appbal) + "</td>";
                ltots += "<td align='right'>" + numberWithCommas(ownbal) + "</td>";
                ltots += "<td colspan='2'</td></tr>";

                $('#mypays tbody').append(ltots);
            });

        } else {
            $(document).find('#mypays tbody tr[rowid=tots]').remove();
      
            var mydate = new Date();
            var said = Date.parse($('#txtdate').val());

            if(said > mydate){
                bootbox.alert({
                    message:"Invalid Date",
                    size: 'small'
                });
                return false;
            }
            var snd = {
                recno: $(document).find('#txtrecno').val(),
                editing: 'yes',
                amount: $(document).find('#txtamount').val(),
                datepaid: $(document).find('#txtdate').val(),
                ownamount: $(document).find('#txtownamount').val(),
            }
      
            $.post('php/budget.php', snd, function (some) {
                var mines = JSON.parse(some);
                var ele = $("#mypays tr[rowid='" + mines.recno + "']");
                ele.find('td').eq(1).attr('gval', mines.datepaid);
                ele.find('td').eq(1).text(returndate(mines.datepaid));
                ele.find('td').eq(2).attr('gval', mines.amount);
                ele.find('td').eq(2).text(numberWithCommas(mines.amount));
                ele.find('td').eq(3).attr('gval', mines.ownamount);
                ele.find('td').eq(3).text(numberWithCommas(mines.ownamount));
                ele.css({ 'background-color': 'blue', 'color': 'white' });               

                var appbal = $(document).find('#amthidden').val();
                var ownbal = $(document).find('#ownhidden').val();
                $('#mypays tbody tr').each(function () {
                    appbal -= $(this).find('td:eq(2)').attr('gval');
                    ownbal -= $(this).find('td:eq(3)').attr('gval');
                });

                var ltots = '';
                ltots += "<tr rowid='tots' class='baltot'>";
                ltots += "<td></td><td  align='right'><strong>Balance</strong></td>";
                ltots += "<td align='right'>" + numberWithCommas(appbal) + "</td>";
                ltots += "<td align='right'>" + numberWithCommas(ownbal) + "</td>";
                ltots += "<td colspan='2'</td></tr>";

                $('#mypays tbody').append(ltots);
            });
        }
        $('#insert_form')[0].reset();
        $('#addmodal').modal('hide');
    });

    $(document).on('keyup','#findit',function(ev){
        ev.preventDefault();    
        value = $(this).val().toLowerCase().trim();

        $('#badone tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            return ($(this).css('display') != 'none')
        });
    });

    $(document).on('click', '.pedit', function (ev) {
        ev.preventDefault();
        var myid = $(this).closest('tr').attr('rowid');

        $('#addmodal .modal_title').html("Editing Payment");
        var ele = $("#mypays tbody tr[rowid='" + myid + "']");
        $(document).find('#txtdate').val(ele.find('td').eq(1).attr('gval'));
        $(document).find('#txtamount').val(ele.find('td').eq(2).attr('gval'));
        $(document).find('#txtownamount').val(ele.find('td').eq(3).attr('gval'));
        $(document).find('#txtrecno').val(myid);
    });
    $(document).on('click', '.newrec', function (ev) {
        ev.preventDefault();
        $(document).find('#txtrecno').val('');
        $('#insert_form')[0].reset();
        $('#addmodal .modal_title').html("Adding Payment");
        var mydate = new Date().toISOString().toString().substr(0,10);
        document.querySelector('#txtdate').value =mydate;
    });
});

function returndate(sm) {
    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var x = new Date(sm);
    var p = x.getDate() + "-" + months[x.getMonth()] + "-" + x.getFullYear();
    return p;
}

function returnWeekday(df) {
    var g = new Date(df);
    var weekdays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
    return weekdays[g.getDay()];
}

function numberWithCommas(number) {
    var parts = number.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}
