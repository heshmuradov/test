<?php
//print_r($excel_export);exit;
header("Content-type: application/vnd.ms-excel");

?>
<table width="200" height="180" border="1">
    <tr>
        <td colspan="14" align="center" valign="middle"><p>Республика туман (шаҳар), туманлараро, ихтисослаштиришган ТМЭКлари ҳисобида турган ногиронлар тўғрисида тезкор </p>
            <p>МАЪЛУМОТ</p></td>
    </tr>
    <tr >
        <td width="20" rowspan="2">№</td>
        <td width="150" rowspan="2">Ҳудудлар номи</td>
        <td colspan="5" align="center" valign="middle"><?php echo $sana0; ?> - холатига жами ногиронлар</td>
        <td colspan="5" align="center" valign="middle"><?php echo $sana; ?> - холатига жами ногиронлар</td>
        <td width="84" rowspan="2" align="center" valign="middle">Умумий ногиронлар фарқи</td>
        <td width="84" rowspan="2" align="center" valign="middle"><p>БТПЖ бўлимлари фарқи</p></td>
    </tr>
    <tr>
        <td width="84" height="50" align="center" valign="middle">Умумий ногиронлар сони</td>
        <td width="84" align="center" valign="middle">БТПЖ бўлимларида пенсия олувчи 16 ёшдан катта ногиронлар сони</td>
        <td width="84" align="center" valign="middle">ахоли сонидаги умумий ногиронлар улуши %</td>
        <td width="84" align="center" valign="middle">ахоли сонидаги ПЖдан пенсия олувчи ногиронлар улуши</td>
        <td width="84" align="center" valign="middle">БТПЖдан пенсия ва нафақа олувчилар умумий сонидаги ногиронлар умумий улуши</td>
        <td width="84" height="23" align="center" valign="middle">Умумий ногиронлар сони</td>
        <td width="94" align="center" valign="middle">БТПЖ бўлимларида пенсия олувчи 16 ёшдан катта ногиронлар сони</td>
        <td width="84" align="center" valign="middle">ахоли сонидаги умумий ногиронлар улуши %</td>
        <td width="84" align="center" valign="middle">ахоли сонидаги ПЖдан пенсия олувчи ногиронлар улуши</td>
        <td width="84" align="center" valign="middle">БТПЖдан пенсия ва нафақа олувчилар умумий сонидаги ногиронлар умумий улуши</td>
    </tr>
    <?php
    $i = 1;
    foreach ($respotchot10 as $re) :
         ?>

    <tr>
        <td><?php echo $re->otchot_order; ?></td>
        <td><?php echo $re->vil_name; ?></td>
        <td><?php echo $re->tmek0; ?></td>
        <td><?php echo $re->pf0; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo $re->tmek; ?></td>
        <td><?php echo $re->pf; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><?php echo ($re->tmek)-($re->tmek0) ; ?></td>
        <td><?php echo ($re->pf)-($re->pf0) ; ?></td>
    </tr>
        <?php  endforeach; ?>
</table>

</body>
</html>