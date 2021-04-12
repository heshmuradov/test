<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Approve extends MyController
{
    function Approve()
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
        if (($this->session->userdata['admin_type'] != 3)&&($this->session->userdata['admin_type'] != 5)) {
        $data = array();
        $data['menus'] = $this->_build_menu();
        $iddep = $this->session->userdata("iddep");
        $data['region'] = $this->db->query('SELECT * FROM spr_region')->result();
        $data['code9'] = $this->db->query('SELECT * FROM spr_illness')->result();
        $data['royhatuz'] = $this->db->query('SELECT * FROM spr_royhat')->result();
        $data['kritic'] = $this->db->query('SELECT * FROM spr_kritic')->result();
        $data['checktype'] = $this->db->query('SELECT * FROM spr_checktype')->result();
        $data['sabab'] = $this->db->query('SELECT * FROM spr_sabab')->result();
        $data['code_ill_10'] = $this->db->query('select t.row_num, t.r from  spr_illness_10 t where t.r_level in (3,4) ORDER BY t.row_num LIMIT 14200 OFFSET 0')->result();
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
        $this->load->view("/monitoring/approve", $data);
        }
        else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function search()
    {
        header("Content-type: text/x-json");
        try {
            $page = isset($_REQUEST['page'])?$_REQUEST['page']:1;
            $limit = isset($_REQUEST['rows'])?$_REQUEST['rows']:15;
            $sidx = isset($_REQUEST['sidx'])?$_REQUEST['sidx']:'familiya';
            $sord = isset($_REQUEST['sord'])?$_REQUEST['sord']:'asc';
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
                } else{
                    $ufilter = $ufilter . " and m.id_born=$id_born";}
            }
            if (!empty($date_birth)) {
                $ufilter = $ufilter . " and m.date_birth like '%$date_birth%'";
            }


            if (!$sidx) $sidx = 1;


            $rows = $this->db->query("SELECT count(*)
                        FROM mijoz m
                        left join spr_region sr on m.id_born=sr.id
                        LEFT JOIN spr_place sp ON m.id_place=sp.id
                        left join spr_gender sg on m.gender=sg.id
                        left join mijoz_ill_history mih on mih.id_mijoz=m.id
                        WHERE m.id_depart=$iddep
                        {$ufilter}
                        and mih.old=0
						and m.selected in (0,1)
                        and mih.approve=0
                        and m.move=0
                       -- and mih.end_date >= CURRENT_DATE
                        and m.pass_seriya in (select pass_seriya from spr_pass_seriya)
                        and (mih.seriya in (select name_uz from spr_spseriya) or mih.seriya is null)
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
                               m.notcitizen
                        FROM mijoz m
                        left join spr_region sr on m.id_born=sr.id
                        LEFT JOIN spr_place sp ON m.id_place=sp.id
                        left join spr_gender sg on m.gender=sg.id
                        left join mijoz_ill_history mih on mih.id_mijoz=m.id
                        WHERE m.id_depart=$iddep
                        {$ufilter}
                        and mih.old=0
						and m.selected in (0,1)
                        and mih.approve=0
                        and m.move=0
                        -- and mih.end_date >= CURRENT_DATE
                        and m.pass_seriya in (select pass_seriya from spr_pass_seriya)
                        and (mih.seriya in (select name_uz from spr_spseriya) or mih.seriya is null)
                        ORDER BY $sidx $sord LIMIT $limit OFFSET $start
                        ";
            $res = $this->db->query($hfilter);
            $response = new \stdClass();
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

    function edit_history()
    {
        $id_user = $this->session->userdata("admin_id");
        $id = $_POST["id"];
        $approve_date = date('Y-m-d');
        $approve = $_POST['approve'];


        if ($_POST['oper'] == "edit") {
            $this->db->query("UPDATE mijoz_ill_history
                                            SET
                                              approve=$approve,
                                              who_approved=$id_user,
                                              approve_date='$approve_date'
                                            WHERE id=$id");
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
            	AND mih.old=0
            	ORDER BY $sidx $sord LIMIT $limit OFFSET $start
            ");
            $response = new \stdClass();
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
                    $row['approve'],
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
            		mih.foiz, mih.id_ortoped, mih.knomer, mih.ktashkilot, mih.tdate, mih.trdate, mih.seriya, mih.nomer, mih.pdate,
            		sk1.reason_uz as kriteria1,
            		sk2.reason_uz as kriteria2,
            		sk3.reason_uz as kriteria3,
            		sk4.reason_uz as kriteria4,
            		sk5.reason_uz as kriteria5,
            		sk6.reason_uz as kriteria6,
            		sk7.reason_uz as kriteria7,
            		so.name_uz as ortoped_name
            	FROM mijoz_ill_history mih
            		LEFT JOIN spr_kdaraja sk1 ON sk1.id = mih.kriteria_1
            		LEFT JOIN spr_kdaraja sk2 ON sk2.id = mih.kriteria_2
            		LEFT JOIN spr_kdaraja sk3 ON sk3.id = mih.kriteria_3
            		LEFT JOIN spr_kdaraja sk4 ON sk4.id = mih.kriteria_4
            		LEFT JOIN spr_kdaraja sk5 ON sk5.id = mih.kriteria_5
            		LEFT JOIN spr_kdaraja sk6 ON sk6.id = mih.kriteria_6
            		LEFT JOIN spr_kdaraja sk7 ON sk7.id = mih.kriteria_7
            		LEFT JOIN spr_ortoped so ON so.id=mih.id_ortoped
            	WHERE mih.id={$_POST['rowid']}
            	AND mih.old=0"
        )->result_array();
        $history = $histories[0];
        ?>

    <ol>
        <li><strong><font color="blue">Пенсия маълумотномаси:</font></strong>
            <?php echo $history['seriya'] . "-" . $history['nomer'] . "; " . $history['pdate'];?>
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
        <li><strong><font color="blue">Тўлиқ реабилитация, вафот этган ёки кўчиб кетган
            санаси:</font></strong> <?php echo $history['trdate']; ?></li>
    </ol>
    <?php

    }

    function search_excel(){
       //s print_r($_POST);//exit;
        require_once 'functions.php';
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
            } else{
                $ufilter = $ufilter . " and m.id_born=$id_born";}
        }
        if (!empty($date_birth)) {
            $ufilter = $ufilter . " and m.date_birth like '%$date_birth%'";
        }
        $data["depart_name"] = $this->db->query("SELECT name_uz from spr_depart WHERE status=0 and id=" . $iddep)->result();
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
                               ss.sabab_uz,
                               h.end_date,
                               h.beg_date,
                               srh.name_uz as rt,
                               h.seriya||' '||h.nomer as penseriya
                        FROM mijoz m
                        left join spr_region sr on m.id_born=sr.id
                        LEFT JOIN spr_place sp ON m.id_place=sp.id
                        left join spr_gender sg on m.gender=sg.id
                        left join spr_depart sd on sd.id = m.id_depart
                        LEFT JOIN mijoz_edu me on me.id_mijoz=m.id
                        LEFT JOIN spr_malumot sm on sm.id=me.id_malumot
                        LEFT JOIN mijoz_ill_history h on h.id_mijoz=m.id
                        left join spr_checktype sc on h.id_checktype = sc.id
                        left join spr_illness si on h.mkb_9=si.id
                        left join spr_illness_10 smk on h.mkb_10=smk.row_num
                        left join spr_sabab ss on h.id_sabab=ss.id
                        left join spr_work sw on me.working=sw.id
                        left join spr_royhat srh on h.id_royhat=srh.id
                        WHERE m.id_depart=$iddep
                        and h.old=0
						and m.selected in (0,1)
                        AND h.approve=0
                        and m.move=0
                        and m.pass_seriya in (select pass_seriya from spr_pass_seriya)
                        and (h.seriya in (select name_uz from spr_spseriya) or h.seriya is null )
                        {$ufilter}
                        ")->result();

       // print_r($data);exit;
        $this->load->view("/reports/rt", $data);

    }

}
