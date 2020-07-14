/*
Author Samuel Bwoye
Title Script to allow grantee to directly enter enrolled members
Languge: Javascript
Date : 01-08-2019
*/


$(document).ready(function () {
    $('#mymenus').load("menugrantee.html");
    var skarea
    var mycurrHtml;
    $.post('php/grantee.php', function (some) {
        var mines = JSON.parse(some);
        skarea = mines.skarea;

        $('#Skillcode').append("<option value='xx' disabled selected>Select Skilling Area</option>");
        for (d in skarea) {
            $('#Skillcode').append("<option value='" + skarea[d].skillcode + "'>" + skarea[d].Skilldescription + "</option>");
        }
        var skstatus = mines.skillstatus;
        var mk = '';
        mk += "<span><strong>file Number:</strong> " + mines.grdetails['fileno'] + "</span>";
        mk += "<span><strong>Name: </strong>" + mines.grdetails['Granteename'] + "</span>";
        mk += "<span><strong>District:</strong> " + mines.grdetails['distname'] + "</span>";
        mk += "<span><strong>Sector:</strong> " + mines.grdetails['Sectordescription'] + "</span>";
        mk += "<span><strong>SDF Budget:</strong> " + numberWithCommas(mines.grdetails['Appbudget']) + "</span>";
        mk += "<span><strong>Own Budget:</strong> " + numberWithCommas(mines.grdetails['grcont']) + "</span>";
        mk += "<span><strong>Window:</strong> " + mines.grdetails['pwindow'] + "</span>";
        mk += "<span><strong>Enrollment Total :</strong> " + numberWithCommas((mines.mygender['F']?mines.mygender['F']:0)+ (mines.mygender['M']?mines.mygender['M']:0)) + " Female :" + (mines.mygender['F']?mines.mygender['F']:0) + " Males :" + (mines.mygender['M']?mines.mygender['M']:0) + "</span><span><strong>Enrollment allowed :</strong>" + numberWithCommas(mines.grdetails['numroll']) + "</span>";

        if (skstatus.tr) {
            mk += "<span><strong>Training Female:</strong> " + numberWithCommas(skstatus.tr.Female) + "</span>";
            mk += "<span><strong>Training Male:</strong> " + numberWithCommas(skstatus.tr.Male) + "</span>";
        }
        if (skstatus.co) {
            mk += "<span><strong>Completed Female:</strong> " + numberWithCommas(skstatus.co.Female) + "</span>";
            mk += "<span><strong>Completed Male:</strong> " + numberWithCommas(skstatus.co.Male) + "</span>";
        }
        if (skstatus.dr) {
            mk += "<span><strong>Droped out Female:</strong> " + numberWithCommas(skstatus.dr.Female) + "</span>";
            mk += "<span><strong>Droped out Male:</strong> " + numberWithCommas(skstatus.dr.Male) + "</span>";
        }

        try {
            var starts = new Date(mines.grdetails['startdate']).toISOString().substr(0, 10);
            mk += "<span><strong>Star Date:</strong> " + starts + "</span>";
        } catch{

            mk += "<span><strong>Star Date:</strong> No Date set </span>";
        }

        try {

            var ends = new Date(mines.grdetails['enddate']).toISOString().substr(0, 10);
            mk += "<span><strong>End Date:</strong> " + ends + "</span>";
        } catch{
            mk += "<span><strong>End Date:</strong>No date set </span>";
        }

        $(document).find(".gparticulars").html(mk);

        var sd = mines.participants;
        var tt = ''
        var k = 1;
        tt = "<table class='table table-bordered table-sm table-hover' style='font-size:0.8em;' id='trainees'>";
        tt += "<thead><tr><th colspan='3'><button type='button' class='btn btn-sm btn-primary btnaddnew' data-toggle='modal' data-target='#editmodal'>Add new Participant</button></th><th colspan='4'><input type='text' id='mysearch' name='mysearch' placeholder='Search'></th></tr>";
        tt += "<tr><th>#</th><th>Name</th><th>Gender</th><th>Skilling Area</th><th>Contact</th><th>Email</th><th>Action</th></tr></thead>";
        tt += "<tbody>";
        for (s in sd) {
            tt += "<tr recno='" + sd[s].recno + "'>";
            tt += "<td align='right'>" + k + "</td>";
            tt += "<td>" + sd[s].fulname + "</td>";
            tt += "<td>" + sd[s].Gender + "</td>";
            tt += "<td skillcode='" + sd[s].Skillcode + "'>" + sd[s].Skilldescription + "</td>";
            tt += "<td>" + sd[s].Phonecontact + "</td>";
            tt += "<td>" + sd[s].email + "</td>";
            tt += "<td><button type='button' class='btn ml-auto mybuts btn-primary btn-sm btnedit' data-toggle='modal' data-target='#editmodal'>Edit</button><button type='button' class='btn ml-auto btn-danger btn-sm btndelete mybuts'>Delete</button></td>";
            tt += "</tr>";
            k += 1;
        }

        tt += "</tbody></table>";
        $(document).find('.details').html(tt);
    });


    $(document).on('click', '.reports', function (ev) {
        //ev.preventDefault();
        $('.details').hide();
        
        $('.otherthings').empty();
        $('.otherthings').show();
        //alert("Monthly returns");
        $.post('php/grantee.php', { myreturns: "yes" }, function (some) {
            var mines = JSON.parse(some);
            var tt = '';
            var k = 1;
            tt += "<table id='idreturn' class='table table-bordered table-sm'>";
            tt += "<thead><tr><th colspan='2'><button type='button' class='btn btn-sm btn-primary myback'>Back</button></th></tr>";
            tt+="<tr><th colspan='2'><button type='button' class='btn btn-small btn-secondary freturn'>Upload return</th><th><input type='date' style='color:#000;' id='myperiod'><input type='file' class='returnup'></th></tr>";
            tt += "<tr><th>#</th><th>Period</th><th>Date filed</th><th>Actions</th></tr></thead>";
            tt += "<tbody>";
            for (s in mines.returns) {
                tt += "<tr idno='"+mines.returns[s].idno+"'>";
                tt += "<td align='right'>" + k + "</td>";
                tt += "<td filename='"+mines.returns[s].filename+"'>" + mines.returns[s].retperiod + "</td>";
                tt += "<td align='right'>" + mines.returns[s].filingdate + "</td>";
                tt+="<td><a href='monthlyreports/"+mines.returns[s].filename+"' class='btn btn-sm' download >Download</a><button type='button' class='btn btn-sm btn-danger btnremove' >Delete</button>";
                tt += "</tr>";
                k += 1;
            }
            tt += "</tbody></table>";

            $(document).find(".otherthings").html(tt);
            makethings();
        })
    })

    $(document).on('click','.btnremove',function(){
        var myrow = $(this).closest('tr');
        var snd = {
            idno:myrow.attr('idno'),
            filename:myrow.find('td:eq(1)').attr('filename'),
            delreturn:"yes"
        }
        $.post('php/grantee.php',snd,function(){
            myrow.remove();
        });        
    })

    function makethings(){
        Array.prototype.forEach.call(document.querySelectorAll('.freturn'), function (button) {
            const hiddenInput = document.querySelector('.returnup');

            button.addEventListener('click', function () {
                hiddenInput.click();
            });

            hiddenInput.addEventListener('change', function () {
                var property = hiddenInput.files[0]; //document.getElementById('myfile').files[0];
                var form_data = new FormData();
                form_data.append('file', property)
                form_data.append('fileins', "yes");
                form_data.append("myperiod", $('#myperiod').val());

                
                //return false;

                $.ajax({
                    url: 'php/grantee.php',
                    method: "POST",
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: form_data,
                    success: function (some) {
                        alert(some);
                        var mines = JSON.parse(some);
                        if(mines.error == false){
                            var tt = '';
                            tt += "<tr idno='"+mines.myreturn.idno+"'>";
                            tt += "<td align='right'>0</td>";
                            tt += "<td filename='"+mines.myreturn.filename+"'>" + mines.myreturn.retperiod + "</td>";
                            tt += "<td align='right'>" + mines.myreturn.filingdate + "</td>";
                            tt+="<td><button type='button class='btn btn-sm btndowns btn-primary'>Download</button><button type='button' class='btn btn-sm btn-danger btnremove' download='/monthlyreturns/'"+mines.myreturn.filename+"'>Delete</button>";
                            tt += "</tr>";

                            $('#idreturn tbody').append(tt);
                            kk = 0;
                            $('#idreturn tbody tr').each(function(){
                                kk += 1;
                                $(this).find('td:eq(0)').text(kk);                                
                            });
                        }else{
                            alert("Error saving Uploading report");
                        }
                    }
                });
            });
        });
    }

    $(document).on('click','.freturn',function(ev){
        ev.preventDefault();
        var form_data = new FormData();

    })

    $(document).on('click', '.myback', function (ev) {
        //ev.preventDefault();
        $('.details').show();
        $('.otherthings').empty();
        $('.otherthings').hide();
    })

    $(document).on('keyup', '#mysearch', function () {
        var value = $(this).val().toLowerCase().trim();

        var numrows = $("#trainees tbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            return $(this).css('display') !== 'none';
        });
    });


    $(document).on('click', '.btndelete', function () {
        var recno = $(this).closest('tr').attr('recno');
        var myrow = $(this).closest('tr');
        $.post('php/grantee.php', { delpart: recno }, function () {
            myrow.remove();
        })
    });
    $(document).on('submit', '#edit_form', function (ev) {
        ev.preventDefault();
        if ($('#recno').val() == '') {
            var snd = {
                adding: "yes",
                fulname: $('#fulname').val(),
                email: $('#email').val(),
                Phonecontact: $('#Phonecontact').val(),
                Gender: $('#Gender').val(),
                Skillcode: $('#Skillcode').val()
            }
            $.post('php/grantee.php', snd, function (some) {
                var mines = JSON.parse(some);
                var tt = '';
                tt += "<tr recno='" + mines.recno + "' style='background-color:black;color:white;'>";
                tt += "<td align='right'>0</td>";
                tt += "<td>" + snd['fulname'] + "</td>";
                tt += "<td>" + snd['Gender'] + "</td>";
                var mt = '';
                for(s in skarea){
                    if(skarea[s].skillcode == snd['Skillcode']){
                        mt = skarea[s].Skilldescription;
                        break;
                    }
                }
                tt += "<td skillcode='" + snd['Skillcode'] + "'>" + mt + "</td>";
                tt += "<td>" + snd['Phonecontact'] + "</td>";
                tt += "<td>" + snd['email'] + "</td>";
                tt += "<td><button type='button' class='btn ml-auto mybuts btn-primary btn-sm btnedit' data-toggle='modal' data-target='#editmodal'>Edit</button><button type='button' class='btn ml-auto btn-danger btn-sm btndelete mybuts'>Delete</button></td>";
                tt += "</tr>";
               
                if ($('#trainees tbody tr').length < 1) {
                    $('#trainees tbody').append(tt);

                } else {
                    var nrow = $(document).find('#trainees tbody tr').eq(0);
                    nrow.before(tt);
                }
                
                tt = 0;
                $('#trainees tbody tr').each(function () {
                    tt += 1;
                    $(this).find('td:eq(0)').text(tt);
                });
            })
        } else {
            var snd = {
                recno: $('#recno').val(),
                fulname: $('#fulname').val(),
                email: $('#email').val(),
                Phonecontact: $('#Phonecontact').val(),
                Gender: $('#Gender').val(),
                Skillcode: $('#Skillcode').val()
            }

            $.post('php/grantee.php', snd, function () {
                var ele = $("#trainees tbody tr[recno='" + snd['recno'] + "']");
                ele.find('td:eq(1)').text(snd['fulname']);
                ele.find('td:eq(2)').text(snd['Gender']);
                ele.find('td:eq(2)').attr('Gender', snd['fulname']);
                ele.find('td:eq(3)').text(snd['Skillcode']);
                ele.find('td:eq(4)').text(snd['Phonecontact']);
                ele.find('td:eq(5)').text(snd['email']);
                ele.css({ 'background-color': 'blue', 'color': 'white' });
            });
        }
        $('#editmodal').modal('hide');
    });

    $(document).on('click', '.btnaddnew', function () {
        document.getElementById('edit_form').reset();
        $('#recno').val('');
        $('.modal_title').html("Add participant");
    });


    $(document).on('click', '.btnedit', function () {
        document.getElementById('edit_form').reset();
        $('#recno').val('');
        $('.modal_title').html("Edit participant");
        var myrow = $(this).closest('tr');
        $('#recno').val(myrow.attr('recno'));
        $('#fulname').val(myrow.find('td:eq(1)').text());
        $('#Skillcode').val(myrow.find('td:eq(3)').attr('skillcode'));
        $('#Gender').val(myrow.find('td:eq(2)').text());
        $('#Phonecontact').val(myrow.find('td:eq(4)').text())
        $('#email').val(myrow.find('td:eq(5)').text())
    })

    function numberWithCommas(number) {
        var parts = number.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }
});

