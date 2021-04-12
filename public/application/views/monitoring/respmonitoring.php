<?php
$is_mon = ($this->session->userdata['admin_type'] == 3);
$admin_id = ($this->session->userdata['admin_id']);

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
    <button id="mijoz-search" style="float:right;">Умумий қидириш</button>
    <BR><BR><BR>
    <?php if ($admin_id == 95 ) : ?>
    <button id="e_passport" style="float:right;">Хатолик ҳисоботи</button>
    <button id="etasdiq" style="float:right;">Тасдиқ. ва пенсиядан ўтганлар</button>
    <button id="etable01" style="float:right;">ПЖ сўровлари</button>
    <button id="et" style="float:right;">Тасдиқланмаган. рўйхати</button>
    <button id="rt_umumiy" style="float:right;">Умумий сони</button>
    <button id="tb" style="float:right;">Стыковка</button>
    <button id="full"style="float:right;">Барча ногиронлар </button>
    <?php endif; ?>
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
            <li><a href="#fragment-1"><span>Ногиронлик тарихи</span></a></li>
            <li><a href="#fragment-2"><span>Ногирон мурожаати</span></a></li>
            <li><a href="#fragment-3"><span>Иш жойи маълумотлари</span></a></li>
        </ul>
        <div id="fragment-1">
            <table id="history" class="scroll"></table>
            <div id="history-pager" class="scroll" style="text-align:center;"></div>
            <BR/>
            <?php if ($admin_id == 103) : ?>
            <button id="edit-history">Ногиронлик тарихини таҳрирлаш</button>
            <button id="edit-old">Тарихни ўзгартириш</button>
            <button id="edit-pcheck">Пенсияни таҳрирлаш</button>
            <?php endif;
            if (!$is_mon) : ?>
                <button id="create-history">Ногиронлик тарихини қўшиш</button>
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
    if (colname != 'П.рақам')
        return;

    if (value.length != 7) {
        return [false, "Pass_code 7 ta sondan tashkil topgan bo'lishi kerak", ""];
    }

    var pass_seriya = $("#pass_seriya").val();

    var row_id = $("#list").jqGrid("getGridParam", "selrow");
    if (row_id == $("#id_g").val()) {
        var row = $("#list").jqGrid("getRowData", row_id);
        if (value == row.pass_code) {
            return [true, "", ""];
        }
    }

    var jqXHR = $.ajax({
        url:"/respmonitoring/check_pass",
        async:false,
        data:{
            pass_seriya:pass_seriya,
            pass_code:value
        }
    });

    if (jqXHR.responseText) {
        return [false, jqXHR.responseText, ""];
    }

    return [true, "", ""];
}
function passchecksa(value, colname) {
    if (colname != 'П.серия')
        return;

    var pass_code = $("#pass_code").val();

    var row_id = $("#list").jqGrid("getGridParam", "selrow");
    if (row_id == $("#id_g").val()) {
        var row = $("#list").jqGrid("getRowData", row_id);
        if (value == row.pass_seriya) {
            return [true, "", ""];
        }
    }

    var jqXHR = $.ajax({
        url:"/respmonitoring/check_pass",
        async:false,
        data:{
            pass_seriya:value,
            pass_code:pass_code
        }
    });

    if (jqXHR.responseText) {
        return [false, jqXHR.responseText, ""];
    }

    return [true, "", ""];
}
$(function () {
  initNumberInput();
  initPinflInput();
    var mygrid = $('#list').jqGrid({
        url:'/respmonitoring/search',
        height:340,
        datatype:'json',
        mtype:'POST',
        colNames:['Ун.Рақам', 'Деп.Рақам', 'Фамилияси', 'Исми', 'Шарифи', 'Туғ. сана',
            'Жинси', 'П.серия', 'П.рақам', 'Ҳудуди', 'Турар жойи', 'Манзили', 'Фуқаро эмас'],
        colModel:[
            {name:'id', index:'id', width:15},
            {name:'id_depart', index:'id_depart', width:15},
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
            {name:'pass_seriya', index:'pass_seriya', width:10, editable:true, edittype:'text', editable:true, edittype:'select', editoptions:{value:"<?php print $pass_seriya;?>"}, editrules:{edithidden:true, required:true, custom:true, custom_func:passchecksa}},
            {name:'pass_code', index:'pass_code', width:15, editable:true, edittype:'text', editrules:{edithidden:true, required:true, custom:true, custom_func:passcheck, number:true}},
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
        editurl:'/respmonitoring/edit_mijoz',
        pager:'#pager',
        caption:'Базага киритилган ногиронларнинг тўлиқ рўйхати',
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

                jQuery("#wwork").jqGrid('setGridParam', {url:"/respmonitoring/work/" + ids, page:1});
                jQuery("#wwork").trigger('reloadGrid');

                jQuery("#murojaat").jqGrid('setGridParam', {url:"/respmonitoring/murojaat/" + ids, page:1});
                jQuery("#murojaat").trigger('reloadGrid');

                jQuery("#history").jqGrid('setGridParam', {url:"/respmonitoring/history/" + ids, page:1});
                jQuery("#history").trigger('reloadGrid');
            }
        }
    });
<?php if ($admin_id==103) : ?>
    $("#list").jqGrid('navGrid', '#pager', {add:false, del:true, edit:false, refresh:false, search:false, view:true},
        {closeAfterEdit:true},
        {closeAfterAdd:true}
    );
    <?php endif; ?>
    jQuery('#list').jqGrid('navButtonAdd', '#pager', {id:'pager_excel', caption:'Юклаш', title:'Объективкани чиқариш', onClickButton:function (e) {
        try {
            jQuery("#list").jqGrid('setGridParam', {postData:{oblist:list_id}});
            jQuery("#list").jqGrid('excelExport', {tag:'excel', url:'/obj/objective'});
        } catch (e) {
            alert("Маълумоти керак бўлган ногирон танланмади. Ногиронни танланг!");
        }
    },
        buttonicon:'ui-icon-print'});

    $('#murojaat').jqGrid({
        url:'/respmonitoring/murojaat/0',
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
        width:900,
        beforeRequest:function () {
            $('#murojaat').setGridParam({editurl:"/respmonitoring/edit_murojaat/" + list_id});
        }
    });

<?php if (!$is_mon) : ?>
    $("#murojaat").jqGrid('navGrid', '#murojaat-pager', {add:false, del:false, edit:true, search:false, view:true},
        {closeAfterEdit:true},
        {closeAfterAdd:true}
    );
    <?php endif; ?>
    $('#history').jqGrid({
        datatype:'json',
        mtype:'POST',
        colModel:[
            {name:'id', index:'id', width:100, sorttype:'int', hidden:true},
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
            {label:'Рўйхат', name:'royhat_name', width:40},
            {label:'С-ка серия', name:'seriya', width:15},
            {label:'С-ка номери', name:'nomer', width:20},
            {label:'Рас-н.сана ', name:'pdate', width:15},
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
            {label:'trdate', name:'trdate', hidden:true},
            {label:'mkb10_parent', name:'mkb10_parent', hidden:true},
            {label:'old', name:'old', hidden:true},
            {label:'pcheck', name:'pcheck', hidden:true}
        ],
        //rowNum:10,
        rowList:[2, 10, 20, 30],
        sortname:'beg_date',
        viewrecords:true,
        sortorder:"asc",
        height:150,
        pager:'#history-pager',
        caption:'Касалликлар тарихи',
        editurl:'/respmonitoring/edit_history',
        autowidth:true,
        subGrid:true,
        subGridRowExpanded:function (subgridid, id) {
            var data = {subgrid:subgridid, rowid:id};
            $("#" + jQuery.jgrid.jqID(subgridid)).load('/respmonitoring/history_detail', data);
        }

    });

<?php if ($admin_id == 103) : ?>
    $("#history").jqGrid('navGrid', '#history-pager', {add:false, del:true, edit:false, search:false, view:true},
        {closeAfterEdit:true},
        {closeAfterAdd:true}
    );
    <?php endif; ?>


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
        caption:'Беморнинг иш жойи ҳақида маълумотнома',
        beforeRequest:function () {
            $('#wwork').setGridParam({editurl:"/respmonitoring/edit_work/" + list_id});
        }
    });

