<?php

header("Content-type: application/vnd.ms-excel");

?>

<table width=100% border="1">
    <tr>
        <td>&nbsp;</td>
        <td colspan="3"><?php echo date('Y.m.d'); ?></td>
        <td colspan="8">Кун ҳолатига киритилган справка сериялари</td>
    </tr>
    <tr>
        <td width="32">№</td>
        <td width="50">Сериялар</td>
    </tr>
    <?php
    $i = 1;
    foreach ($excel_export as $re) :
        ?>
        <tr>
            <td><?php echo $re->id; ?></td>
            <td><?php echo $re->name_uz; ?></td>
        </tr>
        <?php $i++; endforeach; ?>
</table>
</body>
</html>