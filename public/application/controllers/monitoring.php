<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');
require_once('passpartDataService.php');

class Monitoring extends MyController
{
    function Monitoring()
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

    function getPersonData(){
      $responce = getPersonalData($_POST['passSerie'], $_POST['pinfl']);
      echo json_encode($responce);
    }

    function index()
    {
        if (($this->session->userdata['admin_type'] != 3) && ($this->session->userdata['admin_type'] != 5)) {
            $data = array();
            $data['menus'] = $this->_build_menu();
            $iddep = $this->session->userdata("iddep");
            if ($this->session->userdata['admin_type'] == 4) {
                $dep = $this->db->query("select id from spr_depart where par_id=$iddep");
                $dep = $dep->result_array();
                $ddep = array();
                foreach ($dep as $item):
                    $ddep[] = $item['id'];
                endforeach;
            }
            if ($this->session->userdata['admin_type'] == 3) {
                $dep = $this->db->query("select id from spr_depart where id not in (0,1,2)");
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

            $par_id_dep = $this->db->query("select par_id from spr_depart where id=$iddep")->result();
            if ($par_id_dep[0]->par_id != 2) {
                $data['royhatuz'] = $this->db->query('SELECT * FROM spr_royhat sr where sr.id in (4,6,7,9)')->result();
            } else {
                $data['royhatuz'] = $this->db->query('SELECT * FROM spr_royhat sr')->result();
            }
            $data['royhatuz18'] = $this->db->query('SELECT * FROM spr_royhat sr where sr.id in (6, 14, 16, 17, 7)')->result();

            $data['id_depart'] = $this->db->query("select * from spr_depart where id in ($iddep, $ddep) order by id")->result();
            $data['region'] = $this->db->query('SELECT * FROM spr_region order by id')->result();
            $data['code9'] = $this->db->query('SELECT * FROM spr_illness order by id')->result();
            $data['checktype'] = $this->db->query('SELECT * FROM spr_checktype')->result();
            $data['sabab'] = $this->db->query('SELECT * FROM spr_sabab order by id')->result();
            $data['fmalumot'] = $this->db->query('SELECT * FROM spr_malumot')->result();
            $data['ortoped'] = $this->db->query('SELECT * FROM spr_ortoped')->result();
            $data['working'] = $this->db->query('SELECT * FROM spr_work')->result();
            $data['tm'] = $this->db->query('SELECT * FROM spr_status')->result();
            $data['code_ill_10'] = $this->db->query('select t.group_id, t.name_uz from  spr_illness_10 t where t.r_level=1 ORDER BY t.row_num ')->result();
            $data['kdaraja1'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=1 order by id')->result();
            $data['kdaraja2'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=2 order by id')->result();
            $data['kdaraja3'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=3 order by id')->result();
            $data['kdaraja4'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=4 order by id')->result();
            $data['kdaraja5'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=5 order by id')->result();
            $data['kdaraja6'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=6 order by id')->result();
            $data['kdaraja7'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=7 order by id')->result();
            $data['murojaat'] = $this->db->query("SELECT * FROM spr_murojaat_sabab")->result();
            $data['tmek_xulosasi'] = $this->db->query("SELECT * FROM spr_nogironlik_sabab")->result();
            $data['seriya'] = $this->db->query("SELECT * FROM spr_spseriya order by id")->result();
            $is_mon = $this->session->userdata['admin_type'];
            if ($is_mon == 2 or $is_mon == 4) {
                $data['fborn'] = $this->db->query("select * from spr_region sr
                        where sr.par_id in
                            (select s.id  FROM depart_role d, spr_region s
                            WHERE d.id_depart = $iddep
                            AND d.id_region = s.id )
                        union
                            select * from spr_region sr where sr.id in
                            (select s.id  FROM depart_role d, spr_region s WHERE d.id_depart = $iddep
                            AND d.id_region = s.id) and sr.par_id>0")->result();
            } else {
                $data['fborn'] = $this->db->query("select * from spr_region where id NOT IN (0) ORDER BY par_id ")->result();
            }


            $data['fplace'] = $this->db->query('SELECT * FROM spr_place')->result();
            $data['fpass_seriya'] = $this->db->query('SELECT * FROM spr_pass_seriya')->result();

            $mur = $this->db->query('SELECT * FROM spr_murojaat_sabab')->result();
            $data["murojaatgrid"] = "";
            foreach ($mur as $rw) {
                $data["murojaatgrid"] .= $rw->id . ":" . $rw->murojaat_sababi . ";";
            }
            $data["murojaatgrid"] = substr($data["murojaatgrid"], 0, strlen($data["murojaatgrid"]) - 1);

            $gender = $this->db->query('SELECT id, name_uz FROM spr_gender')->result();
            $data["sgen"] = "";
            foreach ($gender as $rw) {
                $data["sgen"] .= $rw->id . ":" . $rw->name_uz . ";";
            }
            $data["sgen"] = substr($data["sgen"], 0, strlen($data["sgen"]) - 1);

            $born = $this->db->query("select * from spr_region sr
                        where sr.par_id in
                            (select s.id  FROM depart_role d, spr_region s
                            WHERE d.id_depart = $iddep
                            AND d.id_region = s.id )
                        union
                            select * from spr_region sr where sr.id in
                            (select s.id  FROM depart_role d, spr_region s WHERE d.id_depart = $iddep
                            AND d.id_region = s.id) and sr.par_id>0")->result();
            $data["sborn"] = "";
            foreach ($born as $rw) {
                $data["sborn"] .= $rw->id . ":" . $rw->name_uz . ";";
            }
            $data["sborn"] = substr($data["sborn"], 0, strlen($data["sborn"]) - 1);

            $place = $this->db->query('SELECT * FROM spr_place')->result();

            $data["splace"] = "";
            foreach ($place as $rw) {
                $data["splace"] .= $rw->id . ":" . $rw->name_uz . ";";
            }
            $data["splace"] = substr($data["splace"], 0, strlen($data["splace"]) - 1);


            $ps = $this->db->query("SELECT * FROM spr_pass_seriya")->result();
            $data["pass_seriya"] = "";
            foreach ($ps as $rw) {
                $data["pass_seriya"] .= $rw->pass_seriya . ":" . $rw->pass_seriya . ";";
            }
            $data["pass_seriya"] = substr($data["pass_seriya"], 0, strlen($data["pass_seriya"]) - 1);

            $sm = $this->db->query("SELECT * FROM spr_malumot")->result();
            $data["malumot"] = "";
            foreach ($sm as $rw) {
                $data["malumot"] .= $rw->id . ":" . $rw->name_uz . ";";
            }
            $data["malumot"] = substr($data["malumot"], 0, strlen($data["malumot"]) - 1);

            $swork = $this->db->query("SELECT * FROM spr_work")->result();
            $data["work"] = "";
            foreach ($swork as $rw) {
                $data["work"] .= $rw->id . ":" . $rw->name_uz . ";";
            }
            $data["work"] = substr($data["work"], 0, strlen($data["work"]) - 1);

            $this->load->view("header_index");
            $this->load->view("/monitoring/monitoring", $data);
        } else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function kk()
    {
        $id_mijoz = isset($_POST['id_mijoz']) ? $_POST['id_mijoz'] : null;
        $move = $_POST['move'];
        $iddep = $this->session->userdata['iddep'];

        $this->db->trans_begin();

        $this->db->query("UPDATE mijoz SET move=$move WHERE id=$id_mijoz");
        $this->db->query("UPDATE mijoz_ill_history SET kk_id_depart=$iddep, kk_date=CURRENT_DATE
                                            WHERE id_mijoz=$id_mijoz AND old=0");

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

    }

    function search()
    {
        header("Content-type: text/x-json");

        try {
            $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
            $limit = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : 15;
            $sidx = isset($_REQUEST['sidx']) ? $_REQUEST['sidx'] : 'familiya';
            $sord = isset($_REQUEST['sord']) ? $_REQUEST['sord'] : 'asc';
            $iddep = ($this->session->userdata['iddep']);
            $is_mon = $this->session->userdata['admin_type'];

            $ufilter = "";
            $id = isset($_POST['fid']) ? $_POST['fid'] : null;
            $familiya = isset($_POST['ffamiliya']) ? $_POST['ffamiliya'] : null;
            $name = isset($_POST['fname']) ? $_POST['fname'] : null;
            $fagefrom = isset($_POST['fagefrom']) ? $_POST['fagefrom'] : null;
            $fagetill = isset($_POST['fagetill']) ? $_POST['fagetill'] : null;
            $date_birth = isset($_POST['fdate_birth']) ? $_POST['fdate_birth'] : null;
            $pass_code = isset($_POST['fpass_code']) ? $_POST['fpass_code'] : null;
            $pass_seriya = isset($_POST['fpass_seriya']) ? $_POST['fpass_seriya'] : null;
            $infl = isset($_POST['finfl']) ? $_POST['finfl'] : null;
            $gender = isset($_POST['fgender']) ? $_POST['fgender'] : null;
            $id_place = isset($_POST['fid_place']) ? $_POST['fid_place'] : null;
            $id_born = isset($_POST['fid_born']) ? $_POST['fid_born'] : null;
            $id_depart = isset($_POST['fid_depart']) ? $_POST['fid_depart'] : null;
            $fupload = isset($_POST['fupload']) ? $_POST['fupload'] : null;

            if (!empty($id)) {
                $ufilter = $ufilter . " and CAST(m.id AS TEXT) LIKE '%$id%' ";
            }
            if (!empty($familiya)) {
                $ufilter = $ufilter . " and upper(m.familiya) like upper('%$familiya%') ";
            }
            if (!empty($name)) {
                $ufilter = $ufilter . " and upper(m.name) like upper('%$name%') ";
            }
            if (!empty($fagefrom) || !empty($fagetill)) {
                if (!empty($fagefrom) && !empty($fagetill)) {
                    $ufilter = $ufilter . " and public.\"f_GetAge\"(CURRENT_DATE,m.date_birth) BETWEEN $fagefrom and $fagetill";
                } elseif (empty($fagetill)) {
                    $ufilter = $ufilter . " and public.\"f_GetAge\"(CURRENT_DATE,m.date_birth)>=$fagefrom";

                } elseif (empty($fagefrom)) {
                    $ufilter = $ufilter . " and public.\"f_GetAge\"(CURRENT_DATE,m.date_birth)<=$fagetill";

                }

            }
            if (!empty($pass_seriya)) {
                $ufilter = $ufilter . " and upper(m.pass_seriya) like upper('%$pass_seriya%') ";
            }
            if (!empty($pass_code)) {
              $ufilter = $ufilter . " and m.pass_code like '%$pass_code%' ";
            }
            if (strlen($infl) == 14) {
              $ufilter = $ufilter . " and m.infl like '%$infl%' ";
            }
            if (!empty($gender)) {
                $ufilter = $ufilter . " and m.gender=$gender";
            }
            if (!empty($id_place)) {
                $ufilter = $ufilter . " and m.id_place=$id_place";
            }
            if (!empty($id_born)) {
                if ($is_mon == 3) {
                    $id_parent = $this->db->query("select par_id from spr_region where id=$id_born")->result();
                    if ($id_parent[0]->par_id == 0) {
                        $ufilter = $ufilter . " and m.id_born in (select id from spr_region where par_id=$id_born)";
                    } else {
                        $ufilter = $ufilter . " and m.id_born=$id_born";
                    }
                } else {
                    $ufilter = $ufilter . " and m.id_born=$id_born";
                }
            }
            if (!empty($date_birth)) {
                $ufilter = $ufilter . " and m.date_birth like '%$date_birth%'";
            }
            if (!empty($fupload)) {
                $ufilter = $ufilter . " and m.id in (select id_mijoz from pdfoid pd where pd.file_type='$fupload')";
            }

            $wfilter = '';
            $wlfilter = '';
            $id_malumot = isset($_POST['fid_malumot']) ? $_POST['fid_malumot'] : null;
            $staj = isset($_POST['fstaj']) ? $_POST['fstaj'] : null;
            $working = isset($_POST['fworking']) ? $_POST['fworking'] : null;

            if (!empty($id_malumot)) {
                $wfilter = $wfilter . " and w.id_malumot=$id_malumot ";
            }
            if (!empty($staj)) {
                $wfilter = $wfilter . " and w.staj=$staj ";
            }
            if (!empty($working)) {
                $wfilter = $wfilter . " and w.working=$working ";
            }
            if (!empty($wfilter)) {
                $wlfilter = "LEFT JOIN mijoz_edu w on w.id_mijoz=m.id";
            }

            $hfilter = '';
            $hlfilter = '';
            $hofilter = '';
            $tmek_xulosasi = isset($_POST['ftmek_xulosasi']) ? $_POST['ftmek_xulosasi'] : null;
            $guruh = isset($_POST['fguruh']) ? $_POST['fguruh'] : null;
            $id_royhat = isset($_POST['fid_royhat']) ? $_POST['fid_royhat'] : null;
            $id_checktype = isset($_POST['fid_checktype']) ? $_POST['fid_checktype'] : null;
            $beg_date = isset($_POST['fbeg_date']) ? $_POST['fbeg_date'] : null;
            $beg_date_gacha = isset($_POST['fbeg_date_gacha']) ? $_POST['fbeg_date_gacha'] : null;
            $end_date = isset($_POST['fend_date']) ? $_POST['fend_date'] : null;
            $end_date_gacha = isset($_POST['fend_date_gacha']) ? $_POST['fend_date_gacha'] : null;
            $id_sabab = isset($_POST['fid_sabab']) ? $_POST['fid_sabab'] : null;
            $seriya = isset($_POST['fseriya']) ? $_POST['fseriya'] : null;
            $nomer = isset($_POST['fnomer']) ? $_POST['fnomer'] : null;
            $pdate = isset($_POST['fpdate']) ? $_POST['fpdate'] : null;
            $md = isset($_POST['fmd']) ? $_POST['fmd'] : null;
            $ekriteria_4= isset($_POST['ekriteria_4']) ? $_POST['ekriteria_4'] : null;
            $ekriteria_6= isset($_POST['ekriteria_6']) ? $_POST['ekriteria_6'] : null;
            $ekriteria_7= isset($_POST['ekriteria_7']) ? $_POST['ekriteria_7'] : null;

            if (!empty($tmek_xulosasi)) {
                $hfilter = $hfilter . " and h.tmek_xulosasi=$tmek_xulosasi";
            }

            if (!empty($guruh)) {
                $hfilter = $hfilter . " and h.guruh=$guruh";
            }

            if (!empty($beg_date) || !empty($beg_date_gacha)) {

                if (!empty($beg_date) && !empty($beg_date_gacha)) {
                    $hfilter = $hfilter . " and h.beg_date BETWEEN to_date('$beg_date', 'yyyy-mm-dd') and to_date('$beg_date_gacha', 'yyyy-mm-dd')";
                } elseif (empty($beg_date)) {
                    $hfilter = $hfilter . " and h.beg_date<=to_date('$beg_date_gacha', 'yyyy-mm-dd')";

                } elseif (empty($beg_date_gacha)) {
                    $hfilter = $hfilter . " and h.beg_date>=to_date('$beg_date', 'yyyy-mm-dd')";

                }
            }

            if (!empty($end_date) || !empty($end_date_gacha)) {
                if (!empty($end_date) && !empty($end_date_gacha)) {
                    $hfilter = $hfilter . " and h.end_date BETWEEN to_date('$end_date', 'yyyy-mm-dd') and to_date('$end_date_gacha', 'yyyy-mm-dd')";
                } elseif (empty($end_date)) {
                    $hfilter = $hfilter . " and h.end_date<=to_date('$end_date_gacha', 'yyyy-mm-dd')";

                } elseif (empty($end_date_gacha)) {
                    $hfilter = $hfilter . " and h.end_date>=to_date('$end_date', 'yyyy-mm-dd')";

                }

            }

            if (!empty($md)) {
                $hfilter = $hfilter . " and h.end_date='2099-01-01'";
            }

            if (!empty($id_sabab)) {
                $hfilter = $hfilter . " and h.id_sabab=$id_sabab";
            }

            if (!empty($id_royhat)) {
                $hfilter = $hfilter . " and h.id_royhat=$id_royhat";
            }
            if (!empty($id_checktype)) {
                $hfilter = $hfilter . " and h.id_checktype=$id_checktype";
            }

            if (!empty($ekriteria_4)) {
                $hfilter = $hfilter . " and h.kriteria_4=$ekriteria_4";
            }

            if (!empty($ekriteria_6)) {
                $hfilter = $hfilter . " and h.kriteria_6=$ekriteria_6";
            }

            if (!empty($ekriteria_7)) {
                $hfilter = $hfilter . " and h.kriteria_7=$ekriteria_7";
            }

            if (!empty($seriya)) {
                $hfilter = $hfilter . " and upper(h.seriya) like upper('%$seriya%')";
            }

            if (!empty($nomer)) {
                $hfilter = $hfilter . "and upper(h.nomer) like upper('%$nomer%')";
            }

            if (!empty($pdate)) {
                $hfilter = $hfilter . " and h.pdate=$pdate";
            }
            if (!empty($hfilter)) {
                $hlfilter = "LEFT JOIN mijoz_ill_history h on h.id_mijoz=m.id";
                $hofilter = "and h.old=0";
            }

            if (!$sidx) $sidx = 1;
            ##############################################################################
            $ddep = '';
            if ($this->session->userdata['admin_type'] == 4) {
                $dep = $this->db->query("select id from spr_depart where par_id=$iddep");
                $dep = $dep->result_array();
                $ddep = array();
                foreach ($dep as $item):
                    $ddep[] = $item['id'];
                endforeach;
            }
            if ($this->session->userdata['admin_type'] == 3) {
                $dep = $this->db->query("select id from spr_depart where id not in (0,1,2,3)");
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

            if (!empty($id_depart)) {
                $depart_sql = "m.id_depart in ($id_depart)";
            } else {
                $depart_sql = "m.id_depart in ($iddep,$ddep)";
            }
            #############################################################################


            $rows = $this->db->query("SELECT count(*)
                        FROM mijoz m
                        left join spr_region sr on m.id_born=sr.id
                        LEFT JOIN spr_place sp ON m.id_place=sp.id
                        left join spr_gender sg on m.gender=sg.id
                        {$wlfilter}
                        {$hlfilter}
                        WHERE {$depart_sql}
                        and m.move=0
						and m.selected in (0,1)
                        {$ufilter}
                        {$wfilter}
                        {$hfilter}
                        {$hofilter}
                        ");

            $count = $rows->row()->count;

            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }

            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;

            $hfilter = "SELECT m.id,
                               m.familiya,
                               m.name,
                               m.middle,
                               m.date_birth,
                               sg.name_uz as gender,
                               m.pass_seriya,
                               m.pass_code,
                               m.infl,
                               sr.name_uz as id_born,
                               sp.name_uz as id_place,
                               m.address,
                               m.notcitizen,
                               mih.kriteria_4,
                               mih.kriteria_6,
                               mih.kriteria_7
                        FROM mijoz m
                        left join spr_region sr on m.id_born=sr.id
                        LEFT JOIN spr_place sp ON m.id_place=sp.id
                        left join spr_gender sg on m.gender=sg.id
                        left join mijoz_ill_history mih on m.id=mih.id_mijoz and mih.old=0
                        {$wlfilter}
                        {$hlfilter}
                        WHERE {$depart_sql}
                        and m.move=0
						and m.selected in (0,1)
                        {$ufilter}
                        {$wfilter}
                        {$hfilter}
                        {$hofilter}
                        ORDER BY $sidx $sord LIMIT $limit OFFSET $start
                        ";
            $res = $this->db->query($hfilter);
            $response = new \stdClass();;
            $response->rows = array();
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            //print_r($res);exit;

            $i = 0;
            if ($res->num_rows() > 0) {
                foreach ($res->result_array() as $row) {
                    $response->rows[$i]['id'] = $row['id'];

                    if($row['kriteria_4'] == 0) {
                        $row['kriteria_4'] = "Белгиланмаган";
                    } elseif ($row['kriteria_4'] == 18) {
                        $row['kriteria_4'] = "ахборот олиш ва бериш суръати ва ҳажмини пасайтириб муомала қилиш лаёқати, зарурият бўлганда ёрдам беришнинг ёрдамчи техник воситаларидан фойдаланиш";
                    } elseif ($row['kriteria_4'] == 19) {
                        $row['kriteria_4'] = "зарурият бўлганда ёрдамчи техник воситалардан фойдаланиб бошқа шахсларнинг қисман ёрдами билан муомала қилиш лаёқати";
                    } else {
                        $row['kriteria_4'] = "муомала қила олмаслик ва бошқа шахсларнинг доимий ёрдамига муҳтожлик";
                    }

                    if($row['kriteria_6'] == 0) {
                        $row['kriteria_6'] = "Белгиланмаган";
                    } elseif ($row['kriteria_6'] == 24) {
                        $row['kriteria_6'] = "зарурат бўлганда ёрдамчи техник воситалар ва технологияларни қўллаб ўқитишнинг махсус усулларидан, ўқитишнинг махсус режимидан фойдаланиб умумий типдаги таълим муассасаларида давлат таълим стандартлари доирасида ўқиш, шунингдек муайян даражадаги маълумотга эга бўлиш лаёқати";
                    } elseif ($row['kriteria_6'] == 25) {
                        $row['kriteria_6'] = "зарурат бўлганда ёрдамчи техник воситалар ва технологиялардан фойдаланиб фақат ривожланишида нуқсонлари бўлган таълим олувчилар, тарбияланувчилар учун ихтисослаштирилган таълим муассасаларида ёки уйда махсус дастурлар бўйича ўқиш лаёқати";
                    } else {
                        $row['kriteria_6'] = "ўқий олмаслик";
                    }

                    if($row['kriteria_7']== 0) {
                        $row['kriteria_7'] = "Белгиланмаган";
                    } elseif ($row['kriteria_7']== 24) {
                        $row['kriteria_7'] = "малака, оғирлик, кескинлик пасайган ва (ёки) иш ҳажми камайганда меҳнатнинг одатдаги шароитларида меҳнат фаолиятини бажариш лаёқати, меҳнатнинг одатдаги шароитларида паст малакали меҳнат фаолиятини бажариш имкони сақланиб қолган ҳолда асосий касб бўйича ишни давом эттира олмаслик";
                    } elseif ($row['kriteria_7']== 25) {
                        $row['kriteria_7'] = "ёрдамчи техник воситалардан фойдаланиб ва (ёки) бошқа шахслар ёрдамида махсус ташкил этилган меҳнат шароитларида меҳнат фаолиятини бажариш лаёқати";
                    } else {
                        $row['kriteria_7'] = "меҳнат фаолияти билан шуғуллана олмаслик ёки меҳнат фаолияти билан шуғулланиб бўлмаслиги (ёки меҳнат фаолияти билан шуғулланиш тақиқланганлиги)";
                    }

                    //print_r($row);exit;
                    $response->rows[$i]['cell'] = array($row['id'],
                        $row['familiya'],
                        $row['name'],
                        $row['middle'],
                        $row['date_birth'],
                        $row['gender'],
                        $row['pass_seriya'],
                        $row['pass_code'],
                        $row['infl'],
                        $row['id_born'],
                        $row['id_place'],
                        $row['address'],
                        $row['notcitizen'],
                        $row['kriteria_4'],
                        $row['kriteria_6'],
                        $row['kriteria_7']
                    );
                    $i++;
                }
                //                print_r($response);exit;
                echo json_encode($response);
            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_mijoz()
    {
        try {
            $iddep = $this->session->userdata("iddep");
            $id = $_POST['id'];
            $familiya = $_POST['familiya'];
            $name = $_POST['name'];
            $middle = $_POST['middle'];
            $date_birth = $_POST['date_birth'];
            $gender = $_POST['gender'];
            $pass_seriya = $_POST['pass_seriya'];
            $pass_code = $_POST['pass_code'];
            $infl = $_POST['infl'];
            $id_born = $_POST['id_born'];
            $id_place = $_POST['id_place'];
            $address = $_POST['address'];
            $notcitizen = $_POST['notcitizen'];

            $this->db->trans_begin();

            if ($_POST['oper'] == "add") {

                $result = $this->db->query("INSERT INTO mijoz (id_depart, familiya, name, middle, date_birth, gender, pass_seriya, pass_code, infl, id_born, id_place, address, notcitizen)
                                            VALUES ($iddep,'$familiya', '$name', '$middle', '$date_birth', $gender, '$pass_seriya', '$pass_code', '$infl', $id_born, $id_place, '$address', $notcitizen)");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE mijoz
                                            SET familiya='$familiya',
                                                name='$name',
                                                middle='$middle',
                                                date_birth='$date_birth',
                                                gender = $gender,
                                                pass_seriya='$pass_seriya',
                                                pass_code='$pass_code',
                                                infl='$infl',
                                                id_born=$id_born,
                                                id_place=$id_place,
                                                address='$address',
                                                notcitizen=$notcitizen
                                            WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {

                $pcheck = $this->db->query("select pcheck from  mijoz_ill_history mih  where mih.id_mijoz=$id and mih.old=0 ")->result();
                if ($pcheck[0]->pcheck > 0) {
                    ?>
                Бу ногирон пенсия маълумотномаси жамғарма томонидан текширилгани учун тахрирлаш мумкин эмас;

                <?php
                    exit;
                }

                $this->db->query("DELETE from mijoz_edu WHERE id_mijoz=$id");
                $this->db->query("DELETE from mijoz_ill_history WHERE id_mijoz=$id");
                $this->db->query("DELETE from mijoz_murojaat WHERE id_mijoz=$id");
                $this->db->query("DELETE from mijoz WHERE id=$id");

            }
            if ($this->db->trans_status() == FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    //ish joyi ma'lumotlari
    function work($mijoz_id)
    {
        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            $iddep = $this->session->userdata("iddep");
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query("SELECT COUNT(*) AS count FROM mijoz_edu where id_mijoz=$mijoz_id");
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if (($total_pages != 0) && ($page > $total_pages)) $page = $total_pages;

            $start = $limit * $page - $limit;
            $res = $this->db->query("SELECT me.*, sm.name_uz, sw.name_uz as wwork
                                     FROM mijoz_edu me, spr_malumot sm, spr_work sw
                                     WHERE id_mijoz=$mijoz_id
                                     AND   me.id_malumot=sm.id
                                     AND   me.working=sw.id
                                     ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $i = 0;
            $_rows = $res->result_array();
            $response->rows = array();
            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'],
                    $row['id_mijoz'],
                    $row['name_uz'],
                    $row['spec'],
                    $row['work'],
                    $row['staj'],
                    $row['wwork']
                );
                $i++;
            }

            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_work($mijoz_id)
    {
        if (empty($mijoz_id) || ($mijoz_id == 'undefined')) exit;
        //print_r($_POST);
        $oper = $_POST['oper'];
        unset($_POST['oper']);

        if ($oper == 'del') {
            $this->db->delete('mijoz_edu', array('id' => $_POST['id']));
            exit;
        }

        $_POST['id_mijoz'] = $mijoz_id;

        $new = true;
        if ($oper == 'add') {
            unset($_POST['id']);
        } else {
            $new = false;
            $id = $_POST['id'];
            unset($_POST['id']);
        }

        foreach ($_POST as $i => $v) {
            if (($v != '0') && empty($v)) {
                $_POST[$i] = 'NULL';
            }
        }

        if ($new) {
            $this->db->insert('mijoz_edu', $_POST);
        } else {
            $this->db->where('id', $id);
            $this->db->update('mijoz_edu', $_POST);
        }
    }

    function mdelete()
    {
        try {


            $id = $_POST['id'];
            if ($_POST['oper'] == "del") {
                $this->db->trans_begin();
                $this->db->query("DELETE from mijoz_ortoped WHERE id_mijoz=$id");
                $this->db->query("DELETE from mijoz_edu WHERE id_mijoz=$id");
                $this->db->query("DELETE from mijoz_pspravka WHERE id_mijoz=$id");
                $this->db->query("DELETE from history WHERE id_mijoz=$id");
                $this->db->query("DELETE from mijoz WHERE id=$id");

                if ($this->db->trans_status() == FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }

            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

    function edit_history()
    {
        try {


            $id = $_POST['id'];
            if ($_POST['oper'] == "del") {
                $this->db->trans_begin();
                $this->db->query("DELETE from mijoz_murojaat WHERE id=(select id_murojaat from mijoz_ill_history where id=$id)");
                $this->db->query("DELETE from mijoz_ill_history WHERE id=$id");

                if ($this->db->trans_status() == FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }

            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

    function history_save()
    {
        if (empty($_POST['id_mijoz']) || ($_POST['id_mijoz'] == 'undefined')) exit;
        $id_mijoz = $_POST['id_mijoz'];


        $new = true;
        if (empty($_POST['id'])) {
            unset($_POST['id']);
        } else {
            $new = false;
            $id = $_POST['id'];
            unset($_POST['id']);
        }


        //if (empty($_POST['protocol'])) $_POST['protocol'] = NULL;


        if ($_POST['thistory'] == 0) {
            $_POST['old'] = 0;
            $qisman_r_o = $this->db->query("select id, guruh from  mijoz_ill_history mih  where mih.id_mijoz=$id_mijoz and mih.OLD=0 ")->result();
            $check_date_new = $_POST['beg_date'];
            $chech_history = $this->db->query("select count(id) as jami from mijoz_ill_history mih where mih.id_mijoz=$id_mijoz and mih.beg_date>='$check_date_new'")->result();
            if (($chech_history[0]->jami) > 0) {
                ?>
            Бу ногиронга 'янги ногиронлик тарихи' танланган холатда эски ногиронлик тарихи киритиляпти;
            Илтимос киритилаётган ногирон тарихини текширинг...;

            <?php
                exit;
            }


        } elseif ($_POST['thistory'] == 1) {
            $_POST['old'] = 1;
            $empty_history = $this->db->query("select * from mijoz_ill_history mih where mih.id_mijoz=$id_mijoz")->result();
            if (empty($empty_history)) {
                ?>
            Бу ногиронга 'эски ногиронлик тарихи' танланган холда охирги ногиронлик тарихи киритиляпти;
            Илтимос киритилаётган ногирон тарихини текширинг ...;

            <?php
                exit;
            }


        }

        if (empty($_POST['foiz'])) $_POST['foiz'] = 0;
        if (empty($_POST['knomer'])) $_POST['knomer'] = NULL;
        if (empty($_POST['ktashkilot'])) $_POST['ktashkilot'] = NULL;
        if (empty($_POST['tdate'])) $_POST['tdate'] = NULL;
        if (empty($_POST['trdate'])) $_POST['trdate'] = '1900-01-01';
        if (empty($_POST['asos'])) $_POST['asos'] = NULL;
        if (empty($_POST['guruh'])) $_POST['guruh'] = 0;
        if (empty($_POST['seriya'])) $_POST['seriya'] = NULL;
        if (empty($_POST['nomer'])) $_POST['nomer'] = NULL;
        if (empty($_POST['pdate'])) $_POST['pdate'] = NULL;


        if ($_POST['code_ill_10_par'] == 0) {
            $_POST['mkb_10'] = 0;
        }

        if (!empty($_POST['end_date_combo'])) {
            $date = date($_POST['murojaat_sana']);
            if ($_POST['end_date_combo'] == 1) {
                $day = date("d", strtotime($date));
				$month= date("m", strtotime($date));
                if ($day < 31 && $month!=7) {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +7 month");
                    $enddate = date("Y-m-d", $date);
                    $mm = date("m", strtotime($enddate));
                    $yy = date("Y", strtotime($enddate));
                    $start = date("$yy-$mm-01"); //t -> last day of month
                    $start_1 = strtotime(date("Y-m-d", strtotime($start)));
                    $enddate = date("Y-m-d", $start_1);
                    $enddate = strtotime(date("Y-m-d", strtotime($enddate)) . " -1 day");
                    $enddate = date("Y-m-d", $enddate);
                } else {
                    $date = strtotime(date("Y-m-d", strtotime($date)));
                    $enddate = date("Y-m-d", $date);
					$mm = date("m", strtotime($enddate));
                    $yy = date("Y", strtotime($enddate));
					$mm= $mm+7;
					if ($mm>12) {$yy=$yy+1; $mm=$mm-12;}
					$start = date("$yy-$mm-01");
					$start_1 = strtotime(date("Y-m-d", strtotime($start)));
                    $enddate = date("Y-m-d", $start_1);
                    $enddate = strtotime(date("Y-m-d", strtotime($enddate)) . " -1 day");
                    $enddate = date("Y-m-d", $enddate);
                }

            } elseif ($_POST['end_date_combo'] == 2) {
                $day = date("d", strtotime($date));
                $yan_month = date("m", strtotime($date));
                if ($day < 31 && $yan_month != '01') {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +13 month");
                    $enddate = date("Y-m-d", $date);
                    $mm = date("m", strtotime($enddate));
                    $yy = date("Y", strtotime($enddate));
                    $start = date("$yy-$mm-01"); //t -> last day of month
                    $start_1 = strtotime(date("Y-m-d", strtotime($start)));
                    $enddate = date("Y-m-d", $start_1);
                    $enddate = strtotime(date("Y-m-d", strtotime($enddate)) . " -1 day");
                    $enddate = date("Y-m-d", $enddate);
                } elseif ($yan_month == '01') {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +12 month");
                    $enddate = date("Y-m-d", $date);
                    $yanday = 31;
                    $yy = date("Y", strtotime($enddate));
                    $enddate = date("$yy-01-$yanday");
                }

                else {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +12 month");
                    $enddate = date("Y-m-d", $date);
                }

            }
            elseif ($_POST['end_date_combo'] == 3) {
                $day = date("d", strtotime($date));
                $yan_month = date("m", strtotime($date));
                if ($day < 31 && $yan_month != '01') {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +25 month");
                    $enddate = date("Y-m-d", $date);
                    $mm = date("m", strtotime($enddate));
                    $yy = date("Y", strtotime($enddate));
                    $start = date("$yy-$mm-01"); //t -> last day of month
                    $start_1 = strtotime(date("Y-m-d", strtotime($start)));
                    $enddate = date("Y-m-d", $start_1);
                    $enddate = strtotime(date("Y-m-d", strtotime($enddate)) . " -1 day");
                    $enddate = date("Y-m-d", $enddate);
                } elseif ($yan_month == '01') {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +24 month");
                    $enddate = date("Y-m-d", $date);
                    $yanday = 31;
                    $yy = date("Y", strtotime($enddate));
                    $enddate = date("$yy-01-$yanday");
                }
                else {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +24 month");
                    $enddate = date("Y-m-d", $date);
                }

            }
            elseif ($_POST['end_date_combo'] == 4) {
                $enddate = "2099-01-01";
            }
            elseif ($_POST['end_date_combo'] == 0) {
                $enddate = "1700-01-01";
            }
            elseif ($_POST['end_date_combo'] == 5) {
                $day = date("d", strtotime($date));
                $yan_month = date("m", strtotime($date));
                if ($day < 31 && $yan_month != '01') {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +61 month");
                    $enddate = date("Y-m-d", $date);
                    $mm = date("m", strtotime($enddate));
                    $yy = date("Y", strtotime($enddate));
                    $start = date("$yy-$mm-01"); //t -> last day of month
                    $start_1 = strtotime(date("Y-m-d", strtotime($start)));
                    $enddate = date("Y-m-d", $start_1);
                    $enddate = strtotime(date("Y-m-d", strtotime($enddate)) . " -1 day");
                    $enddate = date("Y-m-d", $enddate);
                } elseif ($yan_month == '01') {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +60 month");
                    $enddate = date("Y-m-d", $date);
                    $yanday = 31;
                    $yy = date("Y", strtotime($enddate));
                    $enddate = date("$yy-01-$yanday");
                }
                else {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +60 month");
                    $enddate = date("Y-m-d", $date);
                }
            }
            elseif ($_POST['end_date_combo'] == 6) {
                $date = strtotime(date("Y-m-d", strtotime($date)) . " +2 month");
                $enddate = date("Y-m-d", $date);

            }
        } else {
            $enddate = "1700-01-01";
        }

        // $id_mijoz = $_POST['id_mijoz'];

        $this->db->trans_begin();

        $murojaat = array(
            'id_mijoz' => $id_mijoz,
            'protocol' => $_POST['protocol'],
            'murojaat_sababi' => $_POST['murojaat_sababi'],
            'asos' => $_POST['asos']
        );
        $this->db->insert('mijoz_murojaat', $murojaat);
        $id_murojaat = $this->db->insert_id();

        if (!empty($qisman_r_o[0]->id)) {
            $old_history_id = $qisman_r_o[0]->id;
            $old_guruh = $qisman_r_o[0]->guruh;
            if ($old_guruh - $_POST['guruh'] == 0) {
                $old_holat_reabilit = 0;
            } elseif ($old_guruh - $_POST['guruh'] < 0) {
                $old_holat_reabilit = 1;
            } elseif ($old_guruh - $_POST['guruh'] > 0) {
                $old_holat_reabilit = 2;
            }
        } else {
            $old_history_id = null;
            $old_guruh = null;
            $old_holat_reabilit = 4;
        }

        $history = array(
            'old' => $_POST['old'],
            'id_mijoz' => $id_mijoz,
            'id_murojaat' => $id_murojaat,
            'murojaat_sana' => $_POST['murojaat_sana'],
            'tmek_xulosasi' => $_POST['tmek_xulosasi'],
            'foiz' => $_POST['foiz'],
            'knomer' => $_POST['knomer'],
            'ktashkilot' => $_POST['ktashkilot'],
            'id_ortoped' => $_POST['id_ortoped'],
            'tdate' => $_POST['tdate'],
            'trdate' => $_POST['trdate'],
            'id_checktype' => $_POST['id_checktype'],
            'id_royhat' => $_POST['id_royhat'],
            'id_sabab' => $_POST['id_sabab'],
            'mkb_9' => $_POST['mkb_9'],
            'mkb_10' => $_POST['mkb_10'],
            'guruh' => $_POST['guruh'],
            'beg_date' => $_POST['beg_date'],
            'end_date_combo' => $_POST['end_date_combo'],
            'end_date' => $enddate,
            'seriya' => $_POST['seriya'],
            'nomer' => $_POST['nomer'],
            'pdate' => $_POST['pdate'],
            'kriteria_1' => $_POST['kriteria_1'],
            'kriteria_2' => $_POST['kriteria_2'],
            'kriteria_3' => $_POST['kriteria_3'],
            'kriteria_4' => $_POST['kriteria_4'],
            'kriteria_5' => $_POST['kriteria_5'],
            'kriteria_6' => $_POST['kriteria_6'],
            'kriteria_7' => $_POST['kriteria_7'],
            'mkb10_parent' => $_POST['code_ill_10_par'],
            'old_history_id' => $old_history_id,
            'old_guruh' => $old_guruh,
            'old_holat_reabilit' => $old_holat_reabilit,
            'degree_violation' => $_POST['degree_violation']
        );

        $history_id = null;

        if ($new) {
            if ($_POST['thistory'] == 0) {

                $this->db->query("UPDATE mijoz_ill_history SET old=old+1 WHERE id_mijoz=$id_mijoz");
                $this->db->insert('mijoz_ill_history', $history);
                $history_id = $this->db->insert_id();

                $id_user = $this->session->userdata("admin_id");
                $iddep = $this->session->userdata("iddep");

                $log = array('id_depart' => $iddep,
                    'id_user' => $id_user,
                    'id_history' => $history_id,
                    'change' => "INSERT",
                    'id_mijoz' => $id_mijoz);

                $this->db->insert('logs', $log);


            } elseif ($_POST['thistory'] == 1) {
                $this->db->insert('mijoz_ill_history', $history);
                $history_id = $this->db->insert_id();
            }
        } else {
            $history_id = $id;
            $this->db->where('id', $id);
            $this->db->update('mijoz_ill_history', $history);
        }
        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }


        foreach ($_FILES as $index => $file) {
            $type = substr($index, 5);
            $uploads_dir = '/var/www/html/tmek.uz/upload/';
            $tmp_name = $file["tmp_name"];
            $name = $file["name"];
			//$print_r($name);
            $fullname=str_replace(' ','_',$name);
            //$print_r($fullname);
			$uploadfile = $uploads_dir.basename($fullname);
			//exit;

            move_uploaded_file($tmp_name, "$uploadfile");

            $result = $this->db->query("SELECT id FROM pdfoid WHERE id_history = {$history_id} AND file_type = '{$type}'")->result();

            if (empty($result)) {

                $this->db->query("INSERT INTO pdfoid (id_history, name, content, file_type,id_mijoz)
                                VALUES ({$history_id},'$fullname',lo_import('$uploadfile'), '{$type}',$id_mijoz)
                                ");

            } else {
                $this->db->query("UPDATE pdfoid SET content = lo_import('$uploadfile'), name = '$fullname'
                                  WHERE id_history = {$history_id} AND file_type = '{$type}'");
            }

            unlink("$uploadfile");
            //$file_id = $this->db->insert_id();
        }
    }


    function ehistory_save()
    {

        if (empty($_POST['id_mijoz']) || ($_POST['id_mijoz'] == 'undefined')) exit;

        $id = $_POST['id'];
        $pcheck = $this->db->query("select pcheck from  mijoz_ill_history mih  where mih.id=$id ")->result();
      /*  if ($pcheck[0]->pcheck > 1) {
            ?>
        Бу ногирон пенсия маълумотномаси жамғарма томонидан текширилгани учун тахрирлаш мумкин эмас;

        <?php
            exit;
        } */
        $qisman_r_o = $this->db->query("select old_history_id, old_guruh from  mijoz_ill_history mih  where mih.id=$id ")->result();
        if (empty($_POST['efoiz'])) $_POST['efoiz'] = 0;
        if (empty($_POST['eknomer'])) $_POST['eknomer'] = NULL;
        if (empty($_POST['ektashkilot'])) $_POST['ektashkilot'] = NULL;
        if (empty($_POST['etdate'])) $_POST['etdate'] = NULL;
        if (empty($_POST['etrdate'])) $_POST['etrdate'] = '1900-01-01';
        if (empty($_POST['eguruh'])) $_POST['eguruh'] = 0;
        if (empty($_POST['ebeg_date'])) $_POST['ebeg_date'] = NULL;
        if (empty($_POST['eseriya'])) $_POST['eseriya'] = NULL;
        if (empty($_POST['enomer'])) $_POST['enomer'] = NULL;
        if (empty($_POST['epdate'])) $_POST['epdate'] = NULL;

        if ($_POST['ecode_ill_10_par'] == 0) {
            $_POST['emkb_10'] = 0;
        }

        if (!empty($_POST['eend_date_combo'])) {
            $date = date($_POST['emurojaat_sana']);
            if ($_POST['eend_date_combo'] == 1) {
                $day = date("d", strtotime($date));
                $month= date("m", strtotime($date));
                if ($day < 31 && $month!=7) {
                     $date = strtotime(date("Y-m-d", strtotime($date)) . " + 7 month");
                    $enddate = date("Y-m-d", $date);
                    $mm = date("m", strtotime($enddate));
                    $yy = date("Y", strtotime($enddate));
                    $start = date("$yy-$mm-01"); //t -> last day of month
                    $start_1 = strtotime(date("Y-m-d", strtotime($start)));
                    $enddate = date("Y-m-d", $start_1);
                    $enddate = strtotime(date("Y-m-d", strtotime($enddate)) . " -1 day");
                    $enddate = date("Y-m-d", $enddate);
                }
				else {

					$date = strtotime(date("Y-m-d", strtotime($date)));
                    $enddate = date("Y-m-d", $date);
					$mm = date("m", strtotime($enddate));
                    $yy = date("Y", strtotime($enddate));
					$mm= $mm+7;
					if ($mm>12) {$yy=$yy+1; $mm=$mm-12;}
					$start = date("$yy-$mm-01");
					$start_1 = strtotime(date("Y-m-d", strtotime($start)));
                    $enddate = date("Y-m-d", $start_1);
                    $enddate = strtotime(date("Y-m-d", strtotime($enddate)) . " -1 day");
                    $enddate = date("Y-m-d", $enddate);

                }

            } elseif ($_POST['eend_date_combo'] == 2) {
                $day = date("d", strtotime($date));
                $yan_month = date("m", strtotime($date));
                //print_r($yan_month);exit;
                if ($day < 31 && $yan_month != '01') {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +13 month");
                    $enddate = date("Y-m-d", $date);
                    $mm = date("m", strtotime($enddate));
                    $yy = date("Y", strtotime($enddate));
                    $start = date("$yy-$mm-01"); //t -> last day of month
                    $start_1 = strtotime(date("Y-m-d", strtotime($start)));
                    $enddate = date("Y-m-d", $start_1);
                    $enddate = strtotime(date("Y-m-d", strtotime($enddate)) . " -1 day");
                    $enddate = date("Y-m-d", $enddate);
                } elseif ($yan_month == '01') {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +12 month");
                    $enddate = date("Y-m-d", $date);
                    $yanday = 31;
                    $yy = date("Y", strtotime($enddate));
                    $enddate = date("$yy-01-$yanday");
                }
                else {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +12 month");
                    $enddate = date("Y-m-d", $date);
                }
            }
            elseif ($_POST['eend_date_combo'] == 3) {
                $day = date("d", strtotime($date));
                $yan_month = date("m", strtotime($date));
                if ($day < 31 && $yan_month != '01') {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +25 month");
                    $enddate = date("Y-m-d", $date);
                    $mm = date("m", strtotime($enddate));
                    $yy = date("Y", strtotime($enddate));
                    $start = date("$yy-$mm-01"); //t -> last day of month
                    $start_1 = strtotime(date("Y-m-d", strtotime($start)));
                    $enddate = date("Y-m-d", $start_1);
                    $enddate = strtotime(date("Y-m-d", strtotime($enddate)) . " -1 day");
                    $enddate = date("Y-m-d", $enddate);
                } elseif ($yan_month == '01') {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +24 month");
                    $enddate = date("Y-m-d", $date);
                    $yanday = 31;
                    $yy = date("Y", strtotime($enddate));
                    $enddate = date("$yy-01-$yanday");
                }
                else {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +24 month");
                    $enddate = date("Y-m-d", $date);
                }
            }
            elseif ($_POST['eend_date_combo'] == 4) {
                $enddate = "2099-01-01";
            }
            elseif ($_POST['eend_date_combo'] == 0) {
                $enddate = "1700-01-01";
            }
            elseif ($_POST['eend_date_combo'] == 5) {
                $day = date("d", strtotime($date));
                $yan_month = date("m", strtotime($date));
                if ($day < 31 && $yan_month != '01') {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +61 month");
                    $enddate = date("Y-m-d", $date);
                    $mm = date("m", strtotime($enddate));
                    $yy = date("Y", strtotime($enddate));
                    $start = date("$yy-$mm-01"); //t -> last day of month
                    $start_1 = strtotime(date("Y-m-d", strtotime($start)));
                    $enddate = date("Y-m-d", $start_1);
                    $enddate = strtotime(date("Y-m-d", strtotime($enddate)) . " -1 day");
                    $enddate = date("Y-m-d", $enddate);
                } elseif ($yan_month == '01') {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +60 month");
                    $enddate = date("Y-m-d", $date);
                    $yanday = 31;
                    $yy = date("Y", strtotime($enddate));
                    $enddate = date("$yy-01-$yanday");
                }
                else {
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +60 month");
                    $enddate = date("Y-m-d", $date);
                }
            }
            elseif ($_POST['eend_date_combo'] == 6) {
                $date = strtotime(date("Y-m-d", strtotime($date)) . " +2 month");
                $enddate = date("Y-m-d", $date);

            }

        } else {
            $enddate = "1700-01-01";
        }

        $id_mijoz = $_POST['id_mijoz'];

        if (!empty($qisman_r_o[0]->old_history_id)) {
            $old_history_id = $qisman_r_o[0]->old_history_id;
            $old_guruh = $qisman_r_o[0]->old_guruh;
            if ($old_guruh - $_POST['eguruh'] == 0) {
                $old_holat_reabilit = 0;
            } elseif ($old_guruh - $_POST['eguruh'] < 0) {
                $old_holat_reabilit = 1;
            } elseif ($old_guruh - $_POST['eguruh'] > 0) {
                $old_holat_reabilit = 2;
            }
        } else {
            $old_history_id = null;
            $old_guruh = null;
            $old_holat_reabilit = 4;
        }

        $this->db->trans_begin();

        $ehistory = array(
            'tmek_xulosasi' => $_POST['etmek_xulosasi'],
            'murojaat_sana' => $_POST['emurojaat_sana'],
            'foiz' => $_POST['efoiz'],
            'knomer' => $_POST['eknomer'],
            'ktashkilot' => $_POST['ektashkilot'],
            'id_ortoped' => $_POST['eid_ortoped'],
            'tdate' => $_POST['etdate'],
            'trdate' => $_POST['etrdate'],
            'id_checktype' => $_POST['eid_checktype'],
            'id_royhat' => $_POST['eid_royhat'],
            'id_sabab' => $_POST['eid_sabab'],
            'mkb_9' => $_POST['emkb_9'],
            'mkb_10' => $_POST['emkb_10'],
            'guruh' => $_POST['eguruh'],
            'beg_date' => $_POST['ebeg_date'],
            'end_date_combo' => $_POST['eend_date_combo'],
            'end_date' => $enddate,
            'seriya' => $_POST['eseriya'],
            'nomer' => $_POST['enomer'],
            'pdate' => $_POST['epdate'],
            'kriteria_1' => $_POST['ekriteria_1'],
            'kriteria_2' => $_POST['ekriteria_2'],
            'kriteria_3' => $_POST['ekriteria_3'],
            'kriteria_4' => $_POST['ekriteria_4'],
            'kriteria_5' => $_POST['ekriteria_5'],
            'kriteria_6' => $_POST['ekriteria_6'],
            'kriteria_7' => $_POST['ekriteria_7'],
            'mkb10_parent' => $_POST['ecode_ill_10_par'],
            'old_history_id' => $old_history_id,
            'old_guruh' => $old_guruh,
            'old_holat_reabilit' => $old_holat_reabilit,
            'degree_violation' => $_POST['edegree_violation']
        );
        $this->db->where('id', $id);
        $this->db->update('mijoz_ill_history', $ehistory);

        $id_user = $this->session->userdata("admin_id");
        $iddep = $this->session->userdata("iddep");

        $log = array('id_depart' => $iddep,
            'id_user' => $id_user,
            'id_history' => $id,
            'change' => "UPDATE",
            'id_mijoz' => $id_mijoz);

        $this->db->insert('logs', $log);

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }


         foreach ($_FILES as $index => $file) {
             $type = substr($index, 5);
             $uploads_dir = '/var/www/html/tmek.uz/upload/';
             $tmp_name = $file["tmp_name"];
             $name = $file["name"];
			// print_r($name);
             $fullname=str_replace(' ','_',$name);
            // print_r($fullname);
			 $uploadfile = $uploads_dir.basename($fullname);
			// print_r($uploadfile);
			// exit;
            move_uploaded_file($tmp_name, "$uploadfile");

            $result = $this->db->query("SELECT id FROM pdfoid WHERE id_history = {$id} AND file_type = '{$type}'")->result();

            if (empty($result)) {

                $this->db->query("INSERT INTO pdfoid (id_history, name, content, file_type, id_mijoz)
                                VALUES ({$id},'$fullname',lo_import('$uploadfile'), '{$type}',$id_mijoz)
                                ");

            } else {
                $this->db->query("UPDATE pdfoid SET content = lo_import('$uploadfile'), name = '$fullname'
                                  WHERE id_history = {$id} AND file_type = '{$type}'");
            }

            unlink("$uploadfile");
            //$file_id = $this->db->insert_id();
        }

    }

    function mkb10($mkb)
    {
        $rows = $this->db->query("select row_num, r  from spr_illness_10  where  r_level in (3,4) and group_id=$mkb ORDER BY row_num")->result();
        echo json_encode($rows);
    }

    function txulosa($x)
    {
        $rows = $this->db->query("select * from spr_nogironlik_sabab sns where sns.par_id=$x order by sns.id")->result();
        echo json_encode($rows);
    }

    function murojaat($mijoz_id)
    {
        if (empty($mijoz_id)) exit;
        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            $iddep = $this->session->userdata("iddep");
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query("SELECT COUNT(*) AS count FROM mijoz_murojaat where id_mijoz=$mijoz_id");
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if (($total_pages != 0) && ($page > $total_pages)) $page = $total_pages;

            $start = $limit * $page - $limit;
            $res = $this->db->query("
            	select mm.id, mm.protocol, sms.murojaat_sababi as sabab_nomi,
                  COALESCE(mm.tr88, ' ')||'-'||COALESCE(mm.tr88date, NULL)||'-'||COALESCE(mm.tr88dmp, ' ')||', '
                  ||COALESCE(mm.ktk, ' ')||'-'||COALESCE(mm.ktkdate, NULL)||'-'||COALESCE(mm.ktkdmp, ' ')||', '
                  ||COALESCE(mm.amb, ' ')||'-'||COALESCE(mm.ambdate, NULL)||'-'||COALESCE(mm.ambdmp, ' ')||', '
                  ||COALESCE(mm.asos, ' ') as asos
                from mijoz_murojaat mm
                left join spr_murojaat_sabab sms ON sms.id=mm.murojaat_sababi
            	WHERE id_mijoz=$mijoz_id
            	ORDER BY $sidx $sord LIMIT $limit OFFSET $start
            ");
            $response = new \stdClass();;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $i = 0;
            $_rows = $res->result_array();
            $response->rows = array();
            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'],
                    $row['protocol'],
                    $row['sabab_nomi'],
                    $row['asos']
                );
                $i++;
            }
            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_murojaat($mijoz_id)
    {
        //print_r($_POST);exit;

        if (empty($mijoz_id) || ($mijoz_id == 'undefined')) exit;
        //print_r($_POST);
        $oper = $_POST['oper'];
        unset($_POST['oper']);

//        if ($oper == 'del') {
//            $this->db->delete('mijoz_edu', array('id' => $_POST['id']));
//            exit;
//        }

        $_POST['id_mijoz'] = $mijoz_id;

        $new = true;
        if ($oper == 'add') {
            unset($_POST['id']);
        } else {
            $new = false;
            $id = $_POST['id'];
            unset($_POST['id']);
        }

        foreach ($_POST as $i => $v) {
            if (($v == '0') || empty($v)) {
                $_POST[$i] = NULL;
            }
        }
        if (!$new) {
            $this->db->where('id', $id);
            $this->db->update('mijoz_murojaat', $_POST);
        }
    }

    function history($mijoz_id)
    {
        if (empty($mijoz_id)) exit;
        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            $iddep = $this->session->userdata("iddep");
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query("SELECT COUNT(*) AS count FROM mijoz_ill_history where id_mijoz=$mijoz_id");
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if (($total_pages != 0) && ($page > $total_pages)) $page = $total_pages;

            $start = $limit * $page - $limit;
            $res = $this->db->query("
            	SELECT
            		mih.*,
            		sns.nogironlik_sababi as xulosa_name,
            		i.code_9 as mkb_9_name,
            		ii.r as mkb_10_name,
            		sr.name_uz as royhat_name,
            		sk1.reason_uz as kriteria1,
            		sk2.reason_uz as kriteria2,
            		sk3.reason_uz as kriteria3,
            		sk4.reason_uz as kriteria4,
            		sk5.reason_uz as kriteria5,
            		sk6.reason_uz as kriteria6,
            		sk7.reason_uz as kriteria7,
            		sc.name_uz as checktype_name,
            		ss.sabab_uz as sabab_name,
            		so.name_uz as ortoped_name
            	FROM mijoz_ill_history mih
            		LEFT JOIN spr_nogironlik_sabab sns ON sns.id = mih.tmek_xulosasi
            		LEFT JOIN spr_illness i ON i.id = mih.mkb_9
                    LEFT JOIN spr_illness_10  ii  ON  ii.row_num = mih.mkb_10
            		LEFT JOIN spr_royhat sr ON sr.id = mih.id_royhat
            		LEFT JOIN spr_kdaraja sk1 ON sk1.id = mih.kriteria_1
            		LEFT JOIN spr_kdaraja sk2 ON sk2.id = mih.kriteria_2
            		LEFT JOIN spr_kdaraja sk3 ON sk3.id = mih.kriteria_3
            		LEFT JOIN spr_kdaraja sk4 ON sk4.id = mih.kriteria_4
            		LEFT JOIN spr_kdaraja sk5 ON sk5.id = mih.kriteria_5
            		LEFT JOIN spr_kdaraja sk6 ON sk6.id = mih.kriteria_6
            		LEFT JOIN spr_kdaraja sk7 ON sk7.id = mih.kriteria_7
            		LEFT JOIN spr_checktype sc ON sc.id = mih.id_checktype
            		LEFT JOIN spr_sabab ss ON ss.id = mih.id_sabab
            		LEFT JOIN spr_ortoped so ON so.id=mih.id_ortoped
            	WHERE id_mijoz=$mijoz_id
            	ORDER BY $sidx $sord LIMIT $limit OFFSET $start
            ");
            $response = new \stdClass();;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $i = 0;
            $_rows = $res->result_array();
            $response->rows = array();
            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                if ($row['end_date'] == "2099-01-01") {
                    $row['end_date'] = "муддатсиз";
                }
                if ($row['end_date'] == "1700-01-01") {
                    $row['end_date'] = "рўйхатда турмайди";
                }
                if ($row['seriya'] == "NOSER") {
                    $row['seriya'] = " ";
                }
                $response->rows[$i]['cell'] = array($row['id'],
                    $row['tmek_xulosasi'],
                    $row['xulosa_name'],
                    $row['id_checktype'],
                    $row['checktype_name'],
                    $row['mkb_9'],
                    $row['mkb_9_name'],
                    $row['mkb_10'],
                    $row['mkb_10_name'],
                    $row['guruh'],
                    $row['id_sabab'],
                    $row['sabab_name'],
                    $row['murojaat_sana'],
                    $row['beg_date'],
                    $row['end_date_combo'],
                    $row['end_date'],
                    $row['id_royhat'],
                    $row['royhat_name'],
                    $row['seriya'],
                    $row['nomer'],
                    $row['pdate'],
                    $row['foiz'],
                    $row['id_ortoped'],
                    $row['ortoped_name'],
                    $row['tdate'],
                    $row['knomer'],
                    $row['ktashkilot'],
                    $row['kriteria_1'],
                    $row['kriteria1'],
                    $row['kriteria_2'],
                    $row['kriteria2'],
                    $row['kriteria_3'],
                    $row['kriteria3'],
                    $row['kriteria_4'],
                    $row['kriteria4'],
                    $row['kriteria_5'],
                    $row['kriteria5'],
                    $row['kriteria_6'],
                    $row['kriteria6'],
                    $row['kriteria_7'],
                    $row['kriteria7'],
                    $row['trdate'],
                    $row['mkb10_parent'],
                    $row['degree_violation']
                );
                $i++;
            }
            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function history_detail()
    {
        $histories = $this->db->query(
            "SELECT
            		mih.foiz, mih.id_ortoped, mih.knomer, mih.ktashkilot, mih.tdate, mih.trdate, sns.nogironlik_sababi as tmek_xulosasi,
            		sk1.reason_uz as kriteria1,
            		sk2.reason_uz as kriteria2,
            		sk3.reason_uz as kriteria3,
            		sk4.reason_uz as kriteria4,
            		sk5.reason_uz as kriteria5,
            		sk6.reason_uz as kriteria6,
            		sk7.reason_uz as kriteria7,
            		so.name_uz as ortoped_name,
            		mih.old
            	FROM mijoz_ill_history mih
            		LEFT JOIN spr_kdaraja sk1 ON sk1.id = mih.kriteria_1
            		LEFT JOIN spr_kdaraja sk2 ON sk2.id = mih.kriteria_2
            		LEFT JOIN spr_kdaraja sk3 ON sk3.id = mih.kriteria_3
            		LEFT JOIN spr_kdaraja sk4 ON sk4.id = mih.kriteria_4
            		LEFT JOIN spr_kdaraja sk5 ON sk5.id = mih.kriteria_5
            		LEFT JOIN spr_kdaraja sk6 ON sk6.id = mih.kriteria_6
            		LEFT JOIN spr_kdaraja sk7 ON sk7.id = mih.kriteria_7
            		LEFT JOIN spr_ortoped so ON so.id=mih.id_ortoped
            		LEFT JOIN spr_nogironlik_sabab sns ON sns.id=mih.tmek_xulosasi
            	WHERE mih.id={$_POST['rowid']}"
        )->result_array();

        $pdf_download = $this->db->query("SELECT id, name FROM pdfoid p WHERE p.id_history={$_POST['rowid']}")->result_array();
        //$pdownload=$pdf_download[0];

        $history = $histories[0];

        ?>

    <ol>
        <li><strong><font color="blue">ТМЭК хулосаси:</font></strong>
            <?php echo $history['tmek_xulosasi']; ?>
        </li>
        <li><strong><font color="blue">Белгиланган фоиз:</font></strong>
            <?php echo $history['foiz']; ?>
        </li>
        <li><strong><font color="blue">Протез-ортопедик маҳсулотлар:</font></strong>
            <?php echo $history['ortoped_name'] . ", " . $history['tdate'];?>
        </li>
        <li><strong><font color="blue">Касаллик варақаси:</font></strong>
            <?php echo $history['knomer'] . ", " . $history['ktashkilot'];?>
        </li>
        <li><strong><font color="blue">Белгиланган ногиронлик мезонлари:
        </font></strong> <?php echo "<b>K1:</b>" . $history['kriteria1'] . "; " . "<b>K2:</b>" . $history['kriteria2'] . "; " . "<b>K3:</b>" . $history['kriteria3'] . "; " . "<b>K4:</b>" . $history['kriteria4'] . "; " . "<b>K5:</b>" . $history['kriteria5'] . "; " . "<b>K6:</b>" . $history['kriteria6'] . "; " . "<b>K7:</b>" . $history['kriteria7'] . "; "; ?>
        </li>
        <li><strong><font color="blue">Тўлиқ реабилитация ёки вафот этган :
            санаси:</font></strong> <?php if ($history['trdate'] == '1900-01-01') {
            echo ' ';
        } else {
            echo $history['trdate'];
        }; ?></li>
        <li><strong><font color="blue">Тарих:</font></strong> <?php echo $history['old']; ?></li>
        <li><strong><font color="blue">Далолатномалар:</font></strong></li>
        <?php foreach ($pdf_download as $p): ?>
        <li><a href="/monitoring/download/<?php echo $p['id']; ?>"><?php echo $p['name']; ?></a></li>
        <?php endforeach; ?>
    </ol>
    <?php

    }

    function download($file_id)
    {
        //print_r($_GET);exit;
        //$file_id=$_GET["file"];
        //lo_export(image.OJpeg, 'C:/F2.jpg')

		$tmpfile = getcwd() . '/upload/' . time();
        $rows = $this->db->query("SELECT name, lo_export(content, '{$tmpfile}') FROM pdfoid WHERE id=$file_id")->result_array();
        $row = $rows[0];

        if (file_exists($tmpfile)) {
            header("Content-Type: application/octet-stream");
            header("Accept-Ranges: bytes");
            header("Content-Length: " . filesize($tmpfile));
            header("Content-Disposition: attachment; filename=" . $row['name']);
            readfile($tmpfile);
        }
        exit;
    }

    function search_excel()
    {
//        print_r($_POST);exit;

        require_once 'functions.php';

        $iddep = ($this->session->userdata['iddep']);
        $is_mon = $this->session->userdata['admin_type'];


        $ufilter = "";
        $id = isset($_POST['fid']) ? $_POST['fid'] : null;
        $familiya = isset($_POST['ffamiliya']) ? $_POST['ffamiliya'] : null;
        $name = isset($_POST['fname']) ? $_POST['fname'] : null;
        $fagefrom = isset($_POST['fagefrom']) ? $_POST['fagefrom'] : null;
        $fagetill = isset($_POST['fagetill']) ? $_POST['fagetill'] : null;
        $date_birth = isset($_POST['fdate_birth']) ? $_POST['fdate_birth'] : null;
        $pass_code = isset($_POST['fpass_code']) ? $_POST['fpass_code'] : null;
        $infl = isset($_POST['finfl']) ? $_POST['finfl'] : null;
        $pass_seriya = isset($_POST['fpass_seriya']) ? $_POST['fpass_seriya'] : null;
        $gender = isset($_POST['fgender']) ? $_POST['fgender'] : null;
        $id_place = isset($_POST['fid_place']) ? $_POST['fid_place'] : null;
        $id_born = isset($_POST['fid_born']) ? $_POST['fid_born'] : null;
        $fupload = isset($_POST['fupload']) ? $_POST['fupload'] : null;
        if (!empty($id)) {
            $ufilter = $ufilter . " and m.id=$id ";
        }
        if (!empty($familiya)) {
            $ufilter = $ufilter . " and upper(m.familiya) like upper('%$familiya%') ";
        }
        if (!empty($name)) {
            $ufilter = $ufilter . " and upper(m.name) like upper('%$name%') ";
        }
        if (!empty($fagefrom) || !empty($fagetill)) {
            if (!empty($fagefrom) && !empty($fagetill)) {
                $ufilter = $ufilter . " and public.\"f_GetAge\"(CURRENT_DATE,m.date_birth) BETWEEN $fagefrom and $fagetill";
            } elseif (empty($fagetill)) {
                $ufilter = $ufilter . " and public.\"f_GetAge\"(CURRENT_DATE,m.date_birth)>=$fagefrom";

            } elseif (empty($fagefrom)) {
                $ufilter = $ufilter . " and public.\"f_GetAge\"(CURRENT_DATE,m.date_birth)<=$fagetill";

            }

        }
        if (!empty($pass_seriya)) {
            $ufilter = $ufilter . " and upper(m.pass_seriya) like upper('%$pass_seriya%') ";
        }
        if (!empty($pass_code)) {
          $ufilter = $ufilter . " and m.pass_code like '%$pass_code%' ";
        }
        if (strlen($infl) == 14) {
          $ufilter = $ufilter . " and m.infl like '%$infl%' ";
        }
        if (!empty($gender)) {
            $ufilter = $ufilter . " and m.gender=$gender";
        }
        if (!empty($id_place)) {
            $ufilter = $ufilter . " and m.id_place=$id_place";
        }
        if (!empty($id_born)) {
            if ($is_mon == 3) {
                $id_parent = $this->db->query("select par_id from spr_region where id=$id_born")->result();
                if ($id_parent[0]->par_id == 0) {
                    $ufilter = $ufilter . " and m.id_born in (select id from spr_region where par_id=$id_born)";
                } else {
                    $ufilter = $ufilter . " and m.id_born=$id_born";
                }
            } else {
                $ufilter = $ufilter . " and m.id_born=$id_born";
            }
        }
        if (!empty($date_birth)) {
            $ufilter = $ufilter . " and m.date_birth like '%$date_birth%'";
        }
        if (!empty($fupload)) {
            $ufilter = $ufilter . " and m.id in (select id_mijoz from pdfoid pd where pd.file_type='$fupload')";
        }

        $wfilter = '';

        $id_malumot = isset($_POST['fid_malumot']) ? $_POST['fid_malumot'] : null;
        $staj = isset($_POST['fstaj']) ? $_POST['fstaj'] : null;
        $working = isset($_POST['fworking']) ? $_POST['fworking'] : null;

        if (!empty($id_malumot)) {
            $wfilter = $wfilter . " and w.id_malumot=$id_malumot ";
        }
        if (!empty($staj)) {
            $wfilter = $wfilter . " and w.staj=$staj ";
        }
        if (!empty($working)) {
            $wfilter = $wfilter . " and w.working=$working ";
        }
        ##############################################################################
        $ddep = '';
        if ($this->session->userdata['admin_type'] == 4) {
            $dep = $this->db->query("select id from spr_depart where par_id=$iddep");
            $dep = $dep->result_array();
            $ddep = array();
            foreach ($dep as $item):
                $ddep[] = $item['id'];
            endforeach;
        }
        if ($this->session->userdata['admin_type'] == 3) {
            $dep = $this->db->query("select id from spr_depart where id not in (0,1,2,3)");
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

        if (!empty($id_depart)) {
            $depart_sql = "m.id_depart in ($id_depart)";
        } else {
            $depart_sql = "m.id_depart in ($iddep,$ddep)";
        }
        #############################################################################

        $hfilter = '';

        $hofilter = '';
        $tmek_xulosasi = isset($_POST['ftmek_xulosasi']) ? $_POST['ftmek_xulosasi'] : null;
        $guruh = isset($_POST['fguruh']) ? $_POST['fguruh'] : null;
        $id_royhat = isset($_POST['fid_royhat']) ? $_POST['fid_royhat'] : null;
        $id_checktype = isset($_POST['fid_checktype']) ? $_POST['fid_checktype'] : null;
        $beg_date = isset($_POST['fbeg_date']) ? $_POST['fbeg_date'] : null;
        $beg_date_gacha = isset($_POST['fbeg_date_gacha']) ? $_POST['fbeg_date_gacha'] : null;
        $end_date = isset($_POST['fend_date']) ? $_POST['fend_date'] : null;
        $end_date_gacha = isset($_POST['fend_date_gacha']) ? $_POST['fend_date_gacha'] : null;
        $id_sabab = isset($_POST['fid_sabab']) ? $_POST['fid_sabab'] : null;
        $seriya = isset($_POST['fseriya']) ? $_POST['fseriya'] : null;
        $nomer = isset($_POST['fnomer']) ? $_POST['fnomer'] : null;
        $pdate = isset($_POST['fpdate']) ? $_POST['fpdate'] : null;
        $md = isset($_POST['fmd']) ? $_POST['fmd'] : null;
        $ekriteria_4= isset($_POST['ekriteria_4']) ? $_POST['ekriteria_4'] : null;
        $ekriteria_6= isset($_POST['ekriteria_6']) ? $_POST['ekriteria_6'] : null;
        $ekriteria_7= isset($_POST['ekriteria_7']) ? $_POST['ekriteria_7'] : null;

        if (!empty($tmek_xulosasi)) {
            $hfilter = $hfilter . " and h.tmek_xulosasi=$tmek_xulosasi";
        }

        if (!empty($guruh)) {
            $hfilter = $hfilter . " and h.guruh=$guruh";
        }

        if (!empty($beg_date) || !empty($beg_date_gacha)) {

            if (!empty($beg_date) && !empty($beg_date_gacha)) {
                $hfilter = $hfilter . " and h.beg_date BETWEEN to_date('$beg_date', 'yyyy-mm-dd') and to_date('$beg_date_gacha', 'yyyy-mm-dd')";
            } elseif (empty($beg_date)) {
                $hfilter = $hfilter . " and h.beg_date<=to_date('$beg_date_gacha', 'yyyy-mm-dd')";

            } elseif (empty($beg_date_gacha)) {
                $hfilter = $hfilter . " and h.beg_date>=to_date('$beg_date', 'yyyy-mm-dd')";

            }
        }

        if (!empty($end_date) || !empty($end_date_gacha)) {
            if (!empty($end_date) && !empty($end_date_gacha)) {
                $hfilter = $hfilter . " and h.end_date BETWEEN to_date('$end_date', 'yyyy-mm-dd') and to_date('$end_date_gacha', 'yyyy-mm-dd')";
            } elseif (empty($end_date)) {
                $hfilter = $hfilter . " and h.end_date<=to_date('$end_date_gacha', 'yyyy-mm-dd')";

            } elseif (empty($end_date_gacha)) {
                $hfilter = $hfilter . " and h.end_date>=to_date('$end_date', 'yyyy-mm-dd')";

            }

        }

        if (!empty($md)) {
            $hfilter = $hfilter . " and h.end_date='2099-01-01'";
        }

        if (!empty($id_sabab)) {
            $hfilter = $hfilter . " and h.id_sabab=$id_sabab";
        }

        if (!empty($ekriteria_4)) {
            $hfilter = $hfilter . " and h.kriteria_4=$ekriteria_4";
        }

        if (!empty($ekriteria_6)) {
            $hfilter = $hfilter . " and h.kriteria_6=$ekriteria_6";
        }

        if (!empty($ekriteria_7)) {
            $hfilter = $hfilter . " and h.kriteria_7=$ekriteria_7";
        }

        if (!empty($id_royhat)) {
            $hfilter = $hfilter . " and h.id_royhat=$id_royhat";
        }
        if (!empty($id_checktype)) {
            $hfilter = $hfilter . " and h.id_checktype=$id_checktype";
        }
        if (!empty($seriya)) {
            $hfilter = $hfilter . " and upper(h.seriya) like upper('%$seriya%')";
        }

        if (!empty($nomer)) {
            $hfilter = $hfilter . "and upper(h.nomer) like upper('%$nomer%')";
        }

        if (!empty($pdate)) {
            $hfilter = $hfilter . " and h.pdate=$pdate";
        }

        $data["depart_name"] = $this->db->query("SELECT name_uz from spr_depart WHERE id=" . $iddep)->result();
        $data["excel_export"] = $this->db->query("SELECT m.id,
                               COALESCE(m.familiya,' ')||' '||COALESCE(m.NAME,' ')||' '|| COALESCE(m.middle,' ') as fio,
                               m.pass_seriya||' '||m.pass_code as pasport,
                               m.infl,
                               sd.name_uz as depart,
                               m.date_birth,
                               sg.name_uz as gender,
                               sr.name_uz as id_born,
                               sp.name_uz as region,
                               m.address,
                               m.notcitizen,
                               sc.name_uz as checktype,
                               sm.name_uz as malumot,
                               me.work as kasbi,
                               sw.name_uz as working,
                               si.code_9 as mkb9,
                               smk.r as mkb10,
                               h.guruh,
                               h.foiz,
                               ss.sabab_uz,
                               h.end_date,
                               h.beg_date,
                               srh.name_uz as rt,
                               h.pcheck,
                               h.kriteria_4,
                               h.kriteria_6,
                               h.kriteria_7,
                               h.seriya||' '||h.nomer as penseriya
                        FROM mijoz m
                        left join spr_region sr on m.id_born=sr.id
                        LEFT JOIN spr_place sp ON m.id_place=sp.id
                        left join spr_gender sg on m.gender=sg.id
                        left join spr_depart sd on sd.id = m.id_depart
                        LEFT JOIN mijoz_edu me on me.id_mijoz=m.id
                        LEFT JOIN spr_malumot sm on sm.id=me.id_malumot
                        left join spr_work sw on me.working=sw.id
                        LEFT JOIN mijoz_ill_history h on h.id_mijoz=m.id
                        left join spr_checktype sc on h.id_checktype = sc.id
                        left join spr_illness si on h.mkb_9=si.id
                        left join spr_illness_10 smk on h.mkb_10=smk.row_num
                        left join spr_sabab ss on h.id_sabab=ss.id
                        left join spr_royhat srh on h.id_royhat=srh.id
                        WHERE {$depart_sql}
                        and h.old=0
                        and m.move=0
						and m.selected in (0,1)
                        {$ufilter}
                        {$wfilter}
                        {$hfilter}
                        {$hofilter}
                        ")->result();

        $this->load->view("/reports/rt", $data);
    }

    function check_pass()
    {
        //print_r($_REQUEST);
        $rows = $this->db->query("SELECT m.id, sd.name_uz as depart, m.name||' '||m.familiya as fio FROM mijoz m
                                        left join spr_depart sd on sd.id=m.id_depart
                                        where  pass_code='{$_REQUEST['pass_code']}' AND pass_seriya='{$_REQUEST['pass_seriya']}'
                                        ")->result();

        if (!empty($rows)) {
            //echo '1';
            echo 'Бу паспорт ' . $rows[0]->depart . ' сонли ТМЭКда рўйхатда турувчи ' . $rows[0]->fio . 'га тегишли';
        } else {
            echo '';
        }
    }

}
