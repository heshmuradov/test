<?php

header("Content-type: application/vnd.ms-excel");
  // print_r($excel_export);exit;
?>

<table align="center" cellpadding="0" cellspacing="0" border="1">
    <col width="64" />
    <col width="186" />
    <col width="67" />
    <col width="64" span="11" />
    <tr>
        <td width="64"></td>
        <td width="186"></td>
        <td width="67"></td>
        <td width="64"></td>
        <td width="64"></td>
        <td width="64"></td>
        <td width="64"></td>
        <td width="64"></td>
        <td width="64"></td>
        <td width="64"></td>
        <td width="64"></td>
        <td width="64"></td>
        <td colspan="2" width="128"></td>
    </tr>
    <tr>
        <td width="1021" colspan="14" align="center" valign="middle"> Ҳудудий Пенсия жамғармаси    бўлимлари ва ТМЭКлар ҳисобида турувчи жами 16 ёшдан катта ногиронлар    сони<br />
            ҳақида <?php echo date('Y.m.d'); ?>  кун ҳолатига таққослама </td>
    </tr>
    <tr>
        <td width="1021" colspan="14" align="center" valign="middle">М А Ъ Л У М О Т*</td>
    </tr>
    <tr>
        <td width="64" rowspan="3" align="center" valign="middle">№</td>
        <td width="186" rowspan="3" align="center" valign="middle">Туман (шаҳар) бўлимлари номи</td>
        <td width="131" colspan="2" rowspan="2" align="center" valign="middle">Жами ногиронлар сони</td>
        <td width="64" rowspan="3" align="center" valign="middle">Фарқи</td>
        <td colspan="9" align="center" valign="middle">Шундан:</td>
    </tr>
    <tr>
        <td colspan="2" align="center" valign="middle">1-гуруҳ</td>
        <td width="64" rowspan="2" align="center" valign="middle">Фарқи</td>
        <td colspan="2" align="center" valign="middle">2-гуруҳ</td>
        <td width="64" rowspan="2" align="center" valign="middle">Фарқи</td>
        <td colspan="2" align="center" valign="middle">3-гуруҳ</td>
        <td width="64" rowspan="2" align="center" valign="middle">Фарқи</td>
    </tr>
    <tr>
        <td width="67" align="center" valign="middle">Пенсия жамғар-масида</td>
        <td width="64" align="center" valign="middle">ТМЭКда</td>
        <td width="64" align="center" valign="middle">Пенсия жамғар-масида</td>
        <td width="64" align="center" valign="middle">ТМЭКда</td>
        <td width="64" align="center" valign="middle">Пенсия жамғар-масида</td>
        <td width="64" align="center" valign="middle">ТМЭКда</td>
        <td width="64" align="center" valign="middle">Пенсия жамғар-масида</td>
        <td width="64" align="center" valign="middle">ТМЭКда</td>
    </tr>
<?php
    $i = 1;
    foreach ($umum_soni as $re) :

        ?>
    <tr>
        <td align="center" valign="middle"><?php echo $i; ?></td>
        <td align="center" valign="middle"><?php echo $re->name_uz; ?></td>
        <td align="center" valign="middle"><?php echo $re->pension; ?></td>
        <td align="center" valign="middle"><?php echo $re->vtek; if ($re->vtek==0){$vtek=1;}else{$vtek=$re->vtek;}?></td>
        <td align="center" valign="middle"><?php echo ($re->pension/$re->vtek)*100; ?></td>
        <td align="center" valign="middle"><?php echo $re->pension1; ?></td>
        <td align="center" valign="middle"><?php echo $re->vtek1;  if ($re->vtek1==0){$vtek1=1;}else{$vtek1=$re->vtek1;} ?></td>
        <td align="center" valign="middle"><?php echo ($re->pension1/$vtek1)*100; ?></td>
        <td align="center" valign="middle"><?php echo $re->pension2; ?></td>
        <td align="center" valign="middle"><?php echo $re->vtek2; if ($re->vtek2==0){$vtek2=1;}else{$vtek2=$re->vtek2;} ?></td>
        <td align="center" valign="middle"><?php echo ($re->pension2/$vtek2)*100; ?></td>
        <td align="center" valign="middle"><?php echo $re->pension3; ?></td>
        <td align="center" valign="middle"><?php echo $re->vtek3; if ($re->vtek3==0){$vtek3=1;}else{$vtek3=$re->vtek3;} ?></td>
        <td align="center" valign="middle"><?php echo ($re->pension3/$vtek3)*100; ?></td>
    </tr>
        <?php $i++; endforeach; ?>
</table>
</body>
</html>