<?php if (!$is_mon) : ?>
    $("#wwork").jqGrid('navGrid', '#wwork-pager', {add:false, del:false, edit:false, search:false, view:true},
        {closeAfterEdit:true},
        {closeAfterAdd:true}
    );
    <?php endif; ?>

});
</script>

<div id="dialog-form" title="Янги ногирон қўшиш">
    <form id="illness-form">
        <input type="hidden" name="id_mijoz" value=""/>
        <table border="0" width="100%">
            <TR>
                <TD>
                    <label style="display:block;">Кўчиб кетган: </label>
                    <input style="margin-bottom:12px; padding: .3em; display:block;" type="checkbox"
                           id="move" name="move" value="1" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </TR>
        </table>
    </form>
</div>

<div id="error-form">
    <?php print form_open("/respmonitoring/errors", array('method' => 'post', 'id' => 'eform')) ?>
    <table border="0" width="100%">
        <tr>
            <td>
                <label style="display:block;">Департамент:</label>
            </td>
            <td>
                <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                        name="error_depart" id="error_depart"
                        class="text ui-widget-content ui-corner-all">
                    <option value=""></option>
                    <?php foreach ($id_depart as $ed) : ?>
                    <option value="<?php echo $ed->id; ?>"><?php echo $ed->name_uz; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
    <input value="Чоп этиш" tabindex="6" type="submit" name="enter">
    </form>
</div>
<div id="full-form">
    <?php print form_open("/respmonitoring/full", array('method' => 'post', 'id' => 'eform')) ?>
    <table border="0" width="100%">
        <tr>
            <td>
                <label style="display:block;">Вилоят:</label>
            </td>
            <td>
                <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                        name="id_region" id="id_region"
                        class="text ui-widget-content ui-corner-all">
                    <option value=""></option>
                    <?php foreach ($vregion as $vr) : ?>
                        <option value="<?php echo $vr->id; ?>"><?php echo $vr->name_uz; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
    <input value="Чоп этиш" tabindex="6" type="submit" name="enter">
    </form>
</div>

<div id="tb-form">
    <?php print form_open("/respmonitoring/tb", array('method' => 'post', 'id' => 'tb_form')) ?>
    <label>Барча ногиронлар ва рўйхатда турувчи ногиронлар сони</label><BR><BR>
    <input value="Чоп этиш" tabindex="6" type="submit" name="tb_enter">
    </form>
