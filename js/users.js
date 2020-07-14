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

    $.post('php/users.php', function (some) {
        var mines = JSON.parse(some);
        var k = 1;
        var tab = '';
        tab += "<table class='table-sm table-bordered table-hover' id='tusers'>";
        tab += "<thead><tr><th colspan='5'><button class='btn btn-sm btn-success mybuts newuser' data-toggle='modal' data-target='#addmodal' style='float:right;'>Add</button></th></tr>";
        tab += "<tr><th>#</th><th>Name</th><th>Userid</th><th>Type</th><th>Option</th></tr>";
        tab += "</thead>";
        tab += "<tbody id='ubody'>";
        for (s in mines.users) {
            tab += "<tr>";
            tab += "<td align='right'>" + k + "</td>";
            tab += "<td>" + mines.users[s].fulname + "</td>";
            tab += "<td>" + mines.users[s].userid + "</td>";
            tab += "<td ut='" + mines.users[s].ucode + "'>" + mines.users[s].utype + "</td>";
            tab += "<td><a href='' class='btn btn-primary btn-xs mybuts changeperm'>Permission</a> | <a href='' class='btn btn-primary btn-xs mybuts edituser' data-toggle='modal' data-target='#addmodal'>Edit</a> | <a href='' class='btn btn-danger  btn-xs mybuts mydels'>Delete</a></td></tr>";
            k += 1;
        }
        tab += "</tbody></table>";
        $(document).find('#uselist').html(tab);
        $('#curruser').val(mines.curruser);
        $('#currlevel').val(mines.currlevel);
        $('#currfulname').val(mines.currfulname);

        var mperm = '';
        mperm += "<table id='permission' class='table table-bordered table-hover'>";
        mperm += "<thead>";
        mperm += "<tr><th colspan='2'><span id='whoisit'></span><button style='float:right' class='mybuts btn btn-sm btn-info goback'>Back</button></th></tr>"
        mperm += "<tr><th>Page</th><th>Permission</th></tr></thead>";
        mperm += "<tbody>";
        for (d in mines.perms) {
            var mts = mines.perms[d].perms == 'yes'?'checked' : '';
            mperm += "<tr rowid='"+mines.perms[d].idno+"'>";
            mperm += "<td myname='"+mines.perms[d].userid+"'>" + mines.perms[d].mypage + "</td>";
            mperm += "<td><input type='checkbox' " + mts + " class='chkperm'></td>";
            mperm += "</tr>";
        }
        mperm += "</tbody>";
        mperm += "</table>"

        $(document).find('#userperm').html(mperm);
    });

    $(document).on('click', '.changeperm', function (ev) {
        ev.preventDefault();
        if ($('#currlevel').val() != 'Ad') {
            alert("Dont show user permissions");
            return false;
        }
        var look = $(this).closest('tr').find('td:eq(2)').text();
        var person = $(this).closest('tr').find('td:eq(1)').text();
        $('#userperm').css({'display':'block'});
        $('#uselist').css({'display':'none'});
        $(document).find('#whoisit').html("Permission for "+person);
        $('#permission tbody tr').each(function(){
           if($(this).find('td:eq(0)').attr('myname') == look){
               $(this).css({'display':''});
           }else{
            $(this).css({'display':'none'});
            // $(this).closest('tr').css({'display':'none'});
           }
        });
        
    });

    $(document).on('change','.chkperm',function(ev){
       var give = $(this).prop('checked') == true?'yes':'no';
       var rowid = $(this).closest('tr').attr('rowid');

       var snd = {
           idno:rowid,
           perms:give
       }
       //alert(JSON.stringify(snd));
       $.post('php/users.php',snd,function(){});
    });

    $(document).on('click','.goback',function(ev){
        ev.preventDefault();
        $('#userperm').css({'display':'none'});
        $('#uselist').css({'display':'block'});
    });

    $(document).on('click', '.newuser', function (ev) {
        ev.preventDefault();
        $('#insert_form')[0].reset();
        $(document).find('#addmodal .modal_title').html("Add new user");
    });

    $('#insert_form').on('submit', function (ev) {
        ev.preventDefault();
        if ($('#kpass').val() != $('#kpass2').val()) {
            bootbox.alert({
                message: "Passwords not matching",
                size: 'small'
            });
            return false;
        }
        var snd = {
            newuser: $('#fulname').val(),
            userid: $('#userid').val(),
            kpass: $('#kpass').val(),
            utype: $('#utype').val()
        }
        //document.write(JSON.stringify(snd));
        $('#addmodal').modal('hide');
        $.post('php/users.php', snd, function (some) {
            var mines = JSON.parse(some);
            if (mines.error == true) {
                alert(mines.errmsg);
                return false;
            }
            var mm = $(document).find('#tusers tbody tr').length;
            var trow = $(document).find('#tusers tbody tr').eq(0);
            vf = mm + 1;
            var tab = '';
            $('#utype').val(mines.utype);
            tab += "<tr style='background-color:black;color:white;'>";
            tab += "<td align='right' >" + vf + "</td>";
            tab += "<td>" + mines.fulname + "</td>";
            tab += "<td>" + mines.userid + "</td>";
            tab += "<td ut='" + mines.utype + "'>" + $('#utype option:selected').text() + "</td>";
            tab += "<td><a href='' class='btn btn-primary btn-xs mybuts edituser' data-toggle='modal' data-target='#addmodal'>Edit</a> | <a href='' class='btn btn-danger  btn-xs mybuts mydels'>Delete</a></td></tr>";
            tab += "</tr>";

            trow.before(tab);
        });
    });

    $(document).on('click', '.edituser', function (ev) {
        ev.preventDefault();
        $('#insert_form')[0].reset();
        $('#addmodal').find('.modal_title').html("Edit user Details");
        if ($('#currlevel').val() != 'Ad') {
            $('#fulname').val($('#currfulname').val());
            $('#userid').val($('#curruser').val());
            $('#utype').val($('#currlevel').val());
            $('#formuserid').val($('#curruser').val());

        } else {
            myrow = $(this).closest('tr');
            $('#fulname').val(myrow.find('td:eq(1)').text());
            $('#userid').val(myrow.find('td:eq(2)').text());
            $('#utype').val(myrow.find('td:eq(3)').attr('ut'));
            $('#formuserid').val(myrow.find('td:eq(2)').text());
        }
        $('#fulname').prop('readonly', true);
        $('#userid').prop('readonly', true);
        $('#utype').prop('readonly', true);
    });

    $(document).on('click', '.mydels', function (ev) {
        ev.preventDefault();
        kt = $(this).closest('tr');
        var snd = {
            delete: $(this).closest('tr').find('td:eq(2)').text(),
            mytype: $(this).closest('tr').find('td:eq(3)').attr('ut')
        }
        //document.write(JSON.stringify(snd));
        $.post('php/users.php', snd, function (some) {
            //alert(some);
            var mines = JSON.parse(some);
            if (mines.error == true) {
                alert(mines.errmsg);
                return false;
            }
            kt.remove();
            alert(mines.errmsg);
        });
    });
});

// $('.control').prop('readOnly', true);