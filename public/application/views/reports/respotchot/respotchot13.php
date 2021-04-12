<?php
//print_r($re->tmek);exit;
header("Content-type: application/vnd.ms-excel");

?>
<table width="653" border="1">
    <tr>
        <td colspan="4" align="center" valign="middle"><p>Республика тиббий-меҳнат эксперт комиссиялари ҳисобида турувчи</p>
            <p>жами 16 ёшдан катта ногиронлар сони тўғрисида </p>
            <p>МАЪЛУМОТ</p></td>
    </tr>
    <tr>
        <td width="43" align="center" valign="middle">№</td>
        <td width="269" align="center" valign="middle">Кўрсаткичлар</td>
        <td width="156" align="center" valign="middle">Абсолют рақамларда</td>
        <td width="157" align="center" valign="middle">Нисбий рақамларда</td>
    </tr>
<?php
    $i = 1;
    foreach ($respotchot13 as $re) :
        ?>
    <tr>
        <td align="center" valign="middle">1</td>
        <td align="center" valign="middle">Жами ТМЭКлар ҳисобида турувчи ногиронлар сони</td>
        <td align="center" valign="middle"><?php echo $re->tmek; ?></td>
        <td align="center" valign="middle"><?php echo (($re->tmek/$re->tmek)*100).'%'; ?></td>
    </tr>
    <tr>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">шундан:</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
    </tr>
    <tr>
        <td align="center" valign="middle">1.1</td>
        <td align="center" valign="middle">1-гуруҳ</td>
        <td align="center" valign="middle"><?php echo $re->tmek1; ?></td>
        <td align="center" valign="middle"><?php echo (($re->tmek1/$re->tmek)*100).'%'; ?></td>
    </tr>
    <tr>
        <td align="center" valign="middle">1.2</td>
        <td align="center" valign="middle">2-гуруҳ</td>
        <td align="center" valign="middle"><?php echo $re->tmek2; ?></td>
        <td align="center" valign="middle"><?php echo (($re->tmek2/$re->tmek)*100).'%'; ?></td>
    </tr>
    <tr>
        <td align="center" valign="middle">1.3</td>
        <td align="center" valign="middle">3-гуруҳ</td>
        <td align="center" valign="middle"><?php echo $re->tmek3; ?></td>
        <td align="center" valign="middle"><?php echo (($re->tmek3/$re->tmek)*100).'%'; ?></td>
    </tr>
    <tr>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">улардан,</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
    </tr>
    <tr>
        <td align="center" valign="middle">2</td>
        <td align="center" valign="middle">Махсус жамғармалардан пенсия олувчи сони</td>
        <td align="center" valign="middle"><?php echo $re->voyt; ?></td>
        <td align="center" valign="middle"><?php echo (($re->voyt/$re->voyt)*100).'%'; ?></td>
    </tr>
    <tr>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">шундан:</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
    </tr>
    <tr>
        <td align="center" valign="middle">2.1</td>
        <td align="center" valign="middle">1-гуруҳ</td>
        <td align="center" valign="middle"><?php echo $re->voyt1; ?></td>
        <td align="center" valign="middle"><?php echo (($re->voyt1/$re->voyt)*100).'%'; ?></td>
    </tr>
    <tr>
        <td align="center" valign="middle">2.2</td>
        <td align="center" valign="middle">2-гуруҳ</td>
        <td align="center" valign="middle"><?php echo $re->voyt2; ?></td>
        <td align="center" valign="middle"><?php echo (($re->voyt2/$re->voyt)*100).'%'; ?></td>
    </tr>
    <tr>
        <td align="center" valign="middle">2.3</td>
        <td align="center" valign="middle">3-гуруҳ</td>
        <td align="center" valign="middle"><?php echo $re->voyt3; ?></td>
        <td align="center" valign="middle"><?php echo (($re->voyt3/$re->voyt)*100).'%'; ?></td>
    </tr>
    <tr>
        <td align="center" valign="middle">3</td>
        <td align="center" valign="middle">БТПЖ бўлимлари ҳисобида турувчи ва ногиронлик пенсиясини олувчи жами 16 ёшдан юқори ногиронлар сони</td>
        <td align="center" valign="middle"><?php echo $re->pf; ?></td>
        <td align="center" valign="middle"><?php echo (($re->pf/$re->pf)*100).'%'; ?></td>
    </tr>
    <tr>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">шундан:</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
    </tr>
    <tr>
        <td align="center" valign="middle">3.1</td>
        <td align="center" valign="middle">1-гуруҳ</td>
        <td align="center" valign="middle"><?php echo $re->pf1; ?></td>
        <td align="center" valign="middle"><?php echo (($re->pf1/$re->pf)*100).'%'; ?></td>
    </tr>
    <tr>
        <td align="center" valign="middle">3.2</td>
        <td align="center" valign="middle">2-гуруҳ</td>
        <td align="center" valign="middle"><?php echo $re->pf2; ?></td>
        <td align="center" valign="middle"><?php echo (($re->pf3/$re->pf)*100).'%'; ?></td>
    </tr>
    <tr>
        <td align="center" valign="middle">3.3</td>
        <td align="center" valign="middle">3-гуруҳ</td>
        <td align="center" valign="middle"><?php echo $re->pf3; ?></td>
        <td align="center" valign="middle"><?php echo (($re->pf3/$re->pf)*100).'%'; ?></td>
    </tr>
    <tr>
        <td align="center" valign="middle">4</td>
        <td align="center" valign="middle">Ногиронлик статусига эга, лекин айрим сабабларга кўра</td>
        <td align="center" valign="middle"><?php echo $re->nopf; ?></td>
        <td align="center" valign="middle"><?php echo (($re->nopf/$re->nopf)*100).'%'; ?></td>
    </tr>
    <tr>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">шундан:</td>
        <td align="center" valign="middle">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
    </tr>
    <tr>
        <td align="center" valign="middle">4.1</td>
        <td align="center" valign="middle">1-гуруҳ</td>
        <td align="center" valign="middle"><?php echo $re->nopf1; ?></td>
        <td align="center" valign="middle"><?php echo (($re->nopf1/$re->nopf)*100).'%'; ?></td>
    </tr>
    <tr>
        <td align="center" valign="middle">4.2</td>
        <td align="center" valign="middle">2-гуруҳ</td>
        <td align="center" valign="middle"><?php echo $re->nopf2; ?></td>
        <td align="center" valign="middle"><?php echo (($re->nopf2/$re->nopf)*100).'%'; ?></td>
    </tr>
    <tr>
        <td align="center" valign="middle">4.3</td>
        <td align="center" valign="middle">3-гуруҳ</td>
        <td align="center" valign="middle"><?php echo $re->nopf3; ?></td>
        <td align="center" valign="middle"><?php echo (($re->nopf3/$re->nopf)*100).'%'; ?></td>
    </tr>
        <?php  endforeach; ?>
</table>


</body>
</html>