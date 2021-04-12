<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.21.custom.min.js"></script>

<?php
$iddep = $this->session->userdata('iddep');
$dep = $this->db->query("select id, name_uz from spr_depart where id=$iddep OR par_id = $iddep")->result();
?>

<body>
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

    label.error {
        font-family: Arial, sans-serif;
        font-size: 11px;
        color: #51b500;
    }

    input.error {
        border: 1px solid #51b500;
    }

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

<div id="asosiy">
<?php $this->load->view("menu")?>
<div id="vback">
<div class="demo">
    <script type="text/javascript">

        $(document).ready(function () {
            $("#tabs").tabs();
        });

    </script>


    <div id="tabs">
        <ul>
            <li><a href="#fragment-1"><span>Ойлик ҳисоботлар</span></a></li>
            <li><a href="#fragment-2"><span>Чораклик ҳисоботлар</span></a></li>
            <li><a href="#fragment-3"><span>Йиллик ҳисоботлар</span></a></li>
        </ul>
        <div id="fragment-1">
            <CENTER>
                <table align="center" cellpadding="0" cellspacing="0" width="80%">
                    <tr class="table_header">
                        <td>Ҳисобот номи :</td>
                        <td>Қисқача изоҳи :</td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report8">8 - жадвал</button>
                        </td>
                        <td class="first">ТМЭКлари ва БТПЖлар хисобида турувчи жами 16 ёшдан юкори ногиронлар сони</td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report81">81 - жадвал</button>
                        </td>
                        <td class="first">ТМЭК ва БТПЖ худудлари хисобида турувчи жами 16 ёшдан юкори ногиронлар сони</td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report10">10 - жадвал</button>
                        </td>
                        <td class="first">Ҳудудий ТМЭКларда белгиланган бирламчи ногиронлик</td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report12">12 - жадвал</button>
                        </td>
                        <td class="first"> Ҳудудий ТМЭКларда белгиланган қайта ногиронлик</td>
                    </tr>
                   <!-- <tr class="qator">
                        <td class="first">
                            <button id="report16">16 - жадвал</button>
                        </td>
                        <td class="first"> Реабилитация, ногиронликни оғирлаштириш ва муддатсиз белгилаш кўрсаткичлари
                        </td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report20">.. - жадвал</button>
                        </td>
                        <td class="first"> Ногиронлик белгиланган (меҳнат қобилиятини йўқотиш даражаси аниқланган)
                            фуқаролар
                        </td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report21">.. - жадвал</button>
                        </td>
                        <td class="first">Ногиронлиги (меҳнат қобилиятини йўқотиш даражаси) бекор қилинган фуқаролар
                        </td>
                    </tr>-->
                </table>
            </CENTER>
        </div>
        <div id="fragment-2">

            <CENTER>
                <table align="center" cellpadding="0" cellspacing="0" width="80%">
                    <tr class="table_header">
                        <td>Ҳисобот номи :</td>
                        <td>Қисқача изоҳи :</td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report1">1 - жадвал</button>
                        </td>
                        <td class="first">Ҳудудий ТМЭКлар ҳисобида турувчи жами 16 ёшдан катта ногиронлар</td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report4">4 - жадвал</button>
                        </td>
                        <td class="first">Ҳудудий Пенсия жамғармаси бўлимлари ва ТМЭКлар ҳисобида турувчи жами 16 ёшдан
                            катта ногиронлар
                        </td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report5">5 - жадвал</button>
                        </td>
                        <td class="first"> Ҳудудий ТМЭКлар томонидан 2011 йилда бирламчи белгиланган ногиронлик сони ва
                            Пенсия жамғармаси бўлимлари томонидан тайинланган ногиронлик пенсия ва нафақа ишлари сони
                        </td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report11">11 - жадвал</button>
                        </td>
                        <td class="first">2010-2011 йиллар давомида ҳудудий ТМЭКларда белгиланган бирламчи ногиронлик
                            тўғрисида солиштирма
                        </td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report13">13 - жадвал</button>
                        </td>
                        <td class="first">2010-2011 йиллар давомида ҳудудий ТМЭКларда белгиланган қайта ногиронлик
                            тўғрисида солиштирма
                        </td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report14">14 - жадвал</button>
                        </td>
                        <td class="first">Бирламчи ногиронлик белгиланганларнинг касаллик турлари бўйича тақсимланиши
                        </td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report15">15 - жадвал</button>
                        </td>
                        <td class="first">2090-2011 йил давомида бирламчи ногиронлик белгиланганларнинг касаллик турлари
                            бўйича тақсимланиши
                        </td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report17">17 - жадвал</button>
                        </td>
                        <td class="first">2012 йилда ногиронликни реабилитация қилиш, оғирлаштириш ва муддатсиз белгилаш
                            кўрсаткичлари
                        </td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report19">19 - жадвал</button>
                        </td>
                        <td class="first">ТМЭКлар томонидан 16 ёшгача болаларга расмийлаштирилган ногиронлик
                            хулосаларини қайта кўриб чиқиш натижалари
                        </td>
                    </tr>
                </table>
            </CENTER>
        </div>
        <div id="fragment-3">
            <CENTER>
                <table align="center" cellpadding="0" cellspacing="0" width="80%">
                    <tr class="table_header">
                        <td>Ҳисобот номи :</td>
                        <td>Қисқача изоҳи :</td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report2">2 - жадвал</button>
                        </td>
                        <td class="first">Республика ТМЭКлар ҳисобида турувчи жами 16 ёшдан катта ногиронлар сони
                            тўғрисида асосий кўрсаткичлар
                        </td>
                    </tr>
                    <tr class="qator">
                        <td class="first">
                            <button id="report9">9 - жадвал</button>
                        </td>
                        <td class="first">Ўзбекистон Республикасида ногиронлик ва реабилитация кўрсаткичлари тўғрисида
                            умумлаштирилган маълумот (1991-2008 йй.)
                        </td>
                    </tr>
                </table>
            </CENTER>
        </div>
        <br clear="all"/>
    </div>
