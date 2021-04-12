<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Respmonitoring extends MyController
{
    function Respmonitoring()
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
        if ($this->session->userdata['admin_type'] == 3) {
            $data = array();
            $data['menus'] = $this->_build_menu();
            $iddep = $this->session->userdata("iddep");
            if ($this->session->userdata['admin_type'] == 3) {
                $dep = $this->db->query("select id from spr_depart where id not in (0,1,2)");
                $dep = $dep->result_array();
                $ddep = array();
                foreach ($dep as $item):
                    $ddep[] = $item['id'];
                endforeach;
                $ddep = implode(", ", $ddep);
            }

            //$data['error_depart'] = $this->db->query("select * from spr_depart where id not in (0,1,2) ")->result();
            $data['id_depart'] = $this->db->query("select * from spr_depart where id in ($iddep, $ddep) order by id")->result();
            $data['region'] = $this->db->query('SELECT * FROM spr_region')->result();
            $data['vregion'] = $this->db->query('SELECT * FROM spr_region where par_id=0')->result();
            $data['code9'] = $this->db->query('SELECT * FROM spr_illness')->result();
            $data['royhatuz'] = $this->db->query('SELECT * FROM spr_royhat')->result();
            $data['kritic'] = $this->db->query('SELECT * FROM spr_kritic')->result();
            $data['checktype'] = $this->db->query('SELECT * FROM spr_checktype')->result();
            $data['sabab'] = $this->db->query('SELECT * FROM spr_sabab order by id')->result();
            //$data['code_ill_10'] = $this->db->query('select t.row_num, t.r from  spr_illness_10 t where t.r_level in (3,4) ORDER BY t.row_num LIMIT 14200 OFFSET 0')->result();
            $data['fmalumot'] = $this->db->query('SELECT * FROM spr_malumot')->result();
            $data['ortoped'] = $this->db->query('SELECT * FROM spr_ortoped')->result();
            $data['working'] = $this->db->query('SELECT * FROM spr_work')->result();
            $data['tm'] = $this->db->query('SELECT * FROM spr_status')->result();
            $data['code_ill_10'] = $this->db->query('select t.group_id, t.name_uz from  spr_illness_10 t where t.r_level=1 ORDER BY t.row_num ')->result();
            $data['kdaraja1'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=1')->result();
            $data['kdaraja2'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=2')->result();
            $data['kdaraja3'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=3')->result();
            $data['kdaraja4'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=4')->result();
            $data['kdaraja5'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=5')->result();
            $data['kdaraja6'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=6')->result();
            $data['kdaraja7'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=7')->result();
            $data['murojaat'] = $this->db->query("SELECT * FROM spr_murojaat_sabab")->result();
            $data['tmek_xulosasi'] = $this->db->query("SELECT * FROM spr_nogironlik_sabab")->result();
            $data['seriya'] = $this->db->query("SELECT * FROM spr_spseriya")->result();
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
            $this->load->view("/monitoring/respmonitoring", $data);
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
            $beg_date = isset($_POST['fbeg_date']) ? $_POST['fbeg_date'] : null;
            $beg_date_gacha = isset($_POST['fbeg_date_gacha']) ? $_POST['fbeg_date_gacha'] : null;
            $end_date = isset($_POST['fend_date']) ? $_POST['fend_date'] : null;
            $end_date_gacha = isset($_POST['fend_date_gacha']) ? $_POST['fend_date_gacha'] : null;
            $id_sabab = isset($_POST['fid_sabab']) ? $_POST['fid_sabab'] : null;
            $seriya = isset($_POST['fseriya']) ? $_POST['fseriya'] : null;
            $nomer = isset($_POST['fnomer']) ? $_POST['fnomer'] : null;
            $pdate = isset($_POST['fpdate']) ? $_POST['fpdate'] : null;
            $md = isset($_POST['fmd']) ? $_POST['fmd'] : null;

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
                               sd.code,
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
                               m.notcitizen
                        FROM mijoz m
                        left join spr_region sr on m.id_born=sr.id
                        LEFT JOIN spr_place sp ON m.id_place=sp.id
                        left join spr_gender sg on m.gender=sg.id
                        left join spr_depart sd on m.id_depart=sd.id
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
                    //print_r($row);exit;
                    $response->rows[$i]['cell'] = array($row['id'],
                        $row['code'],
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
                        $row['notcitizen']
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
            $id_born = $_POST['id_born'];
            $id_place = $_POST['id_place'];
            $address = $_POST['address'];
            $notcitizen = $_POST['notcitizen'];

            $this->db->trans_begin();

            if ($_POST['oper'] == "add") {

                $result = $this->db->query("INSERT INTO mijoz (id_depart, familiya, name, middle, date_birth, gender, pass_seriya, pass_code, id_born, id_place, address, notcitizen)
                                            VALUES ($iddep,'$familiya', '$name', '$middle', '$date_birth', $gender, '$pass_seriya', '$pass_code', $id_born, $id_place, '$address', $notcitizen)");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE mijoz
                                            SET familiya='$familiya',
                                                name='$name',
                                                middle='$middle',
                                                date_birth='$date_birth',
                                                gender = $gender,
                                                pass_seriya='$pass_seriya',
                                                pass_code='$pass_code',
                                                id_born=$id_born,
                                                id_place=$id_place,
                                                address='$address',
                                                notcitizen=$notcitizen
                                            WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {

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
        //print_r($_POST);
        // exit;
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
        }

        if (empty($_POST['foiz'])) $_POST['foiz'] = 0;
        if (empty($_POST['knomer'])) $_POST['knomer'] = NULL;
        if (empty($_POST['ktashkilot'])) $_POST['ktashkilot'] = NULL;
        if (empty($_POST['ktashkilot'])) $_POST['ktashkilot'] = NULL;
        if (empty($_POST['tdate'])) $_POST['tdate'] = NULL;
        if (empty($_POST['trdate'])) $_POST['trdate'] = '1900-01-01';
        if (empty($_POST['tr88'])) $_POST['tr88'] = NULL;
        if (empty($_POST['tr88date'])) $_POST['tr88date'] = NULL;
        if (empty($_POST['tr88dmp'])) $_POST['tr88dmp'] = NULL;
        if (empty($_POST['amb'])) $_POST['amb'] = NULL;
        if (empty($_POST['ambdate'])) $_POST['ambdate'] = NULL;
        if (empty($_POST['ambdmp'])) $_POST['ambdmp'] = NULL;
        if (empty($_POST['ktk'])) $_POST['ktk'] = NULL;
        if (empty($_POST['ktkdate'])) $_POST['ktkdate'] = NULL;
        if (empty($_POST['ktkdmp'])) $_POST['ktkdmp'] = NULL;
        if (empty($_POST['asos'])) $_POST['asos'] = NULL;
        if (empty($_POST['guruh'])) $_POST['guruh'] = 0;
        //if (empty($_POST['beg_date'])) $_POST['beg_date'] = NULL;
        if (empty($_POST['seriya'])) $_POST['seriya'] = NULL;
        if (empty($_POST['nomer'])) $_POST['nomer'] = NULL;
        if (empty($_POST['pdate'])) $_POST['pdate'] = NULL;


        if ($_POST['code_ill_10_par'] == 0) {
            $_POST['mkb_10'] = 0;
        }

        if (!empty($_POST['end_date_combo'])) {
            $date = date($_POST['beg_date']);
            if ($_POST['end_date_combo'] == 1) {
                $day = date("d", strtotime($date));
                if ($day < 31) {
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
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +6 month");
                    $enddate = date("Y-m-d", $date);
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
            'murojaat_sana' => $_POST['murojaat_sana'],
            'protocol' => $_POST['protocol'],
            'murojaat_sababi' => $_POST['murojaat_sababi'],
            'tr88' => $_POST['tr88'],
            'tr88date' => $_POST['tr88date'],
            'tr88dmp' => $_POST['tr88dmp'],
            'amb' => $_POST['amb'],
            'ambdate' => $_POST['ambdate'],
            'ambdmp' => $_POST['ambdmp'],
            'ktk' => $_POST['ktk'],
            'ktkdate' => $_POST['ktkdate'],
            'ktkdmp' => $_POST['ktkdmp'],
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
        );

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
            }
        } else {
            $this->db->where('id', $id);
            $this->db->update('mijoz_ill_history', $history);
        }
        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }


    function ehistory_save()
    {
        //print_r($_POST);
        // exit;
        if (empty($_POST['id_mijoz']) || ($_POST['id_mijoz'] == 'undefined')) exit;

        $id = $_POST['id'];
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
            $date = date($_POST['ebeg_date']);
            if ($_POST['eend_date_combo'] == 1) {
                $day = date("d", strtotime($date));
                if ($day < 31) {
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
                    $date = strtotime(date("Y-m-d", strtotime($date)) . " +6 month");
                    $enddate = date("Y-m-d", $date);
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
    }

    function mkb10($mkb)
    {
        $rows = $this->db->query("select row_num, r  from spr_illness_10  where  r_level in (3,4) and group_id=$mkb ORDER BY row_num")->result();
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
            	select mm.*,
                      sms.murojaat_sababi as sabab_nomi
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
                    $row['murojaat_sana'],
                    $row['sabab_nomi'],
                    $row['tr88'],
                    $row['tr88date'],
                    $row['tr88dmp'],
                    $row['amb'],
                    $row['ambdate'],
                    $row['ambdmp'],
                    $row['ktk'],
                    $row['ktkdate'],
                    $row['ktkdmp'],
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
                    $row['old'],
                    $row['pcheck']
                );
                $i++;
            }
            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
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
    function history_detail()
    {
        $histories = $this->db->query(
            "SELECT
            		mih.foiz, mih.id_ortoped, mih.knomer, mih.ktashkilot, mih.tdate, mih.trdate, mih.approve, mih.pcheck,
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
            	WHERE mih.id={$_POST['rowid']}"
        )->result_array();
        $pdf_download = $this->db->query("SELECT id, name FROM pdfoid p WHERE p.id_history={$_POST['rowid']}")->result_array();
        $history = $histories[0];
        ?>

    <ol>
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
        <li><strong><font color="blue">Тўлиқ реабилитация, вафот этган ёки кўчиб кетган
            санаси:</font></strong> <?php if ($history['trdate'] == '1900-01-01') {
            echo ' ';
        } else {
            $history['trdate'];
        }; ?></li>
        <li><strong><font color="blue">Тарих:</font></strong> <?php echo $history['old']; ?></li>
        <li><strong><font color="blue">Тасдиқланганлиги:</font></strong> <?php echo $history['approve']; ?></li>
        <li><strong><font color="blue">ПЖ маълумоти:</font></strong> <?php echo $history['pcheck']; ?></li>
        <li><strong><font color="blue">Бириктирилган хужжатлар:</font></strong></li>
        <?php foreach ($pdf_download as $p): ?>
        <li><a href="/respmonitoring/download/<?php echo $p['id']; ?>"><?php echo $p['name']; ?></a></li>
        <?php endforeach; ?>
    </ol>
    <?php

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
        $pass_seriya = isset($_POST['fpass_seriya']) ? $_POST['fpass_seriya'] : null;
        $infl = isset($_POST['finfl']) ? $_POST['finfl'] : null;
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
        $beg_date = isset($_POST['fbeg_date']) ? $_POST['fbeg_date'] : null;
        $beg_date_gacha = isset($_POST['fbeg_date_gacha']) ? $_POST['fbeg_date_gacha'] : null;
        $end_date = isset($_POST['fend_date']) ? $_POST['fend_date'] : null;
        $end_date_gacha = isset($_POST['fend_date_gacha']) ? $_POST['fend_date_gacha'] : null;
        $id_sabab = isset($_POST['fid_sabab']) ? $_POST['fid_sabab'] : null;
        $seriya = isset($_POST['fseriya']) ? $_POST['fseriya'] : null;
        $nomer = isset($_POST['fnomer']) ? $_POST['fnomer'] : null;
        $pdate = isset($_POST['fpdate']) ? $_POST['fpdate'] : null;
        $md = isset($_POST['fmd']) ? $_POST['fmd'] : null;

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
                               h.foiz,
                               srh.name_uz as rt,
                               h.approve,
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

        $this->load->view("/reports/rt_resp", $data);
    }

    function check_pass()
    {
        //print_r($_REQUEST);
        $rows = $this->db->query("SELECT m.id, sd.name_uz as depart, m.name||' '||m.familiya as fio FROM mijoz m
                                        left join spr_depart sd on sd.id=m.id_depart
                                        where pass_code='{$_REQUEST['pass_code']}' AND pass_seriya='{$_REQUEST['pass_seriya']}'
                                        ")->result();

        if (!empty($rows)) {
            //echo '1';
            echo 'Бу паспорт ' . $rows[0]->depart . ' сонли ТМЭКда рўйхатда турувчи ' . $rows[0]->fio . 'га тегишли';
        } else {
            echo '';
        }
    }

    function errors()
    {
//        print_r($_POST);exit;
        $id_depart = $_POST["error_depart"];
        //  print_r($id_depart);
        $par_id_dep = $this->db->query("select par_id from spr_depart sd where sd.id=$id_depart")->result();
        // print_r($par_id_dep);exit;
        if ($par_id_dep[0]->par_id == 2) {

            $dep = $this->db->query("select id from spr_depart where par_id=$id_depart or id=$id_depart");
            $dep = $dep->result_array();
            $ddep = array();
            foreach ($dep as $item):
                $ddep[] = $item['id'];
            endforeach;
            $ddep = implode(", ", $ddep);

        } elseif ($par_id_dep[0]->par_id == 1) {
            $dep = $this->db->query("select id from spr_depart where id not in (0,1,2)");
            $dep = $dep->result_array();
            $ddep = array();
            foreach ($dep as $item):
                $ddep[] = $item['id'];
            endforeach;
            $ddep = implode(", ", $ddep);
        } else {
            $ddep = $id_depart;
        }

        $depart_sql = "m.id_depart in ($ddep)";
        //   print_r($depart_sql);exit;

        $data["excel_export"] = $this->db->query("select sd.name_uz as depart,m.id, m.NAME,m.familiya,m.middle,
                    m.date_birth,sg.name_uz as gender, sr.name_uz as region,
                    m.pass_seriya, m.pass_code, m.address  from mijoz m
                    left join spr_depart sd on sd.id=m.id_depart
                    left join spr_gender sg on m.gender=sg.id
                    left join spr_region sr on sr.id=m.id_born
                    left join mijoz_ill_history mih on mih.id_mijoz=m.id
                  where {$depart_sql}
                    and m.pass_seriya not in (select sps.pass_seriya from spr_pass_seriya sps)
                    and mih.old=0
                    and mih.approve=1
                    and m.MOVE=0
                    and mih.end_date>=CURRENT_DATE
                    and mih.id_royhat in (1,3,4,5,6,11,12)
                    order by m.id_depart"
        )->result();

//            print_r($data);exit;
        $this->load->view("/reports/rt_pass_error", $data);
    }

    function tasdiq()
    {
        // print_r($_POST);
        $tasdiq_sana = $_POST["tasdiq-sana"];
        $hfilter = "and mih.approve_date BETWEEN to_date('$tasdiq_sana', 'yyyy-mm-dd') and to_date('$tasdiq_sana', 'yyyy-mm-dd') group by m.id_depart";
        $data["excel_export"] = $this->db->query("select depart,sd.name_uz,sum(um.umumiysoni) as all,sum(um.rsoni) as rt,sum(um.pcheck) as pcheck, sum(day_approve) as day_approve, sum(pxato) as pxato from  (
                                                                                select m.id_depart as depart,count(m.id) as umumiysoni,0 as rsoni,0 as pcheck, 0 as day_approve, 0 as pxato  from mijoz m
                                                                                                where m.move=0
																								and m.selected in (0,1)
                                                                                               group by m.id_depart
                                                                                UNION
                                                                                select m.id_depart as depart,0 as umumiysoni, count(m.id) as rsoni, 0 as pcheck,0 as day_approve, 0 as pxato  from mijoz m
                                                                                              left join mijoz_ill_history mih on m.id=mih.id_mijoz
                                                                                              where mih.id_royhat in (1,2,3,4,5,6,10,11,12)
                                                                                              and mih.OLD=0
                                                                                              and m.MOVE=0
																							  and m.selected in (0,1)
                                                                                              and mih.approve=1
                                                                                              and mih.end_date>=CURRENT_DATE
                                                                                              group by m.id_depart
                                                                                union
                                                                                select m.id_depart as depart,0 as umumiysoni,0 as rsoni,count(m.id) as pcheck,0 as day_approve, 0 as pxato  from mijoz m
                                                                                              left join mijoz_ill_history mih on m.id=mih.id_mijoz
                                                                                              where mih.id_royhat in (1,2,3,4,5,6,10,11,12)
                                                                                              and mih.OLD=0
                                                                                              and mih.approve=1
                                                                                              and m.MOVE=0
																							  and m.selected in (0,1)
                                                                                              and mih.pcheck in (1,2)
                                                                                              and mih.end_date>=CURRENT_DATE
                                                                                              group by m.id_depart
                                                                                union
                                                                                select m.id_depart as depart,0 as umumiysoni, 0 as rsoni, 0 as pcheck,count(*) as day_approve, 0 as pxato   from mijoz m
                                                                                              left join mijoz_ill_history mih on m.id=mih.id_mijoz
                                                                                              where mih.id_royhat in (1,2,3,4,5,6,10,11,12)
                                                                                              and mih.OLD=0
                                                                                              and mih.approve=1
                                                                                              and m.MOVE=0
																							  and m.selected in (0,1)
                                                                                              and mih.end_date>=CURRENT_DATE
                                                                                              {$hfilter}
                                                                                union
                                                                                select m.id_depart as depart,0 as umumiysoni, 0 as rsoni, 0 as pcheck,0 as day_approve, count(*) as pxato  from mijoz m
                                                                                              left join mijoz_ill_history mih on mih.id_mijoz=m.id
                                                                                              where m.pass_seriya not in (select sps.pass_seriya from spr_pass_seriya sps)
                                                                                              and mih.old=0
                                                                                              and mih.approve=1
                                                                                              and m.MOVE=0
																							  and m.selected in (0,1)
                                                                                              and mih.end_date>=CURRENT_DATE
                                                                                              and mih.id_royhat in (1,3,4,5,6,11,12)
                                                                                              group by m.id_depart
                                                                                               ) um
                                                                                left join spr_depart sd on um.depart=sd.id
                                                                                              group by um.depart,sd.name_uz  order by um.depart")->result();

        //  print_r($data["excel_export"]);exit;
        $this->load->view("/reports/tasdiq_soni", $data);
    }

    function table01()
    {
        require_once 'functions.php';
        //  print_r($_POST);
        $sana = $_POST["table01-sana"];
        $day = date("d", strtotime($sana));
        $month = date("m", strtotime($sana));
        $year = date("Y", strtotime($sana));
        $beg_date = date("$year-$month-$day 00:00:00");
        $end_date = date("$year-$month-$day 23:59:59");
        //echo $beg_date, $end_date;

        //exit;

        //  print_r($sana);
        // if (!empty($sana)) {
        $hfilter = " t.add_date BETWEEN '$beg_date' and '$end_date' group by t.pass_seriya,t.pass_code,t.citizen,t.seriya,t.nomer,t.beg_date,t.end_date,t.guruh, t.foiz, t.opekun, m.name, m.familiya, mih.pcheck ";
        // }
        //   print_r($hfilter);

        $data["excel_export"] = $this->db->query("select t.pass_seriya,t.pass_code,t.citizen,t.seriya,t.nomer,t.beg_date,t.end_date,t.guruh,t.foiz,t.opekun,count(*) as count ,m.name, m.familiya,mih.pcheck   from table01 t
                                                   left join mijoz m ON m.pass_seriya=t.pass_seriya and m.pass_code=t.pass_code
                                                   left join mijoz_ill_history mih on m.id=mih.id_mijoz and mih.pcheck=1
                                                   where
                                                    {$hfilter}
                                                   ")->result();

        // print_r($data["excel_export"]);exit;
        $this->load->view("/reports/table01_error", $data);
    }

    function et()
    {
//        print_r($_POST);exit;
        $id_depart = $_POST["et-depart"];
        //  print_r($id_depart);
        $par_id_dep = $this->db->query("select par_id from spr_depart sd where sd.id=$id_depart")->result();
        // print_r($par_id_dep);exit;
        if ($par_id_dep[0]->par_id == 2) {

            $dep = $this->db->query("select id from spr_depart where par_id=$id_depart or id=$id_depart");
            $dep = $dep->result_array();
            $ddep = array();
            foreach ($dep as $item):
                $ddep[] = $item['id'];
            endforeach;
            $ddep = implode(", ", $ddep);

        } elseif ($par_id_dep[0]->par_id == 1) {
            $dep = $this->db->query("select id from spr_depart where id not in (0,1,2)");
            $dep = $dep->result_array();
            $ddep = array();
            foreach ($dep as $item):
                $ddep[] = $item['id'];
            endforeach;
            $ddep = implode(", ", $ddep);
        } else {
            $ddep = $id_depart;
        }

        $depart_sql = "m.id_depart in ($ddep)";
        //   print_r($depart_sql);exit;

        $data["excel_export"] = $this->db->query("select sd.name_uz as depart,m.id, m.NAME,m.familiya,m.middle,
                    m.date_birth,sg.name_uz as gender, sr.name_uz as region,
                    m.pass_seriya, m.pass_code, m.address  from mijoz m
                    left join spr_depart sd on sd.id=m.id_depart
                    left join spr_gender sg on m.gender=sg.id
                    left join spr_region sr on sr.id=m.id_born
                    left join mijoz_ill_history mih on mih.id_mijoz=m.id
                  where {$depart_sql}
                    and mih.old=0
                    and mih.approve=0
                    and m.MOVE=0
                    and mih.end_date>=CURRENT_DATE
                    and mih.id_royhat in (1,3,4,5,6,11,12)
                    order by m.id_depart"
        )->result();

//            print_r($data);exit;
        $this->load->view("/reports/et_error", $data);
    }

    function rt_umumiy()
{

    $data["excel_export"] = $this->db->query("select depart,sd.name_uz,sum(rt) as rt,sum(ve) as ve, sum(tr) as tr, sum(mt) as mt,sum(urt) as urt,sum(uve) as uve, sum(utr) as utr, sum(umt) as umt from
                                                    (select m.id_depart as depart, count(*) as rt, 0 as ve, 0 as tr,0 as mt, 0 as urt, 0 as uve, 0 as utr,0 as umt from mijoz m
                                                                      left join mijoz_ill_history mih on mih.id_mijoz=m.id
                                                                      where m.id_depart not in (0,1,2)
                                                                      and m.MOVE=0
                                                                      and mih.OLD=0
                                                                      and mih.approve=1
																	  and m.selected in (0,1)
                                                                      and mih.end_date >=CURRENT_DATE
                                                                      and mih.id_royhat in (1,3,4,5,6,11,12)
                                                                      group by id_depart
                                                    union
                                                    select m.id_depart as depart, 0 as rt, count(*) as ve, 0 as tr, 0 as mt, 0 as urt, 0 as uve, 0 as utr,0 as umt from mijoz m
                                                                      left join mijoz_ill_history mih on mih.id_mijoz=m.id
                                                                      where m.id_depart not in (0,1,2)
                                                                      and m.MOVE=0
                                                                      and mih.OLD=0
                                                                      and mih.approve=1
																	  and m.selected in (0,1)
                                                                     -- and mih.end_date >=CURRENT_DATE
                                                                      and mih.id_royhat in (7)
                                                                      group by id_depart
                                                    union
                                                    select m.id_depart as depart, 0 as rt, 0 as ve, count(*) as tr, 0 as mt, 0 as urt, 0 as uve, 0 as utr,0 as umt  from mijoz m
                                                                      left join mijoz_ill_history mih on mih.id_mijoz=m.id
                                                                      where m.id_depart not in (0,1,2)
                                                                      and m.MOVE=0
                                                                      and mih.OLD=0
                                                                      and mih.approve=1
																	  and m.selected in (0,1)
                                                                     -- and mih.end_date >=CURRENT_DATE
                                                                      and mih.id_royhat in (9)
                                                                      group by id_depart
                                                    union
                                                    select m.id_depart as depart, 0 as rt, 0 as ve, 0 as tr, count(*) as mt, 0 as urt, 0 as uve, 0 as utr,0 as umt  from mijoz m
                                                                      left join mijoz_ill_history mih on mih.id_mijoz=m.id
                                                                      where m.id_depart not in (0,1,2)
                                                                      and m.MOVE=0
                                                                      and mih.OLD=0
                                                                      and mih.approve=1
																	  and m.selected in (0,1)
                                                                      and mih.end_date < CURRENT_DATE
                                                                      and mih.id_royhat in (1,3,4,5,6,11,12)
                                                                      group by id_depart
                                                    union
                                                    select m.id_depart as depart, 0 as rt, 0 as ve, 0 as tr, 0 as mt, count(*) as urt, 0 as uve, 0 as utr,0 as umt from mijoz m
                                                                      left join mijoz_ill_history mih on mih.id_mijoz=m.id
                                                                      where m.id_depart not in (0,1,2)
                                                                      and m.MOVE=0
                                                                      and mih.OLD=0
                                                                     -- and mih.approve=1
																	 and m.selected in (0,1)
                                                                      and mih.end_date >=CURRENT_DATE
                                                                      and mih.id_royhat in (1,3,4,5,6,11,12)
                                                                      group by id_depart
                                                    union
                                                    select m.id_depart as depart, 0 as rt, 0 as ve, 0 as tr, 0 as mt, 0 as urt, count(*) as uve, 0 as utr,0 as umt from mijoz m
                                                                      left join mijoz_ill_history mih on mih.id_mijoz=m.id
                                                                      where m.id_depart not in (0,1,2)
                                                                      and m.MOVE=0
                                                                      and mih.OLD=0
                                                                     -- and mih.approve=1
																	 and m.selected in (0,1)
                                                                     -- and mih.end_date >=CURRENT_DATE
                                                                      and mih.id_royhat in (7)
                                                                      group by id_depart
                                                    union
                                                    select m.id_depart as depart, 0 as rt, 0 as ve, 0 as tr,0 as mt,0 as urt, 0 as uve, count(*) as utr,0 as umt  from mijoz m
                                                                      left join mijoz_ill_history mih on mih.id_mijoz=m.id
                                                                      where m.id_depart not in (0,1,2)
                                                                      and m.MOVE=0
                                                                      and mih.OLD=0
                                                                     -- and mih.approve=1
																	 and m.selected in (0,1)
                                                                     -- and mih.end_date >=CURRENT_DATE
                                                                      and mih.id_royhat in (9)
                                                                      group by id_depart
                                                    union
                                                    select m.id_depart as depart, 0 as rt, 0 as ve, 0 as tr,0 as mt,0 as urt, 0 as uve, 0 as utr, count(*) as umt  from mijoz m
                                                                      left join mijoz_ill_history mih on mih.id_mijoz=m.id
                                                                      where m.id_depart not in (0,1,2)
                                                                      and m.MOVE=0
                                                                      and mih.OLD=0
                                                                      --and mih.approve=1
																	  and m.selected in (0,1)
                                                                      and mih.end_date < CURRENT_DATE
                                                                      and mih.id_royhat in (1,3,4,5,6,11,12)
                                                                      group by id_depart
                                                    ) um
                                                    left join spr_depart sd ON depart=sd.id
                                                    group by depart,sd.name_uz order by depart")->result();


    $this->load->view("/reports/rt_um_error", $data);
}

    function old_save()
    {

        $id = $_POST["id"];
        $old = $_POST["old"];
        if (isset($old)) {
            $this->db->query("UPDATE mijoz_ill_history SET old=$old WHERE id=$id");
        }
    }

    function pcheck_save()
    {

        $id = $_POST["id"];
        $pcheck = $_POST["id_pcheck"];
        if (isset($pcheck)) {
            $this->db->query("UPDATE mijoz_ill_history SET pcheck=$pcheck, pcheck_date=CURRENT_DATE WHERE id=$id");
        }
    }

    function tb()
    {
        $data["excel_export"] = $this->db->query("select sr.par_id, sr.name_uz,sum(rt) as rt,sum(pcheck) as pcheck from
                                                            (select m.id_born as region, count(*) as rt, 0 as pcheck from mijoz m
                                                                     left join mijoz_ill_history mih on m.id=mih.id_mijoz
                                                                     where
                                                                     mih.id_royhat in (1,3,4,5,6,11,12)
                                                                     and mih.OLD=0
                                                                     and mih.approve=1
																	 and m.selected in (0,1)
                                                                     and mih.end_date>=CURRENT_DATE
                                                                       group by m.id_born
                                                  union
                                                            select m.id_born as region, 0 as rt, count(*) as pcheck from mijoz m
                                                                     left join mijoz_ill_history mih on m.id=mih.id_mijoz
                                                                     where
                                                                     mih.id_royhat in (1,3,4,5,6,11,12)
                                                                     and mih.OLD=0
                                                                     and mih.approve=1
																	 and m.selected in (0,1)
                                                                     and mih.pcheck in (1,2)
                                                                     and mih.end_date>=CURRENT_DATE
                                                                     group by m.id_born) as vt
                                                                     left join spr_region sr on vt.region=sr.id
                                                                     group by sr.par_id, sr.name_uz
                                                                     order by sr.par_id
         ")->result();
        $this->load->view("/reports/tb_error", $data);
    }

    function full()
    {
        $id = $_POST["id_region"];
       // print_r($id);exit;
        $data["vnogiron"] = $this->db->query("SELECT m.id,
                                                            m.familiya,m.NAME,m.middle,
                                                            m.date_birth,
                                                            concat(m.pass_seriya,' ',m.pass_code) as pasport,
                                                            sd.name_uz as depart,
                                                            sg.name_uz as gender,
                                                            sr.name_uz as id_born,
                                                            sp.name_uz as region,
                                                            m.address,
                                                            m.notcitizen,
                                                            sc.name_uz as checktype,
                                                            smk.r as mkb10,
                                                            h.guruh,
                                                            ss.sabab_uz,
                                                            h.end_date,
                                                            h.beg_date,
                                                            srh.name_uz as rt,
                                                            concat(h.seriya,' ',h.nomer) as seriya,
                                                            h.foiz,
                                                            h.old_holat_reabilit,
                                                            h.pcheck_date,
                                                            m.move
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
                                                            WHERE m.id_born in (select id from spr_region srr where srr.par_id=$id)
                                                            and h.old=0
															and m.selected in (0,1)
         ")->result();
        $this->load->view("/reports/fullinvalid", $data);
    }

}