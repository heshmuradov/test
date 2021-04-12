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

<div class="demo">
    <button id="mijoz-search" style="float:right;">Умумий қидириш</button>
    <BR>
    <BR>
    <BR>
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
            <li><a href="#fragment-1"><span>Пенсия маълумотномаси</span></a></li>
        </ul>
        <div id="fragment-1">
            <table id="history" class="scroll"></table>
            <div id="history-pager" class="scroll" style="text-align:center;"></div>
            <BR/>
            <?php if (!$is_mon) : ?>
            <!--            <button id="create-history">Ногиронлик тарихини қўшиш</button>-->
            <!--            <button id="edit-history">Ногиронлик тарихини таҳрирлаш</button>-->
            <?php endif; ?>
        </div>

        <br clear="all"/>
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

    function passcheck(value, colname) {
        if (colname == 'П.рақам' && value.length == 7) {
            return [true, "", ""];
        } else {
            return [false, "Pass_code 7 ta sondan tashkil topgan bo'lishi kerak", ""];
        }
    }
    $(function () {
        var mygrid = $('#list').jqGrid({
            url:'/pension/search',
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
                {name:'pass_code', index:'pass_code', width:15, editable:true, edittype:'text', editrules:{edithidden:true, required:true, custom:true, custom_func:passcheck, number:true}},
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
            caption:'Жамғарма томонодан текширилган ногиронлар рўйхати',
            autowidth:true,
            onSelectRow:function (ids) {
                list_id = ids;
                if (ids == null) {
                    if (jQuery("#history").jqGrid('getGridParam', 'records') > 0) {
                        jQuery("#history").jqGrid('clearGridData');
                    }
                } else {

                    jQuery("#history").jqGrid('setGridParam', {url:"/pension/history/" + ids, page:1});
                    jQuery("#history").trigger('reloadGrid');
                }
            }
        });
        $("#list").jqGrid('navGrid', '#pager', {add:false, del:false, edit:false, refresh:false, search:false, view:true});

        $('#history').jqGrid({
            datatype:'json',
            mtype:'POST',
            colNames:['№', 'Пенсия', 'ПЖ ўтган вақти', 'Кўрик', 'Гуруҳ', 'Ногиронлик сабаби', 'Рўйхат',
                'Д.кўрик', 'Қайта кўрик', 'Справка серия', 'Справка номер', 'Берилган вақти', 'Фоиз'],
            colModel:[
                {name:'id', index:'id', width:10, sorttype:'int', hidden:true},
                {name:'pcheck', index:'pcheck', width:15},
                {name:'pcheck_date', index:'pcheck_date', width:15},
                {name:'id_checktype', index:'id_checktype', width:20},
                {name:'guruh', index:'guruh', width:15},
                {name:'id_sabab', index:'id_sabab', width:40},
                {name:'id_royhat', index:'id_royhat', width:45},
                {name:'beg_date', index:'beg_date', width:20},
                {name:'end_date', index:'end_date', width:20},
                {name:'seriya', index:'seriya', width:15},
                {name:'nomer', index:'nomer', width:20},
                {name:'pdate', index:'pdate', width:20},
                {name:'foiz', index:'foiz', width:10}
            ],
            //rowNum:10,
            rowList:[2, 10, 20, 30],
            sortname:'beg_date',
            viewrecords:true,
            sortorder:"asc",
            height:150,
            pager:'#history-pager',
            caption:'Касалликлар тарихи',
            autowidth:true
        });

        $("#history").jqGrid('navGrid', '#history-pager', {add:false, del:false, edit:false, search:false, view:true});

    });
</script>
<div id="dialog-form-search" title="Қидирув параметрларини танланг">
<form id="illnesssearch-form" action="/pension/search_excel" method="POST">
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
                    <option value="<?php echo $b->id; ?>"><?php echo $b->pass_seriya; ?></option>
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
            <TD>
                <label style="display:block;">ТМЭК</label>
                <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                        name="fid_depart" id="fid_depart"
                        class="text ui-widget-content ui-corner-all">
                    <option value=""></option>
                    <?php foreach ($id_depart as $idep) : ?>
                    <option value="<?php echo $idep->id; ?>"><?php echo $idep->name_uz; ?></option>
                    <?php endforeach; ?>
                </select>
            </TD>
        </TR>
    </table>
</div>

<h3><a href="#">Пенсия маълумотномасига оид параметрлар</a></h3>

