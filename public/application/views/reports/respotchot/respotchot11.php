<?php
//print_r($excel_export);exit;
header("Content-type: application/vnd.ms-excel");

?>
<table width="1200" height="180" border="1">
    <tr>
        <td colspan="14" align="center" valign="middle"><p> <?php echo $sana0; ?> ва <?php echo $sana; ?> - холатига туман (шаҳар), туманлараро, ихтисослаштиришган ТМЭКлари ҳисобида турган ногиронлар тўғрисида тезкор </p>
            <p>МАЪЛУМОТ</p></td>
    </tr>
    <tr>
        <td width="20" rowspan="3">№</td>
        <td width="150" rowspan="3">Ҳудудлар номи</td>
        <td width="84" rowspan="3" align="center" valign="middle"><?php echo $sana0; ?> холатига ногиронлар сони</td>
        <td width="84" rowspan="3" align="center" valign="middle"><?php echo $sana; ?> холатига ногиронлар сони</td>
        <td width="84" rowspan="3" align="center" valign="middle">Фарқи</td>
        <td colspan="9" align="center" valign="middle">шундан :</td>
    </tr>
    <tr>
        <td height="23" colspan="2" align="center" valign="middle">1-гурух</td>
        <td width="84" rowspan="2" align="center" valign="middle">Фарқи</td>
        <td colspan="2" align="center" valign="middle">2-гурух</td>
        <td width="84" rowspan="2" align="center" valign="middle">Фарқи</td>
        <td colspan="2" align="center" valign="middle">3-гурух</td>
        <td width="84" rowspan="2" align="center" valign="middle">Фарқи</td>
    </tr>
    <tr>
        <td width="84"><?php echo $sana0; ?></td>
        <td width="84"><?php echo $sana; ?></td>
        <td width="84"><?php echo $sana0; ?></td>
        <td width="84"><?php echo $sana; ?></td>
        <td width="84"><?php echo $sana0; ?></td>
        <td width="84"><?php echo $sana; ?></td>
    </tr>

    <?php
    $i = 1;
    foreach ($respotchot11 as $re) :
         ?>

    <tr>
        <td><?php echo $re->otchot_order; ?></td>
        <td><?php echo $re->vil_name; ?></td>
        <td><?php echo $re->tmek0; ?></td>
        <td><?php echo $re->tmek; ?></td>
        <td><?php echo ($re->tmek)-($re->tmek0); ?></td>
        <td><?php echo $re->tmek01; ?></td>
        <td><?php echo $re->tmek1; ?></td>
        <td><?php echo ($re->tmek1)-($re->tmek01); ?></td>
        <td><?php echo $re->tmek02; ?></td>
        <td><?php echo $re->tmek2; ?></td>
        <td><?php echo ($re->tmek2)-($re->tmek02); ?></td>
        <td><?php echo $re->tmek03; ?></td>
        <td><?php echo $re->tmek3; ?></td>
        <td><?php echo ($re->tmek3)-($re->tmek03); ?></td>
    </tr>
        <?php  endforeach; ?>
</table>

</body>
</html>