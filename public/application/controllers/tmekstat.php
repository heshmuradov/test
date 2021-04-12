<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Tmekstat extends MyController
{
    function Tmekstat()
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
        $data['id_depart'] = $this->db->query("select * from spr_depart where id not in (0,1,2) order by id")->result();
        $this->load->view("header_index");
        $this->load->view("/reports/tmekstat", $data);
        }
        else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function tmekreport()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        //        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load("templates/report2.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        // $iddep = $this->session->userdata("iddep");

        $iddep = $this->session->userdata("iddep");
        $dep_name = $this->db->query("select name_uz from spr_depart where id=$iddep")->result();
        $objPHPExcel->getActiveSheet()->setCellValue('F' . 2, $dep_name[0]->name_uz);

        $is_mon = $this->session->userdata['admin_type'];
        if ($is_mon == 3) {
            $id_depart = $_POST["id_depart"];
            //  print_r($id_depart);
            $par_id_dep = $this->db->query("select par_id from spr_depart sd where sd.id=$id_depart")->result();
            // print_r($par_id_dep);exit;
            if ($par_id_dep[0]->par_id == 2) {

                $dep = $this->db->query("select id from spr_depart where par_id=$id_depart or id=$id_depart order by id");
                $dep = $dep->result_array();
                $ddep = array();
                foreach ($dep as $item):
                    $ddep[] = $item['id'];
                endforeach;
                $ddep = implode(", ", $ddep);

            } elseif ($par_id_dep[0]->par_id == 1) {
                $dep = $this->db->query("select id from spr_depart where id not in (0,1,2) order by id");
                $dep = $dep->result_array();
                $ddep = array();
                foreach ($dep as $item):
                    $ddep[] = $item['id'];
                endforeach;
                $ddep = implode(", ", $ddep);
            } else {
                $ddep = $id_depart;
            }
        } elseif ($is_mon == 4) {

            $dep = $this->db->query("select id from spr_depart where par_id=$iddep or id=$iddep order by id");
            $dep = $dep->result_array();
            $ddep = array();
            foreach ($dep as $item):
                $ddep[] = $item['id'];
            endforeach;
            $ddep = implode(", ", $ddep);
        } else {
            $ddep = $iddep;
        }
//        if ($this->session->userdata['admin_type'] == 3) {
//            $dep = $this->db->query("select id from spr_depart where id not in (0,1,2,3)");
//            $dep = $dep->result_array();
//            $ddep = array();
//            foreach ($dep as $item):
//                $ddep[] = $item['id'];
//            endforeach;
//

        $depart_sql = "sd.id in ($ddep)";
        //   print_r($depart_sql);exit;
        $department = $this->db->query("select id , name_uz from spr_depart sd   where {$depart_sql} order by sd.id")->result();
        $baseRow = 26;
        foreach ($department as $row) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($baseRow, 1);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $baseRow, $row->name_uz);
            $k = $row->id;
            $guruh0 = $this->db->query("select   count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h On m.id=h.id_mijoz
                                                                   where m.id_depart = $k
                                                                   and h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
                                                                   and h.guruh in (0)
                                                                   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $baseRow, $guruh0[0]->count);
            $guruh1 = $this->db->query("select   count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h On m.id=h.id_mijoz
                                                                   where m.id_depart = $k
                                                                   and h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
                                                                   and h.guruh in (1)
                                                                   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $baseRow, $guruh1[0]->count);
            $guruh2 = $this->db->query("select   count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h On m.id=h.id_mijoz
                                                                   where m.id_depart = $k
                                                                   and h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
                                                                   and h.guruh in (2)
                                                                   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $baseRow, $guruh2[0]->count);
            $guruh3 = $this->db->query("select   count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h On m.id=h.id_mijoz
                                                                   where m.id_depart = $k
                                                                   and h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
                                                                   and h.guruh in (3)
                                                                   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $baseRow, $guruh3[0]->count);

            $usoni = $this->db->query("select   count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h On m.id=h.id_mijoz
                                                                   where  m.id_depart = $k
                                                                   and h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.guruh in (0,1,2,3)
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $baseRow, $usoni[0]->count);


            $sabab0 = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.id_sabab=0
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $baseRow, $sabab0[0]->count);
            $sabab1 = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.id_sabab=1
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $baseRow, $sabab1[0]->count);
            $sabab2 = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.id_sabab=2
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $baseRow, $sabab2[0]->count);
            $sabab3 = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.id_sabab=3
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $baseRow, $sabab3[0]->count);
            $sabab4 = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.id_sabab=4
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $baseRow, $sabab4[0]->count);
            $sabab5 = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.id_sabab=5
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $baseRow, $sabab5[0]->count);
            $sabab6 = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.id_sabab=6
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $baseRow, $sabab6[0]->count);
            $sabab7 = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.id_sabab=7
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $baseRow, $sabab7[0]->count);
            $sabab8 = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.id_sabab=8
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $baseRow, $sabab8[0]->count);
            $sabab9 = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.id_sabab=9
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $baseRow, $sabab9[0]->count);
            $sabab10 = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.id_sabab=10
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $baseRow, $sabab10[0]->count);
            $sabab11 = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.id_sabab=11
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('S' . $baseRow, $sabab11[0]->count);
            $sabab12 = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.id_sabab=12
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('T' . $baseRow, $sabab12[0]->count);
            $sabab13 = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.id_sabab=13
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $baseRow, $sabab13[0]->count);
            $sabab14 = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.id_sabab=14
																   and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('V' . $baseRow, $sabab14[0]->count);

            $muddatsiz = $this->db->query("select count(*) as count from mijoz m
                                                                   left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                   where   m.id_depart  = $k
                                                                   AND h.OLD=0
                                                                   and h.approve=1
																   and m.selected in (0,1)
                                                                   and m.move=0
                                                                   and h.id_royhat in (1,3,4,5,6,11,12)
																   and h.end_date='2099-01-01'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('W' . $baseRow, $muddatsiz[0]->count);

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

}