</div>

<div id="rt_umumiy-form">
    <?php print form_open("/respmonitoring/rt_umumiy", array('method' => 'post', 'id' => 'rt_form')) ?>
    <label>Барча ногиронлар ва рўйхатда турувчи ногиронлар сони</label><BR><BR>
    <input value="Чоп этиш" tabindex="6" type="submit" name="enter">
    </form>
</div>

<div id="tasdiq-form">
    <?php print form_open("/respmonitoring/tasdiq", array('method' => 'post', 'id' => 'eformtasdiq')) ?>
    <table border="0" width="100%">
        <tr>
            <td>
                <label style="display:block;">Сана:</label>
            </td>
            <td>
                <input style="margin-bottom:12px; padding: .4em; display:block;" type="text"
                       name="tasdiq-sana" id="tasdiq-sana" value=""
                       class="text ui-widget-content ui-corner-all"/>
                </select>
            </td>
        </tr>
    </table>
    <input value="Чоп этиш" tabindex="6" type="submit" name="enter">
    </form>
</div>

<div id="table01-form">
    <?php print form_open("/respmonitoring/table01", array('method' => 'post', 'id' => 'eformtable01')) ?>
    <table border="0" width="100%">
        <tr>
            <td>
                <label style="display:block;">Сана:</label>
            </td>
            <td>
                <input style="margin-bottom:12px; padding: .4em; display:block;" type="text"
                       name="table01-sana" id="table01-sana" value=""
                       class="text ui-widget-content ui-corner-all"/>
                </select>
            </td>
        </tr>
    </table>

    <input value="Чоп этиш" tabindex="6" type="submit" name="enter">
    </form>
</div>

<div id="et-form">
    <?php print form_open("/respmonitoring/et", array('method' => 'post', 'id' => 'etform')) ?>
    <table border="0" width="100%">
        <tr>
            <td>
                <label style="display:block;">Департамент:</label>
            </td>
            <td>
                <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                        name="et-depart" id="et-depart"
                        class="text ui-widget-content ui-corner-all">
                    <option value=""></option>
                    <?php foreach ($id_depart as $ed) : ?>
                    <option value="<?php echo $ed->id; ?>"><?php echo $ed->name_uz; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
    <input value="Чоп этиш" tabindex="6" type="submit" name="enter">
    </form>
</div>

<div id="dialog-form-search" title="Қидирув параметрларини танланг">
<form id="illnesssearch-form" action="/respmonitoring/search_excel" method="POST">
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
                <label style="display:block;">Ногирон Ёши дан:</label>
                <input style="margin-bottom:12px; padding: .4em; display:block;" type="text"
                       name="fagefrom" id="fagefrom" value=""
                       class="text ui-widget-content ui-corner-all"/>
            </TD>
            <TD>
                <label style="display:block;">Ногирон Ёши гача:</label>
                <input style="margin-bottom:12px; padding: .4em; display:block;" type="text"
                       name="fagetill" id="fagetill" class="text ui-widget-content ui-corner-all"/>
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
                    <option value="<?php echo $b->pass_seriya; ?>"><?php echo $b->pass_seriya; ?></option>
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
            <TD>
                <label style="display:block;">Хужжатлар бириктирилганлиги маълумоти :</label>
                <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                        name="fupload" id="fupload"
                        class="text ui-widget-content ui-corner-all">
                    <option value=""></option>
                    <option value="vtk"> ТМЭК хужжати </option>
                    <option value="amb"> Ногиронни расми </option>
                    <option value="ktk"> Ногиронни врач билан расми </option>

                </select>
            </TD>

        </TR>
    </table>
</div>
<h3><a href="#">Маълумоти ва иш фаолияти</a></h3>

<div>
    <table width="100%" border="0">
        <tr>
            <td><label style="display:block; color:blue;">Маълумоти:</label>
                <select style="margin-bottom:12px; padding: .2em; display:block;"
                        type="combo" name="fid_malumot" id="fid_malumot"
                        class="text ui-widget-content ui-corner-all">
                    <option value=""></option>
                    <?php foreach ($fmalumot as $ml) : ?>
                    <option value="<?php echo $ml->id; ?>"><?php echo $ml->name_uz; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><label style="display:block; color:blue;">Иш стажи:</label>
                <input style="margin-bottom:12px; padding: .3em; display:block;" type="text"
                       name="fstaj" id="fstaj" value="" class="text ui-widget-content ui-corner-all"/>
            </td>
            <td><label style="display:block; color:blue;">Ҳозирда ишлайди</label>
                <select style="margin-bottom:12px; padding: .2em; display:block;"
                        type="combo" name="fworking" id="fworking"
                        class="text ui-widget-content ui-corner-all">
                    <option value=""></option>
                    <?php foreach ($working as $w) : ?>
                    <option value="<?php echo $w->id; ?>"><?php echo $w->name_uz; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    </table>
</div>
<h3><a href="#">Касалликка оид параметрлар</a></h3>

