<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Stat extends MyController
{
    function Stat()
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
        $data['region'] = $this->db->query("select * from spr_region sr where sr.par_id=0 order by id")->result();
        $this->load->view("header_index");
        $this->load->view("/reports/stat", $data);
        }
        else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function statreport()
    {
        include  'excel/Classes/PHPExcel.php';
        include 'excel/Classes/PHPExcel/IOFactory.php';

        //        $objPHPExcel = new PHPExcel();
        $objPHPExcel = PHPExcel_IOFactory::load("templates/report1.xls");
        $objPHPExcel->setActiveSheetIndex(0);

        $iddep = $this->session->userdata("iddep");
        $is_mon = $this->session->userdata['admin_type'];
        $dep_name = $this->db->query("select name_uz from spr_depart where id=$iddep")->result();
        $objPHPExcel->getActiveSheet()->setCellValue('D' . 2, $dep_name[0]->name_uz);

        $ddep = '';
        if ($is_mon == 3) {
            $id_region = $_POST["id_region"];
            $rayon = $this->db->query("select id, name_uz from spr_region where par_id=$id_region order by id")->result();
            $dep = $this->db->query("select id from spr_depart where id not in (0,1,2) order by id");
            $dep = $dep->result_array();
            $ddep = array();
            foreach ($dep as $item):
                $ddep[] = $item['id'];
            endforeach;
            $ddep = implode(", ", $ddep);
            $depart_sql = "m.id_depart in ($ddep)";

        } elseif ($is_mon == 4) {
            $dep = $this->db->query("select id from spr_depart where par_id=$iddep or id=$iddep order by id");
            $dep = $dep->result_array();
            $ddep = array();
            foreach ($dep as $item):
                $ddep[] = $item['id'];
            endforeach;
            $ddep = implode(", ", $ddep);
            $dep_sql = "dr.id_depart in ($ddep)";
            $depart_sql = "m.id_depart in ($ddep)";
            $rayon = $this->db->query("select id , name_uz from spr_region srr
                                                                    where
                                                                    srr.id in (select dr.id_region from depart_role dr
                                                                        left join spr_region sr on dr.id_region=sr.id
                                                                        where {$dep_sql})
                                                                        or srr.par_id in (select dr.id_region from depart_role dr
                                                                        left join spr_region sr on dr.id_region=sr.id
                                                                        where {$dep_sql})
                                                                        order by srr.id ")->result();
        } else {
            $ddep = $iddep;
            $dep_sql = "dr.id_depart in ($ddep)";
            $depart_sql = "m.id_depart in ($ddep)";
            $rayon = $this->db->query("select id , name_uz from spr_region srr
                                                                    where
                                                                    srr.id in (select dr.id_region from depart_role dr
                                                                        left join spr_region sr on dr.id_region=sr.id
                                                                        where {$dep_sql})
                                                                        or srr.par_id in (select dr.id_region from depart_role dr
                                                                        left join spr_region sr on dr.id_region=sr.id
                                                                        where {$dep_sql})
                                                                        order by srr.id ")->result();
        }
        #############################################################################

        $baseRow = 26;
        foreach ($rayon as $row) {
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($baseRow, 1);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $baseRow, $row->name_uz);
            $k = $row->id;
            $guruh0 = $this->db->query("select   count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where {$depart_sql}
                                                            and h.OLD=0
                                                            and h.approve=1
															and m.selected in (0,1)
                                                            and m.id_born=$k
                                                            and m.move=0
                                                            and h.id_royhat in (1,3,4,5,6,11,12)
                                                            and h.guruh in (0)
                                                            and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $baseRow, $guruh0[0]->count);
            $guruh1 = $this->db->query("select   count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where {$depart_sql}
                                                            and h.OLD=0
                                                            and h.approve=1
															and m.selected in (0,1)
                                                            and m.id_born=$k
                                                            and m.move=0
                                                            and h.id_royhat in (1,3,4,5,6,11,12)
                                                            and h.guruh in (1)
                                                            and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $baseRow, $guruh1[0]->count);
            $guruh2 = $this->db->query("select   count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where {$depart_sql}
                                                            and h.OLD=0
                                                            and h.approve=1
															and m.selected in (0,1)
                                                            and m.id_born=$k
                                                            and m.move=0
                                                            and h.id_royhat in (1,3,4,5,6,11,12)
                                                            and h.guruh in (2)
                                                            and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $baseRow, $guruh2[0]->count);
            $guruh3 = $this->db->query("select   count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where {$depart_sql}
                                                            and h.OLD=0
                                                            and h.approve=1
															and m.selected in (0,1)
                                                            and m.id_born=$k
                                                            and m.move=0
                                                            and h.id_royhat in (1,3,4,5,6,11,12)
                                                            and h.guruh in (3)
                                                            and h.end_date >=CURRENT_DATE")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $baseRow, $guruh3[0]->count);

            $usoni = $this->db->query("select   count(*) as count from mijoz m
                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                            where {$depart_sql}
                                                            and h.OLD=0
                                                            and h.approve=1
															and m.selected in (0,1)
                                                            and m.id_born=$k
                                                            and m.move=0
                                                            and h.id_royhat in (1,3,4,5,6,11,12)
                                                            and h.end_date >=CURRENT_DATE
															and h.guruh in (0,1,2,3)")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $baseRow, $usoni[0]->count);

            $sabab0 = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where   {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date >=CURRENT_DATE
																	   and h.id_sabab=0 ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $baseRow, $sabab0[0]->count);
            $sabab1 = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where  {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date >=CURRENT_DATE
																	   and h.id_sabab=1 ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $baseRow, $sabab1[0]->count);
            $sabab2 = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where   {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date >=CURRENT_DATE
																	   and h.id_sabab=2")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $baseRow, $sabab2[0]->count);
            $sabab3 = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date >=CURRENT_DATE
																	   and h.id_sabab=3")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $baseRow, $sabab3[0]->count);
            $sabab4 = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date >=CURRENT_DATE
																	   and h.id_sabab=4")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $baseRow, $sabab4[0]->count);
            $sabab5 = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date >=CURRENT_DATE
																	   and h.id_sabab=5")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $baseRow, $sabab5[0]->count);
            $sabab6 = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date >=CURRENT_DATE
																	   and h.id_sabab=6")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $baseRow, $sabab6[0]->count);
            $sabab7 = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date >=CURRENT_DATE
																	   and h.id_sabab=7")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $baseRow, $sabab7[0]->count);
            $sabab8 = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date >=CURRENT_DATE
																	   and h.id_sabab=8")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $baseRow, $sabab8[0]->count);
            $sabab9 = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date >=CURRENT_DATE
																	   and h.id_sabab=9")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $baseRow, $sabab9[0]->count);
            $sabab10 = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date >=CURRENT_DATE
																	   and h.id_sabab=10")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $baseRow, $sabab10[0]->count);
            $sabab11 = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where  {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date >=CURRENT_DATE
																	   and h.id_sabab=11")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('S' . $baseRow, $sabab11[0]->count);
            $sabab12 = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date >=CURRENT_DATE
																	   and h.id_sabab=12")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('T' . $baseRow, $sabab12[0]->count);
            $sabab13 = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where  {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date >=CURRENT_DATE
																	   and h.id_sabab=13")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $baseRow, $sabab13[0]->count);
            $sabab14 = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where  {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date >=CURRENT_DATE
																	   and h.id_sabab=14")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('V' . $baseRow, $sabab14[0]->count);

            $muddatsiz = $this->db->query("select count(*) as count from mijoz m
                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
                                                                    where  {$depart_sql}
                                                                       and m.id_born=$k
                                                                       AND h.OLD=0
                                                                       and h.approve=1
																	   and m.selected in (0,1)
                                                                       and m.move=0
                                                                       and h.id_royhat in (1,3,4,5,6,11,12)
                                                                       and h.end_date = '2099-01-01' ")->result();
            $objPHPExcel->getActiveSheet()->setCellValue('W' . $baseRow, $muddatsiz[0]->count);

//            $umtsoni = $this->db->query("select count(*) as count from mijoz m
//                                                             left join mijoz_ill_history h on m.id=h.id_mijoz
//                                                                    where   m.id_depart in ($iddep,$ddep)
//                                                                       and m.id_born=$k
//                                                                       AND h.OLD=0
//                                                                       and h.approve=1
//                                                                       and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
//                                                                       and h.end_date >= CURRENT_DATE ")->result();
//             $objPHPExcel->getActiveSheet()->setCellValue('V' . $baseRow, $umtsoni[0]->count);
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