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
            colNames:['Ун.Рақам', 'Фамилияси', 'Исми', 'Шарифи'],
            colModel:[
                {name:'id', index:'id', width:15},
                {name:'familiya', index:'familiya', width:25, editable:true, edittype:'text', editrules:{edithidden:true, required:true}},
                {name:'name', index:'name', width:25, editable:true, edittype:'text', editrules:{edithidden:true, required:true}},
                {name:'middle', index:'middle', width:25, editable:true, edittype:'text'}
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
            autowidth:true
        });
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
