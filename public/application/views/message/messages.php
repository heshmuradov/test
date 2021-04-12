<?php
$is_mon = ($this->session->userdata['admin_type'] == 4 || $this->session->userdata['admin_type'] == 3);
?>
<body>
<div id="asosiy">
<?php $this->load->view("menu")?>
<div id="vback">

<style>
    body {
        font-size: 62.5%;
    }

    fieldset {
        padding: 0;
        border: 0; /*margin-top: 0px;*/
    }

    div#users-contain {
        width: 350px;
        margin: 20px 0;
    }

    div#users-contain table {
        margin: 1em 0;
        border-collapse: collapse;
        width: 100%;
    }

    div#users-contain table td, div#users-contain table th {
        border: 1px solid #eee;
        padding: .6em 10px;
        text-align: left;
    }

    .ui-dialog .ui-state-error {
        padding: .3em;
    }

    .validateTips {
        border: 1px solid transparent;
        padding: 0.3em;
    }

    label.error {
        font-family: Arial, sans-serif;
        font-size: 11px;
        color: #51b500;
    }

    input.error, select.error {
        border: 3px solid #a00;
        background-color: #fcc;

    }

</style>

<div class="demo">

    <div id="tabs">
        <ul>
            <li><a href="#fragment-1"><span>Келган хабарлар</span></a></li>
            <li><a href="#fragment-2"><span>Жўнатилган хабарлар</span></a></li>
        </ul>
        <div id="fragment-1">
            <button id="mijoz-search" style="float:right;">Умумий қидириш</button>
            <BR>
            <BR>
            <BR>

            <table id="list" class="scroll"></table>
            <div id="pager" class="scroll" style="text-align:center;"></div>
            <BR clear="all">
            <BR clear="all">

            <table id="message-detail" class="scroll"></table>
            <div id="message-pager" class="scroll" style="text-align:center;"></div>
            <BR/>
        </div>
        <div id="fragment-2">
            <button id="sent-search" style="float:right;">Қидириш</button>
            <button id="new-message" style="float:right; margin-right: 5px;">Хабар жўнатиш</button>
            <BR clear="all">
            <BR clear="all">
            <BR clear="all">
            <table id="sent" class="scroll"></table>
            <div id="sent-pager" class="scroll" style="text-align:center;"></div>
        </div>
        <br clear="all"/>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        $("#tabs").tabs();
    });

</script>

<style>
    @import "/css/new/jquery-ui-1.8.21.custom.css";
    @import "/css/ui_jqgrid.css";
