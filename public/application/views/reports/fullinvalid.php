<?php

header("Content-type: application/vnd.ms-excel");

?>

<table width="1225" border="1">
    <tr>
        <td>&nbsp;</td>
        <td colspan="3"><?php echo date('Y.m.d'); ?></td>
        <td colspan="21">Кун Холатига Барча ногиронлар рўйхати </td>
    </tr>
    <tr>
        <td width="32">№</td>
        <td width="50">Ун.Ракам</td>
        <td width="93">Исми</td>
        <td width="93">Фамилияси</td>
        <td width="100">Шарифи</td>
        <td width="88">Тугил.куни</td>
        <td width="100">Пас.Серия</td>
        <td width="250">Департамент номи</td>
        <td width="50">Жинси</td>
        <td width="100">Район</td>
        <td width="60">Турар жойи</td>
        <td width="297">Адрес</td>
        <td width="50">Фуқаролиги</td>
        <td width="50">Кўрик тури</td>
        <td width="50">МКБ10</td>
        <td width="50">Гурух</td>
        <td width="100">Сабаби</td>
        <td width="60">Тугаш санаси</td>
        <td width="60">Курик сана</td>
        <td width="150">Рўйхат</td>
        <td width="120">Серия справка</td>
        <td width="40">Фоиз</td>
        <td width="50">Реабилитация холати</td>
        <td width="88">Пенсия текшириш сана</td>
        <td width="60">Кўчиб кетиш</td>
    </tr>
    <?php
    $i = 1;
    foreach ($vnogiron as $re) :
        if (empty($re->pcheck_date)){
            $kk="---";
        }else {
            $kk=date('d.m.Y',strtotime($re->pcheck_date));
        }
    ?>
    <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $re->id; ?></td>
        <td><?php echo $re->familiya; ?></td>
        <td><?php echo $re->name; ?></td>
        <td><?php echo $re->middle; ?></td>
        <td><?php echo date('d.m.Y',strtotime($re->date_birth)); ?></td>
        <td><?php echo $re->pasport; ?></td>
        <td><?php echo $re->depart; ?></td>
        <td><?php echo $re->gender; ?></td>
        <td><?php echo $re->id_born; ?></td>
        <td><?php echo $re->region; ?></td>
        <td><?php echo $re->address; ?></td>
        <td><?php echo $re->notcitizen; ?></td>
        <td><?php echo $re->checktype; ?></td>
        <td><?php echo $re->mkb10; ?></td>
        <td><?php echo $re->guruh; ?></td>
        <td><?php echo $re->sabab_uz; ?></td>
        <td><?php echo $re->end_date; ?></td>
        <td><?php echo $re->beg_date; ?></td>
        <td><?php echo $re->rt; ?></td>
        <td><?php echo $re->seriya; ?></td>
        <td><?php echo $re->foiz; ?></td>
        <td><?php echo $re->old_holat_reabilit; ?></td>
        <td><?php echo $kk; ?></td>
        <td><?php echo $re->move; ?></td>

    </tr>
    <?php $i++; endforeach; ?>
</table>
</body>
</html>