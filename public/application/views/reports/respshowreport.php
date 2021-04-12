<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.21.custom.min.js"></script>
<?php
$iddep = $this->session->userdata('iddep');
$dep = $this->db->query("select id, name_uz from spr_depart where id=$iddep OR par_id = $iddep")->result();
?>
<style type="text/css">
    tr.table_header td {
        border-top: 1px solid #94614e;
        border-bottom: 1px solid #94614e;
        background: transparent url(/images/table-header.png) repeat-x;
        height: 25px;
        font-size: 12px;
        font-family: arial, sans-serif;
        color: #333399;
        text-align: center;
        padding: 0px;
        width: 50%;
    }

    tr.table_header tr.toq td {
        border: 1px;
    }

    tr.qator td {
        font-family: tahoma;
        font-size: 12px;
        padding: 7px;
        line-height: 15px;
        border-bottom: 1px solid #e4e4e4;
        text-align: center;
    }

    tr.qator td.first {
        border-left: 1px solid #e4e4e4;
        border-right: 1px solid #e4e4e4;
        text-align: center;
        font-size: 11px;
    }

    tr.qator td.first_report {
        border-left: 1px solid #e4e4e4;
        font-family: tahoma;
        font-size: 12px;
        font-weight: bold;
    }

    tr.qator td.last {
        border-right: 1px solid #e4e4e4;
        text-align: center;
        font-size: 11px;
    }
</style>
<body>
<div id="asosiy">
    <?php $this->load->view("menu")?>
    <div id="vback">
        <style>
            @import "/css/new/jquery-ui-1.8.21.custom.css";
            @import "/css/ui_jqgrid.css";

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
        </style>

        <CENTER>
            <BR>
            <table align="center" cellpadding="0" cellspacing="0" width="80%">
                <tr class="table_header">
                    <td>Ҳисобот номи :</td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-0">Ногиронлар рўйхатини чиқариш(ТМЭКлар бўйича)
                        </button>
                    </td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-1">Жами ногиронликнинг касаллик турлари бўйича солиштирма таҳлили ҳақида
                            маълумот
                        </button>
                    </td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-3">ТМЭК ҳисобида турувчи 16 ёшдан катта жами ногиронлар сони ҳақида маълумот
                        </button>
                    </td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-4">16 ёшдан катта ногиронларни ногиронлик сабаблари бўйича маълумот</button>
                    </td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-5">Биринчи маротаба ногиронлик аниқланганларни гуруҳлар бўйича тақсимлаш
                            ҳақида маълумот
                        </button>
                    </td>
                </tr>
            </table>
        </CENTER>
    </div>