</div>

<div id="r1" title="1 - жадвал">
    <form id="report-from" action="/showreports/chreport1" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart" id="id_depart" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="period" id="period" class="text ui-widget-content ui-corner-all">
                        <option value="1">I - чорак</option>
                        <option value="2">II - чорак</option>
                        <option value="3">III - чорак</option>
                        <option value="4">IV - чорак</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>

<div id="r2" title="2 - жадвал">
    <form id="report-from1" action="/showreports/yreport2" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart1" id="id_depart1" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="period1" id="period1" class="text ui-widget-content ui-corner-all">
                        <option value="0">Барча йиллар</option>
                        <option value="1">2010</option>
                        <option value="2">2011</option>
                        <option value="3">2012</option>
                        <option value="4">2013</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>

<div id="r4" title="4 - жадвал">
    <form id="report-from4" action="/showreports/chreport4" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart4" id="id_depart4" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="period4" id="period4" class="text ui-widget-content ui-corner-all">
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

<div id="r5" title="5 - жадвал">
    <form id="report-from5" action="/showreports/chreport5" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart5" id="id_depart5" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="period5" id="period5" class="text ui-widget-content ui-corner-all">
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

<div id="r8" title="8 - жадвал">
    <form id="report-from8" action="/showreports/oreport8" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart8" id="id_depart8" class="text ui-widget-content ui-corner-all">

                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
                        <?php endforeach; ?>
                    </select>
                </TD>
            </tr>
            <tr>
                <TD>
                    &nbsp;
                </TD>
            </tr>
            <!--<tr>
                <TD>
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="period8" id="period8" class="text ui-widget-content ui-corner-all">
                        <option value="1">Январь</option>
                        <option value="2">Февраль</option>
                        <option value="3">Март</option>
                        <option value="4">Апрель</option>
                        <option value="4">Май</option>
                        <option value="4">Июнь</option>
                        <option value="4">Июль</option>
                        <option value="4">Августь</option>
                        <option value="4">Сентябрь</option>
                        <option value="4">Октябрь</option>
                        <option value="4">Ноябрь</option>
                        <option value="4">Декабрь</option>
                    </select>
                </TD>
            </tr> -->
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="r81" title="81 - жадвал">
    <form id="report-from81" action="/showreports/oreport81" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">Худудларни танланг:</label>
                    <select name="hudud8" id="hudud8" class="text ui-widget-content ui-corner-all">
                        <!-- <option value="2000">Жами</option> -->
                        <?php foreach ($fborn as $fb) : ?>
                        <option value="<?php echo $fb->id; ?>"><?php echo $fb->name_uz; ?></option>
                        <?php endforeach; ?>
                    </select>
                </TD>
            </tr>
            <tr>
                <TD>
                    &nbsp;
                </TD>
            </tr>
            <!--<tr>
                <TD>
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="period8" id="period8" class="text ui-widget-content ui-corner-all">
                        <option value="1">Январь</option>
                        <option value="2">Февраль</option>
                        <option value="3">Март</option>
                        <option value="4">Апрель</option>
                        <option value="4">Май</option>
                        <option value="4">Июнь</option>
                        <option value="4">Июль</option>
                        <option value="4">Августь</option>
                        <option value="4">Сентябрь</option>
                        <option value="4">Октябрь</option>
                        <option value="4">Ноябрь</option>
                        <option value="4">Декабрь</option>
                    </select>
                </TD>
            </tr> -->
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>