<div>
    <table border="0" width="100%">
        <TR>
            <TD>
                <label style="display:block; margin-left: 5px;">Тмек хулосаси:</label>
                <select style="margin-bottom:10px; margin-left: 5px; padding: .3em; display:block; "
                        name="ftmek_xulosasi" id="ftmek_xulosasi"
                        class="text ui-widget-content ui-corner-all">
                    <option value=""></option>
                    <?php foreach ($tmek_xulosasi as $tx) : ?>
                    <option value="<?php echo $tx->id; ?>"><?php echo $tx->nogironlik_sababi; ?></option>
                    <?php endforeach; ?>
                </select>
            </TD>
            <TD>
                <label style="display:block; margin-left: 5px;">Ногиронлик гуруҳи:</label>
                <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                        name="fguruh" id="fguruh"
                        class="text ui-widget-content ui-corner-all">
                    <option value=""></option>
                    <option value="0">Белгиланмаган</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </TD>
            <TD>
                <label style="display:block; margin-left: 5px;">Сабаби:</label>
                <select style="margin-bottom:10px; margin-left: 5px; padding: .3em; display:block; width: 95%"
                        name="fid_sabab" id="fid_sabab"
                        class="text ui-widget-content ui-corner-all">
                    <option value=""></option>
                    <?php foreach ($sabab as $s) : ?>
                    <option value="<?php echo $s->id; ?>"><?php echo $s->sabab_uz; ?></option>
                    <?php endforeach; ?>
                </select>
            </TD>
            <TD>
                <label style="display:block; margin-left: 5px;">Рўйхатда:</label>
                <select style="margin-bottom:10px; margin-left: 5px; padding: .3em; display:block; "
                        name="fid_royhat" id="fid_royhat"
                        class="text ui-widget-content ui-corner-all ">
                    <option value=""></option>
                    <?php foreach ($royhatuz as $r) : ?>
                    <option value="<?php echo $r->id; ?>"><?php echo $r->name_uz; ?></option>
                    <?php endforeach; ?>
                </select>
            </TD>
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
                <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                        name="fseriya" id="fseriya"
                        class="text ui-widget-content ui-corner-all code-ill">
                    <option value="">Белгиланмаган</option>
                    <?php foreach ($seriya as $c10) : ?>
                    <option value="<?php echo $c10->name_uz; ?>"><?php echo $c10->name_uz; ?></option>
                    <?php endforeach; ?>
                </select>
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

<div id="eold-form" title="Бемор касаллик тарихлари">
    <form id="old-form">
        <input type="hidden" name="id" id="id-eold" value=""/>
        <input type="hidden" name="oldid_mijoz" value=""/>
        <fieldset>
            <table>
                <TR>
                    <TD>
                        <label style="display:block;  color: #05519e;">Тарих:</label>
                        <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                               name="old" id="old" value="" class="text ui-widget-content ui-corner-all"/>
                    </TD>
                </TR>
            </table>
        </fieldset>
    </form>
</div>

<div id="epcheck-form" title="Бемор касаллик тарихлари">
    <form id="pcheck-form">
        <input type="hidden" name="id" id="id-epcheck" value=""/>
        <input type="hidden" name="oldid_mijoz" value=""/>
        <fieldset>
            <table>
                <TR>
                    <TD>
                        <label style="display:block;  color: #05519e;">Маълумотнома:</label>
                        <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                                name="id_pcheck" id="id_pcheck"
                                class="text ui-widget-content ui-corner-all code-ill required">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </TD>
                </TR>
            </table>
        </fieldset>
    </form>
</div>

<div id="ehistory-form" title="Бемор касаллик тарихлари">
<form id="illehistory-form">
<input type="hidden" name="id" id="id-ehistory" value=""/>
<input type="hidden" name="id_mijoz" value=""/>
<fieldset>
<table>
    <TR>
        <TD style="width: 20%">
            <label style="display:block; color: #05519e;">Ногиронлик белгиланиш сабаби:</label>
            <select style="margin-bottom:10px; width:100%; padding: .3em; display:block;"
                    name="etmek_xulosasi" id="etmek_xulosasi"
                    class="text ui-widget-content ui-corner-all">
                <?php foreach ($tmek_xulosasi as $tx) : ?>
                <option value="<?php echo $tx->id; ?>"><?php echo $tx->nogironlik_sababi; ?></option>
                <?php endforeach; ?>
            </select>
        </TD>
    </TR>
</table>
<div>
    <div id="ep1" style="display: none">
        <label style="display:block;">Касбий мехнат лаёқати йўқотилганлик даражаси фоизда:</label>
        <input style="margin-bottom:12px; padding: .4em; display:block;" type="text"
               name="efoiz" id="efoiz" value="" class="text ui-widget-content ui-corner-all">
    </div>
    <div id="ep2" style="display: none">
        <label style="display:block;">Касаллик варақасининг рақами:</label>
        <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
               name="eknomer" id="eknomer" value="" class="text ui-widget-content ui-corner-all">
        <label style="display:block;">Касаллик варақасини берган тиббий муассаса:</label>
        <input style="margin-bottom:12px; padding: .4em; display:block;" type="text"
               name="ektashkilot" id="ektashkilot" value="" class="text ui-widget-content ui-corner-all">
    </div>
    <div id="ep3" style="display: none">
        <label style="display:block; ">Номланиши:</label>
        <select style="margin-bottom:2px; width:20%; padding: .3em; display:block;"
                name="eid_ortoped" id="eid_ortoped"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Тавсия этилмаган</option>
            <?php foreach ($ortoped as $ort) : ?>
            <option value="<?php echo $ort->id; ?>"><?php echo $ort->name_uz; ?></option>
            <?php endforeach; ?>
        </select>
        <label style="display:block; ">Тавсия этилган муддати:</label>
        <input style="margin-bottom:12px; width:19%; padding: .4em; display:block;" type="text"
               name="etdate" id="etdate" value="" class="text ui-widget-content ui-corner-all"/></div>
    <div id="ep4" style="display: none">
        <label style="display:block; color: #05519e;"> Тўлиқ реабилтация ёки вафот этган санаси:</label>
        <input style="margin-bottom:12px; width:20%; padding: .4em; display:block;" type="text"
               name="etrdate" id="etrdate" value=""
               class="text ui-widget-content ui-corner-all"/>
    </div>
    <br clear="all"/>
