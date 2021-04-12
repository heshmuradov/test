<?php

header("Content-type: application/vnd.ms-excel");
  // print_r($excel_export);exit;
?>

<table width="1225" border="1">
    <tr>
        <td>&nbsp;</td>
        <td colspan="3"><?php echo date('Y.m.d'); ?></td>
        <td colspan="9">Кун Холатига Пенсия жамғармаси томонодан жўнатилган сўровлар </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td colspan="11">Пенсия Жамғармаси томонидан жўнатилган сўровлар</td>
        <td colspan="3">ТМЕК маълумоти </td>
    </tr>
    <tr>
        <td width="32">№</td>
        <td width="50">Паспорт серия</td>
        <td width="200">Паспорт рақами</td>
        <td width="93">Фуқаролиги</td>
        <td width="93">Маълумотнома серияси</td>
        <td width="100">Маълумотнома рақами</td>
        <td width="88">Маълумотнома расмий.сана</td>
        <td width="88">Ногиронлик тугаш.санаси</td>
        <td width="84">Гурухи</td>
        <td width="84">Фоиз</td>
        <td width="84">Опекун</td>
        <td width="84">Сони</td>
        <td width="84">ТМЕК даги Исми</td>
        <td width="84">ТМЕК даги Фамилия</td>
        <td width="120">Текширишдан ўтгани</td>

    </tr>
    <?php
    $i = 1;
    foreach ($excel_export as $re) :
        if ($re->end_date=="2099-01-01"){
            $kk="муддатсиз";
        }else
        {
            $kk = d($re->end_date);

        }
    ?>
    <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $re->pass_seriya; ?></td>
        <td><?php echo $re->pass_code; ?></td>
        <td><?php echo $re->citizen; ?></td>
        <td><?php echo $re->seriya; ?></td>
        <td><?php echo $re->nomer; ?></td>
        <td><?php echo $re->beg_date; ?></td>
        <td><?php echo $kk; ?></td>
        <td><?php echo $re->guruh; ?></td>
        <td><?php echo $re->foiz; ?></td>
        <td><?php echo $re->opekun; ?></td>
        <td><?php echo $re->count; ?></td>
        <td><?php echo $re->name; ?></td>
        <td><?php echo $re->familiya; ?></td>
        <td><?php echo $re->pcheck; ?></td>
    </tr>
    <?php $i++; endforeach; ?>
</table>
</body>
</html>