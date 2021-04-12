<?php
//print_r($excel_export);exit;
header("Content-type: application/vnd.ms-excel");

?>
<table border="1" cellspacing="0" cellpadding="0">
  <tr height="24">
    <td colspan="23" height="24" width="1693" align="center"><?php echo $depart_name[0]->name_uz; ?>  бўйича 16 ёшдан катта ногиронлар  тўғрисида </td>
  </tr>
  <tr height="21">
    <td colspan="23" height="21" align="center"><strong>МАЪЛУМОТ</strong></td>
  </tr>
  <tr height="42">
    <td rowspan="2" height="158"  align="center">№</td>
    <td rowspan="2" height="158"  align="center">Уник.Рақам</td>
    <td rowspan="2" width="150" align="center" valign="center">ФИШ</td>
    <td rowspan="2" width="90" align="center">Туғилган йили, санаси</td>
    <td rowspan="2" width="30" align="center">Жинси </td>
    <td colspan="3" width="150" align="center">Манзили</td>
    <td rowspan="2" width="60" align="center">Паспорт серияси ва рақами</td>
    <td rowspan="2" width="100" align="center">Ногиронлик белгилаган ТМЭК номи</td>
    <td rowspan="2" width="70" align="center">Бирламчи ёки қайта ногиронлик</td>
    <td rowspan="2" width="70" align="center">Маълумоти</td>
    <td rowspan="2" width="60" align="center">Касби</td>
    <td rowspan="2" width="90" align="center">ТМЭКдан ўтиш вақтида:</td>
    <td colspan="2" width="60" align="center">ТМЭК асосий ташхиси, (МКБ-9, МКБ-10)</td>
    <td colspan="7" width="348" align="center">Ногиронлик </td>
  </tr>
  <tr height="80">
    <td height="57" width="107" align="center">Туман</td>
    <td height="57" width="107" align="center">шахар/қишлоқ</td>
    <td width="150" align="center" >Адрес</td>
    <td width="30" align="center">МКБ-9</td>
    <td width="30" align="center">МКБ-10</td>
    <td width="30" align="center">Гуруҳи</td>
    <td width="100"align="center">Сабаби</td>
    <td width="90" align="center">Муддати қачонгача</td>
    <td width="90" align="center">Сўнги маротаба ногиронлик белгиланган сана</td>
    <td width="100" align="center">Рўйхатда туради</td>
    <td width="50" align="center">Тасдиқланганлиги</td>
    <td width="100" align="center">Пенсия маълумотномаси</td>

  </tr>
  <?php
     $i=1;
    foreach ($excel_export as $re) :
    if ($re->end_date=="2099-01-01"){
        $kk="муддатсиз";
    }
    elseif ($re->end_date=="1700-01-01"){
        $kk="Вафот зтган";
    }
        else
        {
            $kk = date('d.m.Y',strtotime($re->end_date));

        }
    if ($re->approve==1){
        $approve='Тасдиқланган';}
        else {$approve='Тасдиқланмаган';}
            ?>
  <tr height="31">
    <td height="31" width="36"><?php echo $i; ?></td>
    <td width="126"><?php echo $re->id; ?></td>
    <td width="126"><?php echo $re->fio; ?></td>
    <td width="78"><?php echo date('d.m.Y',strtotime($re->date_birth)); ?></td>
    <td width="60"><?php echo $re->gender; ?></td>
    <td width="107"><?php echo $re->id_born; ?></td>
    <td width="107"><?php echo $re->region; ?></td>
    <td width="135"><?php echo $re->address,';'; ?></td>
    <td width="135"><?php echo $re->pasport; ?></td>
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
    <td width="101"><?php echo $approve; ?></td>
    <td width="101"><?php echo $re->penseriya; ?></td>
  </tr>
<?php $i++; endforeach; ?>
 </table>
</body>
</html>