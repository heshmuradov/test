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

    input.error {
        border: 1px solid #51b500;
    }
</style>

<div id="dialog-form-search" title="Қидирув параметрларини танланг">
    <form id="illnesssearch-form" action="/approve/search_excel" method="POST">
        <form id="illnesssearch-form">
            <div id="accordion">
                <h3><a href="#">Умумий маълумотлар</a></h3>

                <div>
                    <table border="0" width="100%">
                        <TR>
                            <TD>
                                <label style="display:block;">Уникал рақами:</label>
                                <input style="margin-bottom:12px; padding: .4em; display:block;" type="text"
                                       name="fid" id="fid" value=""
                                       class="text ui-widget-content ui-corner-all"/>
                            </TD>
                            <TD>
                                <label style="display:block;">Фамилияси</label>
                                <input style="margin-bottom:12px; padding: .4em; display:block;" type="text"
                                       name="ffamiliya" id="ffamiliya" value=""
                                       class="text ui-widget-content ui-corner-all"/>
                            </TD>
                            <TD>
                                <label style="display:block;">Исми</label>
                                <input style="margin-bottom:12px; padding: .4em; display:block;" type="text"
                                       name="fname" id="fname" class="text ui-widget-content ui-corner-all"/>
                            </TD>
                            <TD>
                                <label style="display:block;">Туғилган санаси</label>
                                <input style="margin-bottom:12px; padding: .4em; display:block;" type="text"
                                       name="fdate_birth" id="fdate_birth" value=""
                                       class="text ui-widget-content ui-corner-all"/>
                            </TD>
                        </TR>
                        <TR>
                            <TD>
                                <label style="display:block;"> Жинси:</label>
                                <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                                        name="fgender" id="fgender"
                                        class="text ui-widget-content ui-corner-all code-ill">
                                    <option value=""></option>
                                    <option value="1">Эркак</option>
                                    <option value="2">Аёл</option>
                                </select>
                            </TD>
                            <TD>
                                <label style="display:block;">Паспорт серияси</label>
                                <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                                        name="fpass_seriya" id="fpass_seriya"
                                        class="text ui-widget-content ui-corner-all">
                                    <option value=""></option>
                                    <?php foreach ($fpass_seriya as $b) : ?>
                                    <option
                                        value="<?php echo $b->pass_seriya; ?>"><?php echo $b->pass_seriya; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </TD>
                            <TD>
                              <label style="display:block;">Паспорт рақами</label>
                              <input style="margin-bottom:12px; padding: .4em; display:block;" type="text"
                                    name="fpass_code" id="fpass_code" value=""
                                    class="text only-numbers ui-widget-content ui-corner-all"/>
                            </TD>
                            <TD>
                                <label style="display:block;">ПИНФЛ:</label>
                                <input maxlength="14" style="margin-bottom:12px; padding: .4em; display:block;" type="text"
                                      name="finfl" id="finfl" class="text pinfl only-numbers ui-widget-content ui-corner-all"/>
                            </TD>

                        </TR>
                        <TR>
                          <TD>
                                <label style="display:block;">Турар жойи:</label>
                                <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                                        name="fid_place" id="fid_place"
                                        class="text ui-widget-content ui-corner-all code-ill">
                                    <option value=""></option>
                                    <?php foreach ($fplace as $p) : ?>
                                    <option value="<?php echo $p->id; ?>"><?php echo $p->name_uz; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </TD>
                            <TD>
                                <label style="display:block;">Ҳудуди</label>
                                <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                                        name="fid_born" id="fid_born"
                                        class="text ui-widget-content ui-corner-all">
                                    <option value=""></option>
                                    <?php foreach ($fborn as $b) : ?>
                                    <option value="<?php echo $b->id; ?>"><?php echo $b->name_uz; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </TD>
                        </TR>
                    </table>
                </div>
            </div>
            <BR clear="all"/>
            <BR clear="all"/>
            <input type="submit" value="Натижаларни Excelга юклаш" name="submit">
        </form>
</div>

<div class="demo">
    <button id="mijoz-search" style="float:right;">Умумий қидириш</button>
    <br clear="all">
    <br clear="all">
    <table id="list" class="scroll"></table>
    <div id="pager" class="scroll" style="text-align:center;"></div>
    <BR clear="all">
    <BR clear="all">

    <script type="text/javascript">

        $(document).ready(function () {
            $("#tabs").tabs();
        });

    </script>

    <div id="tabs">
        <ul>
            <li><a href="#fragment-1"><span>Касаллик тарихи</span></a></li>
        </ul>
        <div id="fragment-1">
            <table id="history" class="scroll"></table>
            <div id="history-pager" class="scroll" style="text-align:center;"></div>

        </div>
    </div>