</div>

<table style="width:100%; " border="0" cellpadding="0" cellspacing="0">
<TR>
    <TD style="width: 20%">
        <label style="display:block; color: #05519e;" for="id_checktype">Кўрик тури:</label>
        <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                name="eid_checktype" id="eid_checktype"
                class="text ui-widget-content ui-corner-all code-ill required">
            <?php foreach ($checktype as $ch) : ?>
            <option value="<?php echo $ch->id; ?>"><?php echo $ch->name_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
    <TD style="width: 20%">
        <label style="display:block; color: #05519e;">Рўйхатда:</label>
        <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                name="eid_royhat" id="eid_royhat"
                class="text ui-widget-content ui-corner-all code-ill required">
            <?php foreach ($royhatuz as $r) : ?>
            <option value="<?php echo $r->id; ?>"><?php echo $r->name_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
    <td style="width: 15%">
        <label>Ўзини ўзи бошқариш қобилияти:</label>
    </td>
    <td style="width: 45%">
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="ekriteria_1" id="ekriteria_1"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмаган</option>
            <?php foreach ($kdaraja1 as $c10) : ?>
            <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </td>
<TR>
    <TD>
        <label style="display:block; color: #05519e;">Сабаби:</label>
        <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                name="eid_sabab" id="eid_sabab"
                class="text ui-widget-content ui-corner-all  required">
            <?php foreach ($sabab as $s) : ?>
            <option value="<?php echo $s->id; ?>"><?php echo $s->sabab_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
    <TD style="width: 300px;">
        <label style="color: #05519e;">Ташхис-МКБ9:</label>
        <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                name="emkb_9" id="emkb_9"
                class="text ui-widget-content ui-corner-all ">
            <option value="0">Белгиланмади</option>
            <?php foreach ($code9 as $c9) : ?>
            <option value="<?php echo $c9->id; ?>"><?php echo $c9->code_9; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
    <td>
        <label>Мустақил ҳаракатлана олиш қобилияти:</label>
    </td>
    <td>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="ekriteria_2" id="ekriteria_2"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмаган</option>
            <?php foreach ($kdaraja2 as $c10) : ?>
            <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </td>

</TR>
<TR>
    <TD style="width: 300px;">
        <label style="color: #05519e;">Ташхис-МКБ10(ташхис тури):</label>
        <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                name="ecode_ill_10_par" id="ecode_ill_10_par"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмади</option>
            <?php foreach ($code_ill_10 as $c10) : ?>
            <option value="<?php echo $c10->group_id; ?>"><?php echo $c10->name_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
    <TD style="width: 300px;">
        <label style="color: #05519e;">Ташхис-МКБ10:</label>
        <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                name="emkb_10" id="emkb_10"
                class="text ui-widget-content ui-corner-all code-ill">
        </select>
    </TD>
    <td>
        <label>Ориентация олиш қобилияти:</label>
    </td>
    <td>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="ekriteria_3" id="ekriteria_3"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмаган</option>
            <?php foreach ($kdaraja3 as $c10) : ?>
            <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </td>
</TR>
<TR>
    <TD style="width: 300px;">
        <label style="display:block; color: #05519e;">Ногиронлик гуруҳи:</label>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="eguruh" id="eguruh"
                class="text ui-widget-content ui-corner-all">
            <option value="0">Белгиланмаган</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select>
    </TD>
    <TD>&nbsp;</TD>
    <TD>
        <label>Мулоқот қила олиш қобилияти:</label>
    </TD>
    <TD>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="ekriteria_4" id="ekriteria_4"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмаган</option>
            <?php foreach ($kdaraja4 as $c10) : ?>
            <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
</TR>
<TR>
    <TD>
        <label style="display:block; color: #05519e;">Ногиронлик белгиланган сана:</label>
        <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
               name="ebeg_date" id="ebeg_date" value="" class="text ui-widget-content ui-corner-all">
    </TD>
    <TD>
        <label style="display:block; color: #05519e;">Ногиронлик муддати:</label>
        <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                name="eend_date_combo" id="eend_date_combo"
                class="text ui-widget-content ui-corner-all end_date_combo required">
            <option value="0">Белгиланмади</option>
            <option value="6">ВМЛни узатириш</option>
            <option value="1">6 ой</option>
            <option value="2">1 йил</option>
            <option value="3">2 йил</option>
            <option value="5">5 йил</option>
            <option value="4">муддатсиз</option>
        </select>
    </TD>
    <TD>
        <label>Ҳатти-ҳаракатини бошқариш қобилияти:</label>
    </TD>
    <TD>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="ekriteria_5" id="ekriteria_5"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмаган</option>
            <?php foreach ($kdaraja5 as $c10) : ?>
            <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
