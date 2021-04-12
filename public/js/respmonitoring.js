/**
 * Created with JetBrains PhpStorm.
 * User: admin
 * Date: 18.12.12
 * Time: 19:02
 * To change this template use File | Settings | File Templates.
 */
$(function () {
    $("#code_ill_10_par").change(function () {
        var mkbchild = $(this).val();
        $.ajax({
            url:"/respmonitoring/mkb10/" + mkbchild,
            type:"POST",
            dataType:"json",
            success:function (data) {
                $("#mkb_10").html('<option value=""></option>');
                for (var i = 0; i < data.length; i++) {
                    item = data[i];
                    $("#mkb_10").append('<option value="' + item.row_num + '">' + item.r + '</option>')
                }

            }
        });
    });
    $("#ecode_ill_10_par").change(function () {
        var mkbchild = $(this).val();
        $.ajax({
            url:"/respmonitoring/mkb10/" + mkbchild,
            type:"POST",
            dataType:"json",
            success:function (data) {
                $("#emkb_10").html('<option value=""></option>');
                for (var i = 0; i < data.length; i++) {
                    item = data[i];
                    $("#emkb_10").append('<option value="' + item.row_num + '">' + item.r + '</option>')
                }

                var sel_row = $("#history").jqGrid("getGridParam", "selrow");
                if (sel_row) {
                    row = $("#history").jqGrid("getRowData", sel_row);
                    $("[name=emkb_10]", $("#illehistory-form")).val(row.mkb_10);
                }
            }
        });
    });


    $('.wpbook_hidden').css({
        'display':'none'
    });

    $('#tmek_xulosasi').change(function () {
        var val = $(this).val();
        if (val == 1) {
            $("#p1").show();
            $("#p2").hide();
            $("#p3").hide();
            $("#p4").hide();
        }

        else if (val == 2) {
            $("#p2").show();
            $("#p1").hide();
            $("#p3").hide();
            $("#p4").hide();
        }
        else if (val == 3) {
            $("#p3").show();
            $("#p2").hide();
            $("#p1").hide();
            $("#p4").hide();
        }
        else if (val == 4) {
            $("#p4").show();
            $("#p2").hide();
            $("#p3").hide();
            $("#p1").hide();
        }
        else {
            $("#p1").hide();
            $("#p2").hide();
            $("#p3").hide();
            $("#p4").hide();
        }
    });

    $('#etmek_xulosasi').change(function () {
        var val = $(this).val();
        if (val == 1) {
            $("#ep1").show();
            $("#ep2").hide();
            $("#ep3").hide();
            $("#ep4").hide();
        }

        else if (val == 2) {
            $("#ep2").show();
            $("#ep1").hide();
            $("#ep3").hide();
            $("#ep4").hide();
        }
        else if (val == 3) {
            $("#ep3").show();
            $("#ep2").hide();
            $("#ep1").hide();
            $("#ep4").hide();
        }
        else if (val == 4) {
            $("#ep4").show();
            $("#ep2").hide();
            $("#ep3").hide();
            $("#ep1").hide();
        }
        else {
            $("#ep1").hide();
            $("#ep2").hide();
            $("#ep3").hide();
            $("#ep4").hide();
        }
    });

    $("#trdate").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#etrdate").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#tdate").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#etdate").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#pdate").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#epdate").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#murojaat_sana").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#tr88date").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#ambdate").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#ktkdate").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#beg_date").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#ebeg_date").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#fbeg_date").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#fbeg_date_gacha").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#fend_date").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#fend_date_gacha").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#fpdate").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#fdate_birth").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#tasdiq-sana").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#table01-sana").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });
    $("#et-sana").datepicker({
        dateFormat:'yy-mm-dd',
        changeYear:true,
        yearRange:'1940:2050'
    });

    $("#dialog-form").dialog({
        autoOpen:false,
        height:100,
        width:100,
        modal:true,
        buttons:{
            "Сақлаш":function () {
                $.ajax({
                    url:"/respmonitoring/kk",
                    type:"POST",
                    data:($("#illness-form").serializeArray()),
                    success:function (data) {
                        if (data.length > 0) {
                            alert(data);
                        } else {
                            $("#dialog-form").dialog("close");
                            $("#list").trigger("reloadGrid");
                        }
                    },
                    error:function () {
                        $("#dialog-form").dialog("close");
                        $('#list').trigger("reloadGrid");
                    }
                });
            },
            "Ортга":function () {
                $(this).dialog("close");
            }
        }
    });
    $("#mijoz-search")
        .button()
        .click(function () {
            $("#dialog-form-search").dialog("option", "title", "Параметрларни танланг:");
            $("#dialog-form-search").dialog("open");
        });
    $("#e_passport")
        .button()
        .click(function () {
            $("#error-form").dialog("open");
        });
    $("#full")
        .button()
        .click(function () {
            //alert("salom");
            $("#full-form").dialog("open");
        });
    $("#etasdiq")
        .button()
        .click(function () {
            $("#tasdiq-form").dialog("open");
        });
    $("#etable01")
        .button()
        .click(function () {
            $("#table01-form").dialog("open");
        });
    $("#et")
        .button()
        .click(function () {
            $("#et-form").dialog("open");
        });

    $("#tb")
        .button()
        .click(function () {
            $("#tb-form").dialog("open");
        });

    $("#tb-form").dialog({
        autoOpen:false,
        height:200,
        width:300,
        modal:true,
        buttons:{
            "Ортга":function () {
                $(this).dialog("close");
            }
        }
    });

    $("#rt_umumiy")
        .button()
        .click(function () {
            $("#rt_umumiy-form").dialog("open");
        });

    $("#rt_umumiy-form").dialog({
        autoOpen:false,
        height:200,
        width:300,
        modal:true,
        buttons:{
            "Ортга":function () {
                $(this).dialog("close");
            }
        }
    });

    $("#error-form").dialog({
        autoOpen:false,
        height:200,
        width:300,
        modal:true,
        buttons:{
            "Ортга":function () {
                $(this).dialog("close");
            }
        }
    });

    $("#full-form").dialog({
        autoOpen:false,
        height:200,
        width:300,
        modal:true,
        buttons:{
            "Ортга":function () {
                $(this).dialog("close");
            }
        }
    });
    $("#tasdiq-form").dialog({
        autoOpen:false,
        height:200,
        width:300,
        modal:true,
        buttons:{
            "Ортга":function () {
                $(this).dialog("close");
            }
        }
    });

    $("#table01-form").dialog({
        autoOpen:false,
        height:200,
        width:300,
        modal:true,
        buttons:{
            "Ортга":function () {
                $(this).dialog("close");
            }
        }
    });
    $("#et-form").dialog({
        autoOpen:false,
        height:200,
        width:300,
        modal:true,
        buttons:{
            "Ортга":function () {
                $(this).dialog("close");
            }
        }
    });

    $("#dialog-form-search").dialog({
        autoOpen:false,
        height:600,
        width:850,
        modal:true,
        buttons:{
            "Тозалаш":function () {
                $("#dialog-form-search").dialog("close");
                document.getElementById("illnesssearch-form").reset();
                $("[name=id]", $("#illnesssearch-form")).val("");
                $("#list").jqGrid('clearGridData', true);
                $("#list").jqGrid('setGridParam', {postData:null});
                $('#list').trigger("reloadGrid");
            },
            "Қидириш":function () {
                $("#dialog-form-search").dialog("close");
                var form_data = $("#illnesssearch-form").serializeArray();
                var params = new Object;
                for (var i = 0; i < form_data.length; i++) {
                    var f = form_data[i];
                    params[f["name"]] = f["value"];
                }
                $("#list").jqGrid('clearGridData', true);
                $("#list").jqGrid('setGridParam', {postData:params});
                $('#list').trigger("reloadGrid");
                $('#history').trigger("reloadGrid");
            },
            "Ортга":function () {
                $(this).dialog("close");
            }
        },
        open:function () {
            $("#accordion").accordion();
        }
    });

    $("#create-history")
        .button()
        .click(function () {
            document.getElementById("illhistory-form").reset();
            $("[name=id_mijoz]", $("#illhistory-form")).val(list_id);
            $("[name=id]", $("#illhistory-form")).val("");

            $("#ac-id_royhat_r-value").html("");
            $("#ac-id_kdaraja-value").html("");

            $("#history-form").dialog("option", "title", "Бемор касалликларини қўшиш");
            $("#history-form").dialog("open");

        });

    $("#history-form").dialog({
        autoOpen:false,
        height:700,
        width:900,
        modal:true,
        buttons:{
            "Сақлаш":function () {
                var valid = true;
                var saveForm = $("#illhistory-form");

                if (!$("[name=murojaat_sana]", saveForm).val()) {
                    valid = false;
                    $("[name=murojaat_sana]", saveForm).addClass("error");
                }

                if (!$("[name=beg_date]", saveForm).val()) {
                    valid = false;
                    $("[name=beg_date]", saveForm).addClass("error");
                }
                if (!$("[name=thistory]", saveForm).val()) {
                    valid = false;
                    $("[name=thistory]", saveForm).addClass("error");
                }


                if (valid) {
                    $.ajax({
                        url:"/respmonitoring/history_save",
                        type:"POST",
                        data:($("#illhistory-form").serializeArray()),
                        success:function (data) {
                            if (data.length > 0) {
                                alert(data);
                            } else {
                                $("#history-form").dialog("close");
                                $("#history").trigger("reloadGrid");
                                $("#murojaat").trigger("reloadGrid");
                                $("#wwork").trigger("reloadGrid");
                            }
                        },
                        error:function () {
                            // $("#history-form").dialog("close");
                            $('#history').trigger("reloadGrid");
                            $("#murojaat").trigger("reloadGrid");
                            $("#wwork").trigger("reloadGrid");
                        }
                    });
                } else {
                    // hamma polyalarni to'ldirish kerak, deb
                }
            },
            "Ортга":function () {
                $(this).dialog("close");
            }
        },
        close:function () {
        }
    });

    $("#edit-old")
        .button()
        .click(function () {
            var sel_row = $("#history").jqGrid("getGridParam", "selrow");
            if (sel_row) {
                row = $("#history").jqGrid("getRowData", sel_row);
                var form = $("#old-form");
                document.getElementById("old-form").reset();
                $("[name=id_mijoz]", form).val(list_id);
                $("[name=id]", form).val(row.id);
                $("[name=old]", form).val(row.old);

                $("#old-form").dialog("option", "title", "Тарихни ўзгартириш");
                $("#old-form").dialog("open");
            }
        });

    $("#edit-pcheck")
        .button()
        .click(function () {
            var sel_row = $("#history").jqGrid("getGridParam", "selrow");
            if (sel_row) {
                row = $("#history").jqGrid("getRowData", sel_row);
                var form = $("#pcheck-form");
                document.getElementById("pcheck-form").reset();
                $("[name=id_mijoz]", form).val(list_id);
                $("[name=id]", form).val(row.id);
                $("[name=id_pcheck]", form).val(row.pcheck);

                $("#pcheck-form").dialog("option", "title", "ПЖ маълумотномаси");
                $("#pcheck-form").dialog("open");
            }
        });

    $("#old-form").dialog({
        autoOpen:false,
        height:200,
        width:300,
        modal:true,
        buttons:{
            "Сақлаш":function () {
                $.ajax({
                    url:"/respmonitoring/old_save",
                    type:"POST",
                    data:($("#old-form").serializeArray()),
                    success:function (data) {
                        if (data.length > 0) {
                            alert(data);
                        } else {
                            $("#old-form").dialog("close");
                            $("#history").trigger("reloadGrid");
                        }
                    },
                    error:function () {
                        $("#old-form").dialog("close");
                        $('#history').trigger("reloadGrid");
                    }
                });
            },
            "Ортга":function () {
                $(this).dialog("close");
            }
        },
        close:function () {

        }
    });

    $("#pcheck-form").dialog({
        autoOpen:false,
        height:200,
        width:300,
        modal:true,
        buttons:{
            "Сақлаш":function () {
                $.ajax({
                    url:"/respmonitoring/pcheck_save",
                    type:"POST",
                    data:($("#pcheck-form").serializeArray()),
                    success:function (data) {
                        if (data.length > 0) {
                            alert(data);
                        } else {
                            $("#pcheck-form").dialog("close");
                            $("#history").trigger("reloadGrid");
                        }
                    },
                    error:function () {
                        $("#pcheck-form").dialog("close");
                        $('#history').trigger("reloadGrid");
                    }
                });
            },
            "Ортга":function () {
                $(this).dialog("close");
            }
        },
        close:function () {

        }
    });

    $("#edit-history")
        .button()
        .click(function () {
            var sel_row = $("#history").jqGrid("getGridParam", "selrow");
            if (sel_row) {
                row = $("#history").jqGrid("getRowData", sel_row);
                var form = $("#illehistory-form");
                document.getElementById("illehistory-form").reset();
                $("[name=id_mijoz]", form).val(list_id);
                $("[name=id]", form).val(row.id);
                $("[name=etmek_xulosasi]", form).val(row.tmek_xulosasi);
                $("[name=eid_checktype]", form).val(row.id_checktype);
                $("[name=emkb_9]", form).val(row.mkb_9);
                $("[name=eguruh]", form).val(row.guruh);
                $("[name=eid_sabab]", form).val(row.id_sabab);
                $("[name=ebeg_date]", form).val(row.beg_date);
                $("[name=eend_date_combo]", form).val(row.end_date_combo);
                $("[name=eid_royhat]", form).val(row.id_royhat);
                $("[name=eseriya]", form).val(row.seriya);
                $("[name=enomer]", form).val(row.nomer);
                $("[name=epdate]", form).val(row.pdate);
                $("[name=efoiz]", form).val(row.foiz);
                $("[name=eid_ortoped]", form).val(row.id_ortoped);
                $("[name=etdate]", form).val(row.tdate);
                $("[name=eknomer]", form).val(row.knomer);
                $("[name=ektashkilot]", form).val(row.ktashkilot);
                $("[name=ekriteria_1]", form).val(row.kriteria_1);
                $("[name=ekriteria_2]", form).val(row.kriteria_2);
                $("[name=ekriteria_3]", form).val(row.kriteria_3);
                $("[name=ekriteria_4]", form).val(row.kriteria_4);
                $("[name=ekriteria_5]", form).val(row.kriteria_5);
                $("[name=ekriteria_6]", form).val(row.kriteria_6);
                $("[name=ekriteria_7]", form).val(row.kriteria_7);
                $("[name=etrdate]", form).val(row.trdate);
                $("[name=ecode_ill_10_par]", form).val(row.mkb10_parent);
                $("[name=ecode_ill_10_par]", form).trigger("change");
                $("[name=etmek_xulosasi]", form).trigger("change");

                $("#ehistory-form").dialog("option", "title", "Касалликлар тарихини таҳрирлаш");
                $("#ehistory-form").dialog("open");
            }
        });
    $("#ehistory-form").dialog({
        autoOpen:false,
        height:600,
        width:1000,
        modal:true,
        buttons:{
            "Ортга":function () {
                $(this).dialog("close");
            }
        },
        close:function () {

        }
    });
});
