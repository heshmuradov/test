<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Respvilstat extends MyController
{
    function Respvilstat()
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
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $objPHPExcel = PHPExcel_IOFactory::load("templates/report1.xls");
        $objPHPExcel->setActiveSheetIndex(0);

            $rayon = $this->db->query("select id , name_uz from spr_region srr where srr.par_id=0    order by srr.id")->result();

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
																								and m.selected in (0,1)
                                                                                                and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                and m.id_depart not in (0,1,2)
                                                                                                and h.id_royhat in (1,2,3,4,5,6,10,11,12)
																								and h.guruh in (1,2,3)
                                                                                                group by h.guruh order by h.guruh")->result();
            $usoni = $this->db->query("select   count(*) as count from mijoz m
                                                                                                left join history h On m.id=h.id_mijoz
                                                                                                where h.OLD=0
																								and m.selected in (0,1)
                                                                                                and m.id_depart not in (0,1,2)
                                                                                                and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                and h.id_royhat in (1,2,3,4,5,6,10,11,12)
																								and h.guruh in (1,2,3)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $baseRow, $usoni[0]->count);
            if (empty($guruh[0]->count)) $guruh[0]->count = 0;
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $baseRow, $guruh[0]->count);
            if (empty($guruh[1]->count)) $guruh[1]->count = 0;
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $baseRow, $guruh[1]->count);
            if (empty($guruh[2]->count)) $guruh[2]->count = 0;
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $baseRow, $guruh[2]->count);

            $sabab1 = $this->db->query("select count(*) as count from history h
                                                                                    left join mijoz m on m.id=h.id_mijoz
                                                                                                where  h.OLD=0
																								and m.selected in (0,1)
                                                                                                and m.id_depart not in (0,1,2)
                                                                                                and h.id_royhat in (1,2,3,4,5,6,10,11,12)
																								and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                and h.id_sabab=1")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $baseRow, $sabab1[0]->count);
            $sabab2 = $this->db->query("select count(*) as count from history h
                                                                                    left join mijoz m on m.id=h.id_mijoz
                                                                                                where  h.OLD=0
																								and m.selected in (0,1)
                                                                                                and m.id_depart not in (0,1,2)
                                                                                                and h.id_royhat in (1,2,3,4,5,6,10,11,12)
																								and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                and h.id_sabab=2")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $baseRow, $sabab2[0]->count);
            $sabab3 = $this->db->query("select count(*) as count from history h
                                                                                    left join mijoz m on m.id=h.id_mijoz
                                                                                                where  h.id_royhat in (1,2,3,4,5,6,10,11,12)
                                                                                                and m.id_depart not in (0,1,2)
                                                                                                and h.OLD=0
																								and m.selected in (0,1)
																								and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                and h.id_sabab=3")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $baseRow, $sabab3[0]->count);
            $sabab4 = $this->db->query("select count(*) as count from history h
                                                                                    left join mijoz m on m.id=h.id_mijoz
                                                                                                where  h.OLD=0
																								and m.selected in (0,1)
                                                                                                 and m.id_depart not in (0,1,2)
                                                                                                 and h.id_royhat in (1,2,3,4,5,6,10,11,12)
																								 and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                  and h.id_sabab=4")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $baseRow, $sabab4[0]->count);
            $sabab5 = $this->db->query("select count(*) as count from history h
                                                                                    left join mijoz m on m.id=h.id_mijoz
                                                                                                where h.OLD=0
																								and m.selected in (0,1)
                                                                                                 and m.id_depart not in (0,1,2)
                                                                                                 and h.id_royhat in (1,2,3,4,5,6,10,11,12)
																								 and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                  and h.id_sabab=5")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $baseRow, $sabab5[0]->count);
            $sabab6 = $this->db->query("select count(*) as count from history h
                                                                                    left join mijoz m on m.id=h.id_mijoz
                                                                                                where h.OLD=0
																								and m.selected in (0,1)
                                                                                                 and m.id_depart not in (0,1,2)
                                                                                                 and h.id_royhat in (1,2,3,4,5,6,10,11,12)
																								  and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                  and h.id_sabab=6")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $baseRow, $sabab6[0]->count);
            $sabab7 = $this->db->query("select count(*) as count from history h
                                                                                    left join mijoz m on m.id=h.id_mijoz
                                                                                                where  h.OLD=0
																								and m.selected in (0,1)
                                                                                                 and m.id_depart not in (0,1,2)
                                                                                                 and h.id_royhat in (1,2,3,4,5,6,10,11,12)
																								 and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                  and h.id_sabab=7")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $baseRow, $sabab7[0]->count);
            $sabab8 = $this->db->query("select count(*) as count from history h
                                                                                    left join mijoz m on m.id=h.id_mijoz
                                                                                                where  h.OLD=0
																								and m.selected in (0,1)
                                                                                                 and m.id_depart not in (0,1,2)
                                                                                                 and h.id_royhat in (1,2,3,4,5,6,10,11,12)
																								 and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                  and h.id_sabab=8")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $baseRow, $sabab8[0]->count);
            $sabab9 = $this->db->query("select count(*) as count from history h
                                                                                    left join mijoz m on m.id=h.id_mijoz
                                                                                                where  h.OLD=0
																								and m.selected in (0,1)
                                                                                                 and m.id_depart not in (0,1,2)
                                                                                                 and h.id_royhat in (1,2,3,4,5,6,10,11,12)
																								 and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                  and h.id_sabab=9")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $baseRow, $sabab9[0]->count);
            $sabab10 = $this->db->query("select count(*) as count from history h
                                                                                    left join mijoz m on m.id=h.id_mijoz
                                                                                                where h.OLD=0
																								and m.selected in (0,1)
                                                                                                 and m.id_depart not in (0,1,2)
                                                                                                 and h.id_royhat in (1,2,3,4,5,6,10,11,12)
																								 and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                  and h.id_sabab=10")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $baseRow, $sabab10[0]->count);
            $sabab11 = $this->db->query("select count(*) as count from history h
                                                                                    left join mijoz m on m.id=h.id_mijoz
                                                                                                where  h.OLD=0
																								and m.selected in (0,1)
                                                                                                 and m.id_depart not in (0,1,2)
                                                                                                 and h.id_royhat in (1,2,3,4,5,6,10,11,12)
																								 and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                  and h.id_sabab=11")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $baseRow, $sabab11[0]->count);
            $sabab12 = $this->db->query("select count(*) as count from history h
                                                                                    left join mijoz m on m.id=h.id_mijoz
                                                                                                where  h.OLD=0
																								and m.selected in (0,1)
                                                                                                 and m.id_depart not in (0,1,2)
                                                                                                 and h.id_royhat in (1,2,3,4,5,6,10,11,12)
																								  and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                  and h.id_sabab=12")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $baseRow, $sabab12[0]->count);
            $sabab13 = $this->db->query("select count(*) as count from history h
                                                                                    left join mijoz m on m.id=h.id_mijoz
                                                                                                where  h.OLD=0
																								and m.selected in (0,1)
                                                                                                 and m.id_depart not in (0,1,2)
                                                                                                 and h.id_royhat in (1,2,3,4,5,6,10,11,12)
																								  and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                  and h.id_sabab=13")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('S' . $baseRow, $sabab13[0]->count);
            $sabab14 = $this->db->query("select count(*) as count from history h
                                                                                    left join mijoz m on m.id=h.id_mijoz
                                                                                                where  h.OLD=0
																								and m.selected in (0,1)
                                                                                                 and m.id_depart not in (0,1,2)
                                                                                                 and h.id_royhat in (1,2,3,4,5,6,10,11,12)
																								  and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                  and h.id_sabab=14")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('T' . $baseRow, $sabab14[0]->count);

            $muddatsiz = $this->db->query("select count(*) from history h
                                                                                           left join mijoz m on h.id_mijoz=m.id
                                                                                           where m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                           and m.id_depart not in (0,1,2)
																						   and m.selected in (0,1)
                                                                                           and h.id_royhat in (1,2,3,4,5,6,10,11,12)
                                                                                           and h.old=0
                                                                                           and h.end_date ='2099-01-01'")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $baseRow, $muddatsiz[0]->count);

             $umtsoni = $this->db->query("select   count(*) as count from mijoz m
                                                                                                left join history h On m.id=h.id_mijoz
                                                                                                where h.OLD=0
                                                                                                and m.id_depart not in (0,1,2)
																								and m.selected in (0,1)
                                                                                                and m.id_born in (SELECT id FROM spr_region sr WHERE sr.par_id=$k)
                                                                                                and h.id_royhat in (1,2,3,4,5,6,10,11,12)
                                                                                                and h.end_date < CURRENT_DATE 
																								and h.guruh in (1,2,3)")->result();
             $objPHPExcel->getActiveSheet()->setCellValue('V' . $baseRow, $umtsoni[0]->count);
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