</div>
<div id="b0" title="Жами ногиронликнинг касаллик турлари бўйича солиштирма таҳлили ҳақида  маълумот">
    <form id="form-0" action="/respshowreports/b0" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКлари рўйхати:</label>
                    <select name="id_depart" id="id_depart" class="text ui-widget-content ui-corner-all">
                        <?php foreach ($id_depart as $iddp) : ?>
                        <option value="<?php echo $iddp->id; ?>"><?php echo $iddp->name_uz; ?></option>
                        <?php endforeach; ?>
                    </select>
                </TD>
                <TD>
                    <label style="display:block; margin-left: 15px;">Бирламчи ёки қайта ногиронлик</label>
                    <select name="id_checktype0" id="id_checktype0" class="text ui-widget-content ui-corner-all"
                            style="margin-left: 15px;">
                        <option value="0">Жами</option>
                        <?php foreach ($sabab as $s) : ?>
                        <option value="<?php echo $s->id; ?>"><?php echo $s->name_uz; ?></option>
                        <?php endforeach; ?>
                    </select>
                </TD>
            </TR>
            <TR>
                <TD>
                    <label style="display:block;">Гуруҳи:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="guruh" id="guruh" class="text ui-widget-content ui-corner-all"/>
                </TD>

                <TD>
                    <label style="display:block; margin-left: 15px;">Жинси</label>
                    <select name="gender" id="gender" class="text ui-widget-content ui-corner-all"
                            style="margin-left: 15px; width: 70px">
                        <option value="0">Жами</option>
                        <?php foreach ($gender as $g) : ?>
                        <option value="<?php echo $g->id; ?>"><?php echo $g->name_uz; ?></option>
                        <?php endforeach; ?>
                    </select>
                </TD>
            </TR>
            <TR>
                <TD>
                    <label style="display:block; ">Ногиронлиги</label>
                    <select name="bn" id="bn" class="text ui-widget-content ui-corner-all">
                        <option value="">Жами</option>
                        <option value="1">Болаликдан эмас</option>
                        <option value="2">Болаликдан</option>
                    </select>
                </TD>
            </TR>

            <tr>
                <TD>
                    &nbsp;
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b1" title="Жами ногиронликнинг касаллик турлари бўйича солиштирма таҳлили ҳақида  маълумот">
    <form id="form-1" action="/respshowreports/b1" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">Бирламчи ёки қайта ногиронлик</label>
                    <select name="id_checktype" id="id_checktype" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($sabab as $s) : ?>
                        <option value="<?php echo $s->id; ?>"><?php echo $s->name_uz; ?></option>
                        <?php endforeach; ?>
                    </select>
                </TD>
            </tr>
            <tr>
                <TD>
                    &nbsp;
                </TD>
            </tr>
            <tr>
                <TD>
                    <label style="display:block;">Оралиқни танланг:</label>
                    <select name="period" id="period" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жорий санагача</option>
                        <option value="1">I - чорак</option>
                        <option value="2">II - чорак</option>
                        <option value="3">III - чорак</option>
                        <option value="4">IV - чорак</option>
                        <option value="5">Йиллик</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b3" title="Жами ногиронликнинг касаллик турлари бўйича солиштирма таҳлили ҳақида  маълумот">
    <form id="form-3" action="/respshowreports/b3" method="POST">
        <table>
            <tr>
                <TD>
                    &nbsp;
                </TD>
            </tr>
            <tr>
                <TD>
                    <label style="display:block;">Оралиқни танланг:</label>
                    <select name="period" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жорий санагача</option>
                        <option value="1">I - чорак</option>
                        <option value="2">II - чорак</option>
                        <option value="3">III - чорак</option>
                        <option value="4">IV - чорак</option>
                        <option value="5">Йиллик</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b4" title="Жами ногиронликнинг касаллик турлари бўйича солиштирма таҳлили ҳақида  маълумот">
    <form id="form-4" action="/respshowreports/b4" method="POST">
        <table>
            <tr>
                <TD>
                    &nbsp;
                </TD>
            </tr>
            <tr>
                <TD>
                    <label style="display:block;">Оралиқни танланг:</label>
                    <select name="period" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жорий санагача</option>
                        <option value="1">I - чорак</option>
                        <option value="2">II - чорак</option>
                        <option value="3">III - чорак</option>
                        <option value="4">IV - чорак</option>
                        <option value="5">Йиллик</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b5" title="Жами ногиронликнинг касаллик турлари бўйича солиштирма таҳлили ҳақида  маълумот">
    <form id="form-5" action="/respshowreports/b5" method="POST">
        <table>
            <tr>
                <TD>
                    <label style="display:block;">Бирламчи ёки қайта ногиронлик</label>
                    <select name="id_checktype" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($sabab as $s) : ?>
                        <option value="<?php echo $s->id; ?>"><?php echo $s->name_uz; ?></option>
                        <?php endforeach; ?>
                    </select>
                </TD>
            </tr>
            <tr>
                <TD>
                    &nbsp;
                </TD>
            </tr>
            <tr>
                <TD>
                    <label style="display:block;">Оралиқни танланг:</label>
                    <select name="period" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жорий санагача</option>
                        <!--                        <option value="1">I - чорак</option>-->
                        <!--                        <option value="2">II - чорак</option>-->
                        <!--                        <option value="3">III - чорак</option>-->
                        <!--                        <option value="4">IV -  чорак</option>-->
                        <!--                        <option value="5">Йиллик</option>-->
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<script type="text/javascript">
    $(function () {
        $("#dialog:ui-dialog").dialog("destroy");
        $("#b0").dialog({
            autoOpen:false,
            height:300,
            width:550,
            modal:true,
            buttons:{
                "Ортга":function () {
                    $(this).dialog("close");
                }
            }
        });
        $("#b1").dialog({
            autoOpen:false,
            height:300,
            width:550,
            modal:true,
            buttons:{
                "Ортга":function () {
                    $(this).dialog("close");
                }
            }
        });
        $("#b3").dialog({
            autoOpen:false,
            height:300,
            width:550,
            modal:true,
            buttons:{
                "Ортга":function () {
                    $(this).dialog("close");
                }
            }
        });
        $("#b4").dialog({
            autoOpen:false,
            height:300,
            width:550,
            modal:true,
            buttons:{
                "Ортга":function () {
                    $(this).dialog("close");
                }
            }
        });
        $("#b5").dialog({
            autoOpen:false,
            height:300,
            width:550,
            modal:true,
            buttons:{
                "Ортга":function () {
                    $(this).dialog("close");
                }
            }
        });

        $("#create-0")
            .button()
            .click(function () {
                $("#b0").dialog();
                $("#b0").dialog("open");
            });
        $("#create-1")
            .button()
            .click(function () {
                $("#b1").dialog();
                $("#b1").dialog("open");
            });
        $("#create-3")
            .button()
            .click(function () {
                $("#b3").dialog();
                $("#b3").dialog("open");
            });
        $("#create-4")
            .button()
            .click(function () {
                $("#b4").dialog();
                $("#b4").dialog("open");
            });
        $("#create-5")
            .button()
            .click(function () {
                $("#b5").dialog();
                $("#b5").dialog("open");
            });
    });

</script>

</body>
</html>