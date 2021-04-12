<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.8.21.custom.min.js"></script>
<?php
$is_mon = $this->session->userdata['admin_type'] == 3;
?>
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
        <button id="create-0">ТМЭКни танланг</button>
    </div>
</div>
<div id="b0">
    <form id="form-0" action="/tmekstat/tmekreport" method="POST">
        <table>
            <BR/>
            <?php if ($is_mon) : ?>
            <TR>
                <TD>
                    <label style="display:block;">ТМЭКни танланг</label>
                    <select name="id_depart" id="id_depart" class="text ui-widget-content ui-corner-all">
                        <?php foreach ($id_depart as $iddp) : ?>
                        <option value="<?php echo $iddp->id; ?>"><?php echo $iddp->name_uz; ?></option>
                        <?php endforeach; ?>
                    </select>
                </TD>
            </TR>
            <?php endif; ?>
        </table>
        <BR clear="all">
        <input type="submit" value="Excel га юклаш" name="OK">
    </form>
</div>
<script type="text/javascript">
    $(function () {
        $("#dialog:ui-dialog").dialog("destroy");
        $("#b0").dialog({
            autoOpen:true,
            height:200,
            width:250,
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
    });

</script>

</body>
</html>