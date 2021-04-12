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
    <button id="mijoz-search" style="float:right;">Умумий қидириш</button>
    <BR>
    <BR>
    <BR>
    <table id="list" class="scroll patient-table"></table>
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
            <?php if (!$is_mon) : ?>
            <button id="create-history">Ногиронлик тарихини қўшиш</button>
            <button id="edit-history">Ногиронлик тарихини таҳрирлаш</button>
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
<script type="text/javascript" src="/js/new_jquery.form.js"></script>
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
        url:"/monitoring/check_pass",
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
        url:"/monitoring/check_pass",
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

function _calculateAge(birthday) { // birthday is a date
    var ageDifMs = Date.now() - birthday.getTime();
    var ageDate = new Date(ageDifMs); // miliseconds from epoch
    return Math.abs(ageDate.getUTCFullYear() - 1970);
}

$(function () {
    var mygrid = $('#list').jqGrid({
        url:'/monitoring/search',
        height:340,
        datatype:'json',
        mtype:'POST',
        colNames:['Ун.Рақам', 'Фамилияси', 'Исми', 'Шарифи', 'Туғ. сана',
            'Жинси', 'П.серия', 'П.рақам', 'ПИНФЛ', 'Ҳудуди', 'Турар жойи', 'Манзили', 'Фуқаро эмас', 'Муомала қилиш', 'Ўқиш, билимларни ўзлаштириш ва такрорлаш', 'Меҳнат фаолияти билан шуғулланиш'],
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
            {name:'pass_seriya', index:'pass_seriya', width:10, editable:true, edittype:'text', editable:true, edittype:'select', editoptions:{value:"<?php print $pass_seriya;?>"}, editrules:{edithidden:true, required:true, custom:true, custom_func:passchecksa}},
            {name:'pass_code', index:'pass_code', width:15, editable:true, edittype:'text', editrules:{edithidden:true, required:true, custom:true, custom_func:passcheck, integer:true}},
            {name:'infl', index:'infl', width:15, editable:true, edittype:'text', editrules:{edithidden:true, custom:true, required:true, custom_func:pinflcheck}},
            {name:'id_born', index:'id_born', width:25, editable:true, edittype:'select', editoptions:{value:"<?php print $sborn;?>"}, editrules:{edithidden:true, required:true}},
            {name:'id_place', index:'id_place', width:15, editable:true, edittype:'select', editoptions:{value:"<?php print $splace;?>"}, editrules:{edithidden:true, required:true}},
            {name:'address', index:'address', width:30, editable:true, edittype:'textarea'},
            {name:'notcitizen', index:'notcitizen', width:25, editable:true, edittype:'checkbox', editoptions:{ value:"1:0"}, formatter:"checkbox", formatoptions:{disabled:true}},
            {name:'kriteria_4', index:'kriteria_4', width:25, editable:true, edittype:'text', editrules:{edithidden:true, required:false}},
            {name:'kriteria_6', index:'kriteria_6', width:25, editable:true, edittype:'text', editrules:{edithidden:true, required:false}},
            {name:'kriteria_7', index:'kriteria_7', width:25, editable:true, edittype:'text', editrules:{edithidden:true, required:false}}
        ],
        rownumbers:true,
        rownumWidth:20,
        rowNum:15,
        sortorder:"desc",
        rowList:[10, 15, 30, 50],
        sortname:'id',
        gridview:true,
        viewrecords:true,
        editurl:'/monitoring/edit_mijoz',
        pager:'#pager',
        caption:'Базага киритилган ногиронларнинг тўлиқ рўйхати',
        autowidth:true,
        onSelectRow:function (ids) {
            list_id = ids;
            selRowId = $(this).jqGrid ('getGridParam', 'selrow'),
            dateBirth = $(this).jqGrid ('getCell', selRowId, 'date_birth');
            console.log(dateBirth)
            var mydate = _calculateAge(new Date(dateBirth));

            if(mydate < 18) {
                $('.royhatGT17').hide();
                $('.royhatGT17').prop("disabled", true);
                $('.royhatLT17').show();
                $('.royhatLT17').prop("disabled", false);
            }
            else {
                $('.royhatGT17').show();
                $('.royhatGT17').prop("disabled", false);
                $('.royhatLT17').hide();
                $('.royhatLT17').prop("disabled", true);
            }

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

                jQuery("#wwork").jqGrid('setGridParam', {url:"/monitoring/work/" + ids, page:1});
                jQuery("#wwork").trigger('reloadGrid');

                jQuery("#murojaat").jqGrid('setGridParam', {url:"/monitoring/murojaat/" + ids, page:1});
                jQuery("#murojaat").trigger('reloadGrid');

                jQuery("#history").jqGrid('setGridParam', {url:"/monitoring/history/" + ids, page:1});
                jQuery("#history").trigger('reloadGrid');
            }
        }
    });
    <?php if (!$is_mon) : ?>
    $("#list").jqGrid('navGrid', '#pager', {add:true, del:false, edit:true, refresh:false, search:false, view:true},
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
    jQuery('#list').jqGrid('navButtonAdd', '#pager', {id:'pager_kk', caption:'КК', title:'Кўчиб кетган ногиронни рўйхатдан чиқариш', onClickButton:function (e) {
        try {
            document.getElementById("illness-form").reset();
            $("[name=id_mijoz]", $("#illness-form")).val(list_id);
            $("[name=id]", $("#illness-form")).val("");

            $("#dialog-form").dialog("option", "title", "");
            $("#dialog-form").dialog("open");
        } catch (e) {
            alert("Маълумоти керак бўлган ногирон танланмади. Ногиронни танланг!");
        }
    },
        buttonicon:'ui-icon-cart'});


    $('#murojaat').jqGrid({
        url:'/monitoring/murojaat/0',
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
            $('#murojaat').setGridParam({editurl:"/monitoring/edit_murojaat/" + list_id});
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
            {label:'ТМЕК хулосаси', name:'xulosa_name', hidden:true},
            {name:'id_checktype', hidden:true},
            {label:'Кўрик', name:'checktype_name', width:15},
            {name:'mkb_9', hidden:true},
            {label:'МКБ-9', name:'mkb9', width:10},
            {name:'mkb_10', hidden:true},
            {label:'МКБ-10', name:'code_10', width:10},
            {label:'Гуруҳ', name:'guruh', width:10},
            {name:'id_sabab', hidden:true},
            {label:'Ногиронлик сабаби', name:'sabab_name', width:30},
            {label:'Ног.бош.сана', name:'murojaat_sana', width:25},
            {label:'Кўрик сана', name:'beg_date', width:25},
            {label:'end_date_combo', name:'end_date_combo', hidden:true},
            {label:'Ног.тугаш санаси', name:'end_date', width:25},
            {name:'id_royhat', hidden:true},
            {label:'Рўйхат', name:'royhat_name', width:40},
            {label:'С-ка.серия', name:'seriya', width:15},
            {label:'С-ка.номери', name:'nomer', width:20},
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
            {label:'degree_violation', name:'degree_violation', hidden:true}
        ],
        //rowNum:10,
        rowList:[2, 10, 20, 30],
        sortname:'beg_date',
        viewrecords:true,
        sortorder:"asc",
        height:150,
        pager:'#history-pager',
        caption:'Касалликлар тарихи',
        editurl:'/monitoring/edit_history',
        autowidth:true,
        subGrid:true,
        subGridRowExpanded:function (subgridid, id) {
            var data = {subgrid:subgridid, rowid:id};
            $("#" + jQuery.jgrid.jqID(subgridid)).load('/monitoring/history_detail', data);
        }

    });