<div id="r9" title="9 - жадвал">
    <form id="report-from9" action="/showreports/yreport9" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart9" id="id_depart9" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="period9" id="period9" class="text ui-widget-content ui-corner-all">
                        <option value="1">2010</option>
                        <option value="2">2011</option>
                        <option value="3">2012</option>
                        <option value="3">2013</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>

<div id="r10" title="10 - жадвал">
    <form id="report-from10" action="/showreports/oreport10" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart10" id="id_depart10" class="text ui-widget-content ui-corner-all">
                       <!-- <option value="0">Жами</option>-->
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Ойни танланг:</label>
                    <select name="period10" id="period10" class="text ui-widget-content ui-corner-all">
                        <option value="1">Январь</option>
                        <option value="2">Февраль</option>
                        <option value="3">Март</option>
                        <option value="4">Апрель</option>
                        <option value="5">Май</option>
                        <option value="6">Июнь</option>
                        <option value="7">Июль</option>
                        <option value="8">Августь</option>
                        <option value="9">Сентябрь</option>
                        <option value="10">Октябрь</option>
                        <option value="11">Ноябрь</option>
                        <option value="12">Декабрь</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>

<div id="r11" title="11 - жадвал">
    <form id="report-from11" action="/showreports/chreport11" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart11" id="id_depart11" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="period11" id="period11" class="text ui-widget-content ui-corner-all">
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

<div id="r12" title="12 - жадвал">
    <form id="report-from12" action="/showreports/oreport12" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart12" id="id_depart12" class="text ui-widget-content ui-corner-all">
                     <!--   <option value="0">Жами</option>-->
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Ойни танланг:</label>
                    <select name="period12" id="period12" class="text ui-widget-content ui-corner-all">
                        <option value="1">Январь</option>
                        <option value="2">Февраль</option>
                        <option value="3">Март</option>
                        <option value="4">Апрель</option>
                        <option value="4">Май</option>
                        <option value="4">Июнь</option>
                        <option value="4">Июль</option>
                        <option value="4">Августь</option>
                        <option value="4">Сентябрь</option>
                        <option value="4">Октябрь</option>
                        <option value="4">Ноябрь</option>
                        <option value="4">Декабрь</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>

<div id="r13" title="13 - жадвал">
    <form id="report-from13" action="/showreports/chreport13" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart13" id="id_depart13" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="period13" id="period13" class="text ui-widget-content ui-corner-all">
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

<div id="r14" title="14 - жадвал">
    <form id="report-from14" action="/showreports/chreport14" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart14" id="id_depart14" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="period14" id="period14" class="text ui-widget-content ui-corner-all">
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

<div id="r15" title="15 - жадвал">
    <form id="report-from15" action="/showreports/chreport15" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart15" id="id_depart15" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="period15" id="period15" class="text ui-widget-content ui-corner-all">
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

<div id="r16" title="16 - жадвал">
    <form id="report-from16" action="/showreports/oreport16" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart16" id="id_depart16" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Ойни танланг:</label>
                    <select name="period16" id="period16" class="text ui-widget-content ui-corner-all">
                        <option value="1">Январь</option>
                        <option value="2">Февраль</option>
                        <option value="3">Март</option>
                        <option value="4">Апрель</option>
                        <option value="4">Май</option>
                        <option value="4">Июнь</option>
                        <option value="4">Июль</option>
                        <option value="4">Августь</option>
                        <option value="4">Сентябрь</option>
                        <option value="4">Октябрь</option>
                        <option value="4">Ноябрь</option>
                        <option value="4">Декабрь</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>

<div id="r17" title="17 - жадвал">
    <form id="report-from17" action="/showreports/chreport17" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart17" id="id_depart17" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="period17" id="period17" class="text ui-widget-content ui-corner-all">
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

<div id="r19" title="19 - жадвал">
    <form id="report-from19" action="/showreports/chreport17" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart19" id="id_depart19" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="period19" id="period19" class="text ui-widget-content ui-corner-all">
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

