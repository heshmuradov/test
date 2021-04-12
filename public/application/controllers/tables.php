<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Tables extends MyController
{
    function Tables()
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

        $data['u_mijoz'] = $this->db->query("select m.gender, sg.name_uz as gender,  count(*) as count from mijoz m
                                                                                            left join history h On m.id=h.id_mijoz
                                                                                            left join spr_gender sg on sg.id=m.gender
                                                                                            where m.id_depart=$iddep
                                                                                            and h.OLD=0
																							and m.selected in (0,1)
                                                                                            group by m.gender, sg.name_uz")->result();
        $data['guruh'] = $this->db->query(" select h.guruh, count(*) as count  from mijoz m
                                                                                                   left join history h On m.id=h.id_mijoz
                                                                                                   where m.id_depart=$iddep
                                                                                                   and h.OLD=0
																								   and m.selected in (0,1)
                                                                                                   group by  h.guruh order by h.guruh ASC ")->result();
        $data['e_guruh'] = $this->db->query(" select h.guruh, count(*) as count  from mijoz m
                                                                                                   left join history h On m.id=h.id_mijoz
                                                                                                   where m.id_depart=$iddep
                                                                                                   and m.gender=1
                                                                                                   and h.OLD=0
																								   and m.selected in (0,1)
                                                                                                   group by  h.guruh order by h.guruh ASC ")->result();
        $data['a_guruh'] = $this->db->query(" select h.guruh, count(*) as count  from mijoz m
                                                                                                   left join history h On m.id=h.id_mijoz
                                                                                                   where m.id_depart=$iddep
                                                                                                   and m.gender=2
                                                                                                   and h.OLD=0
																								   and m.selected in (0,1)
                                                                                                   group by  h.guruh order by h.guruh ASC ")->result();

        $data['g_mijoz'] = $this->db->query(" select sg.id,  sg.name_uz, h.guruh, count(*) from mijoz m
                                                                                           left join history h On m.id=h.id_mijoz
                                                                                           left join spr_gender sg on m.gender=sg.id
                                                                                           where m.id_depart=$iddep
                                                                                           and h.OLD=0
																						   and m.selected in (0,1)
                                                                                           group by sg.id, sg.name_uz, h.guruh order by h.guruh,sg.id ASC ")->result();

        $this->load->view("header_index");
        $this->load->view("/tables/table", $data);
    }

    function j1()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/jadval/01jadval.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function j2()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/jadval/02jadval.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function j4()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/jadval/04jadval.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function j5()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/jadval/05jadval.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function j7()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/jadval/07jadval.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function j8()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/jadval/08jadval.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function j10()
    {
         include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/jadval/10jadval.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function j11()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/jadval/11jadval.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function j12()
    {
       include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/jadval/12jadval.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function j13()
    {
         include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/jadval/13jadval.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function j14()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/jadval/14jadval.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function j15()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/jadval/15jadval.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function j16()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/jadval/16jadval.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function j17()
    {
         include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/jadval/17jadval.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report.xls");
        header("Pragma: no-cache");
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    function j18()
    {
       include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        $iddep = $this->session->userdata("iddep");

        $objPHPExcel = PHPExcel_IOFactory::load("templates/jadval/18jadval.xls");
        $objPHPExcel->setActiveSheetIndex(0);

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