<?php if (!$is_mon) : ?>
    $("#history").jqGrid('navGrid', '#history-pager', {add:false, del:false, edit:false, search:false, view:true},
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
          $('#wwork').setGridParam({editurl:"/monitoring/edit_work/" + list_id});
        }
    });

<?php if (!$is_mon) : ?>
    $("#wwork").jqGrid('navGrid', '#wwork-pager', {add:true, del:false, edit:true, search:false, view:true},
        {closeAfterEdit:true},
        {closeAfterAdd:true}
    );
    <?php endif; ?>
});
function GetPersonDocData(e){
  let form = $(e).closest('form');
  let passSerie = form.find('.passport-serie').val().toUpperCase();
  let passNumber = form.find('.passport-number').val();

  let passpost = passSerie + passNumber;
  if(!/[A-Za-z]{2}\d{7}/.exec(passpost)){
    alert('Серия и номер паспорта не указан или указан неправильном формате');
    return;
  }

  let pinfl = form.find('.pinfl').val();
  if(!/\d{14}/.exec(pinfl)){
    alert('ПИНФЛ не указан или указан неправильном формат');
    return;
  }

  //$.get('http://172.23.0.18:89/api/PersonInfo',
  $.post('/monitoring/getPersonData',
    { passSerie: passpost, pinfl: pinfl },
    function(data) {
      data = JSON.parse(JSON.parse(data));
      if(!data || !data.succes){
        alert(data.message || 'Внутренняя ошибка! Попробуйте позже');
      } else {
        let person = data.data;
        form.find('#familiya').val(person.SureName);
        form.find('#name').val(person.Name);
        form.find('#middle').val(person.Patronym);
        form.find('#date_birth').val(person.DateOfBirth);
        form.find('#pass_seriya').val(passSerie);
        form.find('#pass_code').val(passNumber);
        form.find('#infl').val(pinfl);
      }
    });
}
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