<div>
    <table border="0" width="100%">
        <TR>
            <TD>
                <label style="display:block; margin-left: 5px;">Пенсия оляпти:</label>
                <select style="margin-bottom:10px; margin-left: 5px; padding: .3em; display:block; "
                        name="fpcheck" id="fpcheck"
                        class="text ui-widget-content ui-corner-all">
                    <option value=""></option>
                    <option value="1">Пенсия текширилди</option>
                    <option value="2">Пенсия оляпти</option>
                </select>
            </TD>
            <TD>
                <label style="display:block; ">Пенсия маълумотномаси текширилган муддат:</label>
            </TD>
            <TD><label style="display:block; ">дан:</label>
                <input style="margin-bottom:12px;  padding: .3em; display:block;" type="text"
                       name="fpcheck_date" id="fpcheck_date" value="" class="text ui-widget-content ui-corner-all">
            </TD>
            <TD><label style="display:block; ">гача:</label>
                <input style="margin-bottom:12px; padding: .3em; display:block;" type="text"
                       name="fpcheck_date_gacha" id="pcheck_date_gacha" value=""
                       class="text ui-widget-content ui-corner-all">
            </TD>
        </TR>
        <TR>
            <TD>
                <label style="display:block; margin-left: 5px;">Ногиронлик гуруҳи:</label>
                <input style="margin-bottom:12px; margin-left: 5px; padding: .3em; display:block; " type="text"
                       name="fguruh" id="fguruh" value="" class="text ui-widget-content ui-corner-all">
            </TD>
            <TD>
                <label style="display:block; margin-left: 5px;">Сабаби:</label>
                <select style="margin-bottom:10px; padding: .3em; display:block; width: 50%"
                        name="fid_sabab" id="fid_sabab"
                        class="text ui-widget-content ui-corner-all">
                    <option value=""></option>
                    <?php foreach ($sabab as $s) : ?>
                    <option value="<?php echo $s->id; ?>"><?php echo $s->sabab_uz; ?></option>
                    <?php endforeach; ?>
                </select>
            </TD>
            <TD>
                <label style="display:block; ">Рўйхатда:</label>
                <select style="margin-bottom:10px;  padding: .3em; display:block; width: 80%"
                        name="fid_royhat" id="fid_royhat"
                        class="text ui-widget-content ui-corner-all ">
                    <option value=""></option>
                    <?php foreach ($royhatuz as $r) : ?>
                    <option value="<?php echo $r->id; ?>"><?php echo $r->name_uz; ?></option>
                    <?php endforeach; ?>
                </select>
            </TD>
            <td>
                &nbsp;
            </td>
        </TR>
        <TR>
            <TD>
                <label style="display:block; ">Рўйхатдан ўтган муддати:</label>
            </TD>
            <TD><label style="display:block; ">дан:</label>
                <input style="margin-bottom:12px;  padding: .3em; display:block;" type="text"
                       name="fbeg_date" id="fbeg_date" value="" class="text ui-widget-content ui-corner-all">
            </TD>
            <TD><label style="display:block; ">гача:</label>
                <input style="margin-bottom:12px; padding: .3em; display:block;" type="text"
                       name="fbeg_date_gacha" id="fbeg_date_gacha" value=""
                       class="text ui-widget-content ui-corner-all">
            </TD>
            <TD>&nbsp;</TD>
        </TR>
        <TR>
            <TD>
                <label style="display:block; ">Қайта рўйхатдан ўтиш муддати:</label>
            </TD>
            <TD>
                <label style="display:block; ">дан:</label>
                <input style="margin-bottom:12px;  padding: .3em; display:block;" type="text"
                       name="fend_date" id="fend_date" value="" class="text ui-widget-content ui-corner-all">

            </TD>
            <TD>
                <label style="display:block; ">гача:</label>
                <input style="margin-bottom:12px;  padding: .3em; display:block;" type="text"
                       name="fend_date_gacha" id="fend_date_gacha" value=""
                       class="text ui-widget-content ui-corner-all">
            </TD>
            <td><label style="display:block;">Муддатсиз</label>
                <input style="margin-bottom:12px; padding: .3em; display:block;" type="checkbox" name="fmd" id="fmd"
                       class="text ui-widget-content ui-corner-all"/>
            </td>
        </TR>
        <TR>
            <TD>
                <label style="display:block; ">Пенсия маълумотномаси:</label>
            </TD>
            <TD>
                <label style="display:block;">Cерия:</label>
                <input style="margin-bottom:12px; padding: .3em; display:block; " type="text"
                       name="fseriya" id="fseriya" value="" class="text ui-widget-content ui-corner-all">
            </TD>
            <TD>
                <label style="display:block; ">Рақами:</label>
                <input style="margin-bottom:12px;  padding: .3em; display:block; " type="text"
                       name="fnomer" id="fnomer" value="" class="text ui-widget-content ui-corner-all">
            </TD>
            <TD>
                <label style="display:block; ">Расмийлаштирилган сана:</label>
                <input style="margin-bottom:12px;  padding: .3em; display:block; " type="text"
                       name="fpdate" id="fpdate" value="" class="text ui-widget-content ui-corner-all">
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
</div>

<script type="text/javascript">
    $(function () {
      initNumberInput();
  initPinflInput();
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
            },
            open:function () {
                $("#accordion").accordion();
            }
        });

    });


</script>

</body>
</html>
