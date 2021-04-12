<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Respshowreports extends MyController
{
    function Respshowreports()
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
        $data = array();
        $data['menus'] = $this->_build_menu();
        $iddep = $this->session->userdata("iddep");
        $data["sabab"] = $this->db->query('SELECT id, name_uz  FROM spr_checktype ')->result();
        $data["id_depart"] = $this->db->query('SELECT id, name_uz  FROM spr_depart where id not in (0,1,2) ')->result();
        $data["gender"] = $this->db->query('SELECT id, name_uz  FROM spr_gender ')->result();
        $this->load->view("header_index");
        $this->load->view("/reports/respshowreport", $data);
    }

    function b0()
    {
        require_once 'functions.php';
        $filter = "";
        $bn = isset($_POST['bn']) ? $_POST['bn'] : null;
        $gender = isset($_POST['gender']) ? $_POST['gender'] : null;
        $guruh = isset($_POST['guruh']) ? $_POST['guruh'] : null;
        $id_depart = isset($_POST['id_depart']) ? $_POST['id_depart'] : null;
        $id_checktype = isset($_POST['id_checktype0']) ? $_POST['id_checktype0'] : null;

        if (!empty($gender)||$gender!=0) {
            $filter = $filter . " and m.gender=$gender";
        }
        if (!empty($bnn)) {
            $filter = $filter . " and m.bn=$bn-1";
        }
        if (!empty($guruh)) {
            $filter = $filter . " and h.guruh=$guruh";
        }
        if (!empty($id_depart)) {
            $filter = $filter . " and m.id_depart=$id_depart";
        }
        if (!empty($id_checktype)||$id_checktype!=0) {
            $filter = $filter . " and h.id_checktype=$id_checktype";
        }
        $data = array();
        $data["depart_name"] = $this->db->query("SELECT name_uz from spr_depart WHERE id=" . $id_depart)->result();
        $data["monitoring"] = $this->session->userdata['admin_type'];
        $data["royhat_export"] = $this->db->query("SELECT  m.familiya||' '||m.NAME||' '||m.middle as fio, m.date_birth,sg.name_uz as gender,
                                                                                                   sr.name_uz as region, m.address, m.pass_seriya||' '||m.pass_code as pasport,
                                                                                                   sd.name_uz as depart, sc.name_uz as checktype,
                                                                                                   sm.name_uz as malumot, me.spec as kasbi, sw.name_uz as working,
                                                                                                   si.code_9 as mkb9, smk.r as mkb10,
                                                                                                   h.guruh, ss.sabab_uz, h.end_date, h.beg_date, srh.name_uz as rt  FROM mijoz m
                                                                                                     left join spr_gender sg on m.gender=sg.id
                                                                                                     left join spr_region sr on m.id_born=sr.id
                                                                                                     left join spr_depart sd on m.id_depart=sd.id
                                                                                                     left join history h on m.id=h.id_mijoz
                                                                                                     left join spr_checktype sc on h.id_checktype=sc.id
                                                                                                     left join spr_sabab ss on h.id_sabab=ss.id
                                                                                                     left join spr_illness si on h.id_illness=si.id
                                                                                                     left join spr_illness_10 smk on h.code_ill_10=smk.row_num
                                                                                                     left join mijoz_edu me on m.id=me.id_mijoz
                                                                                                     left join spr_malumot sm on me.id_malumot=sm.id
                                                                                                     left join spr_work sw on me.working=sw.id
                                                                                                     left join spr_royhat srh on h.id_royhat=srh.id
                                                                                                     where h.OLD=0
																									 and m.selected in (0,1)
                                                                                                     and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                     and h.end_date >=  CURRENT_DATE
                                                                                                     {$filter}

        ")->result();
        $this->load->view("/reports/rt", $data);
    }

    function b1()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $checktype = $_POST["id_checktype"];
        $period = $_POST["period"];


        $objPHPExcel = PHPExcel_IOFactory::load("templates/report1.xls");
        $objPHPExcel->setActiveSheetIndex(0);


        $rayon = $this->db->query("select id , name_uz from spr_region srr where srr.par_id=$region  order by srr.id")->result();


        $i = 1;
        $baseRow = 22;
        foreach ($rayon as $row) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($baseRow, 1);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $baseRow, $i);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $baseRow, $row->name_uz);
            $i++;
            $k = $row->id;
            $guruh = $this->db->query("select   count(*) as count from mijoz m
                                                                                                left join history h On m.id=h.id_mijoz
                                                                                                where h.OLD=0
                                                                                                and m.id_born=$k
                                                                                                and m.id_depart not in (0,1,2)
																								and m.selected in (0,1)
                                                                                                and h.id_royhat in (1,2,3,4,5,6,10,11,12)
                                                                                                and h.guruh in (1,2,3)
                                                                                                group by h.guruh order by h.guruh")->result();
            $usoni = $this->db->query("select   count(*) as count from mijoz m
                                                                                                left join history h On m.id=h.id_mijoz
                                                                                                where h.OLD=0
                                                                                                and m.id_depart not in (0,1,2)
																								and m.selected in (0,1)
                                                                                                and m.id_born=$k
                                                                                                and h.id_royhat in (1,2,3,4,5,6,10,11,12)
                                                                                                and h.guruh in (1,2,3)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $baseRow, $usoni[0]->count);
            if (empty($guruh[0]->count)) $guruh[0]->count = 0;
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $baseRow, $guruh[0]->count);
            if (empty($guruh[1]->count)) $guruh[1]->count = 0;
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $baseRow, $guruh[1]->count);
            if (empty($guruh[2]->count)) $guruh[2]->count = 0;
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $baseRow, $guruh[2]->count);
        }

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function b3()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';
        $objPHPExcel = PHPExcel_IOFactory::load("templates/b3.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $iddep = $this->session->userdata("iddep");
        $monitoring = $this->session->userdata['admin_type'];
        /*   $checktype = $_POST["id_checktype"];

        if ($checktype == 0) {
            $checktype_sql = "(1,2)";
        } else {
            $checktype_sql = "($checktype)";
        }*/

        //        $period = $_POST["period"];


        $par_id_depart = $this->db->query("select par_id from spr_depart where id=$iddep")->result();
        $par_iddep = $par_id_depart[0]->par_id;


        if ($par_iddep == 2) {
            if ($monitoring == 4) {
                $dep = $this->db->query("select id from spr_depart where id=$iddep or par_id=$iddep")->result_array();
            }
            else
            {
                $dep = $this->db->query("select id from spr_depart where id=$iddep")->result_array();
            }
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

        $rayon = $this->db->query("select id , name_uz from spr_region srr
                                                                    where
                                                                    srr.par_id in (select dr.id_region from depart_role dr    left join spr_region sr on dr.id_region=sr.id
                                                                         where dr.id_depart=$iddep)
                                                                 or srr.id in (select dr.id_region from depart_role dr       left join spr_region sr on dr.id_region=sr.id
                                                                     where dr.id_depart=$iddep) order by srr.id")->result();

        //print_r($rayon); exit;
        $i = 1;
        $baseRow = 9;
        ;
        foreach ($rayon as $row) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($baseRow, 1);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $baseRow, $i);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $baseRow, $row->name_uz);
            $i++;
            $k = $row->id;
            $jami = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                      inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                      where
                                                                      m.id_depart in ($iddep,$ddep)
                                                                      and m.id_born=$k
																	  and m.selected in (0,1)
                                                                      and h.old=0
                                                                      and h.end_date >= CURRENT_DATE
                                                                      and h.OLD=0
                                                                      and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                      and h.guruh in(1,2,3)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $baseRow, $jami[0]->count);
            $jami_ayol = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                      inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                      where
                                                                      m.id_depart in ($iddep,$ddep)
                                                                      and m.id_born=$k
																	  and m.selected in (0,1)
                                                                      and m.gender=2
                                                                      and h.old=0
                                                                      and h.end_date >= CURRENT_DATE
                                                                      and h.OLD=0
                                                                      and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                      and h.guruh in(1,2,3)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $baseRow, $jami_ayol[0]->count);
            $jami_bn = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                      inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                      where
                                                                      m.id_depart in ($iddep,$ddep)
                                                                      and m.id_born=$k
																	  and m.selected in (0,1)
                                                                      and m.bn=1
                                                                      --and m.gender=2
                                                                      and h.old=0
                                                                      and h.end_date >= CURRENT_DATE
                                                                      and h.OLD=0
                                                                      and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                      and h.guruh in(1,2,3)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $baseRow, $jami_bn[0]->count);
            $jami_work = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                      inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                      where
                                                                      m.id_depart in ($iddep,$ddep)
                                                                      and m.id_born=$k
																	  and m.selected in (0,1)
                                                                      --and m.bn=1
                                                                      --and m.gender=2
                                                                      and me.working=1
                                                                      and h.old=0
                                                                      and h.end_date >= CURRENT_DATE
                                                                      and h.OLD=0
                                                                      and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                      and h.guruh in(1,2,3)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $baseRow, $jami_work[0]->count);
            $jami_muddatsiz = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                      inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                      where
                                                                      m.id_depart in ($iddep,$ddep)
                                                                      and m.id_born=$k
																	  and m.selected in (0,1)
                                                                      --and m.bn=1
                                                                      --and m.gender=2
                                                                      --and me.working=1
                                                                      and h.old=0
                                                                     -- and h.end_date >= CURRENT_DATE
                                                                     and h.end_date='01.01.2099'
                                                                      and h.OLD=0
                                                                      and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                      and h.guruh in(1,2,3)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $baseRow, $jami_muddatsiz[0]->count);
            $jami1 = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                      inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                      where
                                                                      m.id_depart in ($iddep,$ddep)
                                                                      and m.id_born=$k
																	  and m.selected in (0,1)
                                                                      and h.old=0
                                                                      and h.end_date >= CURRENT_DATE
                                                                      and h.OLD=0
                                                                      and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                      and h.guruh in(1)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $baseRow, $jami1[0]->count);
            $jami1_ayol = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                      inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                      where
                                                                      m.id_depart in ($iddep,$ddep)
                                                                      and m.id_born=$k
																	  and m.selected in (0,1)
                                                                      and m.gender=2
                                                                      and h.old=0
                                                                      and h.end_date >= CURRENT_DATE
                                                                      and h.OLD=0
                                                                      and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                      and h.guruh in(1)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $baseRow, $jami1_ayol[0]->count);
            $jami1_bn = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                      inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                      where
                                                                      m.id_depart in ($iddep,$ddep)
                                                                      and m.id_born=$k
																	  and m.selected in (0,1)
                                                                      and m.bn=1
                                                                      --and m.gender=2
                                                                      and h.old=0
                                                                      and h.end_date >= CURRENT_DATE
                                                                      and h.OLD=0
                                                                      and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                      and h.guruh in(1)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $baseRow, $jami1_bn[0]->count);
            $jami1_work = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                      inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                      where
                                                                      m.id_depart in ($iddep,$ddep)
                                                                      and m.id_born=$k
																	  and m.selected in (0,1)
                                                                      --and m.bn=1
                                                                      --and m.gender=2
                                                                      and me.working=1
                                                                      and h.old=0
                                                                      and h.end_date >= CURRENT_DATE
                                                                      and h.OLD=0
                                                                      and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                      and h.guruh in(1)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $baseRow, $jami1_work[0]->count);
            $jami1_muddatsiz = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                      inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                      where
                                                                      m.id_depart in ($iddep,$ddep)
                                                                      and m.id_born=$k
																	  and m.selected in (0,1)
                                                                      --and m.bn=1
                                                                      --and m.gender=2
                                                                      --and me.working=1
                                                                      and h.old=0
                                                                     -- and h.end_date >= CURRENT_DATE
                                                                     and h.end_date='01.01.2099'
                                                                      and h.OLD=0
                                                                      and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                      and h.guruh in(1)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $baseRow, $jami1_muddatsiz[0]->count);
            $jami2 = $this->db->query("select count(*) from mijoz m
                                                                                  inner join history h On h.id_mijoz=m.id
                                                                                  inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                                  where
                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                  and m.id_born=$k
																				  and m.selected in (0,1)
                                                                                  and h.old=0
                                                                                  and h.end_date >= CURRENT_DATE
                                                                                  and h.OLD=0
                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                  and h.guruh in(2)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $baseRow, $jami2[0]->count);
            $jami2_ayol = $this->db->query("select count(*) from mijoz m
                                                                                  inner join history h On h.id_mijoz=m.id
                                                                                  inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                                  where
                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                  and m.id_born=$k
																				  and m.selected in (0,1)
                                                                                  and m.gender=2
                                                                                  and h.old=0
                                                                                  and h.end_date >= CURRENT_DATE
                                                                                  and h.OLD=0
                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                  and h.guruh in(2)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $baseRow, $jami2_ayol[0]->count);
            $jami2_bn = $this->db->query("select count(*) from mijoz m
                                                                                  inner join history h On h.id_mijoz=m.id
                                                                                  inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                                  where
                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                  and m.id_born=$k
																				  and m.selected in (0,1)
                                                                                  and m.bn=1
                                                                                  --and m.gender=2
                                                                                  and h.old=0
                                                                                  and h.end_date >= CURRENT_DATE
                                                                                  and h.OLD=0
                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                  and h.guruh in(2)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $baseRow, $jami2_bn[0]->count);
            $jami2_work = $this->db->query("select count(*) from mijoz m
                                                                                  inner join history h On h.id_mijoz=m.id
                                                                                  inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                                  where
                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                  and m.id_born=$k
																				  and m.selected in (0,1)
                                                                                  --and m.bn=1
                                                                                  --and m.gender=2
                                                                                  and me.working=1
                                                                                  and h.old=0
                                                                                  and h.end_date >= CURRENT_DATE
                                                                                  and h.OLD=0
                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                  and h.guruh in(2)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $baseRow, $jami2_work[0]->count);
            $jami2_muddatsiz = $this->db->query("select count(*) from mijoz m
                                                                                  inner join history h On h.id_mijoz=m.id
                                                                                  inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                                  where
                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                  and m.id_born=$k
																				  and m.selected in (0,1)
                                                                                  --and m.bn=1
                                                                                  --and m.gender=2
                                                                                  --and me.working=1
                                                                                  and h.old=0
                                                                                 -- and h.end_date >= CURRENT_DATE
                                                                                 and h.end_date='01.01.2099'
                                                                                  and h.OLD=0
                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                  and h.guruh in(2)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $baseRow, $jami2_muddatsiz[0]->count);
            $jami3 = $this->db->query("select count(*) from mijoz m
                                                                                                          inner join history h On h.id_mijoz=m.id
                                                                                                          inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                                                          where
                                                                                                          m.id_depart in ($iddep,$ddep)
                                                                                                          and m.id_born=$k
																										  and m.selected in (0,1)
                                                                                                          and h.old=0
                                                                                                          and h.end_date >= CURRENT_DATE
                                                                                                          and h.OLD=0
                                                                                                          and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                          and h.guruh in(3)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('S' . $baseRow, $jami3[0]->count);
            $jami3_ayol = $this->db->query("select count(*) from mijoz m
                                                                                                          inner join history h On h.id_mijoz=m.id
                                                                                                          inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                                                          where
                                                                                                          m.id_depart in ($iddep,$ddep)
                                                                                                          and m.id_born=$k
																										  and m.selected in (0,1)
                                                                                                          and m.gender=2
                                                                                                          and h.old=0
                                                                                                          and h.end_date >= CURRENT_DATE
                                                                                                          and h.OLD=0
                                                                                                          and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                          and h.guruh in(3)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('T' . $baseRow, $jami3_ayol[0]->count);
            $jami3_bn = $this->db->query("select count(*) from mijoz m
                                                                                                          inner join history h On h.id_mijoz=m.id
                                                                                                          inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                                                          where
                                                                                                          m.id_depart in ($iddep,$ddep)
                                                                                                          and m.id_born=$k
																										  and m.selected in (0,1)
                                                                                                          and m.bn=1
                                                                                                          --and m.gender=2
                                                                                                          and h.old=0
                                                                                                          and h.end_date >= CURRENT_DATE
                                                                                                          and h.OLD=0
                                                                                                          and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                          and h.guruh in(3)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $baseRow, $jami3_bn[0]->count);
            $jami3_work = $this->db->query("select count(*) from mijoz m
                                                                                                          inner join history h On h.id_mijoz=m.id
                                                                                                          inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                                                          where
                                                                                                          m.id_depart in ($iddep,$ddep)
                                                                                                          and m.id_born=$k
																										  and m.selected in (0,1)
                                                                                                          --and m.bn=1
                                                                                                          --and m.gender=2
                                                                                                          and me.working=1
                                                                                                          and h.old=0
                                                                                                          and h.end_date >= CURRENT_DATE
                                                                                                          and h.OLD=0
                                                                                                          and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                          and h.guruh in(3)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('V' . $baseRow, $jami3_work[0]->count);
            $jami3_muddatsiz = $this->db->query("select count(*) from mijoz m
                                                                                                          inner join history h On h.id_mijoz=m.id
                                                                                                          inner join mijoz_edu me ON me.id_mijoz=m.id
                                                                                                          where
                                                                                                          m.id_depart in ($iddep,$ddep)
                                                                                                          and m.id_born=$k
																										  and m.selected in (0,1)
                                                                                                          --and m.bn=1
                                                                                                          --and m.gender=2
                                                                                                          --and me.working=1
                                                                                                          and h.old=0
                                                                                                         -- and h.end_date >= CURRENT_DATE
                                                                                                         and h.end_date='01.01.2099'
                                                                                                          and h.OLD=0
                                                                                                          and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                          and h.guruh in(3)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('W' . $baseRow, $jami3_muddatsiz[0]->count);

        }

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=b3.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function b4()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';
        $objPHPExcel = PHPExcel_IOFactory::load("templates/b4.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $iddep = $this->session->userdata("iddep");
        $monitoring = $this->session->userdata['admin_type'];

        $par_id_depart = $this->db->query("select par_id from spr_depart where id=$iddep")->result();
        $par_iddep = $par_id_depart[0]->par_id;

        if ($par_iddep == 2) {
            if ($monitoring == 4) {
                $dep = $this->db->query("select id from spr_depart where id=$iddep or par_id=$iddep")->result_array();
            }
            else
            {
                $dep = $this->db->query("select id from spr_depart where id=$iddep")->result_array();
            }
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


        $rayon = $this->db->query("select id , name_uz from spr_region srr
                                                                    where
                                                                    srr.par_id in (select dr.id_region from depart_role dr    left join spr_region sr on dr.id_region=sr.id
                                                                         where dr.id_depart=$iddep)
                                                                 or srr.id in (select dr.id_region from depart_role dr       left join spr_region sr on dr.id_region=sr.id
                                                                     where dr.id_depart=$iddep) order by srr.id")->result();
        $baseRow = 11;
        ;
        //print_r($rayon);exit;
        $i = 1;
        foreach ($rayon as $row) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($baseRow, 1);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $baseRow, $i);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $baseRow, $row->name_uz);
            $i++;
            $k = $row->id;
            $jami = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                    where
                                                                      m.id_depart in ($iddep,$ddep)
                                                                      and m.id_born=$k
                                                                      and h.old=0
																	  and m.selected in (0,1)
                                                                      and h.end_date >= CURRENT_DATE
                                                                      and h.OLD=0
                                                                      and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                      and h.guruh in(1,2,3)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $baseRow, $jami[0]->count);
            $sabab1 = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                                                where
                                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                                  and m.id_born=$k
																								  and m.selected in (0,1)
                                                                                                  and h.old=0
                                                                                                  and h.end_date >= CURRENT_DATE
                                                                                                  and h.OLD=0
                                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                  and h.guruh in(1,2,3)
																								  and h.id_sabab=1 ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $baseRow, $sabab1[0]->count);
            $sabab2 = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                                                where
                                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                                  and m.id_born=$k
																								  and m.selected in (0,1)
                                                                                                  and h.old=0
                                                                                                  and h.end_date >= CURRENT_DATE
                                                                                                  and h.OLD=0
                                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                  and h.guruh in(1,2,3)
																								  and h.id_sabab=2")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $baseRow, $sabab2[0]->count);
            $sabab3 = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                                                where
                                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                                  and m.id_born=$k
																								  and m.selected in (0,1)
                                                                                                  and h.old=0
                                                                                                  and h.end_date >= CURRENT_DATE
                                                                                                  and h.OLD=0
                                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                  and h.guruh in(1,2,3)
																								  and h.id_sabab=3 ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $baseRow, $sabab3[0]->count);
            $sabab4 = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                                                where
                                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                                  and m.id_born=$k
																								  and m.selected in (0,1)
                                                                                                  and h.old=0
                                                                                                  and h.end_date >= CURRENT_DATE
                                                                                                  and h.OLD=0
                                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                  and h.guruh in(1,2,3)
																								  and h.id_sabab=4 ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $baseRow, $sabab4[0]->count);
            $sabab5 = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                                                where
                                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                                  and m.id_born=$k
																								  and m.selected in (0,1)
                                                                                                  and h.old=0
                                                                                                  and h.end_date >= CURRENT_DATE
                                                                                                  and h.OLD=0
                                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                  and h.guruh in(1,2,3)
																								  and h.id_sabab=5 ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $baseRow, $sabab5[0]->count);
            $sabab6 = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                                                where
                                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                                  and m.id_born=$k
																								  and m.selected in (0,1)
                                                                                                  and h.old=0
                                                                                                  and h.end_date >= CURRENT_DATE
                                                                                                  and h.OLD=0
                                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                  and h.guruh in(1,2,3)
																								  and h.id_sabab=6 ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $baseRow, $sabab6[0]->count);
            $sabab7 = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                                                where
                                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                                  and m.id_born=$k
																								  and m.selected in (0,1)
                                                                                                  and h.old=0
                                                                                                  and h.end_date >= CURRENT_DATE
                                                                                                  and h.OLD=0
                                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                  and h.guruh in(1,2,3)
																								  and h.id_sabab=7 ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $baseRow, $sabab7[0]->count);
            $sabab8 = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                                                where
                                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                                  and m.id_born=$k
																								  and m.selected in (0,1)
                                                                                                  and h.old=0
                                                                                                  and h.end_date >= CURRENT_DATE
                                                                                                  and h.OLD=0
                                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                  and h.guruh in(1,2,3)
																								  and h.id_sabab=8 ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $baseRow, $sabab8[0]->count);
            $sabab9 = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                                                where
                                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                                  and m.id_born=$k
																								  and m.selected in (0,1)
                                                                                                  and h.old=0
                                                                                                  and h.end_date >= CURRENT_DATE
                                                                                                  and h.OLD=0
                                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                  and h.guruh in(1,2,3)
																								  and h.id_sabab=9 ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $baseRow, $sabab9[0]->count);
            $sabab10 = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                                                where
                                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                                  and m.id_born=$k
																								  and m.selected in (0,1)
                                                                                                  and h.old=0
                                                                                                  and h.end_date >= CURRENT_DATE
                                                                                                  and h.OLD=0
                                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                  and h.guruh in(1,2,3)
																								  and h.id_sabab=10 ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $baseRow, $sabab10[0]->count);
            $sabab11 = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                                                where
                                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                                  and m.id_born=$k
																								  and m.selected in (0,1)
                                                                                                  and h.old=0
                                                                                                  and h.end_date >= CURRENT_DATE
                                                                                                  and h.OLD=0
                                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                  and h.guruh in(1,2,3)
																								  and h.id_sabab=11 ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $baseRow, $sabab11[0]->count);
            $sabab12 = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                                                where
                                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                                  and m.id_born=$k
																								  and m.selected in (0,1)
                                                                                                  and h.old=0
                                                                                                  and h.end_date >= CURRENT_DATE
                                                                                                  and h.OLD=0
                                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                  and h.guruh in(1,2,3)
																								  and h.id_sabab=12  ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $baseRow, $sabab12[0]->count);
            $sabab13 = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                                                where
                                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                                  and m.id_born=$k
																								  and m.selected in (0,1)
                                                                                                  and h.old=0
                                                                                                  and h.end_date >= CURRENT_DATE
                                                                                                  and h.OLD=0
                                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                  and h.guruh in(1,2,3)
																								  and h.id_sabab=13 ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $baseRow, $sabab13[0]->count);
            $sabab14 = $this->db->query("select count(*) from mijoz m
                                                                      inner join history h On h.id_mijoz=m.id
                                                                                                where
                                                                                                  m.id_depart in ($iddep,$ddep)
                                                                                                  and m.id_born=$k
																								  and m.selected in (0,1)
                                                                                                  and h.old=0
                                                                                                  and h.end_date >= CURRENT_DATE
                                                                                                  and h.OLD=0
                                                                                                  and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                                  and h.guruh in(1,2,3)
																								  and h.id_sabab=14 ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $baseRow, $sabab14[0]->count);


        }

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function b5()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';
        $objPHPExcel = PHPExcel_IOFactory::load("templates/b5.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $iddep = $this->session->userdata("iddep");
        $monitoring = $this->session->userdata['admin_type'];
        $checktype = $_POST["id_checktype"];

        if ($checktype == 0) {
            $checktype_sql = "(1,2)";
        } else {
            $checktype_sql = "($checktype)";
        }

        //        $period = $_POST["period"];


        $par_id_depart = $this->db->query("select par_id from spr_depart where id=$iddep")->result();
        $par_iddep = $par_id_depart[0]->par_id;


        if ($par_iddep == 2) {
            if ($monitoring == 4) {
                $dep = $this->db->query("select id from spr_depart where id=$iddep or par_id=$iddep")->result_array();
            }
            else
            {
                $dep = $this->db->query("select id from spr_depart where id=$iddep")->result_array();
            }
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


        $rayon = $this->db->query("select id , name_uz from spr_region srr
                                                                    where
                                                                    srr.par_id in (select dr.id_region from depart_role dr    left join spr_region sr on dr.id_region=sr.id
                                                                         where dr.id_depart=$iddep)
                                                                 or srr.id in (select dr.id_region from depart_role dr       left join spr_region sr on dr.id_region=sr.id
                                                                     where dr.id_depart=$iddep) order by srr.id")->result();

        //print_r($rayon); exit;
        $i = 1;
        $baseRow = 8;
        foreach ($rayon as $row) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($baseRow, 1);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $baseRow, $i);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $baseRow, $row->name_uz);
            $i++;
            $k = $row->id;
            $jami_guruh = $this->db->query("select  count(m.id) from mijoz m
                                                                                          inner join history h On h.id_mijoz=m.id
                                                                                          where
                                                                                          m.id_depart in ($iddep,$ddep)
                                                                                          and m.id_born=$k
																						  and m.selected in (0,1)
                                                                                          and h.old=0
                                                                                          and h.end_date >= CURRENT_DATE
                                                                                          and h.OLD=0
                                                                                          and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                          and h.id_checktype in {$checktype_sql}
                                                                                          and h.guruh in(1,2,3)
                                                                            ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $baseRow, $jami_guruh[0]->count);
            $bir_guruh = $this->db->query("select  count(m.id) from mijoz m
                                                                                          inner join history h On h.id_mijoz=m.id
                                                                                          where
                                                                                          m.id_depart in ($iddep,$ddep)
                                                                                          and m.id_born=$k
																						  and m.selected in (0,1)
                                                                                          and h.old=0
                                                                                          and h.end_date >= CURRENT_DATE
                                                                                          and h.OLD=0
                                                                                          and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                          and h.id_checktype in {$checktype_sql}
                                                                                          and h.guruh in(1)
                                                                            ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $baseRow, $bir_guruh[0]->count);
            if ($jami_guruh[0]->count == 0) {
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $baseRow, 0);
            }
            else {
                $foiz_1 = round((($bir_guruh[0]->count) / ($jami_guruh[0]->count)) * 100);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $baseRow, $foiz_1);
            }


            $ikki_guruh = $this->db->query("select  count(m.id) from mijoz m
                                                                                          inner join history h On h.id_mijoz=m.id
                                                                                          where
                                                                                          m.id_depart in ($iddep,$ddep)
                                                                                          and m.id_born=$k
																						  and m.selected in (0,1)
                                                                                          and h.old=0
                                                                                          and h.end_date >= CURRENT_DATE
                                                                                          and h.OLD=0
                                                                                          and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                          and h.id_checktype in {$checktype_sql}
                                                                                          and h.guruh in(2)
                                                                            ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $baseRow, $ikki_guruh[0]->count);
            if ($jami_guruh[0]->count == 0) {
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $baseRow, 0);
            } else {
                $foiz_2 = round((($ikki_guruh[0]->count) / ($jami_guruh[0]->count)) * 100);
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $baseRow, $foiz_2);
            }

            $uch_guruh = $this->db->query("select  count(m.id) from mijoz m
                                                                                          inner join history h On h.id_mijoz=m.id
                                                                                          where
                                                                                          m.id_depart in ($iddep,$ddep)
                                                                                          and m.id_born=$k
																						  and m.selected in (0,1)
                                                                                          and h.old=0
                                                                                          and h.end_date >= CURRENT_DATE
                                                                                          and h.OLD=0
                                                                                          and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                          and h.id_checktype in {$checktype_sql}
                                                                                          and h.guruh in(3)
                                                                            ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $baseRow, $uch_guruh[0]->count);
            if ($jami_guruh[0]->count == 0) {
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $baseRow, 0);
            }
            else {
                $foiz_3 = round((($uch_guruh[0]->count) / ($jami_guruh[0]->count)) * 100);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $baseRow, $foiz_3);
            }

        }

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=b5.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

}