<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Pension extends MyController
{
    function Pension()
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

        if ($this->session->userdata['admin_type'] == 4) {
            $dep = $this->db->query("select id from spr_depart where par_id=$iddep");
            $dep = $dep->result_array();
            $ddep = array();
            foreach ($dep as $item):
                $ddep[] = $item['id'];
            endforeach;
        }
        if (($this->session->userdata['admin_type'] == 3) || ($this->session->userdata['admin_type'] == 5)) {
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
        $data['id_depart'] = $this->db->query("select * from spr_depart where id in ($iddep, $ddep)")->result();
        $data['region'] = $this->db->query('SELECT * FROM spr_region')->result();
        $data['code9'] = $this->db->query('SELECT * FROM spr_illness')->result();
        $data['royhatuz'] = $this->db->query('SELECT * FROM spr_royhat where id in (1,2,3,4,5,6,10,11,12)')->result();
        $data['kritic'] = $this->db->query('SELECT * FROM spr_kritic')->result();
        $data['checktype'] = $this->db->query('SELECT * FROM spr_checktype')->result();
        $data['sabab'] = $this->db->query('SELECT * FROM spr_sabab')->result();
        $data['fmalumot'] = $this->db->query('SELECT * FROM spr_malumot')->result();
        $data['ortoped'] = $this->db->query('SELECT * FROM spr_ortoped')->result();
        $data['working'] = $this->db->query('SELECT * FROM spr_work')->result();
        $data['tm'] = $this->db->query('SELECT * FROM spr_status')->result();
        $data['code_ill_10'] = $this->db->query('select t.group_id, t.name from  spr_illness_10 t where t.r_level=1 ORDER BY t.row_num ')->result();
        $data['kdaraja1'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=1')->result();
        $data['kdaraja2'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=2')->result();
        $data['kdaraja3'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=3')->result();
        $data['kdaraja4'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=4')->result();
        $data['kdaraja5'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=5')->result();
        $data['kdaraja6'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=6')->result();
        $data['kdaraja7'] = $this->db->query('SELECT * FROM spr_kdaraja WHERE id_kritik=7')->result();
        $data['murojaat'] = $this->db->query("SELECT * FROM spr_murojaat_sabab")->result();
        $data['tmek_xulosasi'] = $this->db->query("SELECT * FROM spr_nogironlik_sabab")->result();
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
        $this->load->view("/pension/pension", $data);
    }

    function search()
    {
        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            $iddep = ($this->session->userdata['iddep']);
            $is_mon = $this->session->userdata['admin_type'];

            $ufilter = "";
            $id = isset($_POST['fid']) ? $_POST['fid'] : null;
            $familiya = isset($_POST['ffamiliya']) ? $_POST['ffamiliya'] : null;
            $name = isset($_POST['fname']) ? $_POST['fname'] : null;
            $date_birth = isset($_POST['fdate_birth']) ? $_POST['fdate_birth'] : null;
            $pass_code = isset($_POST['fpass_code']) ? $_POST['fpass_code'] : null;
            $pass_seriya = isset($_POST['fpass_seriya']) ? $_POST['fpass_seriya'] : null;
            $infl = isset($_POST['finfl']) ? $_POST['finfl'] : null;
            $gender = isset($_POST['fgender']) ? $_POST['fgender'] : null;
            $id_place = isset($_POST['fid_place']) ? $_POST['fid_place'] : null;
            $id_born = isset($_POST['fid_born']) ? $_POST['fid_born'] : null;
            $id_depart = isset($_POST['fid_depart']) ? $_POST['fid_depart'] : null;
            if (!empty($id)) {
                $ufilter = $ufilter . " and CAST(m.id AS TEXT) LIKE '%$id%' ";
            }
            if (!empty($familiya)) {
                $ufilter = $ufilter . " and upper(m.familiya) like upper('%$familiya%') ";
            }
            if (!empty($name)) {
                $ufilter = $ufilter . " and upper(m.name) like upper('%$name%') ";
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
                if ($is_mon==3){
                    $id_parent=$this->db->query("select par_id from spr_region where id=$id_born")->result();
                    if ($id_parent[0]->par_id==0){
                        $ufilter = $ufilter . " and m.id_born in (select id from spr_region where par_id=$id_born)";
                    }
                    else{
                        $ufilter = $ufilter . " and m.id_born=$id_born";}
                }  else{
                    $ufilter = $ufilter . " and m.id_born=$id_born";}
            }
            if (!empty($date_birth)) {
                $ufilter = $ufilter . " and m.date_birth like '%$date_birth%'";
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
            if (($this->session->userdata['admin_type'] == 3) || ($this->session->userdata['admin_type'] == 5)) {
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
            $pcheck = isset($_POST['fpcheck']) ? $_POST['fpcheck'] : null;
            $pcheck_date = isset($_POST['fpcheck_date']) ? $_POST['fpcheck_date'] : null;
            $pcheck_date_gacha = isset($_POST['fpcheck_date_gacha']) ? $_POST['fpcheck_date_gacha'] : null;
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

            if (!empty($pcheck)) {
                $hfilter = $hfilter . " and h.pcheck=$pcheck";
            }
            if (!empty($pcheck_date) && !empty($pcheck_date_gacha)) {
                $hfilter = $hfilter . " and h.pcheck_date BETWEEN to_date('$pcheck_date', 'yyyy-mm-dd') and to_date('$pcheck_date_gacha', 'yyyy-mm-dd')";
            }
            if (!empty($guruh)) {
                $hfilter = $hfilter . " and h.guruh=$guruh";
            }


            if (!empty($beg_date) && !empty($beg_date_gacha)) {
                $hfilter = $hfilter . " and h.beg_date BETWEEN to_date('$beg_date', 'yyyy-mm-dd') and to_date('$beg_date_gacha', 'yyyy-mm-dd')";
            }

            if (!empty($end_date) && !empty($end_date_gacha)) {
                $hfilter = $hfilter . " and h.end_date BETWEEN to_date('$end_date', 'yyyy-mm-dd') and to_date('$end_date_gacha', 'yyyy-mm-dd')";
            }

            if (!empty($id_sabab)) {
                $hfilter = $hfilter . " and h.id_sabab=$id_sabab";
            }

            if (!empty($id_royhat)) {
                $hfilter = $hfilter . " and h.id_royhat=$id_royhat";
            } else {
                $hfilter = $hfilter . " and h.id_royhat in (1,2,3,4,5,6,9,10,11,12)";
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

            if (!empty($md)) {
                $hfilter = $hfilter . " and h.end_date='2099-01-01'";
            } else {
                $hfilter = $hfilter . " and h.end_date >= CURRENT_DATE";
            }

            if (!$sidx) $sidx = 1;


            $rows = $this->db->query("SELECT count(*)
                        FROM mijoz m
                        left join spr_region sr on m.id_born=sr.id
                        LEFT JOIN spr_place sp ON m.id_place=sp.id
                        left join spr_gender sg on m.gender=sg.id
                        LEFT JOIN mijoz_ill_history h on h.id_mijoz=m.id
                        WHERE $depart_sql
                        AND h.old=0
                        AND h.approve=1
						and m.selected in (0,1)
                        AND h.pcheck in (1,2)
                        {$ufilter}
                        {$hfilter}
                        ");

            $count = $rows->row()->count;

            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }

            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;

            $hfilterall = "SELECT m.id,
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
                        LEFT JOIN mijoz_ill_history h on h.id_mijoz=m.id
                        WHERE $depart_sql
                        AND h.old=0
                        AND h.approve=1
						and m.selected in (0,1)
                        AND h.pcheck in (1,2)
                        {$ufilter}
                        {$hfilter}
                        ORDER BY $sidx $sord LIMIT $limit OFFSET $start
                        ";
            $res = $this->db->query($hfilterall);
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

    function history($mijoz_id)
    {
        if (empty($mijoz_id)) exit;
        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];

            if (!$sidx) $sidx = 1;
            $rows = $this->db->query("SELECT COUNT(*) AS count FROM mijoz_ill_history where id_mijoz=$mijoz_id and old=0");
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
            		sr.name_uz as royhat_name,
            		sc.name_uz as checktype_name,
            		ss.sabab_uz as sabab_name
            	FROM mijoz_ill_history mih
            		LEFT JOIN spr_nogironlik_sabab sns ON sns.id = mih.tmek_xulosasi
            		LEFT JOIN spr_royhat sr ON sr.id = mih.id_royhat
            		LEFT JOIN spr_checktype sc ON sc.id = mih.id_checktype
            		LEFT JOIN spr_sabab ss ON ss.id = mih.id_sabab
            	WHERE id_mijoz=$mijoz_id
            	and mih.old=0

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
                if ($row['seriya'] == "NOSER") {
                    $row['seriya'] = " ";
                }
                if ($row['end_date'] == "1700-01-01") {
                    $row['end_date'] = "рўйхатда турмайди";
                }
                if ($row['pcheck'] == 1) {
                    $row['pcheck'] = "Текширилди";
                } elseif ($row['pcheck'] == 2) {
                    $row['pcheck'] = "Пенсия оляпти";
                }
                $response->rows[$i]['cell'] = array($row['id'],
                    $row['pcheck'],
                    $row['pcheck_date'],
                    $row['checktype_name'],
                    $row['guruh'],
                    $row['sabab_name'],
                    $row['royhat_name'],
                    $row['beg_date'],
                    $row['end_date'],
                    $row['seriya'],
                    $row['nomer'],
                    $row['pdate'],
                    $row['foiz']
                );
                $i++;
            }
            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function search_excel()
    {
        // print_r($_POST);exit;

        require_once 'functions.php';

        $iddep = ($this->session->userdata['iddep']);
        $is_mon = $this->session->userdata['admin_type'];


        $ufilter = "";
        $id = isset($_POST['fid']) ? $_POST['fid'] : null;
        $familiya = isset($_POST['ffamiliya']) ? $_POST['ffamiliya'] : null;
        $name = isset($_POST['fname']) ? $_POST['fname'] : null;
        $date_birth = isset($_POST['fdate_birth']) ? $_POST['fdate_birth'] : null;
        $pass_code = isset($_POST['fpass_code']) ? $_POST['fpass_code'] : null;
        $infl = isset($_POST['finfl']) ? $_POST['finfl'] : null;
        $pass_seriya = isset($_POST['fpass_seriya']) ? $_POST['fpass_seriya'] : null;
        $gender = isset($_POST['fgender']) ? $_POST['fgender'] : null;
        $id_place = isset($_POST['fid_place']) ? $_POST['fid_place'] : null;
        $id_born = isset($_POST['fid_born']) ? $_POST['fid_born'] : null;
        $id_depart = isset($_POST['fid_depart']) ? $_POST['fid_depart'] : null;
        if (!empty($id)) {
            $ufilter = $ufilter . " and m.id=$id ";
        }
        if (!empty($familiya)) {
            $ufilter = $ufilter . " and upper(m.familiya) like upper('%$familiya%') ";
        }
        if (!empty($name)) {
            $ufilter = $ufilter . " and upper(m.name) like upper('%$name%') ";
        }
        if (!empty($pass_seriya)) {
            $ufilter = $ufilter . " and upper(m.pass_seriya) like upper('%$pass_seriya%') ";
        }
        if (!empty($pass_code)) {
            $ufilter = $ufilter . " and m.pass_code like '%$pass_code%' ";
        }        
        if (!empty($gender)) {
          $ufilter = $ufilter . " and m.infl like '%$infl%' ";
        }
        if (strlen($infl) == 14) {
            $ufilter = $ufilter . " and m.gender=$gender";
        }
        if (!empty($id_place)) {
            $ufilter = $ufilter . " and m.id_place=$id_place";
        }
        if (!empty($id_born)) {
            if ($is_mon==3){
                $id_parent=$this->db->query("select par_id from spr_region where id=$id_born")->result();
                if ($id_parent[0]->par_id==0){
                    $ufilter = $ufilter . " and m.id_born in (select id from spr_region where par_id=$id_born)";
                }
                else{
                    $ufilter = $ufilter . " and m.id_born=$id_born";}
            }   else{
                $ufilter = $ufilter . " and m.id_born=$id_born";}
        }
        if (!empty($date_birth)) {
            $ufilter = $ufilter . " and m.date_birth like '%$date_birth%'";
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
        $pcheck = isset($_POST['fpcheck']) ? $_POST['fpcheck'] : null;
        $pcheck_date = isset($_POST['fpcheck_date']) ? $_POST['fpcheck_date'] : null;
        $pcheck_date_gacha = isset($_POST['fpcheck_date_gacha']) ? $_POST['fpcheck_date_gacha'] : null;
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

        if (!empty($pcheck)) {
            $hfilter = $hfilter . " and h.pcheck=$pcheck";
        }
        if (!empty($pcheck_date) && !empty($pcheck_date_gacha)) {
            $hfilter = $hfilter . " and h.pcheck_date BETWEEN to_date('$pcheck_date', 'yyyy-mm-dd') and to_date('$pcheck_date_gacha', 'yyyy-mm-dd')";
        }
        if (!empty($guruh)) {
            $hfilter = $hfilter . " and h.guruh=$guruh";
        }


        if (!empty($beg_date) && !empty($beg_date_gacha)) {
            $hfilter = $hfilter . " and h.beg_date BETWEEN to_date('$beg_date', 'yyyy-mm-dd') and to_date('$beg_date_gacha', 'yyyy-mm-dd')";
        }

        if (!empty($end_date) && !empty($end_date_gacha)) {
            $hfilter = $hfilter . " and h.end_date BETWEEN to_date('$end_date', 'yyyy-mm-dd') and to_date('$end_date_gacha', 'yyyy-mm-dd')";
        }

        if (!empty($id_sabab)) {
            $hfilter = $hfilter . " and h.id_sabab=$id_sabab";
        }

        if (!empty($id_royhat)) {
            $hfilter = $hfilter . " and h.id_royhat=$id_royhat";
        } else {
            $hfilter = $hfilter . " and h.id_royhat in (1,2,3,4,5,6,9,10,11,12)";
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

        if (!empty($md)) {
            $hfilter = $hfilter . " and h.end_date='2099-01-01'";
        } else {
            $hfilter = $hfilter . " and h.end_date >= CURRENT_DATE";
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
                               h.guruh,
                               ss.sabab_uz,
                               h.end_date,
                               h.beg_date,
                               srh.name_uz as rt,
                               h.seriya||' '||h.nomer as penseriya,
                               h.pdate
                        FROM mijoz m
                        left join spr_region sr on m.id_born=sr.id
                        LEFT JOIN spr_place sp ON m.id_place=sp.id
                        left join spr_gender sg on m.gender=sg.id
                        left join spr_depart sd on sd.id = m.id_depart
                        LEFT JOIN mijoz_edu me on me.id_mijoz=m.id
                        LEFT JOIN spr_malumot sm on sm.id=me.id_malumot
                        LEFT JOIN mijoz_ill_history h on h.id_mijoz=m.id
                        left join spr_checktype sc on h.id_checktype = sc.id
                        left join spr_sabab ss on h.id_sabab=ss.id
                        left join spr_work sw on me.working=sw.id
                        left join spr_royhat srh on h.id_royhat=srh.id
                        WHERE {$depart_sql}
                        and h.old=0
                        AND h.approve=1
						and m.selected in (0,1)
                        and h.pcheck in (1,2)
                        {$ufilter}
                        {$hfilter}
                        ")->result();
        ///print_r($data["excel_export"]);exit;

        $this->load->view("/reports/rtpension", $data);
    }

}
