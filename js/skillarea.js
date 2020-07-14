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
    
    $.post('php/skillarea.php',function(some){
        var mines = JSON.parse(some);
        var k = 1;

        tab = '';
        tab += "<table class='table-bordered table-hover' id='skilltab'>";
        tab += "<thead>";
        tab += "<tr><th colspan='3'><input type='text' id='lookup' placeholder='search' ><button class='btn btn-sm btn-success mybuts newuser' style='float:right;' data-toggle='modal' data-target='#addmodal'>Add</button></th>";
        tab += "<tr><th>#</th><th>description</th><th>Options</th></tr>";
        tab += "</thead>";
        tab += "<tbody>";
        for(s in mines.skills){
            tab += "<tr rowid='"+mines.skills[s].skillcode+"'>";
            tab += "<td align='right'>"+k+"</td>";
            tab += "<td>"+mines.skills[s].Skilldescription+"</td>";
            tab += "<td><a href='#' class='btn btn-sm btn-success editme mybuts' data-toggle='modal' data-target='#addmodal'>Edit</a> | <a href='#' class='btn ntn-sm btn-danger mybuts btndelete'>Delete</a></td>";
            tab += "</tr>";
            k += 1;
        }
        tab += "</tbody>"
        tab += "</table>";
        $(document).find('#listing').html(tab);
        $(document).find('#perms').val(mines.perms);

        if($('#perms').val() == 'no'){
            $('#addmodal').remove();
            $('#skilltab tbody tr').each(function(){
                $(this).find('td:eq(2)').empty();
            });
        }
    });

    $(document).on('keyup','#lookup',function(ev){
        var value = $(this).val().toLowerCase();

        var numrows = $("#skilltab tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            return $(this).css('display') !== 'none';
        });
    });

    $(document).on('click','.editme',function(ev){
        $('#insert_form')[0].reset();
        ev.preventDefault();
        myrow = $(this).closest('tr');
        $('#addmodal .modal_title').html("Edit Skill Area");
        $('#skillcode').val(myrow.attr('rowid'));
        $('#skillarea').val(myrow.find('td:eq(1)').text());
    });

    $(document).on('click','.newuser',function(){
        $('#insert_form')[0].reset();
        $('#addmodal .modal_title').html("Add New Skill Area");
    });

    $(document).on('click','.btndelete',function(){

        var myrow = $(this).closest('tr');
        var snd = {
            delete:myrow.attr('rowid')
        }
        //document.write(JSON.stringify(snd));
        $.post('php/skillarea.php',snd,function(){
            myrow.remove();
        });
    });

    $('#insert_form').on('submit',function(ev){
        ev.preventDefault();
        if($('#skillcode').val() != ''){
            var snd = {
                Skilldescription:$('#skillarea').val(),
                updating:$('#skillcode').val()
            }
            //document.write(JSON.stringify(snd));
            $.post('php/skillarea.php',snd,function(some){
                var mines = JSON.parse(some);
                mrow = $('#skilltab tbody tr[rowid="'+mines.skillcode+'"]');
                mrow.find('td:eq(1)').text(mines.Skilldescription);
                mrow.css({'background-color':'blue','color':'white'});
            });
        }else{
            var snd = {
                Skilldescription:$('#skillarea').val(),
                adding:'yes'
            }
            //document.write(JSON.stringify(snd));
            $.post('php/skillarea.php',snd,function(some){
                var sk = $('#skilltab tbody tr').length+1;
                var mines = JSON.parse(some);
                var tab = '';
                tab += "<tr rowid='"+mines.skillcode+"' style='background-color:black;color:white;'>";
                tab += "<td align='right'>"+sk+"</td>";
                tab += "<td>"+mines.Skilldescription+"</td>";
                tab += "<td><a href='#' class='btn btn-sm btn-success editme mybuts'>Edit</a> | <a href='#' class='btn ntn-sm btn-danger mybuts btndelete'>Delete</a></td>";
                tab += "</tr>";

                if($('#skilltab tbody tr').length < 1){
                    $('#skilltab tbody').append(tab);
                }else{
                    nrow = $('#skilltab tbody tr').eq(0);
                    nrow.before(tab);
                }
            });
        }
        $('#insert_form')[0].reset();
        $('#addmodal').modal('hide');
    });
});

// $('.control').prop('readOnly', true);