</TR>
<TR>
    <TD>
        <label style="display:block; color: #05519e; ">Справка серияси:</label>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="eseriya" id="eseriya"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="">Белгиланмаган</option>
            <?php foreach ($seriya as $c10) : ?>
            <option value="<?php echo $c10->name_uz; ?>"><?php echo $c10->name_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
    <TD>
        <label style="display:block; color: #05519e; ">Справка рақами:</label>
        <input style="margin-bottom:12px; padding: .4em; display:block;" type="text"
               name="enomer" id="enomer" value="" class="text ui-widget-content ui-corner-all"/>
    </TD>
    <TD>
        <label>Ўрганиш, тушуниш ва қўллай олиш қобилияти:</label>
    </TD>
    <TD>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="ekriteria_6" id="ekriteria_6"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмаган</option>
            <?php foreach ($kdaraja6 as $c10) : ?>
            <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
</TR>
<TR>
    <TD>
        <label style="display:block;  color: #05519e;">Расмийлаштирилган сана:</label>
        <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
               name="epdate" id="epdate" value="" class="text ui-widget-content ui-corner-all"/>
    </TD>
    <TD>
        &nbsp;
    </TD>
    <TD>
        <label>Меҳнатга лаёқатлилик қобилияти:</label>
    </TD>
    <TD>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="ekriteria_7" id="ekriteria_7"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмаган</option>
            <?php foreach ($kdaraja7 as $c10) : ?>
            <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
</TR>
</table>
</fieldset>
</form>
</div>

<div id="history-form" title="Бемор касаллик тарихлари">
<form id="illhistory-form">
<input type="hidden" name="id" id="id-history" value=""/>
<input type="hidden" name="id_mijoz" value=""/>
<fieldset>
<table>
    <TR>
        <TD style="width: 20%">
            <label style="display:block; color: #05519e;">Ногиронлик тарихини белгиланг:</label>
            <select style="margin-bottom:10px; width:100%; padding: .3em; display:block;"
                    name="thistory" id="thistory"
                    class="text ui-widget-content ui-corner-all  required">
                <option value=""> Танланг</option>
                <option value="0">Янги касаллик тарихи</option>
                <option value="1">Эски касаллик тарихи</option>
            </select>
        </TD>
    </TR>
    <TR>
        <TD style="width: 20%">
            <label style="display:block; color: #05519e;">Ногиронлик белгиланиш сабаби:</label>
            <select style="margin-bottom:10px; width:100%; padding: .3em; display:block;"
                    name="tmek_xulosasi" id="tmek_xulosasi"
                    class="text ui-widget-content ui-corner-all code-ill required">
                <?php foreach ($tmek_xulosasi as $tx) : ?>
                <option value="<?php echo $tx->id; ?>"><?php echo $tx->nogironlik_sababi; ?></option>
                <?php endforeach; ?>
            </select>
        </TD>
    </TR>
</table>
<div id="tabs2">
    <div id="p1" style="display: none">
        <label style="display:block;">Касбий мехнат лаёқати йўқотилганлик даражаси фоизда:</label>
        <input style="margin-bottom:12px; padding: .4em; display:block;" type="text"
               name="foiz" id="foiz" value="" class="text ui-widget-content ui-corner-all">
    </div>
    <div id="p2" style="display: none">
        <label style="display:block;">Касаллик варақасининг рақами:</label>
        <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
               name="knomer" id="knomer" value="" class="text ui-widget-content ui-corner-all">
        <label style="display:block;">Касаллик варақасини берган тиббий муассаса:</label>
        <input style="margin-bottom:12px; padding: .4em; display:block;" type="text"
               name="ktashkilot" id="ktashkilot" value="" class="text ui-widget-content ui-corner-all">
    </div>
    <div id="p3" style="display: none">
        <label style="display:block; ">Номланиши:</label>
        <select style="margin-bottom:2px; width:20%; padding: .3em; display:block;"
                name="id_ortoped" id="id_ortoped"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Тавсия этилмаган</option>
            <?php foreach ($ortoped as $ort) : ?>
            <option value="<?php echo $ort->id; ?>"><?php echo $ort->name_uz; ?></option>
            <?php endforeach; ?>
        </select>
        <label style="display:block; ">Тавсия этилган муддати:</label>
        <input style="margin-bottom:12px; width:19%; padding: .4em; display:block;" type="text"
               name="tdate" id="tdate" value="" class="text ui-widget-content ui-corner-all"/></div>
    <div id="p4" style="display: none">
        <label style="display:block; color: #05519e;"> Тўлиқ реабилтация, вафот этган ва кўчиб кетган санаси:</label>
        <input style="margin-bottom:12px; width:20%; padding: .4em; display:block;" type="text"
               name="trdate" id="trdate" value=""
               class="text ui-widget-content ui-corner-all"/>
    </div>
    <br clear="all"/>
</div>

