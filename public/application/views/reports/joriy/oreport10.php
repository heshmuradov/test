<?php

header("Content-type: application/vnd.ms-excel");
   // echo $beg_date;
   // echo $end_date;
   // print_r($depart_set); exit;
?>

<table border="1" cellpadding="0" cellspacing="0">
    <col width="64" />
    <col width="137" />
    <col width="105" />
    <col width="103" />
    <col width="136" />
    <col width="64" span="18" />
    <tr>
        <td width="44" align="center" valign="middle"></td>
        <td width="254" align="center" valign="middle"></td>
        <td width="183" align="center" valign="middle"><?php echo $yil.' йил'; ?></td>
        <td width="147" align="center" valign="middle"><?php echo $oy;?></td>
        <td colspan="19" align="left" valign="middle">ойида ҳудудий ТМЭКларда    белгиланган бирламчи ногиронлик тўғрисида</td>
    </tr>
    <tr>
        <td colspan="23" align="center" valign="middle">МАЪЛУМОТ</td>
    </tr>
    <tr>
        <td width="44" align="center" valign="middle"></td>
        <td colspan="4" align="right" valign="middle"><?php echo date('Y.m.d'); ?></td>
        <td colspan="18" align="left" valign="middle">кун холатига</td>
    </tr>
    <tr>
        <td rowspan="3" align="center" valign="middle">№</td>
        <td width="254" rowspan="3" align="center" valign="middle">ТМЭКлар</td>
        <td width="183" rowspan="3" align="center" valign="middle">ТМЭКлар хизмат кўрсатадиган ҳудудлар</td>
        <td width="147" rowspan="3" align="center" valign="middle">Жами бирламчи ногиронлик белгиланганлар сони</td>
        <td width="85" rowspan="3" align="center" valign="middle">Шундан танланган ой учун</td>
        <td colspan="18" align="center" valign="middle">Шундан, </td>
    </tr>
    <tr>
        <td width="40" rowspan="2" align="center" valign="middle">1-гурух </td>
        <td colspan="5" align="center" valign="middle">Шундан, </td>
        <td width="40" rowspan="2" align="center" valign="middle">2-гурух</td>
        <td colspan="5" align="center" valign="middle">Шундан, </td>
        <td width="40" rowspan="2" align="center" valign="middle">3-гурух</td>
        <td colspan="5" align="center" valign="middle">Шундан, </td>
    </tr>
    <tr>
        <td width="40" align="center" valign="middle">6 ойга</td>
        <td width="40" align="center" valign="middle">1 йил</td>
        <td width="40" align="center" valign="middle">2 йил</td>
        <td width="40" align="center" valign="middle">5 йил</td>
        <td width="55" align="center" valign="middle">муддатсиз</td>
        <td width="40" align="center" valign="middle">6 ойга</td>
        <td width="40" align="center" valign="middle">1 йил</td>
        <td width="40" align="center" valign="middle">2 йил</td>
        <td width="40" align="center" valign="middle">5 йил</td>
        <td width="55" align="center" valign="middle">муддатсиз</td>
        <td width="40" align="center" valign="middle">6 ойга</td>
        <td width="40" align="center" valign="middle">1 йил</td>
        <td width="40" align="center" valign="middle">2 йил</td>
        <td width="40" align="center" valign="middle">5 йил</td>
        <td width="55" align="center" valign="middle">муддатсиз</td>
    </tr>
    <?php
    $i = 1;
    foreach ($depart_set as $row) {
        $departid=$row->id;
        $departname=$row->name_uz;
        $rayon = $this->db->query("select id , name_uz from spr_region srr
                                        where
                                        srr.par_id in (select dr.id_region from depart_role dr
                                                        left join spr_region sr on dr.id_region=sr.id where dr.id_depart=$departid)
                                        or srr.id in (select dr.id_region from depart_role dr
                                                        left join spr_region sr on dr.id_region=sr.id where dr.id_depart=$departid)
                                                        order by srr.id")->result();

    ?>
    <tr>
        <td align="center" valign="middle"><?php echo $i; ?></td>
        <td align="center" valign="middle"><?php echo $departname; ?></td>
        <td align="center" valign="middle"><?php echo 'Жами'; ?></td>
        <td align="center" valign="middle"><?php
            $all_birlamchi = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1,2,3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date <= '$end_date'
                                                            and mih.id_checktype=1")->result();
                echo $all_birlamchi[0]->count;
            ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1,2,3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_1[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_1_6 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=1
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_1_6[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_1_1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=2
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_1_1[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_1_2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=3
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_1_2[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_1_4 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=5
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_1_4[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_1_5 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=4
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_1_5[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (2)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_2[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_2_6 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (2)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=1
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_2_6[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_2_1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (2)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=2
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_2_1[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_2_2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (2)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=3
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_2_2[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_2_4 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (2)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=5
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_2_4[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_2_5 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (2)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=4
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_2_5[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_3 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_3[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_3_6 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=1
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_3_6[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_3_1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=2
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_3_1[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_3_2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=3
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_3_2[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_3_4 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=5
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_3_4[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $mounth_birlamchi_3_5 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=4
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $mounth_birlamchi_3_5[0]->count; ?></td>
    </tr>
    <?php
    foreach ($rayon as $rrow) {
    $k = $rrow->id;
    $kname= $rrow->name_uz;
        ?>

        <tr>
        <td align="center" valign="middle"> </td>
        <td align="center" valign="middle"></td>
        <td align="center" valign="middle"><?php echo $kname; ?></td>
        <td align="center" valign="middle"><?php
            $kall_birlamchi = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1,2,3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date <= '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kall_birlamchi[0]->count;
            ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1,2,3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_1[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_1_6 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=1
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_1_6[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_1_1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=2
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_1_1[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_1_2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=3
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_1_2[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_1_4 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=5
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_1_4[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_1_5 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (1)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=4
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_1_5[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (2)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_2[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_2_6 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (2)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=1
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_2_6[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_2_1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (2)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=2
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_2_1[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_2_2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (2)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=3
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_2_2[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_2_4 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (2)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=5
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_2_4[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_2_5 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (2)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=4
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_2_5[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_3 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_3[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_3_6 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=1
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_3_6[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_3_1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=2
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_3_1[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_3_2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=3
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_3_2[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_3_4 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=5
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_3_4[0]->count; ?></td>
        <td align="center" valign="middle"><?php
            $kmounth_birlamchi_3_5 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history mih On m.id=mih.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and mih.id_royhat in (1,3,4,5,6,11,12)
                                                            and mih.guruh in (3)
                                                            and mih.OLD=0
                                                            and mih.approve=1
                                                            and mih.end_date_combo=4
                                                            and mih.beg_date between '$beg_date' and '$end_date'
                                                            and mih.id_checktype=1")->result();
            echo $kmounth_birlamchi_3_5[0]->count; ?></td>
    </tr>
        <?php
            }
        $i++;
    } ?>
</table>
</body>
</html>