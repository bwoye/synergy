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

    $.post('php/district.php', function (some) {
        //alert(some);
        var mines = JSON.parse(some);
        var tab = '';
        var k = 1;
        tab += "<table class='table table-bordered table-hover table-sm' id='distable'>";
        tab += "<thead>";
        tab += "<tr><th colspan='4'><span id='recount'></span> | <input type='text' id='lookup' placeholder='Search'> <button style='float:right' class='btn btn-sm btn-primary mybuts btndadd' data-toggle='modal' data-target='#addmodal'>Add District</button></th></tr>";
        tab += "<tr><th>#</th><th>Name</th><th>Region</th><th>Options</th></tr>";
        tab += "</thead>";
        tab += "<tbody>";
  
        for (s in mines.districts) {
            $('#region').val(mines.districts[s].Region);
            tab += "<tr rowid='" + mines.districts[s].Districtcode + "'>";
            tab += "<td align='right'>" + k + "</td>";
            tab += "<td>" + mines.districts[s].Districtname + "</td>";
            tab += "<td gval='" + mines.districts[s].Region+ "'>" + mines.districts[s].description + "</td>";
            tab += "<td><a href='#' class='btn btn-primary btn-sm editbut mybuts' data-toggle='modal' "; 
            tab += "data-target='#addmodal'>Edit</a> | <a href='#' class='btn btn-danger btn-sm delbut mybuts'>Delete</a></td>";
            tab += "</tr>";
            k += 1;
        }
        tab == "</tbody>";
        tab += "</table>";
        $(document).find('#listing').html(tab);
        var counts = $(document).find('#distable tbody tr').length;
        $(document).find('#recount').html("<strong>District count: </strong>" + counts);
        $(document).find('#perms').val(mines.perms);

        if($('#perms').val() == 'no'){
            $('#addmodal').remove();

            $('#distable tbody tr').each(function(){
                $(this).find('td:eq(3)').empty();
            });
        }
    });

    $(document).on('keyup','#lookup',function(ev){
        var value = $(this).val().toLowerCase();

        var numrows = $("#distable tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            return $(this).css('display') !== 'none';
        });
    });

    $(document).on('click', '.btndadd', function (ev) {
        $('#insert_form')[0].reset();
        ev.preventDefault();
        $('#addmodal .modal_title').html("Add New District");
    });
    $(document).on('click', '.editbut', function (ev) {
        $('#insert_form')[0].reset();
        ev.preventDefault();
        $('#addmodal .modal_title').html("Edit District");
        var myrow = $(this).closest('tr');
        $('#distname').val(myrow.find('td:eq(1)').text());
        $('#region').val(myrow.find('td:eq(2)').attr('gval'));
        $('#distcode').val(myrow.attr('rowid'));

    });

    $(document).on('click','.delbut',function(ev){
        ev.preventDefault();
        var mr = $(this).closest('tr');
        var snd = {
            delete:$(this).closest('tr').attr('rowid')
        }
        
        $.post('php/district.php',snd,function(some){
            var mines = JSON.parse(some);
            mr.remove();
            $(document).find('#recount').html("<strong>District count: </strong>" + mines.counts);
        });
    });

    $('#insert_form').on('submit', function (ev) {
        ev.preventDefault();

        if ($('#distcode').val() != '') {
            var snd = {
                Districtname: $('#distname').val(),
                Districtcode: $('#distcode').val(),
                region: $('#region').val(),
                editpal: 'yes'
            }

            $.post('php/district.php', snd, function (some) {
                var mines = JSON.parse(some);
                var ele = $('#distable tbody tr[rowid="' + mines.Districtcode + '"]');
                $('#region').val(mines.region);
                ele.find('td:eq(1)').text(mines.Districtname);
                ele.find('td:eq(2)').attr('gval', mines.region);
                ele.find('td:eq(2)').text($('#region option:selected').text());
                ele.css({ 'background-color': 'blue', 'color': 'white' });
            });
        } else {
            var snd = {
                Districtname: $('#distname').val(),
                region: $('#region').val(),
                adding: 'yes'
            }
            //document.write(JSON.stringify(snd));
            $.post('php/district.php', snd, function (some) {
                var mines = JSON.parse();
                $('#region').val(mines.region);
                var k = $('#distable tbody tr').lenth + 1;
                tab += "<tr rowid='" + mines.Districtcode + "' style='background-color:black;color:white'>";
                tab += "<td align='right'>" + k + "</td>";
                tab += "<td>" + mines.Districtname + "</td>";
                tab += "<td gval='" + mines.region + "'>" + $('#region option:selected').text() + "</td>";
                tab += "<td><a href='#' class='btn btn-primary btn-sm editbut mybuts' data-toggle='modal' "; 
                tab +="data-target='#addmodal'>Edit</a> | <a href='#' class='btn btn-danger btn-sm delbut mybuts'>Delete</a></td>";
                tab += "</tr>"
                tab += "</tr>";

                if($('#distable tbody tr').length < 1){
                    $('#distable tbody tr').append(tab);
                }else{
                    $jk = $('#distable tbody tr').eq(0);
                    jkk.before(tab);
                }

                $(document).find('#recount').html("<strong>District count: </strong>" + mines.counts);
            });
        }
    });
});
//$('#addmodal').modal('hide');
// $('.control').prop('readOnly', true);