<div id="r20" title=".. - жадвал">
    <form id="report-from20" action="/showreports/oreport20" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart20" id="id_depart20" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Ойни танланг:</label>
                    <select name="period20" id="period20" class="text ui-widget-content ui-corner-all">
                        <option value="1">Январь</option>
                        <option value="2">Февраль</option>
                        <option value="3">Март</option>
                        <option value="4">Апрель</option>
                        <option value="4">Май</option>
                        <option value="4">Июнь</option>
                        <option value="4">Июль</option>
                        <option value="4">Августь</option>
                        <option value="4">Сентябрь</option>
                        <option value="4">Октябрь</option>
                        <option value="4">Ноябрь</option>
                        <option value="4">Декабрь</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>

<div id="r21" title=".. - жадвал">
    <form id="report-from21" action="/showreports/oreport21" method="POST">
        <table>
            <BR/>
            <tr>
                <TD>
                    <label style="display:block;">ТМЭКни танланг:</label>
                    <select name="id_depart21" id="id_depart21" class="text ui-widget-content ui-corner-all">
                        <option value="0">Жами</option>
                        <?php foreach ($depart as $d) : ?>
                        <option value="<?php echo $d->id; ?>"><?php echo $d->name_uz; ?></option>
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
                    <label style="display:block;">Ойни танланг:</label>
                    <select name="period21" id="period21" class="text ui-widget-content ui-corner-all">
                        <option value="1">Январь</option>
                        <option value="2">Февраль</option>
                        <option value="3">Март</option>
                        <option value="4">Апрель</option>
                        <option value="4">Май</option>
                        <option value="4">Июнь</option>
                        <option value="4">Июль</option>
                        <option value="4">Августь</option>
                        <option value="4">Сентябрь</option>
                        <option value="4">Октябрь</option>
                        <option value="4">Ноябрь</option>
                        <option value="4">Декабрь</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>


<script type="text/javascript">
$("#report1")
    .button()
    .click(function () {
        $("#r1").dialog();
        $("#r1").dialog("open");
    });

$("#r1").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report2")
    .button()
    .click(function () {
        $("#r2").dialog();
        $("#r2").dialog("open");
    });

$("#r2").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report4")
    .button()
    .click(function () {
        $("#r4").dialog();
        $("#r4").dialog("open");
    });

$("#r4").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report5")
    .button()
    .click(function () {
        $("#r5").dialog();
        $("#r5").dialog("open");
    });

$("#r5").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report8")
    .button()
    .click(function () {
        $("#r8").dialog();
        $("#r8").dialog("open");
    });

$("#r8").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report81")
        .button()
        .click(function () {
            $("#r81").dialog();
            $("#r81").dialog("open");
        });

$("#r81").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});


$("#report9")
    .button()
    .click(function () {
        $("#r9").dialog();
        $("#r9").dialog("open");
    });

$("#r9").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report10")
    .button()
    .click(function () {
        $("#r10").dialog();
        $("#r10").dialog("open");
    });

$("#r10").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report11")
    .button()
    .click(function () {
        $("#r11").dialog();
        $("#r11").dialog("open");
    });

$("#r11").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report12")
    .button()
    .click(function () {
        $("#r12").dialog();
        $("#r12").dialog("open");
    });

$("#r12").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report13")
    .button()
    .click(function () {
        $("#r13").dialog();
        $("#r13").dialog("open");
    });

$("#r13").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report14")
    .button()
    .click(function () {
        $("#r14").dialog();
        $("#r14").dialog("open");
    });

$("#r14").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report15")
    .button()
    .click(function () {
        $("#r15").dialog();
        $("#r15").dialog("open");
    });

$("#r15").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report16")
    .button()
    .click(function () {
        $("#r16").dialog();
        $("#r16").dialog("open");
    });

$("#r16").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report17")
    .button()
    .click(function () {
        $("#r17").dialog();
        $("#r17").dialog("open");
    });

$("#r17").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report19")
    .button()
    .click(function () {
        $("#r19").dialog();
        $("#r19").dialog("open");
    });

$("#r19").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report20")
    .button()
    .click(function () {
        $("#r20").dialog();
        $("#r20").dialog("open");
    });

$("#r20").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

$("#report21")
    .button()
    .click(function () {
        $("#r21").dialog();
        $("#r21").dialog("open");
    });

$("#r21").dialog({
    autoOpen:false,
    height:300,
    width:400,
    modal:true,
    buttons:{
        "Ортга":function () {
            $(this).dialog("close");
        }
    }
});

</script>
</body>
</html>

