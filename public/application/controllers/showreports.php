<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Showreports extends MyController
{
    function Showreports()
    {
        parent::MyController();
    }

    function _before($method)
    {
        $auth_method = array("index", "from");
        if (in_array($method, $auth_method)) {
            $this->_auth();
        }
    }

    function index()
    {
        if ($this->session->userdata['admin_type'] != 5) {
        $data = array();
        $data['menus'] = $this->_build_menu();
        $iddep = $this->session->userdata("iddep");
        if ($this->session->userdata['admin_type'] == 4) {
            $dep = $this->db->query("select id from spr_depart where par_id=$iddep order by id");
            $dep = $dep->result_array();
            $ddep = array();
            foreach ($dep as $item):
                $ddep[] = $item['id'];
            endforeach;
        }
        if ($this->session->userdata['admin_type'] == 3) {
            $dep = $this->db->query("select id from spr_depart where id not in (0,1,2)  order by id");
            $dep = $dep->result_array();
            $ddep = array();
            foreach ($dep as $item):
                $ddep[] = $item['id'];
            endforeach;
        }
        if (empty($ddep)) {
            $ddep = 5000;
        } else {
            $ddep = implode(", ", $ddep);
        }
        $data["depart"] = $this->db->query("select * from spr_depart where id in ($iddep, $ddep)  order by id")->result();
        $data["sabab"] = $this->db->query("SELECT id, name_uz  FROM spr_checktype  order by id")->result();
        $is_mon = $this->session->userdata['admin_type'];
        if ($is_mon==2 or $is_mon==4 ) {
             $data['fborn'] = $this->db->query("select * from spr_region sr
                   where sr.par_id in
                         (select s.id  FROM depart_role d, spr_region s
                         WHERE d.id_depart = $iddep
                         AND d.id_region = s.id )
                   union
                         select * from spr_region sr where sr.id in
                         (select s.id  FROM depart_role d, spr_region s WHERE d.id_depart = $iddep
                         AND d.id_region = s.id) and sr.par_id>0")->result();}
        else{
             $data['fborn'] = $this->db->query("select * from spr_region where id NOT IN (0) ORDER BY par_id ")->result();
            }


        $this->load->view("header_index");
        $this->load->view("/reports/showreport", $data);
        }
        else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function oreport8(){

        $iddep=$_POST["id_depart8"];

        if ($iddep == 2) {
            $dep_sql = "select id from spr_depart where id not in (0,1,2)  order by id";
            }
        else {
            $dep_sql = "select id from spr_depart where id=$iddep or par_id=$iddep";
        }

        $data["umum_soni"]= $this->db->query("select vt.id_depart, sd1.name_uz, sum(vt.pension) as pension, sum(vt.vtek) as vtek,
                                  sum(vt.pension1) as pension1,sum(vt.vtek1) as vtek1,
                                  sum(vt.pension2) as pension2,sum(vt.vtek2) as vtek2,
                                  sum(vt.pension3) as pension3,sum(vt.vtek3) as vtek3 from
                (select m.id_depart, count(*) as pension, 0 as vtek, 0 as pension1, 0 as vtek1, 0 as pension2, 0 as vtek2, 0 as pension3, 0 as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_depart in ($dep_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.approve=1
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (1,2,3)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       and mih.pcheck in (1,2)
                       group by m.id_depart
                union
                select m.id_depart, 0 as pension, count(*) as vtek, 0 as pension1, 0 as vtek1, 0 as pension2, 0 as vtek2, 0 as pension3, 0 as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_depart in ($dep_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.approve=1
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (1,2,3)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       group by m.id_depart
                union
                select m.id_depart, 0 as pension, 0 as vtek, count(*) as pension1, 0 as vtek1, 0 as pension2, 0 as vtek2, 0 as pension3, 0 as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_depart in ($dep_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.approve=1
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (1)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       and mih.pcheck in (1,2)
                       group by m.id_depart
                union
                select m.id_depart, 0 as pension, 0 as vtek, 0 as pension1, count(*) as vtek1, 0 as pension2, 0 as vtek2, 0 as pension3, 0 as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_depart in ($dep_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.approve=1
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (1)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       group by m.id_depart
                union
                select m.id_depart, 0 as pension, 0 as vtek, 0 as pension1, 0 as vtek1, count(*) as pension2, 0 as vtek2, 0 as pension3, 0 as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_depart in ($dep_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.approve=1
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (2)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       and mih.pcheck in (1,2)
                       group by m.id_depart
                union
                select m.id_depart, 0 as pension, 0 as vtek, 0 as pension1, 0 as vtek1, 0 as pension2, count(*) as vtek2, 0 as pension3, 0 as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_depart in ($dep_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.approve=1
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (2)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       group by m.id_depart
                union
                select m.id_depart, 0 as pension, 0 as vtek, 0 as pension1, 0 as vtek1, 0 as pension2, 0 as vtek2, count(*) as pension3, 0 as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_depart in ($dep_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.approve=1
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (3)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       and mih.pcheck in (1,2)
                       group by m.id_depart
                union
                select m.id_depart, 0 as pension, 0 as vtek, 0 as pension1, 0 as vtek1, 0 as pension2, 0 as vtek2, 0 as pension3, count(*) as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_depart in ($dep_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.approve=1
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (3)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       group by m.id_depart ) vt
                       left join spr_depart sd1 on vt.id_depart=sd1.id
                       group by vt.id_depart, sd1.name_uz
                       order by vt.id_depart ")->result();

        $this->load->view("/reports/joriy/oreport8", $data);

    }

    function oreport81(){

        $hudud=$_POST["hudud8"];
        $hudud_sql = "select id from spr_region where id=$hudud or par_id=$hudud order by id";


        $data["umum_soni"]= $this->db->query("select vt.id_born, sd1.name_uz, sum(vt.pension) as pension, sum(vt.vtek) as vtek,
                                  sum(vt.pension1) as pension1,sum(vt.vtek1) as vtek1,
                                  sum(vt.pension2) as pension2,sum(vt.vtek2) as vtek2,
                                  sum(vt.pension3) as pension3,sum(vt.vtek3) as vtek3 from
                (select m.id_born, count(*) as pension, 0 as vtek, 0 as pension1, 0 as vtek1, 0 as pension2, 0 as vtek2, 0 as pension3, 0 as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_born in ($hudud_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (1,2,3)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       and mih.pcheck in (1,2)
                       group by m.id_born
                union
                select m.id_born, 0 as pension, count(*) as vtek, 0 as pension1, 0 as vtek1, 0 as pension2, 0 as vtek2, 0 as pension3, 0 as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_born in ($hudud_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (1,2,3)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       group by m.id_born
                union
                select m.id_born, 0 as pension, 0 as vtek, count(*) as pension1, 0 as vtek1, 0 as pension2, 0 as vtek2, 0 as pension3, 0 as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_born in ($hudud_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (1)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       and mih.pcheck in (1,2)
                       group by m.id_born
                union
                select m.id_born, 0 as pension, 0 as vtek, 0 as pension1, count(*) as vtek1, 0 as pension2, 0 as vtek2, 0 as pension3, 0 as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_born in ($hudud_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (1)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       group by m.id_born
                union
                select m.id_born, 0 as pension, 0 as vtek, 0 as pension1, 0 as vtek1, count(*) as pension2, 0 as vtek2, 0 as pension3, 0 as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_born in ($hudud_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (2)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       and mih.pcheck in (1,2)
                       group by m.id_born
                union
                select m.id_born, 0 as pension, 0 as vtek, 0 as pension1, 0 as vtek1, 0 as pension2, count(*) as vtek2, 0 as pension3, 0 as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_born in ($hudud_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (2)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       group by m.id_born
                union
                select m.id_born, 0 as pension, 0 as vtek, 0 as pension1, 0 as vtek1, 0 as pension2, 0 as vtek2, count(*) as pension3, 0 as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_born in ($hudud_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (3)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       and mih.pcheck in (1,2)
                       group by m.id_born
                union
                select m.id_born, 0 as pension, 0 as vtek, 0 as pension1, 0 as vtek1, 0 as pension2, 0 as vtek2, 0 as pension3, count(*) as vtek3  from mijoz m
                       left join mijoz_ill_history mih ON m.id=mih.id_mijoz
                       where
                       m.id_born in ($hudud_sql)
                       and m.MOVE=0
					   and m.selected in (0,1)
                       and mih.id_royhat in (1,3,4,5,6,11,12)
                       and mih.guruh in (3)
                       and mih.OLD=0
                       and mih.end_date>=CURRENT_DATE
                       group by m.id_born ) vt
                       left join spr_region sd1 on vt.id_born=sd1.id
                       group by vt.id_born, sd1.name_uz
                       order by vt.id_born ")->result();

        $this->load->view("/reports/joriy/oreport81", $data);

    }


    function chreport1()  // bu report qilindi
    {

        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';
        $objPHPExcel = PHPExcel_IOFactory::load("templates/pension/jadval-1.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $iddep = $this->session->userdata("iddep");

        $monitoring = $this->session->userdata['admin_type'];
        $period = $_POST["period"];
        $year=date('Y');
       // print_r($year);exit;
        $objPHPExcel->getActiveSheet()->setCellValue('I' . 3, $period);
        if ($period==1){ $beg_date = date("$year-04-01"); $end_date = date("$year-03-31");  }
        if ($period==2){ $beg_date = date("$year-07-01"); $end_date = date("$year-06-30"); }
        if ($period==3){ $beg_date = date("$year-10-01"); $end_date = date("$year-09-31"); }
        if ($period==4){ $beg_date = date("$year-12-31"); $end_date = date("$year-12-31"); }

        $id_depart = $_POST["id_depart"];
        if ($id_depart == 0){
        $depart_set=$this->db->query("select id, name_uz from spr_depart where par_id=$iddep or id=$iddep order by id")->result();
        }
        else {
            $depart_set=$this->db->query("select id, name_uz from spr_depart where id=$iddep order by id")->result();

        }

         $baseRow=9;
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
            foreach ($rayon as $rrow) {
                $objPHPExcel->getActiveSheet()->insertNewRowBefore($baseRow, 1);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $baseRow, $rrow->name_uz);
                $k = $rrow->id;
                $allinvalid = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                            and h.guruh in (0,1,2,3)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $baseRow, $allinvalid[0]->count);
                $allinvalid0 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                            and h.guruh in (0)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $baseRow, $allinvalid0[0]->count);
                $allinvalid1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                            and h.guruh in (1)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $baseRow, $allinvalid1[0]->count);
                $allinvalid2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                            and h.guruh in (2)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $baseRow, $allinvalid2[0]->count);
                $allinvalid3 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                            and h.guruh in (3)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $baseRow, $allinvalid3[0]->count);
                $mallinvalid = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (3,4,5,12)
                                                            and h.guruh in (0,1,2,3)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $baseRow, $mallinvalid[0]->count);
                $mallinvalid0 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (3,4,5,12)
                                                            and h.guruh in (0)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $baseRow, $mallinvalid0[0]->count);
                $mallinvalid1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (3,4,5,12)
                                                            and h.guruh in (1)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $baseRow, $mallinvalid1[0]->count);
                $mallinvalid2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (3,4,5,12)
                                                            and h.guruh in (2)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $baseRow, $mallinvalid2[0]->count);
                $mallinvalid3 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (3,4,5,12)
                                                            and h.guruh in (3)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $baseRow, $mallinvalid3[0]->count);
                $pallinvalid = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck in (1,2)
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (0,1,2,3)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $baseRow, $pallinvalid[0]->count);
                $pallinvalid0 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck in (1,2)
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (0)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $baseRow, $pallinvalid0[0]->count);
                $pallinvalid1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck in (1,2)
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (1)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $baseRow, $pallinvalid1[0]->count);
                $pallinvalid2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck in (1,2)
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (2)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $baseRow, $pallinvalid2[0]->count);
                $pallinvalid3 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck in (1,2)
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (3)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $baseRow, $pallinvalid3[0]->count);
                $ballinvalid = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck = 0
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (0,1,2,3)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('S' . $baseRow, $ballinvalid[0]->count);
                $ballinvalid0 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck =0
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (0)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('T' . $baseRow, $ballinvalid0[0]->count);
                $ballinvalid1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck =0
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (1)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('U' . $baseRow, $ballinvalid1[0]->count);
                $ballinvalid2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck =0
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (2)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('V' . $baseRow, $ballinvalid2[0]->count);
                $ballinvalid3 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and m.id_born=$k
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck =0
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (3)
                                                            and h.end_date >='$end_date'")->result();
                $objPHPExcel->getActiveSheet()->setCellValue('W' . $baseRow, $ballinvalid3[0]->count);

            }
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($baseRow, 1);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $baseRow, $departname);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $baseRow, 'Жами');
            $allinvalid = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                            and h.guruh in (0,1,2,3)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $baseRow, $allinvalid[0]->count);
            $allinvalid0 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                            and h.guruh in (0)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $baseRow, $allinvalid0[0]->count);
            $allinvalid1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                            and h.guruh in (1)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $baseRow, $allinvalid1[0]->count);
            $allinvalid2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                            and h.guruh in (2)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $baseRow, $allinvalid2[0]->count);
            $allinvalid3 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                            and h.guruh in (3)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $baseRow, $allinvalid3[0]->count);
            $mallinvalid = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (3,4,5,12)
                                                            and h.guruh in (0,1,2,3)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $baseRow, $mallinvalid[0]->count);
            $mallinvalid0 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (3,4,5,12)
                                                            and h.guruh in (0)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $baseRow, $mallinvalid0[0]->count);
            $mallinvalid1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (3,4,5,12)
                                                            and h.guruh in (1)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $baseRow, $mallinvalid1[0]->count);
            $mallinvalid2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (3,4,5,12)
                                                            and h.guruh in (2)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $baseRow, $mallinvalid2[0]->count);
            $mallinvalid3 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.id_royhat in (3,4,5,12)
                                                            and h.guruh in (3)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $baseRow, $mallinvalid3[0]->count);
            $pallinvalid = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck in (1,2)
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (0,1,2,3)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $baseRow, $pallinvalid[0]->count);
            $pallinvalid0 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck in (1,2)
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (0)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $baseRow, $pallinvalid0[0]->count);
            $pallinvalid1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck in (1,2)
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (1)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $baseRow, $pallinvalid1[0]->count);
            $pallinvalid2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck in (1,2)
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (2)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $baseRow, $pallinvalid2[0]->count);
            $pallinvalid3 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck in (1,2)
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (3)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $baseRow, $pallinvalid3[0]->count);
            $ballinvalid = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck = 0
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (0,1,2,3)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('S' . $baseRow, $ballinvalid[0]->count);
            $ballinvalid0 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck =0
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (0)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('T' . $baseRow, $ballinvalid0[0]->count);
            $ballinvalid1 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck =0
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (1)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $baseRow, $ballinvalid1[0]->count);
            $ballinvalid2 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
															and m.selected in (0,1)
                                                            and h.approve=1
                                                            and h.pcheck =0
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (2)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('V' . $baseRow, $ballinvalid2[0]->count);
            $ballinvalid3 = $this->db->query("select count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where m.id_depart in ($departid)
                                                            and h.OLD=0
                                                            and h.approve=1
															and m.selected in (0,1)
                                                            and h.pcheck =0
                                                            and h.id_royhat in (1,2,6,10,11,13)
                                                            and h.guruh in (3)
                                                            and h.end_date >='$end_date'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('W' . $baseRow, $ballinvalid3[0]->count);
        }

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=b1.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }


    function oreport10 (){
        $data = array();
        $id_depart=$_POST["id_depart10"];
        $period=$_POST["period10"];
        //$data["oy"]=$period;
        $year=date('Y');
        $data["yil"]=$year;

        if ($period==1){ $data["beg_date"] = date("$year-01-01"); $data["end_date"] = date("$year-01-31"); $data["oy"]='Январь'; }
        if ($period==2){ $data["beg_date"] = date("$year-02-01"); $data["end_date"] = date("$year-02-29"); $data["oy"]='Февраль';}
        if ($period==3){ $data["beg_date"] = date("$year-03-01"); $data["end_date"] = date("$year-03-31"); $data["oy"]='Март';}
        if ($period==4){ $data["beg_date"] = date("$year-04-01"); $data["end_date"] = date("$year-04-30"); $data["oy"]='Апрел';}
        if ($period==5){ $data["beg_date"] = date("$year-05-01"); $data["end_date"] = date("$year-05-31"); $data["oy"]='Май';}
        if ($period==6){ $data["beg_date"] = date("$year-06-01"); $data["end_date"] = date("$year-06-30"); $data["oy"]='Июнь';}
        if ($period==7){ $data["beg_date"] = date("$year-07-01"); $data["end_date"] = date("$year-07-31"); $data["oy"]='Июль';}
        if ($period==8){ $data["beg_date"] = date("$year-08-01"); $data["end_date"] = date("$year-08-31"); $data["oy"]='Август';}
        if ($period==9){ $data["beg_date"] = date("$year-09-01"); $data["end_date"] = date("$year-09-30"); $data["oy"]='Сентябрь';}
        if ($period==10){ $data["beg_date"] = date("$year-10-01"); $data["end_date"] = date("$year-10-31"); $data["oy"]='Октябрь';}
        if ($period==11){ $data["beg_date"] = date("$year-11-01"); $data["end_date"] = date("$year-11-30"); $data["oy"]='Ноябрь';}
        if ($period==12){ $data["beg_date"] = date("$year-12-01"); $data["end_date"] = date("$year-12-31"); $data["oy"]='Декабрь';}



        $data["depart_set"]=$this->db->query("select id, name_uz from spr_depart where id=$id_depart order by id")->result();
        $this->load->view("/reports/joriy/oreport10", $data);

    }
    function oreport12 (){
        $data = array();
        $id_depart=$_POST["id_depart12"];
        $period=$_POST["period12"];
        //$data["oy"]=$period;
        $year=date('Y');
        $data["yil"]=$year;

        if ($period==1){ $data["beg_date"] = date("$year-01-01"); $data["end_date"] = date("$year-01-31"); $data["oy"]='Январь'; }
        if ($period==2){ $data["beg_date"] = date("$year-02-01"); $data["end_date"] = date("$year-02-29"); $data["oy"]='Февраль';}
        if ($period==3){ $data["beg_date"] = date("$year-03-01"); $data["end_date"] = date("$year-03-31"); $data["oy"]='Март';}
        if ($period==4){ $data["beg_date"] = date("$year-04-01"); $data["end_date"] = date("$year-04-30"); $data["oy"]='Апрел';}
        if ($period==5){ $data["beg_date"] = date("$year-05-01"); $data["end_date"] = date("$year-05-31"); $data["oy"]='Май';}
        if ($period==6){ $data["beg_date"] = date("$year-06-01"); $data["end_date"] = date("$year-06-30"); $data["oy"]='Июнь';}
        if ($period==7){ $data["beg_date"] = date("$year-07-01"); $data["end_date"] = date("$year-07-31"); $data["oy"]='Июль';}
        if ($period==8){ $data["beg_date"] = date("$year-08-01"); $data["end_date"] = date("$year-08-31"); $data["oy"]='Август';}
        if ($period==9){ $data["beg_date"] = date("$year-09-01"); $data["end_date"] = date("$year-09-30"); $data["oy"]='Сентябрь';}
        if ($period==10){ $data["beg_date"] = date("$year-10-01"); $data["end_date"] = date("$year-10-31"); $data["oy"]='Октябрь';}
        if ($period==11){ $data["beg_date"] = date("$year-11-01"); $data["end_date"] = date("$year-11-30"); $data["oy"]='Ноябрь';}
        if ($period==12){ $data["beg_date"] = date("$year-12-01"); $data["end_date"] = date("$year-12-31"); $data["oy"]='Декабрь';}



        $data["depart_set"]=$this->db->query("select id, name_uz from spr_depart where id=$id_depart order by id")->result();
        $this->load->view("/reports/joriy/oreport12", $data);

    }

}