<div id="dialog-form-search" title="Қидирув параметрларини танланг">
<form id="illnesssearch-form" action="/monitoring/search_excel" method="POST">
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
        <TR>
            <TD>
                <label>Муомала қилиш лаёқати:</label>
                <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                        name="ekriteria_4" id="ekriteria_4"
                        class="text ui-widget-content ui-corner-all code-ill">
                    <option value="0">Белгиланмаган</option>
                    <?php foreach ($kdaraja4 as $c10) : ?>
                        <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
                    <?php endforeach; ?>
                </select>
            </TD>
            <TD>
                <label>Ўқиш, билимларни ўзлаштириш ва такрорлаш, малака ва кўникмаларни эгаллаб олиш лаёқати:</label>
                <select style="margin-bottom:10px; width:70%; padding: .3em; display:block;"
                        name="ekriteria_6" id="ekriteria_6"
                        class="text ui-widget-content ui-corner-all code-ill">
                    <option value="0">Белгиланмаган</option>
                    <?php foreach ($kdaraja6 as $c10) : ?>
                        <option value="<?php echo $c10->id; ?>"><?php echo $c10->reason_uz; ?></option>
                    <?php endforeach; ?>
                </select>
            </TD>
            <TD>
                <label>Меҳнат фаолияти билан шуғулланиш лаёқати:</label>
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
            <TD >
                <label style="display:block; margin-left: 5px;">Кўрик тури:</label>
                <select style="margin-bottom:10px; padding: .4em; display:block;"
                        name="fid_checktype" id="fid_checktype"
                        class="text ui-widget-content ui-corner-all code-ill required">
                    <option value=""></option>
                    <?php foreach ($checktype as $ch) : ?>
                        <option value="<?php echo $ch->id; ?>"><?php echo $ch->name_uz; ?></option>
                    <?php endforeach; ?>
                </select>
            </TD>
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
        </TR>
        <TR>
            <TD>
                <label style="display:block; margin-left: 5px;">Ногиронлик гуруҳи:</label>
                <select style="margin-bottom:10px; padding: .3em; display:block;"
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
                <select style="margin-bottom:10px; margin-left: 5px; padding: .3em; display:block; width: 80%"
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
            <TD><label style="display:block; margin-left: 5px;">дан:</label>
                <input style="margin-bottom:12px; margin-left: 5px; padding: .3em; display:block;" type="text"
                       name="fbeg_date" id="fbeg_date" value="" class="text ui-widget-content ui-corner-all">
            </TD>
            <TD><label style="display:block; margin-left: 5px;">гача:</label>
                <input style="margin-bottom:12px; margin-left: 5px; padding: .3em; display:block;" type="text"
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
                <label style="display:block; margin-left: 5px;">дан:</label>
                <input style="margin-bottom:12px; margin-left: 5px; padding: .3em; display:block;" type="text"
                       name="fend_date" id="fend_date" value="" class="text ui-widget-content ui-corner-all">

            </TD>
            <TD>
                <label style="display:block; margin-left: 5px; ">гача:</label>
                <input style="margin-bottom:12px; margin-left: 5px;  padding: .3em; display:block;" type="text"
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
                    <option value=""></option>
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
                <label style="display:block; ">Расмийлаштирилган сана(Агар Мехнатда майибланганлик фоизи бўлса протокол расмийлаштирилган сана):</label>
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

