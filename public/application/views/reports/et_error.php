<?php

header("Content-type: application/vnd.ms-excel");

?>

<table width="1225" border="1">
    <tr>
        <td>&nbsp;</td>
        <td colspan="3"><?php echo date('Y.m.d'); ?></td>
        <td colspan="8">Кун Холатига Тасдиқланмаганлар рўйхати </td>
    </tr>
    <tr>
        <td width="32">№</td>
        <td width="50">Ун.Ракам</td>
        <td width="200">Департамент номи</td>
        <td width="93">Исми</td>
        <td width="93">Фамилияси</td>
        <td width="100">Шарифи</td>
        <td width="88">Тугил.куни</td>
        <td width="84">Жинси</td>
        <td width="84">Район</td>
        <td width="84">Пас.Серия</td>
        <td width="120">Пас.Ракам</td>
        <td width="297">Адрес</td>
    </tr>
    <?php
    $i = 1;
    foreach ($excel_export as $re) :
    ?>
    <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $re->id; ?></td>
        <td><?php echo $re->depart; ?></td>
        <td><?php echo $re->name; ?></td>
        <td><?php echo $re->familiya; ?></td>
        <td><?php echo $re->middle; ?></td>
        <td><?php echo date('d.m.Y',strtotime($re->date_birth)); ?></td>
        <td><?php echo $re->gender; ?></td>
        <td><?php echo $re->region; ?></td>
        <td><?php echo $re->pass_seriya; ?></td>
        <td><?php echo $re->pass_code; ?></td>
        <td><?php echo $re->address; ?></td>
    </tr>
    <?php $i++; endforeach; ?>
</table>
</body>
</html>