<table style="width:100%; " border="0" cellpadding="0" cellspacing="0">
    <TR>
        <TD style="width: 30%">
            <label style="display:block; color: #05519e;"> Мурожаат санаси:</label>
            <input style="margin-bottom:12px;  width:70%; padding: .4em; display:block;" type="text"
                   name="murojaat_sana" id="murojaat_sana" value=""
                   class="text ui-widget-content ui-corner-all"/>
        </TD>
        <TD style="width: 30%">
            <label style="display:block; color: #05519e;"> Протокол рақами:</label>
            <input style="margin-bottom:12px;  width:70%; padding: .4em; display:block;" type="text"
                   name="protocol" id="protocol" value=""
                   class="text ui-widget-content ui-corner-all"/>
        </TD>
        <TD style="width: 40%">
            <label style="display:block; color: #05519e;"> Мурожаат сабаби:</label>
            <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                    name="murojaat_sababi" id="murojaat_sababi"
                    class="text ui-widget-content ui-corner-all code-ill required">
                <?php foreach ($murojaat as $ch) : ?>
                <option value="<?php echo $ch->id; ?>"><?php echo $ch->murojaat_sababi; ?></option>
                <?php endforeach; ?>
            </select>
        </TD>
    </TR>
</table>
<H3>Асос бўлган ҳужжатлар:</H3>
<BR>
<table style="width:80%; " border="1" cellpadding="2" cellspacing="1">
    <TR align="center">
        <TD>Ҳужжат номи</TD>
        <TD>Тартиб рақами:</TD>
        <TD>Расмий. сана:</TD>
        <TD>Расмий. ДПМ:</TD>
    </TR>
    <TR align="center">
        <TD> 088/x форма:</TD>
        <TD>
            <input style="margin-bottom:12px; margin:10px; padding: .3em; display:block;" type="text"
                   name="tr88" id="tr88" value="" class="text ui-widget-content ui-corner-all">
        </TD>
        <TD>
            <input style="margin-bottom:12px; margin:10px; padding: .3em; display:block;" type="text"
                   name="tr88date" id="tr88date" value="" class="text ui-widget-content ui-corner-all">
        </TD>
        <TD>
            <input style="margin-bottom:12px; margin:10px; padding: .3em; width:200px; display:block;"
                   type="text"
                   name="tr88dmp" id="tr88dmp" value="" class="text ui-widget-content ui-corner-all">
        </TD>
    </TR>
    <TR align="center">
        <TD> Амб. карточкадан кўчирма:</TD>
        <TD>
            <input style="margin-bottom:12px; margin:10px; padding: .3em; display:block;" type="text"
                   name="amb" id="amb" value="" class="text ui-widget-content ui-corner-all">
        </TD>
        <TD>
            <input style="margin-bottom:12px; margin:10px; padding: .3em; display:block;" type="text"
                   name="ambdate" id="ambdate" value="" class="text ui-widget-content ui-corner-all">
        </TD>
        <TD>
            <input style="margin-bottom:12px; margin:10px; padding: .3em; width:200px; display:block;"
                   type="text"
                   name="ambdmp" id="ambdmp" value="" class="text ui-widget-content ui-corner-all">
        </TD>
    </TR>
    <TR align="center">
        <TD> Касаллик тарихидан кўчирма:</TD>
        <TD>
            <input style="margin-bottom:12px; margin:10px; padding: .3em; display:block;" type="text"
                   name="ktk" id="ktk" value="" class="text ui-widget-content ui-corner-all">
        </TD>
        <TD>
            <input style="margin-bottom:12px; margin:10px; padding: .3em; display:block;" type="text"
                   name="ktkdate" id="ktkdate" value="" class="text ui-widget-content ui-corner-all">
        </TD>
        <TD>
            <input style="margin-bottom:12px; margin:10px; padding: .3em; width:200px; display:block;"
                   type="text"
                   name="ktkdmp" id="ktkdmp" value="" class="text ui-widget-content ui-corner-all">
        </TD>
    </TR>
</table>
<BR>
<label style="display:block;"> Бошқа ҳужжатлар:</label>
<input style=" width:90%; margin-bottom:12px; padding: .4em; display:block;" type="text"
       name="asos" id="asos" value=""
       class="text ui-widget-content ui-corner-all">
<BR clear="all"/>
<BR clear="all"/>

<table style="width:100%; " border="0" cellpadding="0" cellspacing="0">
<TR>
    <TD style="width: 20%">
        <label style="display:block; color: #05519e;" for="id_checktype">Кўрик тури:</label>
        <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                name="id_checktype" id="id_checktype"
                class="text ui-widget-content ui-corner-all code-ill required">
            <?php foreach ($checktype as $ch) : ?>
            <option value="<?php echo $ch->id; ?>"><?php echo $ch->name_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
    <TD style="width: 20%">
        <label style="display:block; color: #05519e;">Рўйхатда:</label>
        <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                name="id_royhat" id="id_royhat"
                class="text ui-widget-content ui-corner-all code-ill required">
            <?php foreach ($royhatuz as $r) : ?>
            <option value="<?php echo $r->id; ?>"><?php echo $r->name_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
    <td style="width: 15%">
        <label>Ўзини ўзи бошқариш қобилияти:</label>
    </td>
    <td style="width: 45%">
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="kriteria_1" id="kriteria_1"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмаган</option>
            <?php foreach ($kdaraja1 as $c10) : ?>
            <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </td>