<div id="ehistory-form" title="Бемор касаллик тарихлари">
<form id="illehistory-form" enctype="multipart/form-data" action="/monitoring/ehistory_save" method="POST">
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
    <TD>
        <label style="display:block; color: #05519e;">Кўрик бошланган сана:</label>
        <input style="margin-bottom:12px; width:100%; padding: .4em; display:block;" type="text"
               name="emurojaat_sana" id="emurojaat_sana" value=""
               class="text ui-widget-content ui-corner-all"/>
    </TD>
</TR>
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
                class="text ui-widget-content ui-corner-all code-ill required royhatGT17">
            <?php foreach ($royhatuz as $r) : ?>
                <option value="<?php echo $r->id; ?>"><?php echo $r->name_uz; ?></option>
            <?php endforeach; ?>
        </select>
        <select style="margin-bottom:10px; width:70%; padding: .4em; display:none;"
                name="eid_royhat" id="eid_royhat"
                class="text ui-widget-content ui-corner-all code-ill required royhatLT17">
            <?php foreach ($royhatuz18 as $r) : ?>
                <option value="<?php echo $r->id; ?>"><?php echo $r->name_uz; ?></option>
            <?php endforeach; ?>
        </select>
    </TD>
    <td style="width: 15%">
        <label>Ўзига ўзи хизмат кўрсатиш лаёқати:</label>
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
        <label>Мустақил ҳаракатланиш лаёқати:</label>
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
        <label>Мўлжал олиш лаёқати:</label>
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
    <TD>
        <div class="royhatLT17">
            <label style="display:block; color: #05519e;">Организм функцияларининг бузилиши даражаси:</label>
            <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                    name="edegree_violation" id="edegree_violation"
                    class="text ui-widget-content ui-corner-all degree_violation required">
                <option value="0">Белгиланмади</option>
                <option value="1">Енгил</option>
                <option value="2">Ўрта</option>
                <option value="3">Ифодаланган</option>
                <option value="4">Кучли ифодаланган</option>
            </select>
        </div>
        <div class="royhatGT17">
            &nbsp;
        </div>
    </TD>
    <TD>
        <label>Муомала қилиш лаёқати:</label>
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
        <label style="display:block; color: #05519e;">Кўрикдан ўтган сана:</label>
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
        <label>Ўз хулқ-атворини назорат қилиш лаёқати:</label>
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
            <!--<option value="NOSER">Белгиланмаган</option> -->
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
        <label>Ўқиш, билимларни ўзлаштириш ва такрорлаш, малака ва кўникмаларни эгаллаб олиш лаёқати:</label>
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
        <label style="display:block;  color: #05519e;">Расмийлаштирилган сана(Агар Мехнатда майибланганлик фоизи бўлса протокол расмийлаштирилган сана):</label>
        <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
               name="epdate" id="epdate" value="" class="text ui-widget-content ui-corner-all"/>
    </TD>
    <TD>
        &nbsp;
    </TD>
    <TD>
        <label>Меҳнат фаолияти билан шуғулланиш лаёқати:</label>
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
<table style="width:80%; " border="0" cellpadding="2" cellspacing="1">
    <TR>
        <TD>TMEK хужжати:</TD>
        <!-- 088 forma bolganligi uchun -->
        <TD><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="file"
                   name="file-vtk" id="vtk" accept="application/pdf"></TD>
    </TR>
    <TR>
        <TD>Ногирон расми :</TD>
        <TD><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="file"
                   name="file-amb" id="amb"></TD>
    </TR>
    <TR>
        <TD>Ногиронни врач билан тушган расми :</TD>
        <TD><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="file"
                   name="file-ktk" id="ktk"></TD>
    </TR>

