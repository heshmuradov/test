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
            $("#illhistory-form").validate({
                rules:{
                    protocol:{
                        required:true
                    }
                },
                messages:{
                    protocol:{
                        required:"Протокол номери киритилиши шарт"
                    }
                },
                errorPlacement:function (error, element) {
                    element.before(error);
                }
            });
        });

        $(document).ready(function () {
            $("#tabs").tabs();
        });

    </script>

    <div id="tabs">
        <ul>
            <li><a href="#fragment-1"><span>Касаллик тарихи</span></a></li>
            <li><a href="#fragment-2"><span>Ногирон мурожаати</span></a></li>
            <li><a href="#fragment-3"><span>Иш жойи маълумотлари</span></a></li>
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
        <div id="fragment-2">
            <table id="murojaat" class="scroll"></table>
            <div id="murojaat-pager" class="scroll" style="text-align:center;"></div>
        </div>
        <div id="fragment-3">
            <table id="wwork" class="scroll"></table>
            <div id="wwork-pager" class="scroll" style="text-align:center;"></div>
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
        url:'/illnesskk/search',
        height:340,
        datatype:'json',
        mtype:'POST',
        colNames:['Ун.Рақам', 'Фамилияси', 'Исми', 'Шарифи', 'Туғ. сана',
            'Жинси', 'П.серия', 'П.рақам', 'ПИНФЛ', 'Ҳудуди', 'Турар жойи', 'Манзили', 'Фуқаро эмас'],
        colModel:[
            {name:'id', index:'id', width:15},
            {name:'familiya', index:'familiya', width:25, editable:false, edittype:'text', editrules:{edithidden:true, required:true}},
            {name:'name', index:'name', width:25, editable:false, edittype:'text', editrules:{edithidden:true, required:true}},
            {name:'middle', index:'middle', width:25, editable:false, edittype:'text'},
            {name:'date_birth', index:'date_birth', width:20, editable:false, "formatter":"date", "formatoptions":{"srcformat":"Y-m-d H:i:s", "newformat":"Y-m-d"}, "editoptions":{"dataInit":function (el) {
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
            {name:'gender', index:'gender', width:10, editable:false, edittype:'select', editoptions:{value:"<?php print $sgen;?>"}, editrules:{edithidden:true, required:true}},
            {name:'pass_seriya', index:'pass_seriya', width:10, editable:false, edittype:'text'},
            {name:'pass_code', index:'pass_code', width:15, editable:false, edittype:'text', editrules:{edithidden:true, required:true, custom:true, custom_func:passcheck, number:true}},
            {name:'infl', index:'infl', width:15, editable:true, edittype:'text', editrules:{edithidden:true}},
            {name:'id_born', index:'id_born', width:25, editable:true, edittype:'select', editoptions:{value:"<?php print $sborn;?>"}, editrules:{edithidden:true, required:true}},
            {name:'id_place', index:'id_place', width:15, editable:true, edittype:'select', editoptions:{value:"<?php print $splace;?>"}, editrules:{edithidden:true, required:true}},
            {name:'address', index:'address', width:30, editable:true, edittype:'textarea'},
            {name:'notcitizen', index:'notcitizen', width:10, editable:false, edittype:'checkbox', editoptions:{ value:"1:0"}, formatter:"checkbox", formatoptions:{disabled:true}}
        ],
        rownumbers:true,
        rownumWidth:20,
        rowNum:15,
        sortorder:"desc",
        rowList:[10, 15, 30, 50],
        sortname:'id',
        gridview:true,
        viewrecords:true,
        editurl:'/illnesskk/edit_mijoz',
        pager:'#pager',
        caption:'Муддати тугаган ногиронларнинг тўлиқ рўйхати',
        autowidth:true,
        onSelectRow:function (ids) {
            list_id = ids;
            if (ids == null) {
                if (jQuery("#history").jqGrid('getGridParam', 'records') > 0) {
                    jQuery("#history").jqGrid('clearGridData');
                }
                if (jQuery("#murojaat").jqGrid('getGridParam', 'records') > 0) {
                    jQuery("#murojaat").jqGrid('clearGridData');
                }
                if (jQuery("#wwork").jqGrid('getGridParam', 'records') > 0) {
                    jQuery("#wwork").jqGrid('clearGridData');
                }
            } else {

                jQuery("#wwork").jqGrid('setGridParam', {url:"/illnesskk/work/" + ids, page:1});
                jQuery("#wwork").trigger('reloadGrid');

                jQuery("#murojaat").jqGrid('setGridParam', {url:"/illnesskk/murojaat/" + ids, page:1});
                jQuery("#murojaat").trigger('reloadGrid');

                jQuery("#history").jqGrid('setGridParam', {url:"/illnesskk/history/" + ids, page:1});
                jQuery("#history").trigger('reloadGrid');
            }
        }
    });
    $("#list").jqGrid('navGrid', '#pager', {add:false, del:false, edit:true, refresh:false, search:false, view:true},
    {closeAfterEdit:true},
    {closeAfterAdd:true});

    $('#murojaat').jqGrid({
        url:'/illnesskk/murojaat/0',
        datatype:'json',
        mtype:'POST',
        colNames:['№', 'Протокол', 'Мурожаат мақсади', 'асос'],
        colModel:[
            {name:'id', index:'id', width:10},
            {name:'protocol', index:'protocol', width:10, editable:true, edittype:'text', editrules:{edithidden:true, required:true}},
            {name:'murojaat_sababi', index:'murojaat_sababi', width:20, editable:true, edittype:'select', editoptions:{value:"<?php print $murojaatgrid;?>"}, editrules:{edithidden:true, required:true}},
            {name:'asos', index:'asos', width:100, editable:true, edittype:'textarea'}
        ],
        rowList:[2, 10, 20, 30],
        sortname:'murojaat_sana',
        viewrecords:true,
        sortorder:"asc",
        height:150,
        pager:'#murojaat-pager',
        caption:'Мурожаатлар тарихи',
        width:900
    });

    $("#murojaat").jqGrid('navGrid', '#murojaat-pager', {add:false, del:false, edit:false, search:false, view:true});

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
            {label:'Д.кўрик', name:'beg_date', width:25},
            {label:'end_date_combo', name:'end_date_combo', hidden:true},
            {label:'Қ.кўрик', name:'end_date', width:25},
            {name:'id_royhat', hidden:true},
            {label:'Рўйхатда', name:'royhat_name', width:40},
            {label:'Справка серия', name:'seriya', width:15},
            {label:'Справка номери', name:'nomer', width:20},
            {label:'Берилган вақти', name:'pdate', width:15},
            {label:'Фоиз', name:'foiz', hidden:true},
            {name:'id_ortoped', hidden:true},
            {label:'ortoped_name', name:'ortoped_name', hidden:true},
            {label:'tdate', name:'tdate', hidden:true},
            {label:'knomer', name:'knomer', hidden:true},
            {label:'ktashkilot', name:'ktashkilot', hidden:true},
            {name:'kriteria_1', hidden:true},
            {label:'Критерия сабаби', index:'kriteria1', hidden:true},
            {name:'kriteria_2', hidden:true},
            {label:'Критерия сабаби2', index:'kriteria2', hidden:true},
            {name:'kriteria_3', hidden:true},
            {label:'Критерия сабаби3', index:'kriteria3', hidden:true},
            {name:'kriteria_4', hidden:true},
            {label:'Критерия сабаби4', index:'kriteria4', hidden:true},
            {name:'kriteria_5', hidden:true},
            {label:'Критерия сабаби5', index:'kriteria5', hidden:true},
            {name:'kriteria_6', hidden:true},
            {label:'Критерия сабаби6', index:'kriteria6', hidden:true},
            {name:'kriteria_7', hidden:true},
            {label:'Критерия сабаби7', index:'kriteria7', hidden:true},
            {label:'trdate', name:'trdate', hidden:true}
        ],
        //rowNum:10,
        rowList:[2, 10, 20, 30],
        sortname:'beg_date',
        viewrecords:true,
        sortorder:"asc",
        height:150,
        pager:'#history-pager',
        caption:'Касалликлар тарихи',
        autowidth:true,
        subGrid:true,
        subGridRowExpanded:function (subgridid, id) {
            var data = {subgrid:subgridid, rowid:id};
            $("#" + jQuery.jgrid.jqID(subgridid)).load('/illnesskk/history_detail', data);
        }
    });

    $("#history").jqGrid('navGrid', '#history-pager', {add:false, del:false, edit:false, search:false, view:true});

    //Ish  joyi ma'lumotlarini tahrirlash
    $('#wwork').jqGrid({
        datatype:'json',
        mtype:'POST',
        colNames:['Базадаги рақами', 'Мижоз рақами', 'Маълумоти', 'Мутахассислиги', 'Касби', 'Стажи', 'Ҳозирги ҳолати'],
        colModel:[
            {name:'id', index:'id', width:100, sorttype:'int', hidden:true},
            {name:'id_mijoz', index:'id_mijoz', width:100, hidden:true},
            {name:'id_malumot', index:'id_malumot', width:100, editable:true, edittype:'select', editoptions:{value:"<?php print $malumot;?>"}},
            {name:'spec', index:'spec', width:100, editable:true, edittype:'text'},
            {name:'work', index:'work', width:200, editable:true, edittype:'text'},
            {name:'staj', index:'staj', width:100, editable:true, edittype:'text'},
            {name:'working', index:'working', width:100, editable:true, edittype:'select', editoptions:{value:"<?php print $work;?>"}}
        ],
        //rowNum:10,
        rowList:[2, 10, 20, 30],
        sortname:'id',
        viewrecords:true,
        sortorder:"asc",
        autowidth:true,
        pager:'#wwork-pager',
        caption:'Беморнинг иш жойи ҳақида маълумотнома'
    });

<?php if (!$is_mon) : ?>
    $("#wwork").jqGrid('navGrid', '#wwork-pager', {add:false, del:false, edit:false, search:false, view:true});
    <?php endif; ?>

});
</script>
<div id="dialog-form-search" title="Қидирув параметрларини танланг">
<form id="illnesssearch-form" action="/illnesskk/search_excel" method="POST">
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
