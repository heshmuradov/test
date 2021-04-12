<?php

header("Content-type: application/vnd.ms-excel");

?>

<table width="1225" border="1">
    <tr>
        <td>&nbsp;</td>
        <td colspan="1"><?php echo date('Y.m.d'); ?></td>
        <td colspan="8">Кун Холатига Умумий рўйхати </td>
    </tr>
    <tr>
        <td width="32"></td>
        <td width="200">Департамент номи</td>
        <td colspan="4">Руйхатда турувчилар</td>
        <td colspan="4">Барча ногиронлар</td>
    </tr>
    <tr>
        <td width="32">№</td>
        <td width="200"></td>
        <td width="100">Руйхатда турувчилар</td>
        <td width="100">Вафот этган </td>
        <td width="100">Тўлиқ реабилитация</td>
        <td width="100">Муддати тугаганлар</td>
        <td width="100">Руйхатда турувчилар</td>
        <td width="100">Вафот этган </td>
        <td width="100">Тўлиқ реабилитация</td>
        <td width="100">Муддати тугаганлар</td>
    </tr>
    <?php
    $i = 1;
    foreach ($excel_export as $re) :
    ?>
    <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $re->name_uz; ?></td>
        <td><?php echo $re->rt; ?></td>
        <td><?php echo $re->ve; ?></td>
        <td><?php echo $re->tr; ?></td>
        <td><?php echo $re->mt; ?></td>
        <td><?php echo $re->urt; ?></td>
        <td><?php echo $re->uve; ?></td>
        <td><?php echo $re->utr; ?></td>
        <td><?php echo $re->umt; ?></td>
    </tr>
    <?php $i++; endforeach; ?>
</table>
</body>
</html>