</table>
</fieldset>
<BR clear="all">
<input style="float: right;" type="submit" value="Таҳрирлаш">
<BR clear="all">
<BR clear="all">
</form>
</div>

<div id="history-form" title="Бемор касаллик тарихлари">
<form id="illhistory-form" enctype="multipart/form-data" action="/monitoring/history_save" method="POST">
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
              <!--  <option value="1">Эски касаллик тарихи</option> -->
            </select>
        </TD>
    </TR>
    <TR>
        <TD style="width: 20%">
            <label style="display:block; color: #05519e;"> Мурожаат сабаби:</label>
            <select style="margin-bottom:10px; width:100%; padding: .4em; display:block;"
                    name="murojaat_sababi" id="murojaat_sababi"
                    class="text ui-widget-content ui-corner-all code-ill required">
                <option value="0"></option>
                <option value="1">Ногиронлик белгилаш</option>
                <option value="2">Ногиронлик белгиланмади</option>
            </select>
        </TD>
    </TR>
    <TR>
        <TD style="width: 20%">
            <label style="display:block; color: #05519e;">Ногиронлик белгиланиш сабаби:</label>
            <select style="margin-bottom:10px; width:100%; padding: .3em; display:block;"
                    name="tmek_xulosasi" id="tmek_xulosasi"
                    class="text ui-widget-content ui-corner-all code-ill required">
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
        <label style="display:block; color: #05519e;"> Тўлиқ реабилтация ёки вафот этган санаси:</label>
        <input style="margin-bottom:12px; width:20%; padding: .4em; display:block;" type="text"
               name="trdate" id="trdate" value=""
               class="text ui-widget-content ui-corner-all"/>
    </div>
    <br clear="all"/>
</div>

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
                    class="text ui-widget-content ui-corner-all code-ill required royhatGT17">
                <?php foreach ($royhatuz as $r) : ?>
                <option value="<?php echo $r->id; ?>"><?php echo $r->name_uz; ?></option>
                <?php endforeach; ?>
            </select>
            <select style="margin-bottom:10px; width:70%; padding: .4em; display:none;"
                    name="id_royhat" id="id_royhat"
                    class="text ui-widget-content ui-corner-all code-ill required royhatLT17">
                <?php foreach ($royhatuz18 as $r) : ?>
                    <option value="<?php echo $r->id; ?>"><?php echo $r->name_uz; ?></option>
                <?php endforeach; ?>
            </select>
        </TD>
</table>
<div id="p5" style="display: block">
<table style="width:100%; " border="0" cellpadding="0" cellspacing="0">
    <TR>
        <TD style="width: 30%">
            <label style="display:block; color: #05519e;"> Кўрик бошланган сана:</label>
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
    </TR>