</style>
<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.21.custom.min.js"></script>
<script type="text/javascript" src="/js/grid.locale-uz.js"></script>
<script type="text/javascript" src="/js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="/js/grid.common.js"></script>
<script type="text/javascript" src="/js/grid.formedit.js"></script>
<script type="text/javascript" src="/js/jquery.searchFilter.js"></script>
<script type="text/javascript" src="/js/ui.multiselect.js"></script>
<script type="text/javascript" src="/js/jqModal.js"></script>
<script type="text/javascript" src="/js/jqDnR.js"></script>
<script type="text/javascript" src="/js/grid.subgrid.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script type="text/javascript">
    var list_id;
    $(function () {
        var mygrid = $('#list').jqGrid({
            url:'/messages/search',
            height:340,
            datatype:'json',
            mtype:'POST',
            colNames:['№', 'Санаси', 'Кимдан', 'Мавзу', 'Кўрилганлиги'],
            colModel:[
                {name:'id', index:'id', hidden:true},
                {name:'created_date', index:'created_date', width:20, editable:true, "formatter":"date", "formatoptions":{"srcformat":"Y-m-d H:i:s", "newformat":"d-F, Y"}, "editoptions":{"dataInit":function (el) {
                    setTimeout(function () {
                        if (jQuery.ui) {
                            if (jQuery.ui.datepicker) {
                                jQuery(el).after('<button>Calendar</button>').next().button({icons:{primary:'ui-icon-calendar'}, text:false}).css({'font-size':'69%'}).click(function (e) {
                                    jQuery(el).datepicker('show');
                                    return false;
                                });
                                jQuery(el).datepicker({"disabled":false, "dateFormat":"yy-mm-dd", "changeYear":true, yearRange:"1940:2050"});
                                jQuery('.ui-datepicker').css({'font-size':'100%'});
                            }
                        }
                    }, 100);
                }}},
                {name:'id_depart', index:'id_depart', width:30, editable:true, edittype:'textarea'},
                {name:'subject', index:'subject', width:30, editable:true, edittype:'textarea'},
                {name:'viewed', index:'viewed', width:10, editable:true, edittype:'checkbox', editoptions:{ value:"1:0"}, formatter:"checkbox", formatoptions:{disabled:true}}
            ],
            rownumbers:true,
            rownumWidth:20,
            rowNum:15,
            sortorder:"desc",
            rowList:[10, 15, 30, 50],
            sortname:'created_date',
            gridview:true,
            viewrecords:true,
            pager:'#pager',
            caption:'Департаментга келган хабарлар',
            autowidth:true,
            onSelectRow:function (ids) {
                list_id = ids;
                if (ids == null) {
                    if (jQuery("#message-detail").jqGrid('getGridParam', 'records') > 0) {
                        jQuery("#message-detail").jqGrid('clearGridData');
                    }

                } else {

                    jQuery("#message-detail").jqGrid('setGridParam', {url:"/messages/mdetail/" + ids, page:1});
                    jQuery("#message-detail").trigger('reloadGrid');
                }
            }
        });
    <?php if (!$is_mon) : ?>
        $("#list").jqGrid('navGrid', '#pager', {add:false, del:false, edit:false, refresh:false, search:false, view:false}
        );
        <?php endif; ?>

        $('#message-detail').jqGrid({
            datatype:'json',
            mtype:'POST',
            colModel:[
                {name:'id', index:'id', hidden:true},
                {name:'message', index:'message', editable:true, edittype:'textarea'}
            ],
            //rowNum:10,
            rowList:[2, 10, 20, 30],
            viewrecords:true,
            sortorder:"asc",
            height:150,
            pager:'#message-pager',
            caption:'Хабар мазмуни',
            autowidth:true
        });

        $('#sent').jqGrid({
            url:'/messages/sentmesg',
            height:600,
            width: 850,
            datatype:'json',
            mtype:'POST',
            colNames:['№', 'Санаси', 'Кимга', 'Мавзу', 'Хабар'],
            colModel:[
                {name:'id', index:'id', hidden:true},
                {name:'cr_date', index:'cr_date', width:20, editable:true, "formatter":"date", "formatoptions":{"srcformat":"Y-m-d H:i:s", "newformat":"d-F, Y"}, "editoptions":{"dataInit":function (el) {
                    setTimeout(function () {
                        if (jQuery.ui) {
                            if (jQuery.ui.datepicker) {
                                jQuery(el).after('<button>Calendar</button>').next().button({icons:{primary:'ui-icon-calendar'}, text:false}).css({'font-size':'69%'}).click(function (e) {
                                    jQuery(el).datepicker('show');
                                    return false;
                                });
                                jQuery(el).datepicker({"disabled":false, "dateFormat":"yy-mm-dd", "changeYear":true, yearRange:"1940:2050"});
                                jQuery('.ui-datepicker').css({'font-size':'100%'});
                            }
                        }
                    }, 100);
                }}},
                {name:'to_depart', index:'to_depart', width:30, editable:true, edittype:'textarea'},
                {name:'to_subject', index:'to_subject', width:30, editable:true, edittype:'textarea'},
                {name:'message', index:'message', width:30, editable:true, edittype:'textarea'}
            ],
            rownumbers:true,
            rownumWidth:20,
            rowNum:15,
            sortorder:"desc",
            rowList:[10, 15, 30, 50],
            sortname:'id',
            gridview:true,
            viewrecords:true,
            pager:'#sent-pager',
            caption:'Департаментга келган хабарлар',
            onSelectRow:function (ids) {
                list_id = ids;
                if (ids == null) {
                    if (jQuery("#message-detail").jqGrid('getGridParam', 'records') > 0) {
                        jQuery("#message-detail").jqGrid('clearGridData');
                    }

                } else {

                    jQuery("#message-detail").jqGrid('setGridParam', {url:"/messages/mdetail/" + ids, page:1});
                    jQuery("#message-detail").trigger('reloadGrid');
                }
            }
        });


    });

</script>


<div id="dialog-form-search" title="Қидирув параметрларини танланг">
    <form id="illnesssearch-form" action="/monitoring/search_excel" method="POST">
        <div>
            <table border="0" width="100%">
                <TR>
                    <TD>
                        <label style="display:block;">ТМЭК</label>
                        <select style="margin-bottom:10px; width:100%; padding: .4em; display:block;"
                                name="fid_depart" id="fid_depart"
                                class="text ui-widget-content ui-corner-all">
                            <option value=""></option>
                            <?php foreach ($id_depart as $idep) : ?>
                            <option value="<?php echo $idep->id; ?>"><?php echo $idep->name_uz; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </TD>
                </TR>
                <TR>
                    <TD>
                        <label style="display:block; ">Сана:</label>
                    </TD>
                    <TD><label style="display:block; ">дан:</label>
                        <input style="margin-bottom:12px;  padding: .3em; display:block;" type="text"
                               name="fcreated_date" id="fcreated_date" value=""
                               class="text ui-widget-content ui-corner-all">
                    </TD>
                    <TD><label style="display:block; ">гача:</label>
                        <input style="margin-bottom:12px; padding: .3em; display:block;" type="text"
                               name="fcreated_date_gacha" id="fcreated_date_gacha" value=""
                               class="text ui-widget-content ui-corner-all">
                    </TD>
                    <TD>&nbsp;</TD>
                </TR>
            </table>
        </div>

        <BR clear="all"/>
        <BR clear="all"/>
    </form>
</div>

