<?php
/*
 * Бу файл менюларни чиқариш учун ишлатилади
 */
$idp = $this->session->userdata("iddep");
$id = $this->session->userdata("admin_id");

$k = $this->db->query("SELECT fio, login  FROM admin_info a, log_admin l
                                  WHERE
                                        a.id_depart=$idp
                                  AND
                                        a.id_log_admin=l.id
                                  AND
                                  		l.id = $id
                          ")->result();
$result = $k[0];

$message = $this->db->query("SELECT dm.id
                        FROM depart_message dm
                        WHERE dm.to_depart=$idp
                        AND dm.view_date is null
                        ")->num_rows();

$approve = $this->db->query("SELECT m.id
                        FROM mijoz m
                        left join spr_region sr on m.id_born=sr.id
                        LEFT JOIN spr_place sp ON m.id_place=sp.id
                        left join spr_gender sg on m.gender=sg.id
                        left join mijoz_ill_history mih on mih.id_mijoz=m.id
                        left join spr_pass_seriya sps on sps.pass_seriya=m.pass_seriya
                        WHERE m.id_depart=$idp
                        and mih.old=0
                        and mih.approve=0
                        and m.move=0
                        and m.pass_seriya in (select pass_seriya from spr_pass_seriya)
                        and (mih.seriya in (select name_uz from spr_spseriya) or mih.seriya is null)")->num_rows();

$mtn = $this->db->query("SELECT m.id
                        FROM mijoz m
                        left join spr_region sr on m.id_born=sr.id
                        LEFT JOIN spr_place sp ON m.id_place=sp.id
                        left join spr_gender sg on m.gender=sg.id
                        LEFT JOIN mijoz_ill_history h on h.id_mijoz=m.id
                        WHERE m.id_depart=$idp
                        and h.id_royhat in (1,2,3,4,5,6,10,11,12)
                        AND h.old=0
                        AND h.approve=1
                        and m.move=0
                        AND h.end_date < CURRENT_DATE")->num_rows();

?>
<style>
    #soc {
        position: fixed;
        left: 0px;
        top: 30%;
        width: 50px;
        height: 400px;
        margin: -90px 0px 0px;
        background-color: #3c4d6d;
        border-top-left-radius: 0px;
        border-top-right-radius: 5px;
        border-bottom-right-radius: 5px;
        border-bottom-left-radius: 0px;
        border-width: 2px 0px 2px 2px;
        border-top-color: white;
        border-bottom-color: white;
        border-left-color: white;
        border-top-style: solid;
        border-bottom-style: solid;
        border-left-style: solid;
        box-shadow: #1c94c4 2px 0px 10px 0px;
        -webkit-box-shadow: #D9D9D9 2px 0px 10px 0px;
        background-position: initial initial;
        background-repeat: initial initial;
    }

    #minimenu {
        margin-top: 50px;
    }

    #minimenu li {
        /*display: inline-block;*/
        /*vertical-align: middle;*/
        /*width: 95px;*/

    }

    #minimenu li a {
        color: #0A83AB;
        /*display: block;*/
        font-style: normal;
        text-decoration: none;
    }

    #minimenu li a:hover {
        visibility: visible;
    }

    #minimenu li i {
        display: block;
        height: 40px;
        margin: 10px 0 0px;
        overflow: hidden;
    }

    #minimenu li a img {
        cursor: pointer;
        margin: 0 auto;
        vertical-align: bottom;
    }

    #minimenu li a:hover img {
        margin: 0px auto 0;
    }

    #minimenu #sms a i {
        background-image: url("/images/message.png");
    }

    #minimenu  #sms  a:hover  i {
        background-position: 50% -41px;
        cursor: pointer;
    }

    #minimenu #mtn a i {
        margin-top: 20px;
        background-image: url("/images/mtn.png");
    }

    #minimenu  #mtn  a:hover  i {
        background-position: 50% -42.5px;
        cursor: pointer;
    }

    #minimenu #tkn a i {
        margin-top: 20px;
        background-image: url("/images/tkn.png");
    }

    #minimenu  #tkn  a:hover  i {
        background-position: 50% -43px;
        cursor: pointer;
    }

    #minimenu #ven a i {
        margin-top: 20px;
        background-image: url("/images/ven.png");
    }

    #minimenu  #ven  a:hover  i {
        background-position: 50% -40px;
        cursor: pointer;
    }

    #minimenu #kkn a i {
        margin-top: 20px;
        background-image: url("/images/kkn.png");
    }

    #minimenu  #kkn  a:hover  i {
        background-position: 50% -41px;
        cursor: pointer;
    }

    #minimenu li a i {
        background-position: 50% 0;
        background-repeat: no-repeat;
    }

    sup {
        font-family: Arial;
        font-size: 14px;
        font-weight: bold;
        color: #b81900;
        margin: 0 0 0 22px;
        background-color: #ffffff;
    }

    .tooltip {
        position: absolute;
        width: 250px;
        background-position: left center;
        color: #333333;
        padding: 5px 5px 5px 8px;
        font-size: 12px;
        font-family: Verdana, Geneva, sans-serif;
        background-color: #FFFFFF;
        border-style: solid;
        border-width: 3;
        border-color: #666666;
    }

    .tooltip-image {
        float: left;
        margin-right: 5px;
        margin-bottom: 5px;
        margin-top: 3px;
    }

    .tooltip span {
        font-weight: 700;
        color: #0066FF;
    }