</table>
<H3>Асос бўлган ҳужжатлар:</H3>
<BR>
<table style="width:80%; " border="0" cellpadding="2" cellspacing="1">
    <TR>
        <TD>TMEK хужжати:</TD>
        <!-- 088 forma bolganligi uchun -->
        <TD><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="file"
                   name="file-vtk" id="vtk" accept="application/pdf"></TD>
    </TR>
    <TR>
        <TD>Ногирон расми :</TD>
        <TD><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="file"
                   name="file-amb" id="amb"></TD>
    </TR>
    <TR>
        <TD>Ногиронни врач билан тушган расми :</TD>
        <TD><input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="file"
                   name="file-ktk" id="ktk"></TD>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td style="width: 15%">
        <label>Ўзига ўзи хизмат кўрсатиш лаёқати:</label>
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
        <label>Мустақил ҳаракатланиш лаёқати:</label>
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
        <label>Мўлжал олиш лаёқати:</label>
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
    <TD>
        <div class="royhatLT17">
            <label style="display:block; color: #05519e;">Организм функцияларининг бузилиши даражаси:</label>
            <select style="margin-bottom:10px; width:70%; padding: .4em; display:block;"
                    name="degree_violation" id="degree_violation"
                    class="text ui-widget-content ui-corner-all end_date_combo required">
                <option value="0">Белгиланмади</option>
                <option value="1">Енгил</option>
                <option value="2">Ўрта</option>
                <option value="3">Ифодаланган</option>
                <option value="4">Кучли ифодаланган</option>
            </select>
        </div>
        <div class="royhatGT17">
            &nbsp;
        </div>
    </TD>
    <TD>
        <label>Муомала қилиш лаёқати:</label>
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
        <label>Ўз хулқ-атворини назорат қилиш лаёқати:</label>
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
            <option value="NOSER">Белгиланмаган</option>
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
        <label>Ўқиш, билимларни ўзлаштириш ва такрорлаш, малака ва кўникмаларни эгаллаб олиш лаёқати:</label>
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
        <label style="display:block;  color: #05519e;">Расмийлаштирилган сана(Агар Мехнатда майибланганлик фоизи бўлса протокол расмийлаштирилган сана):</label>
        <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
               name="pdate" id="pdate" value="" class="text ui-widget-content ui-corner-all"/>
    </TD>
    <TD>
        &nbsp;
    </TD>
    <TD>
        <label>Меҳнат фаолияти билан шуғулланиш лаёқати:</label>
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
</div>
</fieldset>
<BR clear="all">
<input style="float: right" type="submit" value="Сақлаш">
<BR clear="all">
<BR clear="all">
</form>
</div>


</div>