<div id="dialog-form-search-sent" title="Қидирув параметрларини танланг">
    <form id="illnesssearch-sent-form">
        <div>
            <table border="0" width="100%">
                <TR>
                    <TD>
                        <label style="display:block;">Жўнатилган ТМЭК номи:</label>
                        <select style="margin-bottom:10px; width:100%; padding: .4em; display:block;"
                                name="fid_depart-sent" id="fid_depart-sent"
                                class="text ui-widget-content ui-corner-all">
                            <option value=""></option>
                            <?php foreach ($id_depart as $idep) : ?>
                            <option value="<?php echo $idep->id; ?>"><?php echo $idep->name_uz; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </TD>
                </TR>
                <TR>
                    <TD>
                        <label style="display:block; ">Сана:</label>
                    </TD>
                    <TD><label style="display:block; ">дан:</label>
                        <input style="margin-bottom:12px;  padding: .3em; display:block;" type="text"
                               name="fcreated_date-sent" id="fcreated_date-sent" value=""
                               class="text ui-widget-content ui-corner-all">
                    </TD>
                    <TD><label style="display:block; ">гача:</label>
                        <input style="margin-bottom:12px; padding: .3em; display:block;" type="text"
                               name="fcreated_date_gacha-sent" id="fcreated_date_gacha-sent" value=""
                               class="text ui-widget-content ui-corner-all">
                    </TD>
                    <TD>&nbsp;</TD>
                </TR>
            </table>
        </div>

        <BR clear="all"/>
        <BR clear="all"/>
    </form>
</div>

<div id="new-dialog">
    <form id="new-dialog-form">
        <div>
            <table border="0" width="100%">
                <TR>
                    <TD>
                        <label style="display:block;">ТМЭКни танланг:</label>
                        <select style="margin-bottom:10px; width:100%; padding: .4em; display:block;"
                                name="new-id_depart" id="new-id_depart"
                                class="text ui-widget-content ui-corner-all">
                            <option value=""></option>
                            <?php foreach ($id_depart as $idep) : ?>
                            <option value="<?php echo $idep->id; ?>"><?php echo $idep->name_uz; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </TD>
                </TR>
                <TR>
                    <TD>
                        <label style="display:block; ">Мавзу:</label>
                        <input style="margin-bottom:12px; width: 90%;  padding: .3em; display:block;" type="text"
                               name="new-subject" id="new-subject" value=""
                               class="text ui-widget-content ui-corner-all">
                    </TD>
                    </TR>
                <TR>
                    <TD>
                        <label style="display:block; ">Хабар:</label>
                        <textarea cols="100" rows="20"  name="new-message" id="new-message"></textarea>
<!--                        <input style="margin-bottom:12px;  padding: .3em; display:block;" type="textarea" -->
<!--                               name="new-message" id="new-message" value=""-->
<!--                               class="text ui-widget-content ui-corner-all">-->
                    </TD>
                </TR>
            </table>
        </div>

        <BR clear="all"/>
        <BR clear="all"/>
    </form>
</div>


</div>

<script type="text/javascript">
    $(function () {

        $("#new-message")
            .button()
            .click(function () {
                document.getElementById("new-dialog-form").reset();
                $("#new-dialog").dialog("option", "title", "Хабар жўнатиш");
                $("#new-dialog").dialog("open");
            });


        $("#mijoz-search")
            .button()
            .click(function () {
                $("#dialog-form-search").dialog("option", "title", "Параметрларни танланг:");
                $("#dialog-form-search").dialog("open");
            });


        $("#dialog-form-search").dialog({
            autoOpen:false,
            height:300,
            width:400,
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

        $("#new-dialog").dialog({
            autoOpen:false,
            height:600,
            width:600,
            modal:true,
            buttons: {
                "Жўнатиш": function() {
                    $.ajax({
                        url: "/messages/newmesg",
                        type: "POST",
                        data: ($("#new-dialog-form").serializeArray()),
                        success: function (data) {
                            if (data.length > 0) {
                                alert(data);
                            } else {
                                $("#new-dialog").dialog("close");
                                $("#sent").trigger("reloadGrid");
                            }
                        },
                        error: function () {
                            $("#new-dialog").dialog("close");
                            $('#sent').trigger("reloadGrid");
                        }
                    });
                },
                "Ортга": function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#sent-search")
            .button()
            .click(function () {
                $("#dialog-form-search-sent").dialog("option", "title", "Параметрларни танланг:");
                $("#dialog-form-search-sent").dialog("open");
            });

        $("#dialog-form-search-sent").dialog({
            autoOpen:false,
            height:300,
            width:400,
            modal:true,
            buttons:{
                "Тозалаш":function () {
                    $("#dialog-form-search-sent").dialog("close");
                    document.getElementById("illnesssearch-form-sent").reset();
                    $("[name=id]", $("#illnesssearch-form-sent")).val("");
                    $("#list").jqGrid('clearGridData', true);
                    $("#list").jqGrid('setGridParam', {postData:null});
                    $('#list').trigger("reloadGrid");
                },
                "Қидириш":function () {
                    $("#dialog-form-search-sent").dialog("close");
                    var form_data = $("#illnesssearch-form-sent").serializeArray();
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


    });

</script>

</body>
</html>