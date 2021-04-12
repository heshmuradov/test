<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Obj extends MyController
{
    function Obj()
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


    function objective()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';
        require_once 'functions.php';

        $id_mijoz = $_REQUEST["oblist"];
        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/obj.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $depart = $this->db->query("SELECT name_uz FROM spr_depart WHERE id=$iddep")->result();
        $umalumot = $this->db->query("SELECT m.*, sg.name_uz as gender, sr.name_uz as region_name
                                                                             FROM mijoz m, spr_gender sg, spr_region sr
                                                                             WHERE m.id=$id_mijoz
                                                                             AND m.gender=sg.id
                                                                             AND m.id_born=sr.id")->result();
        $ustaj = $this->db->query("SELECT me.*, sm.name_uz as edu, sw.name_uz as workk
                                                                  FROM mijoz_edu me, spr_malumot sm, spr_work sw
                                                                  WHERE me.id_mijoz=$id_mijoz
                                                                  AND me.id_malumot=sm.id
                                                                  AND me.working=sw.id")->result();
        $res = $this->db->query("
            	SELECT
                    mih.id_mijoz, mih.beg_date, mih.end_date, mih.guruh, mih.seriya, mih.nomer,
            		ii.r code_10,
            		i.code_9,
            		r.name_uz royhat_name,
					c.name_uz checktype_name,
					sb.sabab_uz sabab_name
            	FROM mijoz_ill_history mih
            		LEFT JOIN spr_illness i ON i.id = mih.mkb_9
            		LEFT JOIN spr_illness_10  ii  ON  ii.row_num = mih.mkb_10
            		LEFT JOIN spr_royhat r ON r.id = mih.id_royhat
            		LEFT JOIN spr_checktype c ON c.id = mih.id_checktype
            		LEFT JOIN spr_sabab sb ON sb.id = mih.id_sabab
            	WHERE mih.id_mijoz=$id_mijoz
            	ORDER BY mih.beg_date
            ")->result();

        $date_birth = d($umalumot[0]->date_birth);
        if ($umalumot[0]->bn == 0) {
            $bn = "Йўқ";
        } else {
            $bn = "Ҳа";
        }

        //Ногирон ҳақидаги умумий маълумотларни чиқариш
        $objPHPExcel->getActiveSheet()->setCellValue('C2', $depart[0]->name_uz);
        $objPHPExcel->getActiveSheet()->setCellValue('C6', $umalumot[0]->familiya);
        $objPHPExcel->getActiveSheet()->setCellValue('C7', $umalumot[0]->name);
        $objPHPExcel->getActiveSheet()->setCellValue('C8', $umalumot[0]->middle);
        $objPHPExcel->getActiveSheet()->setCellValue('C9', $umalumot[0]->gender);
        $objPHPExcel->getActiveSheet()->setCellValue('C10', $bn);
        $objPHPExcel->getActiveSheet()->setCellValue('F6', $date_birth);
        $objPHPExcel->getActiveSheet()->setCellValue('F7', $umalumot[0]->region_name);
        $objPHPExcel->getActiveSheet()->setCellValue('F8', $umalumot[0]->address);
        $objPHPExcel->getActiveSheet()->setCellValue('F9', $umalumot[0]->pass_seriya);
        $objPHPExcel->getActiveSheet()->setCellValue('G9', $umalumot[0]->pass_code);
        //Ногирон ҳақидаги умумий маълумотларни чиқариш тугади
        //Ногироннинг  иш фаолияти маълумотларни чиқариш
        $objPHPExcel->getActiveSheet()->setCellValue('I6', $ustaj[0]->edu);
        $objPHPExcel->getActiveSheet()->setCellValue('I7', $ustaj[0]->spec);
        $objPHPExcel->getActiveSheet()->setCellValue('I8', $ustaj[0]->work);
        $objPHPExcel->getActiveSheet()->setCellValue('I9', $ustaj[0]->staj);
        $objPHPExcel->getActiveSheet()->setCellValue('I10', $ustaj[0]->workk);
        //Ногироннинг  иш фаолияти маълумотларни чиқариш тугади
        //Ногироннинг  Касаллик тарихи
        $baseRow = 15;
        foreach ($res as $history) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($baseRow, 1);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $baseRow, $history->checktype_name);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $baseRow, d($history->beg_date));
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $baseRow, d($history->end_date));
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $baseRow, $history->code_9);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $baseRow, $history->code_10);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $baseRow, $history->guruh);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $baseRow, $history->sabab_name);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $baseRow, $history->royhat_name);
            //$objPHPExcel->getActiveSheet()->setCellValue('J' . $baseRow, $history->t.'   '.$history->asos);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $baseRow, $history->seriya . '   ' . $history->nomer);
        }
        //Ногироннинг  Касаллик тарихи тугади


        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=obj.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function sprtoexcel()
    {
        $data["excel_export"] = $this->db->query("SELECT * FROM spr_spseriya")->result();
        $this->load->view("/reports/rtser", $data);

    }

    function passtoexcel()
    {
        $data["excel_export"] = $this->db->query("SELECT * FROM spr_pass_seriya")->result();
        $this->load->view("/reports/rtpser", $data);

    }

}
