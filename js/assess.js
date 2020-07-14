/*
Author Samuel Bwoye
Title Script for creating reports and setting page paginations
Languge: Javascript
Date : 27-02-2019
*/

/*function to create all the divs 
the div of class reportman should be appended to maindiv.
this div will hold all report details for a particular Entity and 
*/


$(document).ready(function () {
    $('#mymenus').load("menus.html");

    $('#samples').append("<option value='nsline' selected>Make Selection</option>");
    $('#samples').append("<option value='selbline'>Baseline</option>");
    $('#samples').append("<option value='selmline'>Mid Term</option>");
    $('#samples').append("<option value='seleline'>End Term</option>");


    const inputFind = document.querySelector('#findit');
    const findPanel = document.querySelector('.thefinds');

    var myents;

    $.post('php/assess.php', function (some) {       
        var mines = JSON.parse(some);
        myents = mines.entities;

        for (kk in mines.skills) {
            $('#formskillarea').append("<option value='" + mines.skills[kk].skillcode + "'>" + mines.skills[kk].Skilldescription + "</option>");
        }

        /*var tab = '';
        tab += "<table class='table table-hover table bordered' id='allfirms'>";
        tab += "<thead>";
        tab += "<tr>";
        tab += "<th>Fileno</th><th>Name</th>";
        tab += "<th>District</th></tr>";
        tab += "</thead>";
        tab += "<tbody id='xfind'>";
        for (s in mines.entities) {
            tab += "<tr><td>" + mines.entities[s].fileno + "</td><td>" + mines.entities[s].Granteename + "</td>";
            tab += "<td>" + mines.entities[s].Districtname + "</td></tr>";
        }
        tab += "</tbody>";
        tab += "</table>";

        $(document).find('.allrecs').html(tab);*/

        $(document).find('#perms').val(mines.perms);
        if ($('#perms').val() == 'no') {

        }

    });

    inputFind.addEventListener('keyup',function(){
        var input = inputFind.value.toLowerCase();
        findPanel.innerHTML='';

        const findings = myents.filter(function(grantee){
            return grantee.Granteename.toLowerCase().startsWith(input);
        });

        findings.forEach(function(company){
            const div = document.createElement('div');
            div.append(company.Granteename);
            findPanel.appendChild(div);

            div.addEventListener('click',function(){
                fillme(company);
                findPanel.innerHTML = '';
            })           
        })

        if(input == ''){
            findPanel.innerHTML = '';
        }
    });

    // $(document).on('click', '#findit', function (ev) {
    //     ev.preventDefault();
    //     $(document).find('.allrecs').css({ 'display': 'block' });
    // });

    function fillme (ev) {
        $(document).find('#listing').html('');
        $(document).find('#cfile').html("<strong>File: </strong>"+ev.fileno);
        $(document).find('#cname').html("<strong>Entity: </strong>" +ev.Granteename);
        $(document).find('#cdist').html("<strong>District: </strong>" + ev.Districtname);
        $(document).find('#filehidden').val(ev.fileno);
        //$(document).find('.allrecs').hide('slow');
        $('#samples').val('nsline');
        $('#skillfind').val('');

        var snd = {
            enrol: $(document).find('#filehidden').val(),
        }
        //document.write(JSON.stringify(snd));
        $.post('php/assess.php', snd, function (some) {
3

            var mines = JSON.parse(some);
            var k = 1;
            var tab = '';
            tab += "<table class='table table-hover table-bordered' id='myskills'>";
            tab += "<thead>";
            tab += "<tr><th colspan='5' style='text-align:center'><h3>Select Sampling period</h3></th></tr>";
            tab += "<tr><th colspan='5'><span id='mytotrecs'></span> | <span id='sampsize'></span><button class='btn btn-secondary btn-sm dorates mybuts' style='float:right;' id='myass'>Assessement</button></th></tr>";
            tab += "<tr><th colspan='5' style='text-align:center'><input type='text' placeholder='search' style='float:right' id= 'skillfind'></th><th></tr>";
            // tab += "<tr><th>#</th><th>Select</th><th>Name</th><th>SKill Area</th><th>Satisfaction Level</th><th>selbline</th><th>selmline</th><th>seleline</th></tr>";
            tab += "<tr><th>#</th><th>Select</th><th>Name</th><th>Skill Area</th><th>Satisfaction Level</th></tr>";
            tab += "</thead>";
            tab += "<tbody>";
            for (s in mines.mbrs) {
                var ss = mines.mbrs[s].recno;
                var mt = mines.mbrs[s].mact
                tab += "<tr rowid='" + ss + "' status='" + mt + "'>";
                tab += "<td align='right'>" + k + "</td>";
                tab += "<td style='text-align:center;'><input type='checkbox' disabled class='csel'></td>";
                tab += "<td gval='" + mines.mbrs[s].gender + "'>" + mines.mbrs[s].fulname + "</td>";
                tab += "<td>" + mines.mbrs[s].Skillarea + "</td>";
                tab += "<td></td>";
                // tab += "<td>"+mines.mbrs[s].selbline+"</td>";
                // tab += "<td>"+mines.mbrs[s].selmline+"</td>";
                // tab += "<td>"+mines.mbrs[s].seleline+"</td>";
                k += 1;
            }
            tab += "</tbody>";
            tab + "</table>";
            $(document).find('#listing').html(tab);
            $('#perms').val(mines.perms);
            $('#myskills tbody tr').each(function () {
                if ($(this).attr('status') == 'dr') {
                    $(this).toggleClass('dropedout');
                } else if ($(this).attr('status') == 'co') {
                    $(this).toggleClass('finished');
                }
            });
        });
        if ($('#perms').val() == 'no') {
            $('#myass').removeClass('dorates');
            $('#myskills tbody tr').each(function () {
                $(this).unbind('click');
            });
        }
    }   

    $(document).on('click', '.showthem', function (ev) {
        ev.preventDefault();

    });


    $(document).on('change', '#samples', function (ev) {
        ev.preventDefault();

        var ml = $(this).val();
        var ml = $(this).val();

        if ($(this).val() == 'nsline') {
            //$('.csel').attr('disabled', true);
            $('#myskills h3').html("Select Sampling period");
            $('#sampletaken').val('');
            $('#myskills tbody tr').each(function () {
                $(this).find('td:eq(4)').text('');
            });
            $('#myskills tbody').find('.csel').prop('checked', 0);
        } else {
            $('#myskills h3').html($('#samples option:selected').text() + " Sampling");
            $('.csel').attr('disabled', false);
            $('#sampletaken').val(ml);
            var snd = {
                myline: $(this).val(),
                fileno: $('#filehidden').val(),
            }
            //document.write(JSON.stringify(snd));
            $.post('php/assess.php', snd, function (some) {
                //alert(some);
                var mines = JSON.parse(some);

                for (j in mines.guys) {
                    var tr = $("#myskills tbody tr[rowid='" + mines.guys[j].recno + "']");
                    if (mines.guys[j].prop == 0) {
                        tr.find('.csel').prop('checked', 0);
                        tr.find('td:eq(4)').text('');
                    } else {
                        tr.find('.csel').prop('checked', 1);
                        tr.find('td:eq(4)').text(mines.guys[j].other);
                    }
                }

                // $('#mytotrecs').html("<strong>Record Count: </strong>"+(m+f)+" <strong>Males: </strong>"+m+" <strong>Females :</strong>"+f);
                var fx = parseInt(mines.sm) + parseInt(mines.sf);
                $('#sampsize').html("<strong>Sample size : </strong>" + fx + " <strong>Males : </strong>" + mines.sm + " <strong>Females: </strong>" + mines.sf);

                $('#kntool').val(mines.kntool);
                $('#ustool').val(mines.ustool);
                $('#aptool').val(mines.aptool);
                $('#doline').val(mines.doline);

            });
        }
        if ($('#perms').val() == 'no') {
            $('#myass').unbind('click');
            $('#myskills tbody tr').each(function () {
                $(this).unbind('click');
            });
        }
        countSamples();
    });

    $(document).on('keyup', '#skillfind', function (ev) {

        ev.preventDefault();
        value = $(this).val().toLowerCase().trim();

        $('#myskills >tbody tr').filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    // $(document).on('keyup', '#findit', function (ev) {

    //     ev.preventDefault();
    //     value = $(this).val().toLowerCase().trim();

    //     $('#allfirms >tbody tr').filter(function () {
    //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    //     });
    // });

    $(document).on('click', '#myskills tbody tr', function (ev) {
        ev.preventDefault();

        if ($('#perms').val() == 'no') {
            return false;
        }

        var sp = $('#samples').val();
        var snd;
        var nval;

        if (sp == 'nsline') {
            bootbox.alert({
                message: "Select Sample set",
                size: 'small'
            });
            return false;
        }

        if ($(this).attr('status') == 'dr') {
            bootbox.alert({
                message: "This person droped out",
                size: 'small'
            });
            return false;
        } else { }

        if ($(this).find('.csel').prop('checked') == true) {
            $(this).find('.csel').prop('checked', 0);
            $(this).find('td:eq(4)').text('');
            $(this).find('td:eq(4)').attr('gval', 0);

            nval = 0;
        } else {
            $(this).find('.csel').prop('checked', 1);
            $(this).find('td:eq(4)').attr('gval', 1)
            nval = 1;
        }
        var snd = {
            recno: $(this).attr('rowid'),
            prop: nval,
            oneline: sp,
            linename: $('#samples option:selected').text(),
            fileno: $('#filehidden').val()
        }
        //document.write(JSON.stringify(snd));
        $.post('php/assess.php', snd, function (some) {
            var mines = JSON.parse(some);
            var pp = $("#myskills tbody tr[rowid='" + mines.recno + "']");
            if (mines.nval == 1) {
                pp.find('td:eq(4)').text(mines.other);

            } else {
                pp.find('td:eq(4)').text('');

            }
            var fx = parseInt(mines.sm) + parseInt(mines.sf);
            $('#sampsize').html("<strong>Sample size : </strong>" + fx + " <strong>Males : </strong>" + mines.sm + " <strong>Females: </strong>" + mines.sf);
        });
    });

    $(document).on('click', '.dorates', function (ev) {
        ev.preventDefault();
        if ($('#samples').val() == 'nsline') {
            bootbox.alert({
                message: "Select Sampling Period",
                size: 'small'
            });
            return false;
        }
        var snd = {
            fileno: $('#filehidden').val(),
            dorates: $('#sampletaken').val(),
            kntool: $('#kntool').val(),
            ustool: $('#ustool').val(),
            aptool: $('#aptool').val(),
            doline: $('#doline').val(),

        }

        //document.write(JSON.stringify(snd));
        $.post('php/assess.php', snd, function (some) {
            var k = 1;
            var mines = JSON.parse(some);
            var plays = JSON.parse(some);
            var tab = '';
            var tab2 = '';
            tab2 += "<table id='table1' border='1'>";
            tab2 += "<thead>";
            tab2 += "<tr><th colpspan='5><span id='satlevel'>Satisfaction</span></th></tr>";
            tab2 += "<tr><th style='width:10px;'>#</th><th style='width:150px;'>Name</th><th class='badheader'>Knowledge of Tool</span><br>Not satisfied | Fairly Satisfied | satisfied</th><th class='badheader'>Application of Tool<br>Not satisfied | Fairly Satisfied | satisfied</th><th class='badheader'>Use of Tool<br>Not satisfied | Fairly Satisfied | satisfied</th>";
            tab += "</thead></table>";

            tab += "<table id='forex' border='1'>";
            // tab += "<thead>";
            // tab += "<tr><th>#</th><th>Name</th><th>Knowledge of Tool<br>Not satisfied | Fairly Satisfied | satisfied</th><th>Application of Tool<br>Not satisfied | Fairly Satisfied | satisfied</th></th><th>Use of Tool<br>Not satisfied | Fairly Satisfied | satisfied</th>";
            // tab += "</thead>"; 
            tab += "<tbody>";
            for (j in mines.guys) {
                tab += "<tr chkid='" + mines.guys[j].recno + "'>";
                tab += "<td align='right' style='width:50px;'>" + k + "</td>";
                tab += "<td style='width:180px;'>" + mines.guys[j].fulname + "</td>";

                if (mines.guys[j].kntool == 0) {
                    tab += "<td style='width:280px;'><span class='smtoolsl'><input type='radio' name='a" + k + "' value='0' id ='a0" + k + "' class='chkrates' col_name='" + $('#kntool').val() + "' checked ></span><span class='smtoolsl  smtoolsr'><input type='radio' name='a" + k + "' value='1' id ='a1" + k + "'class='chkrates' col_name='" + $('#kntool').val() + "'></span><span class='smtoolsl'><input type='radio' name='a" + k + "' value='2'  id='a2" + k + "'class='chkrates' col_name='" + $('#kntool').val() + "'></span></td>";
                }

                if (mines.guys[j].kntool == 1) {
                    tab += "<td style='width:285px;'><span class='smtoolsl'><input type='radio' name='a" + k + "' value='0' id ='a0" + k + "' class='chkrates' col_name='" + $('#kntool').val() + "'></span><span class='smtoolsl  smtoolsr'><input type='radio' name='a" + k + "' value='1' id ='a1" + k + "' checked class='chkrates' col_name='" + $('#kntool').val() + "'></span><span class='smtoolsl'><input type='radio' name='a" + k + "' value='2'  id='a2" + k + "'class='chkrates' col_name='" + $('#kntool').val() + "'></span></td>";
                }

                if (mines.guys[j].kntool == 2) {
                    tab += "<td style='width:280px;'><span class='smtoolsl'><input type='radio' name='a" + k + "' value='0' id ='a0" + k + "' class='chkrates' col_name='" + $('#kntool').val() + "'></span><span class='smtoolsl  smtoolsr'><input type='radio' name='a" + k + "' value='1' id ='a1" + k + "' class='chkrates' col_name='" + $('#kntool').val() + "'></span><span class='smtoolsl'><input type='radio' name='a" + k + "' value='2'  id='a2" + k + "'class='chkrates' col_name='" + $('#kntool').val() + "' checked></span></td>";
                }

                if (mines.guys[j].aptool == 0) {
                    tab += "<td style='width:280px;'><span class='smtoolsl'><input type='radio' name='b" + k + "' value='0'  id='b0" + k + "'class='chkrates' col_name='" + $('#aptool').val() + "' checked ></span><span style='padding-left:70px;padding-right:30px;'><input type='radio' name='b" + k + "' value='1' id='b1" + k + "' class='chkrates' col_name='" + $('#aptool').val() + "'></span><span style='padding-left:40px;'><input type='radio' name='b" + k + "' value='2' id='b2" + k + "' class='chkrates' col_name='" + $('#aptool').val() + "'></span></td>";
                }

                if (mines.guys[j].aptool == 1) {
                    tab += "<td style='width:280px;'><span class='smtoolsl'><input type='radio' name='b" + k + "' value='0'  id='b0" + k + "'class='chkrates' col_name='" + $('#aptool').val() + "'></span><span style='padding-left:70px;padding-right:30px;'><input type='radio' name='b" + k + "' value='1' id='b1" + k + "' class='chkrates' col_name='" + $('#aptool').val() + "' checked ></span><span style='padding-left:40px;'><input type='radio' name='b" + k + "' value='2' id='b2" + k + "' class='chkrates' col_name='" + $('#aptool').val() + "'></span></td>";
                }
                if (mines.guys[j].aptool == 2) {
                    tab += "<td style='width:280px;'><span class='smtoolsl'><input type='radio' name='b" + k + "' value='0'  id='b0" + k + "'class='chkrates' col_name='" + $('#aptool').val() + "'></span><span style='padding-left:70px;padding-right:30px;'><input type='radio' name='b" + k + "' value='1' id='b1" + k + "' class='chkrates' col_name='" + $('#aptool').val() + "'></span><span style='padding-left:40px;'><input type='radio' name='b" + k + "' value='2' id='b2" + k + "' class='chkrates' col_name='" + $('#aptool').val() + "' checked ></span></td>";
                }

                if (mines.guys[j].ustool == 0) {
                    tab += "<td style='width:280px;'><span class='smtoolsl'><input type='radio' name='c" + k + "' value='0'  id='c0" + k + "'class='chkrates' col_name='" + $('#ustool').val() + "' checked></span><span style='padding-left:80px;padding-right:40px;'><input type='radio' name='c" + k + "' value='1' id='c1" + k + "'class='chkrates' col_name='" + $('#ustool').val() + "'></span><span style='padding-left:20px;padding-right:0px;'><input type='radio' name='c" + k + "' value='2' id='c2" + k + "' class='chkrates' col_name='" + $('#ustool').val() + "'></span></td>";
                }

                if (mines.guys[j].ustool == 1) {
                    tab += "<td style='width:280px;'><span class='smtoolsl'><input type='radio' name='c" + k + "' value='0'  id='c0" + k + "'class='chkrates' col_name='" + $('#ustool').val() + "'></span><span style='padding-left:80px;padding-right:40px;'><input type='radio' name='c" + k + "' value='1' id='c1" + k + "'class='chkrates' col_name='" + $('#ustool').val() + "' checked ></span><span style='padding-left:20px;padding-right:0px;'><input type='radio' name='c" + k + "' value='2' id='c2" + k + "' class='chkrates' col_name='" + $('#ustool').val() + "'></span></td>";
                }

                if (mines.guys[j].ustool == 2) {
                    tab += "<td style='width:280px;'><span class='smtoolsl'><input type='radio' name='c" + k + "' value='0'  id='c0" + k + "'class='chkrates' col_name='" + $('#ustool').val() + "'></span><span style='padding-left:80px;padding-right:40px;'><input type='radio' name='c" + k + "' value='1' id='c1" + k + "'class='chkrates' col_name='" + $('#ustool').val() + "'></span><span style='padding-left:20px;padding-right:0px;'><input type='radio' name='c" + k + "' value='2' id='c2" + k + "' class='chkrates' col_name='" + $('#ustool').val() + "' checked></span></td>";
                }



                tab += "</tr>";
                k += 1;
            }
            tab += "</tbody>";
            tab += "</table>";

            $(document).find('#htitles').html(tab2);
            $(document).find('#ratingdiv').html(tab);
            $(document).find('#ratingdiv').css({ 'color': 'white' });
            var mk = 1;
            $('#forex tbody tr').each(function () {
                var vf = $("#myskills tbody tr[rowid='" + $(this).attr('chkid') + "']");
                if (vf.attr('status') == 'dr' || vf.attr('status') == 'co') {
                    $(this).remove();
                } else {
                    $(this).find('td:eq(0)').text(mk);
                    mk += 1;
                }


            });
        });
        $(document).find('#ratemodal').css({ 'display': 'block' });
    });

    $(document).on('click', '.chkrates', function (ev) {
        var snd = {
            recno: $(this).closest('tr').attr('chkid'),
            upfile: $(this).attr('col_name'),
            kntool: $('#kntool').val(),
            ustool: $('#ustool').val(),
            aptool: $('#aptool').val(),
            changes: $(this).val(),
            doline: $('#doline').val(),
            linename: $(document).find('#sample option:selected').text()
        }

        //document.write(JSON.stringify(snd));
        $.post('php/assess.php', snd, function (some) {
            var mines = JSON.parse(some);

            var trow = $("#myskills tbody tr[rowid='" + mines.recno + "']");
            trow.find('td:eq(4)').text(mines.skill);
        });
    });

    $(document).on('click', '.saverating', function () {
        $('#ratemodal').hide('slow');
    });

});

function countSamples() {
    // <span id='mytotrecs'></span> | <span id='sampsize'>
    var m = 0;
    var f = 0;
    $('#myskills tbody tr').each(function () {

        if ($(this).find('td:eq(2)').attr('gval') == 'M') {
            m += 1;
        } else {
            f += 1;
        }
    });


    $('#mytotrecs').html("<strong>Record Count: </strong>" + (m + f) + " <strong>Males: </strong>" + m + " <strong>Females :</strong>" + f);

}

// $("input[name=mygroup][value=" + value + "]").prop('checked', true);
