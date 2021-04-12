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
            <font color="#a52a2a">Ҳисоботлар:</font>
            <BR>
            <BR>
            <table align="center" cellpadding="0" cellspacing="0" width="80%">
                <tr class="table_header">
                    <td>Ҳисобот номи :</td>
                    <td>Қисқача изоҳи :</td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-1">1 - Бўлим</button>
                    </td>
                    <td class="first"> Республика туман(шахар), туманлараро, ихтисослатирилган ТМЭКлари хисобида турган ногиронлар тўғрисида тезкор маълумот </td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-2">2 - Бўлим</button>
                    </td>
                    <td class="first"> Танланган муддат ҳолатига туман(шаҳар), туманлараро, ихтисослаштирилган ТМЭКлар ҳисобида турган ногиронлар тўғрисида маълумот </td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-3">3 - Бўлим</button>
                    </td>
                    <td class="first">Ҳудудий ТМЭКлар ҳисобида турувчи жами 16 ёшдан катта ногиронлар сони тўғрисида маълумот</td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-4">4 - Бўлим</button>
                    </td>
                    <td class="first"> Республика тиббий-меҳнат экперт коммисиялари ҳисобида турувчи жами 16 ёшдан катта ногиронлар сони тўғрисида маълумот </td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-5">5 - Бўлим</button>
                    </td>
                    <td class="first"> Худудий Пенсия жамғарма ўлимларида ва ТМЭК лар ҳисобида турувчи жами 16 ёшдан катта ногиронлар тўғрисида малумот
                        натижалари
                    </td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-6">6 - Бўлим</button>
                    </td>
                    <td class="first"> Ҳудудий ТМЭКлар томонидан белгиланган ногиронларни умумий сони танланган ой учун
                    </td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-7">7 - Бўлим</button>
                    </td>
                    <td class="first"> Ҳудудий ТМЭКлар томонидан бирламчи белгиланган ногиронларни умумий сони танланган ой учун
                        натижалари
                    </td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-8">8 - Бўлим</button>
                    </td>
                    <td class="first"> Ногиронларни қайта кўрикдан ўтказиш натижалари</td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-9">9 - Бўлим</button>
                    </td>
                    <td class="first"> Ногиронлиги муддатсиз белгиланган шахслар сони</td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-10">10 - Бўлим</button>
                    </td>
                    <td class="first"> Шаҳарда яшовчи ногиронларни қайта кўрикдан ўтказиш натижалари</td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-11">11 - Бўлим</button>
                    </td>
                    <td class="first"> Қишлоқда яшовчи ногиронларни қайта кўрикдан ўтказиш натижалари</td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-12">12 - Бўлим</button>
                    </td>
                    <td class="first"> Барча ижтимоий тоифалар орасида қайта кўрикдан ўтказиш натижалари</td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-13">13 - Бўлим</button>
                    </td>
                    <td class="first"> Шаҳарда яшовчи аҳолини қайта кўрикдан ўтказиш натижалари</td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-14">14 - Бўлим</button>
                    </td>
                    <td class="first"> Қишлоқда яшовчи аҳолини қайта кўрикдан ўтказиш натижалари</td>
                </tr>
                <tr class="qator">
                    <td class="first">
                        <button id="create-15">15 - Бўлим</button>
                    </td>
                    <td class="first"> Ногиронларни ишга жойлаштириш ва реабилитацияси</td>
                </tr>
            </table>
        </CENTER>
    </div>
