<?php
//print_r($excel_export);exit;
header("Content-type: application/vnd.ms-excel");
header('Content-Disposition: attachment;filename="'.'export_search.xls"');
header('Cache-Control: max-age=0');

?>
<table border="1" cellspacing="0" cellpadding="0">
    <tr height="24">
        <td colspan="23" height="24" width="1693" align="center"><?php echo $depart_name[0]->name_uz; ?>  бўйича 16
            ёшдан катта ногиронлар тўғрисида 
        </td>
    </tr>
    <tr height="21">
        <td colspan="24" height="21" align="center"><strong>МАЪЛУМОТ</strong></td>
    </tr>
    <tr height="42">
        <td rowspan="2" height="158" align="center">№</td>
        <td rowspan="2" height="158" align="center">Уник.Рақам</td>
        <td rowspan="2" width="150" align="center" valign="center">ФИШ</td>
        <td rowspan="2" width="90" align="center">Туғилган йили, санаси</td>
        <td rowspan="2" width="30" align="center">Жинси </td>
        <td colspan="3" width="150" align="center">Манзили</td>
        <td rowspan="2" width="60" align="center">Паспорт серияси ва рақами</td>
        <td rowspan="2" width="60" align="center">ПИНФЛ</td>
        <td rowspan="2" width="100" align="center">Ногиронлик белгилаган ТМЭК номи</td>
        <td rowspan="2" width="70" align="center">Бирламчи ёки қайта ногиронлик</td>
        <td rowspan="2" width="70" align="center">Маълумоти</td>
        <td rowspan="2" width="60" align="center">Касби</td>
        <td rowspan="2" width="90" align="center">ТМЭКдан ўтиш вақтида:</td>
        <td colspan="2" width="60" align="center">ТМЭК асосий ташхиси, (МКБ-9, МКБ-10)</td>
        <td colspan="6" width="348" align="center">Ногиронлик </td>
    </tr>
    <tr height="80">
        <td height="57" width="107" align="center">Туман</td>
        <td height="57" width="107" align="center">шахар/қишлоқ</td>
        <td width="150" align="center">Адрес</td>
        <td width="30" align="center">МКБ-9</td>
        <td width="30" align="center">МКБ-10</td>
        <td width="30" align="center">Гуруҳи</td>
        <td width="100" align="center">Сабаби</td>
        <td width="90" align="center">Муддати қачонгача</td>
        <td width="90" align="center">Сўнги маротаба ногиронлик белгиланган сана</td>
        <td width="100" align="center">Рўйхатда туради</td>
        <td width="100" align="center">Фоиз</td>
        <td width="100" align="center">Пенсия маълумотномаси</td>
        <td width="100" align="center">Втэк маълумотномаси</td>
        <td width="100" align="center">Муомала қилиш</td>
        <td width="100" align="center">Ўқиш, билимларни ўзлаштириш ва такрорлаш</td>
        <td width="100" align="center">Меҳнат фаолияти билан шуғулланиш</td>
    </tr>
    <?php
    $i = 1;
    foreach ($excel_export as $re) :
        if ($re->end_date == "2099-01-01") {
            $kk = "муддатсиз";
        } elseif ($re->end_date == "1700-01-01") {
            $kk = "Вафот зтган";
        }
        else {
            $kk = date('d.m.Y',strtotime($re->end_date));
        }

        if ($re->pcheck == 1) {
            $pcheck = "Текширилган";
        } elseif ($re->pcheck == 2) {
            $pcheck = "Тўловга чиқарилган";
        }
        else {
            $pcheck = "Текширилмаган";
        }

        if($re->kriteria_4 == 0) {
            $kriteria_4 = "Белгиланмаган";
        } elseif ($re->kriteria_4 == 18) {
            $kriteria_4 = "ахборот олиш ва бериш суръати ва ҳажмини пасайтириб муомала қилиш лаёқати, зарурият бўлганда ёрдам беришнинг ёрдамчи техник воситаларидан фойдаланиш";
        } elseif ($re->kriteria_4 == 19) {
            $kriteria_4 = "зарурият бўлганда ёрдамчи техник воситалардан фойдаланиб бошқа шахсларнинг қисман ёрдами билан муомала қилиш лаёқати";
        } else {
            $kriteria_4 = "муомала қила олмаслик ва бошқа шахсларнинг доимий ёрдамига муҳтожлик";
        }

        if($re->kriteria_6 == 0) {
            $kriteria_6 = "Белгиланмаган";
        } elseif ($re->kriteria_6 == 24) {
            $kriteria_6 = "зарурат бўлганда ёрдамчи техник воситалар ва технологияларни қўллаб ўқитишнинг махсус усулларидан, ўқитишнинг махсус режимидан фойдаланиб умумий типдаги таълим муассасаларида давлат таълим стандартлари доирасида ўқиш, шунингдек муайян даражадаги маълумотга эга бўлиш лаёқати";
        } elseif ($re->kriteria_6 == 25) {
            $kriteria_6 = "зарурат бўлганда ёрдамчи техник воситалар ва технологиялардан фойдаланиб фақат ривожланишида нуқсонлари бўлган таълим олувчилар, тарбияланувчилар учун ихтисослаштирилган таълим муассасаларида ёки уйда махсус дастурлар бўйича ўқиш лаёқати";
        } else {
            $kriteria_6 = "ўқий олмаслик";
        }

        if($re->kriteria_7 == 0) {
            $kriteria_7 = "Белгиланмаган";
        } elseif ($re->kriteria_7 == 24) {
            $kriteria_7 = "малака, оғирлик, кескинлик пасайган ва (ёки) иш ҳажми камайганда меҳнатнинг одатдаги шароитларида меҳнат фаолиятини бажариш лаёқати, меҳнатнинг одатдаги шароитларида паст малакали меҳнат фаолиятини бажариш имкони сақланиб қолган ҳолда асосий касб бўйича ишни давом эттира олмаслик";
        } elseif ($re->kriteria_7 == 25) {
            $kriteria_7 = "ёрдамчи техник воситалардан фойдаланиб ва (ёки) бошқа шахслар ёрдамида махсус ташкил этилган меҳнат шароитларида меҳнат фаолиятини бажариш лаёқати";
        } else {
            $kriteria_7 = "меҳнат фаолияти билан шуғуллана олмаслик ёки меҳнат фаолияти билан шуғулланиб бўлмаслиги (ёки меҳнат фаолияти билан шуғулланиш тақиқланганлиги)";
        }
        ?>
        <tr height="31">
            <td height="31" width="36"><?php echo $i; ?></td>
            <td width="126"><?php echo $re->id; ?></td>
            <td width="126"><?php echo $re->fio; ?></td>
            <td width="78"><?php echo date('d.m.Y',strtotime($re->date_birth)); ?></td>
            <td width="60"><?php echo $re->gender; ?></td>
            <td width="107"><?php echo $re->id_born; ?></td>
            <td width="107"><?php echo $re->region; ?></td>
            <td width="135"><?php echo $re->address, ';'; ?></td>
            <td width="135"><?php echo $re->pasport; ?></td>
            <td width="135"> &nbsp <?php echo $re->infl; ?></td>
            <td width="108"><?php echo $re->depart; ?></td>
            <td width="104"><?php echo $re->checktype; ?></td>
            <td width="100"><?php echo $re->malumot; ?></td>
            <td width="59"><?php echo $re->kasbi; ?></td>
            <td width="140"><?php echo $re->working; ?></td>
            <td width="101"><?php echo $re->mkb9; ?></td>
            <td width="98"><?php echo $re->mkb10; ?></td>
            <td width="72"><?php echo $re->guruh; ?></td>
            <td width="72"><?php echo $re->sabab_uz; ?></td>
            <td width="103"><?php echo $kk; ?></td>
            <td width="101"><?php echo date('d.m.Y',strtotime($re->beg_date)); ?></td>
            <td width="101"><?php echo $re->rt; ?></td>
            <td width="101"><?php echo $re->foiz; ?></td>
            <td width="101"><?php echo $re->penseriya; ?></td>
            <td width="101"><?php echo $pcheck; ?></td>
            <td width="101"><?php echo $kriteria_4; ?></td>
            <td width="101"><?php echo $kriteria_6; ?></td>
            <td width="101"><?php echo $kriteria_7; ?></td>
        </tr>
        <?php $i++; endforeach; ?>
</table>
</body>
</html>