</style>
<script type="text/javascript">
    $(document).ready(function () {

        $('[rel=tooltip]').bind('mouseover',function () {

            var theMessage = $(this).attr('content');
            $('<div class="tooltip">' + theMessage + '</div>').appendTo('body').fadeIn('fast');

            $(this).bind('mousemove', function (e) {
                $('div.tooltip').css({
                    'top':e.pageY - ($('div.tooltip').height()) - 10,
                    'left':e.pageX + 25
                });
            });
        }).bind('mouseout', function () {
                $('div.tooltip').fadeOut('fast', function () {
                    $(this).remove();
                });
            });
    });
</script>
<?php if (($this->session->userdata['admin_type'] == 2) || ($this->session->userdata['admin_type'] == 4)) { ?>
<div id="soc">
    <div id="minimenu">
        <ul>
            <li id="sms"><a href="/messages" rel="tooltip"
                            content="Хабар жўнатиш ва қабул қилиш. Сон нечта хабар борлигини билдиради."><i><sup><?php echo $message; ?></sup></i></a>
            </li>
            <li id="mtn"><a href="/illnessmt" rel="tooltip" content="Муддати тугаган ногиронлар сони">
                <i><sup><?php echo $mtn; ?></sup></i></a></li>
            <li id="tkn"><a href="/approve" rel="tooltip" content="Тасдиқланиши керак бўлган ногиронлар сони">
                <i><sup><?php echo $approve; ?></sup></i></a></li>
            <li id="ven"><a href=""> <i></i></a></li>
            <li id="kkn"><a href=""> <i></i></a></li>
        </ul>
    </div>
</div>
<?php } ?>

<div id="divname">
    <h1 id="h1logo">
        ТИББИЙ-ИЖТИМОИЙ ЭКСПЕРТИЗА ХИЗМАТИ&nbsp;&nbsp;
        <?php
        echo "<font size=2px color=green>$result->fio" . "(" . $result->login . ")</font>";

        ?>
    </h1>
</div>
<div id="logo">
    <?php if ($id == 95) : ?>
    <div id="pics">
        <img src="../../images/pen.png"/>
        <br>
        <a id="piclink" href="/main/spr_ser">Справка</a>
    </div>
    <div id="pics">
        <img src="../../images/pen.png"/>
        <br>
        <a id="piclink" href="/main/spr_pser">Паспорт</a>
    </div>
    <?php endif; ?>
    <div id="pics">
        <img src="/temp/icons/app_322.png"/>
        <br>
        <a id="piclink" href="/main/settings">Созлаш</a>
    </div>
    <div id="pics">
        <img src="/temp/icons/help2.png"/>
        <br>
        <a id="piclink" href="/main/help">Ёрдам</a>
    </div>
    <div id="pics">
        <img src="/temp/icons/refresh2.png"/>
        <br>
        <a id="piclink" href="/login/logout">Чиқиш</a>
    </div>
</div>

<div class="markaz">
    <ul id="nav" class="dropdown dropdown-horizontal">
        <li><a href="main">Бош саҳифа</a></li>
        <?php

        foreach ($menus as $menu) {
            ?>
            <li><a href="/<?php echo $menu->link; ?>" class="dir"><?php echo $menu->name_uz; ?></a>
                <ul><?php
                    foreach ($menu->childs as $child) {
                        ?>

                <li><a href="/<?php echo $child->link; ?>" class="dir"><?php echo $child->name_uz; ?></a>
                        <!--<li><a href="./">Киритиш</a></li> -->
                        <?php } ?>
                </ul>
            </li>
            <?php }   ?>
    </ul>
    <div style="float:right; font-size:11px; color:#ffffff; margin-right: 15px; margin-top: 5px;">
        <?php
        $idp = $this->session->userdata("iddep");
        $k = $this->db->query("SELECT name_uz FROM spr_depart WHERE id=$idp");
        foreach ($k->result() as $result):
            echo $result->name_uz;
        endforeach;
        ?>

    </div>
</div>

