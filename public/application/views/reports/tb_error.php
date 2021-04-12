<?php

header("Content-type: application/vnd.ms-excel");

?>

<table width="1225" border="1">
    <tr>
        <td>&nbsp;</td>
        <td colspan="1"><?php echo date('Y.m.d'); ?></td>
        <td colspan="3">Кун Холатига Умумий рўйхати </td>
    </tr>
    <tr>
        <td width="32"></td>
        <td width="200">Вилоят раками</td>
        <td width="200">Туман номи</td>
        <td width="200">Тасдикланганлар сони</td>
        <td width="200">Жамгармадан утганлар сони</td>
    </tr>
     <?php
    $i = 1;
    foreach ($excel_export as $re) :
    ?>
    <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $re->par_id; ?></td>
        <td><?php echo $re->name_uz; ?></td>
        <td><?php echo $re->rt; ?></td>
        <td><?php echo $re->pcheck; ?></td>

    </tr>
    <?php $i++; endforeach; ?>
</table>
</body>
</html>