</div>

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
      initNumberInput();
  initPinflInput();
        var mygrid = $('#list').jqGrid({
            url:'/approve/search',
            height:340,
            datatype:'json',
            mtype:'POST',
            colNames:['Ун.Рақам', 'Фамилияси', 'Исми', 'Шарифи', 'Туғ. сана',
                'Жинси', 'П.серия', 'П.рақам', 'ПИНФЛ', 'Ҳудуди', 'Турар жойи', 'Манзили', 'Фуқаро эмас'],
            colModel:[
                {name:'id', index:'id', width:15},
                {name:'familiya', index:'familiya', width:25, editable:true, edittype:'text', editrules:{edithidden:true, required:true}},
                {name:'name', index:'name', width:25, editable:true, edittype:'text', editrules:{edithidden:true, required:true}},
                {name:'middle', index:'middle', width:25, editable:true, edittype:'text'},
                {name:'date_birth', index:'date_birth', width:20, editable:true, "formatter":"date", "formatoptions":{"srcformat":"Y-m-d H:i:s", "newformat":"Y-m-d"}, "editoptions":{"dataInit":function (el) {
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
                {name:'gender', index:'gender', width:10, editable:true, edittype:'select', editoptions:{value:"<?php print $sgen;?>"}, editrules:{edithidden:true, required:true}},
                {name:'pass_seriya', index:'pass_seriya', width:10, editable:true, edittype:'text', editable:true, edittype:'select', editoptions:{value:"<?php print $pass_seriya;?>"}, editrules:{edithidden:true, required:true}},
                {name:'pass_code', index:'pass_code', width:15, editable:true, edittype:'text', editrules:{edithidden:true, required:true, number:true}},
                {name:'infl', index:'infl', width:15, editable:true, edittype:'text', editrules:{edithidden:true}},
                {name:'id_born', index:'id_born', width:25, editable:true, edittype:'select', editoptions:{value:"<?php print $sborn;?>"}, editrules:{edithidden:true, required:true}},
                {name:'id_place', index:'id_place', width:15, editable:true, edittype:'select', editoptions:{value:"<?php print $splace;?>"}, editrules:{edithidden:true, required:true}},
                {name:'address', index:'address', width:30, editable:true, edittype:'textarea'},
                {name:'notcitizen', index:'notcitizen', width:10, editable:true, edittype:'checkbox', editoptions:{ value:"1:0"}, formatter:"checkbox", formatoptions:{disabled:true}}
            ],
            rownumbers:true,
            rownumWidth:20,
            rowNum:15,
            sortorder:"desc",
            rowList:[10, 15, 30, 50],
            sortname:'id',
            gridview:true,
            viewrecords:true,
            pager:'#pager',
            caption:'Тасдиқланиши керак бўлган ногиронлар рўйхати',
            autowidth:true,
            onSelectRow:function (ids) {
                list_id = ids;
                if (ids == null) {
                    if (jQuery("#history").jqGrid('getGridParam', 'records') > 0) {
                        jQuery("#history").jqGrid('clearGridData');
                    }
                }
                else {
                    jQuery("#history").jqGrid('setGridParam', {url:"/approve/history/" + ids, page:1});
                    jQuery("#history").trigger('reloadGrid');
                }
            }
        });

        $('#history').jqGrid({
            datatype:'json',
            mtype:'POST',
            colModel:[
                {label:'№', name:'id', width:10, sorttype:'int', hidden:true},
                {label:'tmek_xulosasi', name:'tmek_xulosasi', hidden:true},
                {label:'ТМЕК хулосаси', name:'xulosa_name', width:20},
                {name:'id_checktype', hidden:true},
                {label:'Кўрик', name:'checktype_name', width:15},
                {name:'mkb_9', hidden:true},
                {label:'МКБ-9', name:'mkb9', width:10},
                {name:'mkb_10', hidden:true},
                {label:'МКБ-10', name:'code_10', width:10},
                {label:'Гуруҳ', name:'guruh', width:10},
                {name:'id_sabab', hidden:true},
                {label:'Ногиронлик сабаби', name:'sabab_name', width:30},
                {label:'Кўрик сана', name:'beg_date', width:25},
                {label:'end_date_combo', name:'end_date_combo', hidden:true},
                {label:'Ног.тугаш санаси', name:'end_date', width:25},
                {name:'id_royhat', hidden:true},
                {label:'Рўйхатда', name:'royhat_name', width:40},
                {label:'Тасдиқлаш', name:'approve', index:'approve', width:10, editable:true, edittype:'checkbox', editoptions:{value:"1:0"}, formatter:"checkbox", formatoptions:{disabled:true}}
            ],
            //rowNum:10,
            rowList:[2, 10, 20, 30],
            sortname:'beg_date',
            viewrecords:true,
            sortorder:"asc",
            height:150,
            pager:'#history-pager',
            caption:'Ногиронлик тарихи',
            autowidth:true,
            subGrid:true,
            beforeRequest:function () {
                $('#history').setGridParam({editurl:"/approve/edit_history/" + list_id});
            },
            subGridRowExpanded:function (subgridid, id) {
                var data = {subgrid:subgridid, rowid:id};
                $("#" + jQuery.jgrid.jqID(subgridid)).load('/approve/history_detail', data);
            }
        });

        $("#history").jqGrid('navGrid', '#history-pager', {add:false, del:false, edit:true, search:false, view:true},
            {closeAfterEdit:true},
            {closeAfterAdd:true}
        );

    });
</script>
</div>

<script type="text/javascript">

    $("#mijoz-search")
        .button()
        .click(function () {
            $("#dialog-form-search").dialog("option", "title", "Параметрларни танланг:");
            $("#dialog-form-search").dialog("open");
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
        }
    });

</script>

</body>
</html>