<script type="text/javascript">
$(function () {
  initNumberInput();
  initPinflInput();
    $('#illehistory-form').ajaxForm({
	beforeSend:function () {
            //$('Сақлаш').attr("disabled", true);
            var valid = true;
            var saveForm = $("#illehistory-form");
			var reg = /^\d+$/;
			if ($("[name=enomer]", saveForm).val()) {
				if (!(reg.test($("[name=enomer]", saveForm).val()))) {
				valid = false;
				$("[name=enomer]", saveForm).addClass("error");
				}
			}
            if (!$("[name=emurojaat_sana]", saveForm).val()) {
                valid = false;
                $("[name=emurojaat_sana]", saveForm).addClass("error");
            }
            if (!$("[name=ebeg_date]", saveForm).val()) {
                valid = false;
                $("[name=ebeg_date]", saveForm).addClass("error");
            }
            if (!$("[name=ethistory]", saveForm).val()) {
                valid = false;
                $("[name=ethistory]", saveForm).addClass("error");
            }
            if (!$("[name=etmek_xulosasi]", saveForm).val()) {
                valid = false;
                $("[name=etmek_xulosasi]", saveForm).addClass("error");
            }
        },
        complete:function (xhr) {
            if (xhr.responseText.length > 0) {
                alert(xhr.responseText);
            } else {
                $("#ehistory-form").dialog("close")
                $("#history").trigger("reloadGrid");
                $("#murojaat").trigger("reloadGrid");
                $("#wwork").trigger("reloadGrid");
            }
        }
    });

    $('#illhistory-form').ajaxForm({
        beforeSend:function () {
            //$('Сақлаш').attr("disabled", true);
            var valid = true;
            var saveForm = $("#illhistory-form");

			var reg = /^\d+$/;

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
            if (!$("[name=tmek_xulosasi]", saveForm).val()) {
                valid = false;
                $("[name=tmek_xulosasi]", saveForm).addClass("error");
            }
			if ($("[name=nomer]", saveForm).val()) {
				if (!(reg.test($("[name=nomer]", saveForm).val()))) {
				valid = false;
				$("[name=nomer]", saveForm).addClass("error");
				}
			}
			if (valid==false){
				exit;
			}
        },
        complete:function (xhr) {
            if (xhr.responseText.length > 0) {
                alert(xhr.responseText);
            } else {
                $("#history-form").dialog("close");
                $("#history").trigger("reloadGrid");
                $("#murojaat").trigger("reloadGrid");
                $("#wwork").trigger("reloadGrid");
            }
        }
    });

    $("#code_ill_10_par").change(function () {
        var mkbchild = $(this).val();
        $.ajax({
            url:"/monitoring/mkb10/" + mkbchild,
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

    $("#murojaat_sababi").change(function () {
        var mschild = $(this).val();
//        alert(Date('Y-m-d'));
        $.ajax({
            url:"/monitoring/txulosa/" + mschild,
            type:"POST",
            dataType:"json",
            success:function (data) {
                $("#tmek_xulosasi").html('<option value=""></option>');
                for (var i = 0; i < data.length; i++) {
                    item = data[i];
                    $("#tmek_xulosasi").append('<option value="' + item.id + '">' + item.nogironlik_sababi + '</option>')
                }

            }
        });
    });

    $("#ecode_ill_10_par").change(function () {
        var mkbchild = $(this).val();
        $.ajax({
            url:"/monitoring/mkb10/" + mkbchild,
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
        if (val == 1 || val==6 ) {
            $("#p1").show();
            $("#p2").hide();
            $("#p3").hide();
            $("#p4").hide();
            $("#p5").show();
        }

        else if (val == 2) {
            $("#p2").show();
            $("#p1").hide();
            $("#p3").hide();
            $("#p4").hide();
            $("#p5").show();
        }
        else if (val == 3) {
            $("#p3").show();
            $("#p2").hide();
            $("#p1").hide();
            $("#p4").hide();
            $("#p5").show();
        }
        else if (val == 4) {
            $("#p4").show();
            $("#p2").hide();
            $("#p3").hide();
            $("#p1").hide();
            $("#p5").hide();
            var today = new Date();
            var curr_date = today.getDate();
            var curr_month = today.getMonth() + 1;
            var curr_year = today.getFullYear();
            var alldate = curr_year + "-" + curr_month + "-" + curr_date;
            $("#beg_date").val(alldate);
            $("#murojaat_sana").val(alldate);
        }
        else if (val == 5) {
            $("#p4").show();
            $("#p2").hide();
            $("#p3").hide();
            $("#p1").hide();
            $("#p5").show();
        }
        else {
            $("#p1").hide();
            $("#p2").hide();
            $("#p3").hide();
            $("#p4").hide();
            $("#p5").show();
        }
    });

    $('#etmek_xulosasi').change(function () {
        var val = $(this).val();
        if (val == 1 || val==6) {
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
    $("#emurojaat_sana").datepicker({
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
                    url:"/monitoring/kk",
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
            $("#p1").hide();
            $("#p2").hide();
            $("#p3").hide();
            $("#p4").hide();
            $("#p5").show();
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
        modal:true, /*
        buttons:{
            "Сақлаш":function () {
                return true;
                $('Сақлаш').attr("disabled", true);
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
                if (!$("[name=tmek_xulosasi]", saveForm).val()) {
                    valid = false;
                    $("[name=tmek_xulosasi]", saveForm).addClass("error");
                }


                if (valid) {
                    $.ajax({
                        url:"/monitoring/history_save",
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
        },*/
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
                $("[name=emurojaat_sana]", form).val(row.murojaat_sana);
                $("[name=eid_checktype]", form).val(row.id_checktype);
                $("[name=emkb_9]", form).val(row.mkb_9);
                $("[name=eguruh]", form).val(row.guruh);
                $("[name=eid_sabab]", form).val(row.id_sabab);
                $("[name=ebeg_date]", form).val(row.beg_date);
                $("[name=eend_date_combo]", form).val(row.end_date_combo);
                $("[name=edegree_violation]", form).val(row.degree_violation);
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
        height:675,
        width:1000,
        modal:true,
//        buttons:{
//            "Сақлаш":function () {
//                $.ajax({
//                    url:"/monitoring/ehistory_save",
//                    type:"POST",
//                    data:($("#illehistory-form").serializeArray()),
//                    success:function (data) {
//                        if (data.length > 0) {
//                            alert(data);
//                        } else {
//                            $("#ehistory-form").dialog("close");
//                            $("#history").trigger("reloadGrid");
//                        }
//                    },
//                    error:function () {
//                        $("#ehistory-form").dialog("close");
//                        $('#history').trigger("reloadGrid");
//                    }
//                });
//            },
//            "Ортга":function () {
//                $(this).dialog("close");
//            }
//        },
        close:function () {

        }
    });
});

</script>

</body>
</html>