</div>
<div id="b1" title="1 - бўлим  ҳисобот параметрларини танланг">
    <form id="form-1" action="/respreports/resp1b" method="POST">
        <table>
            <tr>
                <TD>
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="kvartal" id="kvartal" class="text ui-widget-content ui-corner-all">
                        <option value="0">янги йил холатига</option>
                        <option value="5">Бугунги кун холатига</option>
                        <option value="1">1 - чорак</option>
                        <option value="2">2 - чорак</option>
                        <option value="3">3 - чорак</option>
                        <option value="4">4 - чорак</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b2" title="2 - бўлим  ҳисобот параметрларини танланг">
    <form id="form-2" action="/respreports/resp2b" method="POST">
        <table>
            <tr>
                <TD>
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="kvartal" id="kvartal" class="text ui-widget-content ui-corner-all">
                        <option value="0">янги йил холатига</option>
                        <option value="5">Бугунги кун холатига</option>
                        <option value="1">1 - чорак</option>
                        <option value="2">2 - чорак</option>
                        <option value="3">3 - чорак</option>
                        <option value="4">4 - чорак</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b3" title="3- бўлим  ҳисобот параметрларини танланг">
    <form id="form-3" action="/respreports/resp3b" method="POST">
        <table>
            <tr>
                <TD>
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="kvartal" id="kvartal" class="text ui-widget-content ui-corner-all">
                        <option value="0">янги йил холатига</option>
                        <option value="5">Бугунги кун холатига</option>
                        <option value="1">1 - чорак</option>
                        <option value="2">2 - чорак</option>
                        <option value="3">3 - чорак</option>
                        <option value="4">4 - чорак</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b4" title="4- бўлим  ҳисобот параметрларини танланг">
    <form id="form-4" action="/respreports/resp4b" method="POST">
        <table>
            <tr>
                <TD>
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="kvartal" id="kvartal" class="text ui-widget-content ui-corner-all">
                        <option value="0">янги йил холатига</option>
                        <option value="5">Бугунги кун холатига</option>
                        <option value="1">1 - чорак</option>
                        <option value="2">2 - чорак</option>
                        <option value="3">3 - чорак</option>
                        <option value="4">4 - чорак</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b5" title="5- бўлим  ҳисобот параметрларини танланг">
    <form id="form-5" action="/respreports/resp5b" method="POST">
        <table>
            <tr>
                <TD>
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="kvartal" id="kvartal" class="text ui-widget-content ui-corner-all">
                        <option value="0">янги йил холатига</option>
                        <option value="5">Бугунги кун холатига</option>
                        <option value="1">1 - чорак</option>
                        <option value="2">2 - чорак</option>
                        <option value="3">3 - чорак</option>
                        <option value="4">4 - чорак</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b6" title="6- бўлим  ҳисобот параметрларини танланг">
    <form id="form-6" action="/respreports/resp6b" method="POST">
        <table>
            <tr>
                <TD>
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="kvartal" id="kvartal" class="text ui-widget-content ui-corner-all">
                        <option value="0">янги йил холатига</option>
                        <option value="5">Бугунги кун холатига</option>
                        <option value="1">1 - чорак</option>
                        <option value="2">2 - чорак</option>
                        <option value="3">3 - чорак</option>
                        <option value="4">4 - чорак</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b7" title="7- бўлим  ҳисобот параметрларини танланг">
    <form id="form-7" action="/respreports/resp7b" method="POST">
        <table>
            <tr>
                <TD>
                    <label style="display:block;">Чоракни танланг:</label>
                    <select name="kvartal" id="kvartal" class="text ui-widget-content ui-corner-all">
                        <option value="0">янги йил холатига</option>
                        <option value="5">Бугунги кун холатига</option>
                        <option value="1">1 - чорак</option>
                        <option value="2">2 - чорак</option>
                        <option value="3">3 - чорак</option>
                        <option value="4">4 - чорак</option>
                    </select>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b8" title="8- бўлим  ҳисобот параметрларини танланг">
    <form id="form-8" action="/resptmekstat/printtmekstat" method="POST">
       <table>
            <tr>
                <TD>
                    <label style="display:block;">Бошланғич сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="begdate8" id="begdate8" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
            <tr>
                <TD>
                    <label style="display:block;">Охирги сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="enddate8" id="enddate8" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b9" title="9 - - бўлим  ҳисобот параметрларини танланг">
    <form id="form-9" action="/resptmekstat/printtmekstat" method="POST">
      <table>
            <tr>
                <TD>
                    <label style="display:block;">Бошланғич сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="begdate9" id="begdate9" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
            <tr>
                <TD>
                    <label style="display:block;">Охирги сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="enddate9" id="enddate9" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b10" title="10 - бўлим  ҳисобот параметрларини танланг">
    <form id="form-10" action="/resptmekstat/printtmekstat" method="POST">
     <table>
            <tr>
                <TD>
                    <label style="display:block;">Бошланғич сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="begdate10" id="begdate10" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
            <tr>
                <TD>
                    <label style="display:block;">Охирги сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="enddate10" id="enddate10" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b11" title="11 - бўлим  ҳисобот параметрларини танланг">
    <form id="form-11" action="/resptmekstat/printtmekstat" method="POST">
      <table>
            <tr>
                <TD>
                    <label style="display:block;">Бошланғич сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="begdate11" id="begdate11" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
            <tr>
                <TD>
                    <label style="display:block;">Охирги сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="enddate11" id="enddate11" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b12" title="12 - бўлим  ҳисобот параметрларини танланг">
    <form id="form-12" action="/resptmekstat/printtmekstat" method="POST">
        <table>
            <tr>
                <TD>
                    <label style="display:block;">Бошланғич сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="begdate12" id="begdate12" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
            <tr>
                <TD>
                    <label style="display:block;">Охирги сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="enddate12" id="enddate12" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b13" title="13 - бўлим  ҳисобот параметрларини танланг ">
    <form id="form-13" action="/resptmekstat/printtmekstat" method="POST">
      <table>
            <tr>
                <TD>
                    <label style="display:block;">Бошланғич сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="begdate13" id="begdate13" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
            <tr>
                <TD>
                    <label style="display:block;">Охирги сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="enddate13" id="enddate13" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b14" title="14 - бўлим  ҳисобот параметрларини танланг ">
    <form id="form-14" action="/resptmekstat/printtmekstat" method="POST">
       <table>
            <tr>
                <TD>
                    <label style="display:block;">Бошланғич сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="begdate14" id="begdate14" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
            <tr>
                <TD>
                    <label style="display:block;">Охирги сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="enddate14" id="enddate14" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<div id="b15" title="15 - бўлим  ҳисобот параметрларини танланг ">
    <form id="form-15" action="/resptmekstat/printtmekstat" method="POST">
       <table>
            <tr>
                <TD>
                    <label style="display:block;">Бошланғич сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="begdate15" id="begdate15" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
            <tr>
                <TD>
                    <label style="display:block;">Охирги сана:</label>
                    <input style="margin-bottom:12px;  padding: .4em; display:block;" type="text"
                           name="enddate15" id="enddate15" class="text ui-widget-content ui-corner-all"/>
                </TD>
            </tr>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<script type="text/javascript">
