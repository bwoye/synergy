/*
Author Samuel Bwoye
Title Script for listing enroolment of a particular entity
Desgigned for Business Synergies
Languge: Javascript
Date : 27-05-2019
*/


$(document).ready(function () {
    $('#mymenus').load("menus.html");
    const inperson = document.querySelector('#findit');
    const emppanel = document.querySelector('.empdiv');
    var allGrantees;
    $.post('php/enrols.php', function (some) {
        //alert(some);
        var mines = JSON.parse(some);
        allGrantees = mines.ents;
        for (kk in mines.skills) {
            //alert("The term is "+mines.terms[kk].termname);
            $('#formskillarea').append("<option value='" + mines.skills[kk].Skillcode + "'>" + mines.skills[kk].Skilldescription + "</option>");
        }

        $(document).find('#perms').val(mines.perms);
    });

    inperson.addEventListener('keyup', function () {
        const input = inperson.value.toLowerCase();
        emppanel.innerHTML = '';

        const grantees = allGrantees.filter(function (employer) {
            return employer.Granteename.toLowerCase().startsWith(input);
        });

        grantees.forEach(function (grantee) {
            const div = document.createElement('div');
            div.append(grantee.Granteename);
            emppanel.appendChild(div);

            div.addEventListener('click', function () {
                fillmeup(grantee);
                emppanel.innerHTML = '';
            });
        });

        if (input == '') {
            emppanel.innerHTML = '';
        }
    });

    $(document).on('keyup', '#skillfind', function (ev) {

        //ev.preventDefault();
        var tt = 0;
        value = $(this).val().toLowerCase().trim();
        $('#myskills tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            return $(this).css('display') !== 'none';
        });
        var tt = 1;
        $('#myskills tbody tr').each(function () {
            tt += 1;
            $(this).find('td:eq(0)').text(tt);
        });

    });

    function fillmeup(ev) {

        $(document).find('#listing').html('');
        $(document).find('#cfile').html("<strong>File: </strong>" + ev.fileno);
        $(document).find('#cname').html("<strong>Entity: </strong>" + ev.Granteename);
        $(document).find('#cdist').html("<strong>District: </strong>" + ev.Districtname);
        $(document).find('#filehidden').val(ev.fileno);
        var snd = {
            enrol: $(document).find('#filehidden').val(),
        }
        //document.write(JSON.stringify(snd));
        $.post('php/enrols.php', snd, function (some) {
            var mines = JSON.parse(some);
            var k = 1;
            var tab = '';
            tab += "<table class='table table-hover table-bordered' id='myskills'>";
            tab += "<thead>";
            tab += "<tr><th colspan='3'><span class='recno'></span> | <span class='gender'></span> | <button class='btn btn-sm addrec btn-success mybuts' data-toggle='modal' data-target='#addmodal'>Add Record</button></th><th colspan='3'><input type='text' placeholder='search' style='float:left' id= 'skillfind'><button class='btn btn-primary mybuts addpass' style='float:right' data-toggle='modal' data-target='#passmodal'>Assign Password</button></th></tr>";
            tab += "<tr><th>#</th><th>Name</th><th>Skill Area</th><th>Gender</th><th>Contact</th><th>Options</th></tr>";
            tab += "</thead>";
            tab += "<tbody>";
            for (s in mines.mbrs) {
                tab += "<tr rowid='" + mines.mbrs[s].recno + "'>";
                tab += "<td align='right'>" + k + "</td>";
                tab += "<td gval='" + mines.mbrs[s].mact + "'>" + mines.mbrs[s].fulname + "</td>";
                tab += "<td gval='" + mines.mbrs[s].Skillcode + "'>" + mines.mbrs[s].Skillarea + "</td>";
                tab += "<td gval='" + mines.mbrs[s].Gender + "'>" + mines.mbrs[s].Gender + "</td>";
                tab += "<td>" + mines.mbrs[s].Phonecontact + "</td>";
                tab += "<td><span class='pedit'><a href='#' class='btn btn-sm  mybuts btn-primary' data-toggle='modal' data-target='#addmodal'>Edit</a></span> | <span class='pdelete'><a href='#' class='btn btn-sm mybuts btn-danger'>Delete</a></span></td>";
                tab += "</tr>";
                k += 1;
            }
            tab += "</tbody></table>";

            $(document).find('#listing').html(tab);
            $(document).find('.recno').html("Record Count: " + $(document).find('#myskills tbody tr').length);
            $(document).find('#perms').val(mines.perms);
            if ($('#perms').val() == 'no') {
                $(document).find('#addmodal').remove();
                $(document).find('#passmodal').remove();
                $('#myskills tbody tr').each(function () {
                    $(this).find('td:eq(5)').empty();
                });
            }

            countUs(mines);
        });
    }

    $(document).on('click', '.addpass', function (ev) {
        ev.preventDefault();
        $('#password_form')[0].reset();
        $('#passmodal .modal_title').html("Assign Password");
    });

    $('#password_form').on('submit', function (ev) {
        ev.preventDefault();
        var snd = {
            fileno: $('#filehidden').val(),
            emppass: $('#newpass').val()
        }
        $('#passmodal').modal('hide');
        $.post('php/enrols.php', snd, function (some) {
            var mines = JSON.parse(some);
            if (mines.error) {
                bootbox.alert({
                    message: mines.errmsg,
                    size: 'small'
                });
                return false;
            }

            bootbox.alert({
                message: mines.errmsg,
                size: 'small'
            });
        });
    });

    $(document).on('click', '.pedit', function (ev) {
        ev.preventDefault();
        $('#insert_form')[0].reset();
        $('#addmodal .modal_title').html("Editing Member");
        myrow = $(this).closest('tr');
        $('#formfulname').val(myrow.find('td:eq(1)').text());
        $('#formskillarea').val(myrow.find('td:eq(2)').attr('gval'));
        $('#formgender').val(myrow.find('td:eq(3)').attr('gval'));
        $('#formstatus').val(myrow.find('td:eq(1)').attr('gval'));
        $('#formphone').val(myrow.find('td:eq(4)').text());
        $('#formlegendx').val(myrow.attr('rowid'));
        $('#formfile').val('');
    });

    $(document).on('click', '.addrec', function (ev) {
        ev.preventDefault();
        $('#insert_form')[0].reset();
        $('#formfile').val($('#filehidden').val());
    });

    $('#insert_form').on('submit', function (ev) {
        ev.preventDefault();
        if ($('#formfile').val() == '') {
            var snd = {
                fulname: $('#formfulname').val(),
                Skillcode: $('#formskillarea').val(),
                Gender: $('#formgender').val(),
                mact: $('#formstatus').val(),
                Phonecontact: $('#formphone').val(),
                editing: $('#formlegendx').val(),
                skilling: $('#formskillarea option:selected').text()
            }
            //document.write(JSON.stringify(snd));
            $.post('php/enrols.php', snd, function (some) {
                var mines = JSON.parse(some);

                var ele = $("#myskills tbody tr[rowid='" + mines.recno + "']");
                ele.find('td:eq(1)').attr('gval', mines.mact);
                ele.find('td:eq(1)').text(mines.fulname);
                ele.find('td:eq(2)').attr('gval', mines.Skillcode);
                ele.find('td:eq(2)').text(mines.skilling);
                ele.find('td:eq(3)').attr('gval', mines.Gender);
                ele.find('td:eq(3)').text(mines.Gender);
                ele.find('td:eq(4)').text(mines.Phonecontact);
                ele.css({ 'background-color': 'blue', 'color': 'white' });


                countUs(mines);
            });
        } else {
            var snd = {
                adding: $('#formfile').val(),
                fulname: $('#formfulname').val(),
                Skillcode: $('#formskillarea').val(),
                skilling: $('#formskillarea option:selected').text(),
                Gender: $('#formgender').val(),
                mact: $('#formstatus').val(),
                Phonecontact: $('#formphone').val()
            }

            $.post('php/enrols.php', snd, function (some) {
                var mines = JSON.parse(some);
                var tab = '';
                tab += "<tr rowid='" + mines.recno + "' style='background-color:black;color:white;'>";
                tab += "<td align='right'>" + mines.counts + "</td>";
                tab += "<td gval='" + mines.mact + "'>" + mines.fulname + "</td>";
                tab += "<td gval='" + mines.Skillcode + "'>" + mines.skilling + "</td>";
                tab += "<td gval='" + mines.Gender + "'>" + mines.Gender + "</td>";
                tab += "<td>" + mines.Phonecontact + "</td>";
                tab += "<td><span class='pedit'><a href='#' class='btn btn-sm  mybuts btn-primary' data-toggle='modal' data-target='#addmodal'>Edit</a></span> | <span class='pdelete'><a href='#' class='btn btn-sm mybuts btn-danger'>Delete</a></span>";
                tab += "</tr>";

                var pp = $('#myskills tbody tr').length;
                if (pp < 1) {
                    $('#myskills tbody').append(tab);
                } else {
                    trow = $('#myskills tbody tr').eq(0);
                    trow.before(tab);
                }


                countUs(mines);

            });
        }
        $('#addmodal').modal('hide');
        $(document).find('.recno').html("Record Count: " + $(document).find('#myskills tbody tr').length);
    });
});

console.log(allGrantees);

function numberWithCommas(number) {
    var parts = number.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

function countUs(mines) {
    var m = 0;
    var f = 0;

    $(document).find('#myskills tbody tr').each(function () {
        if ($(this).find('td:eq(3)').attr('gval') == 'M') {
            m += 1;
        } else {
            f += 1;
        }
    });

    $(document).find('.gender').html("Females :" + f + " Males: " + m);


    $('#completed').html("<strong>Females :" + numberWithCommas(mines.co['F']) + " Males: " + numberWithCommas(mines.co['M']) + "</strong>");
    $('#training').html("<strong>Females :" + numberWithCommas(mines.tr['F']) + " Males: " + numberWithCommas(mines.tr['M']) + "</strong>");
    $('#droped').html("<strong>Females :" + numberWithCommas(mines.dr['F']) + " Males: " + numberWithCommas(mines.dr['M']) + "</strong>");
}

