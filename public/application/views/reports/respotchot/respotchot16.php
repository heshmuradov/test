<?php
//print_r($excel_export);exit;
header("Content-type: application/vnd.ms-excel");

?>
<table width="1200" border="1">
    <tr>
        <td colspan="14" align="center" valign="middle">Худудий ТМЭКлар томонидан <?php echo $sana; ?> белгиланган ногиронлар сони</td>
    </tr>
    <tr>
        <td width="20" rowspan="3" align="center" valign="middle">№</td>
        <td width="138" rowspan="3" align="left" valign="middle">Худудлар</td>
        <td colspan="2" align="center" valign="middle">&nbsp;</td>
        <td width="79" rowspan="3" align="center" valign="middle">Фарқи</td>
        <td colspan="9" align="center" valign="middle">шундан</td>
    </tr>
    <tr>
        <td colspan="2" align="center" valign="middle">Жами ногиронлар сони </td>
        <td colspan="2" align="center" valign="middle">1-гуруҳ</td>
        <td width="79" rowspan="2" align="center" valign="middle">Фарқи</td>
        <td colspan="2" align="center" valign="middle">2-гуруҳ</td>
        <td width="79" rowspan="2" align="center" valign="middle">Фарқи</td>
        <td colspan="2" align="center" valign="middle">3-гуруҳ</td>
        <td width="85" rowspan="2" align="center" valign="middle">Фарқи</td>
    </tr>
    <tr>
        <td width="79" align="center" valign="middle">ПЖ</td>
        <td width="79" align="center" valign="middle">ТМЭК</td>
        <td align="center" valign="middle">ПЖ</td>
        <td align="center" valign="middle">ТМЭК</td>
        <td align="center" valign="middle">ПЖ</td>
        <td align="center" valign="middle">ТМЭК</td>
        <td align="center" valign="middle">ПЖ</td>
        <td align="center" valign="middle">ТМЭК</td>
    </tr>

    <?php
    $i = 1;
    foreach ($respotchot16 as $re) :
         ?>

        <tr>
            <td align="center" valign="middle"><?php echo $re->otchot_order; ?></td>
            <td align="left" valign="middle"><?php echo $re->vil_name; ?></td>
            <td align="center" valign="middle"><?php echo $re->pf0; ?></td>
            <td align="center" valign="middle"><?php echo $re->tmek0; ?></td>
            <td align="center" valign="middle"><?php echo ($re->pf0)-($re->tmek0); ?></td>
            <td align="center" valign="middle"><?php echo $re->pf1; ?></td>
            <td align="center" valign="middle"><?php echo $re->tmek1; ?></td>
            <td align="center" valign="middle"><?php echo ($re->pf1)-($re->tmek1); ?></td>
            <td align="center" valign="middle"><?php echo $re->pf2; ?></td>
            <td align="center" valign="middle"><?php echo $re->tmek2; ?></td>
            <td align="center" valign="middle"><?php echo ($re->pf0)-($re->tmek2); ?></td>
            <td align="center" valign="middle"><?php echo $re->pf3; ?></td>
            <td align="center" valign="middle"><?php echo $re->tmek3; ?></td>
            <td align="center" valign="middle"><?php echo ($re->pf3)-($re->tmek3); ?></td>
        </tr>

        <?php  endforeach; ?>
</table>

</body>
</html>