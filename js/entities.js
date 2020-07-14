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


    var pq = new Date();
    var lm = pq.getMilliseconds();
    var snd = {
        vv: lm
    }

    $.post("php/entities.php", snd, function (some) {
        //alert(some);
        var mines = JSON.parse(some);
        $('#district').append("<option value='0'>Select District</option>");
        $('#district1').append("<option value='0'>Select District</option>");
        for (vk in mines.districts) {
            $('#district').append("<option value='" + mines.districts[vk].Districtcode + "'>" + mines.districts[vk].Districtname + "</option>");
            $('#district1').append("<option value='" + mines.districts[vk].Districtcode + "'>" + mines.districts[vk].Districtname + "</option>");
            //$('#district1').append("<option value='"+mines.terms[kk].code+"'>"+mines.terms[kk].district+"</option>");
        }

        for (kk in mines.sector) {
            $('#Sectorcode').append("<option value='" + mines.sector[kk].Sectorcode + "'>" + mines.sector[kk].Sectordescription + "</option>");
            $('#Sectorcode1').append("<option value='" + mines.sector[kk].Sectorcode + "'>" + mines.sector[kk].Sectordescription + "</option>");
            //$('#district1').append("<option value='"+mines.terms[kk].code+"'>"+mines.terms[kk].district+"</option>");
        }

        for (dk in mines.dtypes) {
            $('#entypesel').append("<option value='" + mines.dtypes[dk].code + "'>" + mines.dtypes[dk].name + "</option>");
            $('#entype1sel').append("<option value='" + mines.dtypes[dk].code + "'>" + mines.dtypes[dk].name + "</option>");
        }

        for(dk in mines.idcat){
            $('#catid').append("<option value='" + mines.idcat[dk].catid + "'>" + mines.idcat[dk].description + "</option>");
            $('#catid1').append("<option value='" + mines.idcat[dk].catid + "'>" + mines.idcat[dk].description + "</option>");
        }


        for(dk in mines.idcriteria){
            $('#catcreteria').append("<option value='" + mines.idcriteria[dk].catcreteria + "'>" + mines.idcriteria[dk].description + "</option>");
            $('#catcreteria1').append("<option value='" + mines.idcriteria[dk].catcreteria + "'>" + mines.idcriteria[dk].description + "</option>");
        }



        var num = 1;
        var tab;
        tab += "<div style='position:fixed;'>";
        tab = "<table class='table-sm table-hover table-bordered' id='gtable'>";
        tab += "<thead>";
        tab += "<tr class='noprint'><th></th><th><input type='text' id='msearch' placeholder='Search'></th><th colspan='2'>Record count " + mines.counts + "</th><th colspan='2'><input type='text' id='found' readonly></th><th colspan='2'><button class='btn btn-sm bg-success btn_new_row' data-toggle='modal' data-target='#addmodal'>Add Entity</button></th><th></th><th colspan='4'></th></tr>";

        tab += "<tr><th>#</th><th>File No.</th><th>Name</th><th>Type</th>";
        tab += "<th>No.</th><th>Window</th><th>Address</th><th>Contact</th><th>Phone</th><th>District</th><th>Sector</th><th class='noprint'>Option</th><th style='display:none;'>duration</th><th style='display:none;'>startdate</th><th style='display:none;'>enddate</th><th style='display:none;'>Appbudget</th><th style='display:none;'>grcont</th><th style='display:none;'>numroll</th></tr>";
        tab += "</thead>";
        tab += "</div>";
        tab += "<tbody id='mtbody'>";
        for (s in mines.entities) {
            tab += "<tr rowid='" + mines.entities[s].fileno + "'>";
            tab += "<td align='right'>" + num + "</td>";
            tab += "<td >" + mines.entities[s].fileno + "</td>";
            tab += "<td >" + mines.entities[s].Granteename + "</td>";
            tab += "<td entries=" + mines.entities[s].entype + ">" + mines.entities[s].Entitytypename + "</td>";
            tab += "<td align='right'>" + mines.entities[s].Beneficiaryno + "</td>";
            tab += "<td align='right'>" + mines.entities[s].pwindow + "</td>";
            tab += "<td>" + mines.entities[s].paddress + "</td>";
            tab += "<td>" + mines.entities[s].contperson + "</td>";
            tab += "<td>" + mines.entities[s].contphone + "</td>";
            tab += "<td distval=" + mines.entities[s].Districtcode + ">" + mines.entities[s].Districtname + "</td>";
            tab += "<td sectorval=" + mines.entities[s].Sectorcode + ">" + mines.entities[s].Sectordescription + "</td>";
            tab += "<td class='noprint'><div class='btn-group btn-group-xs noprint' role='group'><button type='button' class='btn btn-success btn_save mybuts' data-toggle='modal' data-target='#editmodal'>Edit</button><button type='button' class='btn mybuts mydels btn-danger'>Delete</button></div> </td>";
            tab += "<td style='display:none;' dform=" + mines.entities[s].dform + ">" + mines.entities[s].duration + "</td>";
            tab += "<td style='display:none;'>" + mines.entities[s].startdate + "</td>";
            tab += "<td style='display:none;'>" + mines.entities[s].enddate + "</td>";
            tab += "<td style='display:none;'>" + mines.entities[s].Appbudget + "</td>";
            tab += "<td style='display:none;'>" + mines.entities[s].grcont + "</td>";
            tab += "<td style='display:none;'>" + mines.entities[s].numroll + "</td>";
            tab += "<td style='display:none;' catval=" + mines.entities[s].catid + ">"+mines.entities[s].description+"</td>";
            tab += "<td style='display:none;' critval=" + mines.entities[s].catcreteria + ">"+mines.entities[s].catdesc+"</td>";
 
            tab += "</tr>";
            num += 1;
        }
        tab += "</tbody>";
        tab += "</table>";
        $(document).find('#mytable').html(tab);

        $(document).find('#perms').val(mines.perms);

        if (mines.perms == 'no') {
            $('#addmodal').remove();
            $('#gtable tbody tr').each(function () {
                $(this).find('td:eq(11)').empty();
            });
        }


        // numberWithCommas
        $('#sdfbudget').html("<strong>Total SDF Budget : </strong>" + numberWithCommas(mines.appbudget));
        $('#ownbudget').html("<strong>Total Own Budget : </strong>" + numberWithCommas(mines.ownbudget));
        $('#numroll').html("<strong>Total Enrollment :</strong>" + numberWithCommas(mines.appnum));
        $('#rollgender').html("<strong>Females :</strong>" + numberWithCommas(mines.sf) + " <strong>Males :</strong>" + numberWithCommas(mines.sm));
        // var bf = '<strong>Female :</strong>';
        // var bm = '<strong>Male :</strong>';
        $('#completed').html("<strong>Completed : " + (mines.co['F'] ? "Females :" + numberWithCommas(mines.co['F']) : '') + ' ' + (mines.co['M'] ? "Males :" + numberWithCommas(mines.co['M']) : 0) + "</strong>");
        $('#training').html("<strong>Training : " + (mines.tr['F'] ? "Females :" + numberWithCommas(mines.tr['F']) : '') + ' ' + (mines.co['M'] ? "Males :" + numberWithCommas(mines.tr['M']) : 0) + "</strong>");
        $('#droped').html("<strong>Droped Out : " + (mines.dr['F'] ? "Females :" + numberWithCommas(mines.dr['F']) : '') + ' ' + (mines.co['M'] ? "Males :" + numberWithCommas(mines.dr['M']) : 0) + "</strong>");
    });



    $(document).on('click', '.mydels', function (ev) {
        ev.preventDefault();
        var mk = $(this).closest('tr').attr('rowid');

        var mkn = $(this).closest('tr').find('td').eq(2).text();
        myrow = $(this).closest('tr');
        bootbox.confirm({
            message: "Are you sure you want to delete " + mkn + " file number " + mk + "?",
            buttons: {
                cancel: {
                    label: 'No',
                    className: 'btn-danger btn-sm'
                },
                confirm: {
                    label: 'Yes',
                    className: 'btn-success btn-sm'
                }
            },
            callback: function (result) {
                //console.log('This was logged in the callback: ' + result);
                if (result) {
                    var snd = {
                        deleting: mk,
                        namex: mkn
                    }
                    myrow.remove();
                    $.post('php/entities.php', snd, function (some) {
                        // alert(some);
                        // return false;
                        mines = JSON.parse(some);
                        $('#gtable thead tr').eq(0).find('th').eq(2).text("Record Count " + mines.counts);
                        $('#sdfbudget').html("<strong>Total SDF Budget : </strong>" + numberWithCommas(mines.uappbudget));
                        $('#ownbudget').html("<strong>Total Own Budget : </strong>" + numberWithCommas(mines.uownbudget));
                        $('#numroll').html("<strong>Total Enrollment :</strong>" + numberWithCommas(mines.uappnum));
            
                        if (mines.error) {
                            bootbox.alert({
                                message: mines.errmsg,
                                size: 'small'
                            });
                        } else {
                            myrow.remove();
                            bootbox.alert({
                                message: mines.errmsg,
                                size: 'small'
                            });

                        }

                    });
                } else {
                    bootbox.alert({
                        message: "Record was not deleted",
                        size: 'small'
                    });
                }
            }
        });
    });

    $(document).on('keyup', '#msearch', function () {
        var value = $(this).val().toLowerCase().trim();

        var numrows = $("#mtbody tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            return $(this).css('display') !== 'none';
        });

        if ($('#msearch').val() == '')
            $('#found').val('');
        else
            $('#found').val("Filtered " + numrows.length + " record(s)");

        //var numrows =  $(this) $(this).css('display') !== 'none'; 
    });

    $(document).on('click', '.btn_save', function (ev) {
        
        $('#edit_form')[0].reset();
        ev.preventDefault();
       
        var weekdays = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        var myrow = $(this).closest('tr');
        
        var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        var distval = myrow.find('td').eq(9).attr('distval');
        var sectorval = myrow.find('td').eq(10).attr('sectorval');
        var typem = myrow.find('td').eq(3).attr('entries');
        //alert(myrow.find('td:eq(14)').text());
        //return false;
        var mDate = myrow.find('td').eq(12).attr('dform')
        $('#oldfile').val(myrow.attr('rowid'));
        $('#fileno1').val(myrow.find('td').eq(1).text());
        $('#Granteename1').val(myrow.find('td').eq(2).text());
        $('#entype1sel').val(typem);
        $('#Beneficiaryno1').val(myrow.find('td').eq(4).text());
        $('#window1').val(myrow.find('td').eq(5).text());
        $('#paddress1').val(myrow.find('td').eq(6).text());
        $('#contperson1').val(myrow.find('td').eq(7).text());
        $('#contphone1').val(myrow.find('td').eq(8).text());
        $('#district1').val(distval);
        $('#Sectorcode1').val(sectorval);
        var mydate1 = myrow.find('td:eq(13)').text();
        //$('#dweek').val(weekdays[mydate.getDay()]);
        // $('#dform1').val(mDate);
        // $('#duration1').val(myrow.find('td').eq(12).text());
        $('#numroll1').val(myrow.find('td:eq(17)').text());
        $('#catid1').val(myrow.find('td:eq(18)').attr('catval'));
        $('#catcreteria1').val(myrow.find('td:eq(19)').attr('critval'));
        // $('#startdate1').attr('value',mydate1);
        $('#Appbudget1').val(parseInt(myrow.find('td:eq(15)').text()));
        $('#grcont1').val(myrow.find('td:eq(16)').text());
       
        var starting = new Date(mydate1).toISOString().substr(0, 10);
        document.querySelector('#startdate1').value = starting;
        //the one below does not work
        //$('#startdate1').value = starting;
        //$('#startdate1').text(mydate1);
        var s = parseInt(myrow.find('td:eq(12)').text());
        // $('#duration1').val(parseInt(myrow.find('td:eq(12)').text()));
        // $('#duration1').value = s;
        $('#duration1').val(s);
        $('#dform1').val(myrow.find('td:eq(12)').attr('dform'));
       
        //$('#dweek').val(weekdays[mydate.getDay()]);
        var mydate = new Date(myrow.find('td:eq(14)').text());
        var myday = weekdays[mydate.getDay()];
        var tda = mydate.getDate();
        var putdate = pad(tda, 2) + '-' + months[mydate.getMonth()] + '-' + mydate.getFullYear() + ' ' + myday;
        $('#enddate1').val(putdate);
        
        

        function pad(tf, s) {
            var f = tf + '';
            while (f.length < s) f = "0" + f;
            return f;

        }
    });

    $(document).on('submit', '#insert_form', function (ev) {
        ev.preventDefault();
        
        var snd = {
            Granteename: $('#Granteename').val(),
            Beneficiaryno: $('#Beneficiaryno').val(),
            paddress: $('#paddress').val(),
            contperson: $('#contperson').val(),
            contphone: $('#contphone').val(),
            Districtcode: $('#district').val(),
            Sectorcode: $('#Sectorcode').val(),
            fileno: $('#fileno').val(),
            entype: $('#entypesel').val(),
            pwindow: $('#pwindow').val(),
            adding: 'yes',
            entitytypename: $('#entypesel option:selected').text(),
            Districtname: $('#district option:selected').text(),
            Sectorcodename: $('#Sectorcode option:selected').text(),
            Appbudget: $('#Appbudget').val(),
            grcont: $('#grcont').val(),
            startdate: $('#startdate').val(),
            duration: $('#duration').val(),
            dform: $('#dform').val(),
            numroll: $('#numrole').val(),
            catid: $('#catid').val(),
            catcreteria: $('#catcreteria').val()
        }

        // document.write(JSON.stringify(snd));
        // return false;
        $('#insert_form')[0].reset();
        $(document).find('#addmodal').modal('hide');
        // false;
        $.post('php/entities.php', snd, function (some) {
            // document.write(JSON.stringify(snd));
            // return false;
            //alert(some);
            mines = JSON.parse(some);
            var nrow = $(document).find('#mtbody tr').eq(0);
            var tab = '';
            tab += "<tr rowid='" + mines.fileno + "' style='background-color:black;color:white;'>";
            tab += "<td align='right'>" + mines.counts + "</td>";
            tab += "<td>" + mines.fileno + "</td>";
            tab += "<td>" + mines.Granteename + "</td>";
            tab += "<td entries='" + mines.entype + "'>" + mines.Entitytypename + "</td>";
            tab += "<td align='right'>" + mines.Beneficiaryno + "</td>";
            tab += "<td align='right' style='width:70px;'>" + mines.pwindow + "</td>";
            tab += "<td>" + mines.paddress + "</td>";
            tab += "<td>" + mines.contperson + "</td>";
            tab += "<td>" + mines.contphone + "</td>";
            tab += "<td distval='" + mines.Districtcode + "'>" + mines.Districtname + "</td>";
            tab += "<td sectorval='" + mines.Sectorcode + "'>" + mines.Sectordescription + "</td>";
            tab += "<td><div class='btn-group btn-group-xs' role='group'><button type='button' class='btn btn-success btn_save mybuts' data-toggle='modal' data-target='#editmodal'>Edit</button><button type='button' class='btn mybuts mydels btn-danger'>Delete</button></div> </td>";
            tab += "<td style='display:none;' dform=" + mines.dform + ">" + mines.duration + "</td>";
            tab += "<td style='display:none;'>" + mines.startdate + "</td>";
            tab += "<td style='display:none;'>" + mines.enddate + "</td>";
            tab += "<td style='display:none;'>" + mines.Appbudget + "</td>";
            tab += "<td style='display:none;'>" + mines.grcont + "</td>";
            tab += "<td style='display:none;'>" + mines.numroll + "</td>";
            tab += "<td style='display:none;' catval=" + mines.catid + ">"+mines.description+"</td>";
            tab += "<td style='display:none;' critval=" + mines.catcreteria + ">"+mines.catdes+"</td>";            

            tab += "</tr>";

            nrow.before(tab);
            $('#gtable thead tr').eq(0).find('th').eq(2).text("Record Count " + mines.counts);

            $('#sdfbudget').html("<strong>Total SDF Budget : </strong>" + numberWithCommas(mines.Entity.iappbudget));
            $('#ownbudget').html("<strong>Total Own Budget : </strong>" + numberWithCommas(mines.Entity.iownbudget));
            $('#numroll').html("<strong>Total Enrollment :</strong>" + numberWithCommas(mines.Entity.iappnum));
        });
    });

    $(document).on('submit', '#edit_form', function (event) {
        event.preventDefault();
        
        var snd = {
            Granteename: $('#Granteename1').val(),
            paddress: $('#paddress1').val(),
            contperson: $('#contperson1').val(),
            contphone: $('#contphone1').val(),
            Districtcode: $('#district1').val(),
            Sectorcode: $('#Sectorcode1').val(),
            fileno: $('#fileno1').val(),
            entype: $('#entype1sel').val(),
            pwindow: $('#pwindow1').val(),
            startdate: $('#startdate1').val(),
            enddate: '',
            duration:$('#duration1').val(),
            Appbudget: $('#Appbudget1').val(),
            grcont: $('#grcont1').val(),
            dform: $('#dform1').val(),
            numroll: $('#numroll1').val(),
            catid: $('#catid1').val(),
            catcreteria: $('#catcreteria1').val(),
            oldfile: $('#oldfile').val(),
            updating: 'yes'
        }
        $('#editmodal').modal('hide');
        // document.write(JSON.stringify(snd));
        // return false;
        $.post("php/entities.php", snd, function (some) {
            //document.write(some);
            //alert(some);
            var mines = JSON.parse(some);
            var ele = $("tr[rowid='" + mines.fileno + "']");
            ele.attr('rowid', mines.filenonew);
            ele.find('td').eq(1).text(mines.filenonew);
            ele.find('td').eq(2).text(mines.Granteename);
            ele.find('td').eq(3).text(mines.Entitytypename);
            ele.find('td').eq(4).text(mines.Beneficiaryno);
            ele.find('td').eq(5).text(mines.pwindow);
            ele.find('td').eq(6).text(mines.paddress);
            ele.find('td').eq(7).text(mines.contperson);
            ele.find('td').eq(8).text(mines.contphone);
            ele.find('td').eq(9).text(mines.Districtname);
            ele.find('td').eq(10).text(mines.Sectordescription);
            ele.find('td').eq(9).attr('distval', mines.Districtcode);
            ele.find('td').eq(10).attr('sectorval', mines.Sectorcode);
            ele.find('td').eq(3).attr('entries', mines.entype);
            ele.find('td').eq(12).attr('dform', mines.dform);
            ele.find('td').eq(12).text(mines.duration);
            ele.find('td').eq(13).text(mines.startdate);
            ele.find('td').eq(14).text(mines.enddate);
            ele.find('td').eq(15).text(mines.Appbudget);
            ele.find('td').eq(16).text(mines.grcont);
            ele.find('td').eq(17).text(mines.numroll);
            ele.find('td').eq(18).text(mines.description);
            ele.find('td').eq(18).attr('catval',mines.catid);
            ele.find('td').eq(19).text(mines.catdesc);
            ele.find('td:eq(19)').attr('critval',mines.catcreteria);

            ele.css({ 'background-color': 'blue', 'color': 'white' });

            $('#sdfbudget').html("<strong>Total SDF Budget : </strong>" + numberWithCommas(mines.uappbudget));
            $('#ownbudget').html("<strong>Total Own Budget : </strong>" + numberWithCommas(mines.uownbudget));
            $('#numroll').html("<strong>Total Enrollment :</strong>" + numberWithCommas(mines.uappnum));

            bootbox.alert({
                message: mines.errmsg,
                size: 'small'
            });
        });
    });


});

function numberWithCommas(number) {
    var parts = number.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}