<TR>
    <TD>
        <label style="display:block; color: #05519e;">Сабаби:</label>
        <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                name="id_sabab" id="id_sabab"
                class="text ui-widget-content ui-corner-all  required">
            <?php foreach ($sabab as $s) : ?>
            <option value="<?php echo $s->id; ?>"><?php echo $s->sabab_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
    <TD style="width: 300px;">
        <label style="color: #05519e;">Ташхис-МКБ9:</label>
        <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                name="mkb_9" id="mkb_9"
                class="text ui-widget-content ui-corner-all ">
            <option value="0">Белгиланмади</option>
            <?php foreach ($code9 as $c9) : ?>
            <option value="<?php echo $c9->id; ?>"><?php echo $c9->code_9; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
    <td>
        <label>Мустақил ҳаракатлана олиш қобилияти:</label>
    </td>
    <td>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="kriteria_2" id="kriteria_2"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмаган</option>
            <?php foreach ($kdaraja2 as $c10) : ?>
            <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </td>

</TR>
<TR>
    <TD style="width: 300px;">
        <label style="color: #05519e;">Ташхис-МКБ10(ташхис тури):</label>
        <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                name="code_ill_10_par" id="code_ill_10_par"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмади</option>
            <?php foreach ($code_ill_10 as $c10) : ?>
            <option value="<?php echo $c10->group_id; ?>"><?php echo $c10->name_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
    <TD style="width: 300px;">
        <label style="color: #05519e;">Ташхис-МКБ10:</label>
        <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                name="mkb_10" id="mkb_10"
                class="text ui-widget-content ui-corner-all code-ill">
        </select>
    </TD>
    <td>
        <label>Ориентация олиш қобилияти:</label>
    </td>
    <td>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="kriteria_3" id="kriteria_3"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмаган</option>
            <?php foreach ($kdaraja3 as $c10) : ?>
            <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </td>
</TR>
<TR>
    <TD style="width: 300px;">
        <label style="display:block; color: #05519e;">Ногиронлик гуруҳи:</label>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="guruh" id="guruh"
                class="text ui-widget-content ui-corner-all">
            <option value="0">Белгиланмаган</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select>
    </TD>
    <TD>&nbsp;</TD>
    <TD>
        <label>Мулоқот қила олиш қобилияти:</label>
    </TD>
    <TD>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="kriteria_4" id="kriteria_4"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмаган</option>
            <?php foreach ($kdaraja4 as $c10) : ?>
            <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
</TR>
<TR>
    <TD>
        <label style="display:block; color: #05519e;">Кўрикдан ўтган сана:</label>
        <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
               name="beg_date" id="beg_date" value="" class="text ui-widget-content ui-corner-all">
    </TD>
    <TD>
        <label style="display:block; color: #05519e;">Ногиронлик муддати:</label>
        <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                name="end_date_combo" id="end_date_combo"
                class="text ui-widget-content ui-corner-all end_date_combo required">
            <option value="0">Белгиланмади</option>
            <option value="6">ВМЛни узайтириш</option>
            <option value="1">6 ой</option>
            <option value="2">1 йил</option>
            <option value="3">2 йил</option>
            <option value="5">5 йил</option>
            <option value="4">муддатсиз</option>
        </select>
    </TD>
    <TD>
        <label>Ҳатти-ҳаракатини бошқариш қобилияти:</label>
    </TD>
    <TD>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="kriteria_5" id="kriteria_5"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмаган</option>
            <?php foreach ($kdaraja5 as $c10) : ?>
            <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
</TR>
<TR>
    <TD>
        <label style="display:block; color: #05519e; ">Справка серияси:</label>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="seriya" id="seriya"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="">Белгиланмаган</option>
            <?php foreach ($seriya as $c10) : ?>
            <option value="<?php echo $c10->name_uz; ?>"><?php echo $c10->name_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
    <TD>
        <label style="display:block; color: #05519e; ">Справка рақами:</label>
        <input style="margin-bottom:12px; padding: .4em; display:block;" type="text"
               name="nomer" id="nomer" value="" class="text ui-widget-content ui-corner-all"/>
    </TD>
    <TD>
        <label>Ўрганиш, тушуниш ва қўллай олиш қобилияти:</label>
    </TD>
    <TD>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="kriteria_6" id="kriteria_6"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмаган</option>
            <?php foreach ($kdaraja6 as $c10) : ?>
            <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
</TR>
<TR>
    <TD>
        <label style="display:block;  color: #05519e;">Расмийлаштирилган сана:</label>
        <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
               name="pdate" id="pdate" value="" class="text ui-widget-content ui-corner-all"/>
    </TD>
    <TD>
        &nbsp;
    </TD>
    <TD>
        <label>Меҳнатга лаёқатлилик қобилияти:</label>
    </TD>
    <TD>
        <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                name="kriteria_7" id="kriteria_7"
                class="text ui-widget-content ui-corner-all code-ill">
            <option value="0">Белгиланмаган</option>
            <?php foreach ($kdaraja7 as $c10) : ?>
            <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
</TR>
</table>
</fieldset>
</form>
</div>


</div>

<script type="text/javascript" src="/js/respmonitoring.js"></script>

</body>
</html>
