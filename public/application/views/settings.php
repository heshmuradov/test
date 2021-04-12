<body>
<div id="asosiy">
    <?php $this->load->view("menu")?>
    <div id="vback">
        <?php
        foreach($login as $l):
        endforeach;
        ?>
        <script>
            $(document).ready(function () {

                $(".item").click(function () {
                    $($(this).attr("href")).slideToggle();
                    return false;
                });
            });
        </script>
        <?php

       

        ?>
        <div id="main_user">
               <?php print form_open("/main/settings", array('method'=>'post', 'id'=>'signin')) ?>
                <a style="color: #233A60;font-size: 14px;font-weight: bold;margin-bottom: 10px;text-decoration: none;" href="#personal" class="item">Паролни ўзгартириш</a>
                <div id="personal" class="item-c">
                    <BR clear="all">
                    <table style="text-align:left;">
                        <TR>
                            <TD>Фойдаланувчи номи:</TD>
                            <TD id="view_user"><?php echo $l ?>
                            </TD>
                        </TR>
                        <TR>
                            <TD>Пароль:</TD>
                            <TD><input type="password" name="new_pass" size="20">
                            </TD>
                        </TR>
                    </table>
                    <input type="submit" name="new" value="Сақлаш">
                </div>
        </div>

        <table id="list" class="scroll"></table>
        <div id="pager" class="scroll" style="text-align:center;"></div>
    </div>
</div>

</body>
</html>




 
