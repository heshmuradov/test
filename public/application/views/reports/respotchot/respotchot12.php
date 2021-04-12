<?php
//print_r($excel_export);exit;
header("Content-type: application/vnd.ms-excel");

?>
<table width="1500" border="1">
    <tr>
        <td colspan="18" align="center" valign="middle"><p>Ҳудудий ТМЭКлар ҳисобида турувчи жами 16 ёшдан катта ногиронлар сони тўғрисида</p>
            <p>МАЪЛУМОТ ( <?php echo $sana; ?> кун холатига) </p></td>
    </tr>
    <tr>
        <td width="20" rowspan="4" align="center" valign="middle">№</td>
        <td width="150" rowspan="4" align="center" valign="middle">Худудлар</td>
        <td colspan="4" rowspan="2" align="center" valign="middle">Жами ТМЭКлар ҳисобида турувчи ногиронлар сони </td>
        <td colspan="12" align="center" valign="middle">шундан:</td>
    </tr>
    <tr>
        <td colspan="4" align="center" valign="middle">Махсус Жамгармалардан пенсия олувчилар сони (ИИБ,ММХ,Мудофа вазирлиги, ФВВ,Божхона қўмитаси)</td>
        <td colspan="4" align="center" valign="middle">БТПЖ бўлимларида хисобда турувчи ва ногиронлик песиясини олувчи жами 16 ёшдан катта ногиронлар сони</td>
        <td colspan="4" align="center" valign="middle">Ногиронлик статусига эга бўлган, лекин айрим сабабларга </td>
    </tr>
    <tr>
        <td width="83" align="center" valign="middle"><p>Жами</p></td>
        <td width="83" align="center" valign="middle">1-гуруҳ</td>
        <td width="83" align="center" valign="middle">2-гуруҳ</td>
        <td width="83" align="center" valign="middle">3-гуруҳ</td>
        <td align="center" valign="middle"><p>Жами</p></td>
        <td align="center" valign="middle">1-гуруҳ</td>
        <td align="center" valign="middle">2-гуруҳ</td>
        <td align="center" valign="middle">3-гуруҳ</td>
        <td align="center" valign="middle"><p>Жами</p></td>
        <td align="center" valign="middle">1-гуруҳ</td>
        <td align="center" valign="middle">2-гуруҳ</td>
        <td align="center" valign="middle">3-гуруҳ</td>
        <td align="center" valign="middle"><p>Жами</p></td>
        <td align="center" valign="middle">1-гуруҳ</td>
        <td align="center" valign="middle">2-гуруҳ</td>
        <td align="center" valign="middle">3-гуруҳ</td>
    </tr>
    <tr>
        <td width="84" align="center" valign="middle">1</td>
        <td width="84" align="center" valign="middle">2</td>
        <td width="84" align="center" valign="middle">3</td>
        <td width="84" align="center" valign="middle">4</td>
        <td width="84" align="center" valign="middle">5</td>
        <td width="84" align="center" valign="middle">6</td>
        <td width="84" align="center" valign="middle">7</td>
        <td width="84" align="center" valign="middle">8</td>
        <td width="84" align="center" valign="middle">9</td>
        <td width="84" align="center" valign="middle">10</td>
        <td width="84" align="center" valign="middle">11</td>
        <td width="84" align="center" valign="middle">12</td>
        <td width="84" align="center" valign="middle">13</td>
        <td width="84" align="center" valign="middle">14</td>
        <td width="84" align="center" valign="middle">15</td>
        <td width="84" align="center" valign="middle">16</td>
    </tr>

    <?php
    $i = 1;
    foreach ($respotchot12 as $re) :
         ?>

        <tr>
            <td align="center" valign="middle"><?php echo $re->otchot_order; ?></td>
            <td align="center" valign="middle"><?php echo $re->vil_name; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->tmek; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->tmek1; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->tmek2; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->tmek3; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->voyt; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->voyt1; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->voyt2; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->voyt3; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->pf; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->pf1; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->pf2; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->pf3; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->nopf; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->nopf1; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->nopf2; ?></td>
            <td width="84" align="center" valign="middle"><?php echo $re->nopf3; ?></td>
        </tr>

        <?php  endforeach; ?>
</table>

</body>
</html>