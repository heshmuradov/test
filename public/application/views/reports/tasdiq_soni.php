<?php

header("Content-type: application/vnd.ms-excel");
  // print_r($excel_export);exit;
?>

<table width="1225" border="1">
    <tr>
        <td width="32">&nbsp;</td>
        <td colspan="2"><?php echo date('Y.m.d'); ?></td>
        <td colspan="5">Маълумотлар базасида курсатгич ва танланган кун холатига тасдиқланганлар сони </td>
    </tr>

    <tr>
        <td >&nbsp;</td>
        <td width="32">№</td>
        <td width="200">ТМЕК номи</td>
        <td width="85">Ногиронлар Умумий сони </td>
        <td width="85">Рўйхатдагилар сони</td>
        <td width="93">Шу давргача Пенсия Жамғармаси текширган маълумотномалар сони</td>
        <td width="100">Танланган сана тасдиқларганлар сони</td>
        <td width="100">Тасдиқларганлар сони юзасидан паспорт хатолик</td>

    </tr>
    <?php
    $i = 1;
    foreach ($excel_export as $re) :

    ?>
    <tr>
        <td>&nbsp;</td>
        <td><?php echo $i; ?></td>
        <td><?php echo $re->name_uz; ?></td>
        <td><?php echo $re->all; ?></td>
        <td><?php echo $re->rt; ?></td>
        <td><?php echo $re->pcheck; ?></td>
        <td><?php echo $re->day_approve; ?></td>
        <td><?php echo $re->pxato; ?></td>

    </tr>
    <?php $i++; endforeach; ?>
</table>
</body>
</html>