$(function() {
    $("#begdate").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#enddate").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#begdate2").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#enddate2").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#begdate3").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#enddate3").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#begdate4").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#enddate4").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#begdate5").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#enddate5").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#begdate6").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#enddate6").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#begdate7").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#enddate7").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#begdate8").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#enddate8").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#begdate9").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#enddate9").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#begdate10").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#enddate10").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#begdate11").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#enddate11").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#begdate12").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#enddate12").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#begdate13").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#enddate13").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#begdate14").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#enddate14").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#begdate15").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#enddate15").datepicker({
        dateFormat: 'yy-mm-dd',
        changeYear: true,
        yearRange: '1940:2050'
    });
    $("#dialog:ui-dialog").dialog("destroy");
    $("#b1").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Ортга": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#b2").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Ортга": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#b3").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Ортга": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#b4").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Ортга": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#b5").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Ортга": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#b6").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Ортга": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#b7").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Ортга": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#b8").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Ортга": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#b9").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Ортга": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#b10").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Ортга": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#b11").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Ортга": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#b12").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Ортга": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#b13").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Ортга": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#b14").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Ортга": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#b15").dialog({
        autoOpen: false,
        height: 250,
        width: 300,
        modal: true,
        buttons: {
            "Ортга": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#create-1")
            .button()
            .click(function() {
        $("#b1").dialog();
        $("#b1").dialog("open");
    });
    $("#create-2")
            .button()
            .click(function() {
        $("#b2").dialog();
        $("#b2").dialog("open");
    });
    $("#create-3")
            .button()
            .click(function() {
        $("#b3").dialog();
        $("#b3").dialog("open");
    });
    $("#create-4")
            .button()
            .click(function() {
        $("#b4").dialog();
        $("#b4").dialog("open");
    });
    $("#create-5")
            .button()
            .click(function() {
        $("#b5").dialog();
        $("#b5").dialog("open");
    });
    $("#create-6")
            .button()
            .click(function() {
        $("#b6").dialog();
        $("#b6").dialog("open");
    });
    $("#create-7")
            .button()
            .click(function() {
        $("#b7").dialog();
        $("#b7").dialog("open");
    });
    $("#create-8")
            .button()
            .click(function() {
        $("#b8").dialog();
        $("#b8").dialog("open");
    });
    $("#create-9")
            .button()
            .click(function() {
        $("#b9").dialog();
        $("#b9").dialog("open");
    });
    $("#create-10")
            .button()
            .click(function() {
        $("#b10").dialog();
        $("#b10").dialog("open");
    });
    $("#create-11")
            .button()
            .click(function() {
        $("#b11").dialog();
        $("#b11").dialog("open");
    });
    $("#create-12")
            .button()
            .click(function() {
        $("#b12").dialog();
        $("#b12").dialog("open");
    });
    $("#create-13")
            .button()
            .click(function() {
        $("#b13").dialog();
        $("#b13").dialog("open");
    });
    $("#create-14")
            .button()
            .click(function() {
        $("#b14").dialog();
        $("#b14").dialog("open");
    });
    $("#create-15")
            .button()
            .click(function() {
        $("#b15").dialog();
        $("#b15").dialog("open");
    });
});

</script>

</body>
</html>