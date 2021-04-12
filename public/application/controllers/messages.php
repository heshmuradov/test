<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Messages extends MyController
{
    function Messages()
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
        $data['id_depart'] = $this->db->query("select * from spr_depart where id not in (0,1)")->result();

        $gender = $this->db->query('SELECT id, name_uz FROM spr_gender')->result();
        $data["sgen"] = "";
        foreach ($gender as $rw) {
            $data["sgen"] .= $rw->id . ":" . $rw->name_uz . ";";
        }
        $data["sgen"] = substr($data["sgen"], 0, strlen($data["sgen"]) - 1);

        $this->load->view("header_index");
        $this->load->view("/message/messages", $data);
    }


    function search()
    {
        header("Content-type: text/x-json");

        try {
            $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
            $limit = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : 15;
            $sidx = isset($_REQUEST['sidx']) ? $_REQUEST['sidx'] : 'created_date';
            $sord = isset($_REQUEST['sord']) ? $_REQUEST['sord'] : 'desc';
            $iddep = ($this->session->userdata['iddep']);
            $is_mon = $this->session->userdata['admin_type'];

            $ufilter = "";
            $id_depart = isset($_POST['fid_depart']) ? $_POST['fid_depart'] : null;
            $created_date = isset($_POST['fcreated_date']) ? $_POST['fcreated_date'] : null;
            $created_date_gacha = isset($_POST['fcreated_date_gacha']) ? $_POST['fcreated_date_gacha'] : null;

            if (!empty($created_date) && !empty($created_date_gacha)) {
                $ufilter = $ufilter . " and m.created_date BETWEEN to_date('$created_date', 'dd.mm.yyyy') and to_date('$created_date_gacha', 'dd.mm.yyyy')";
            }

            if (!empty($id_depart)) {
                $ufilter = $ufilter . " and m.from_depart='%$id_depart%' ";
            }
            $rows = $this->db->query("select count(*) from depart_message dm
                                            left join messages m on dm.id_message=m.id
                                            left join spr_depart sd on m.from_depart=sd.id
                                            where dm.to_depart=$iddep
                                            {$ufilter}
                                            ");

            $count = $rows->row()->count;

            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }

            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;

            $hfilter = "select m.id,m.created_date, sd.name_uz,m.from_depart, m.subject,dm.view_date from depart_message dm
                                            left join messages m on dm.id_message=m.id
                                            left join spr_depart sd on m.from_depart=sd.id
                                            where dm.to_depart=$iddep
                                            {$ufilter}
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
                    if ($row['view_date']== null){
                        $row['view_date']=0;
                    }else{$row['view_date']=1;}
                    //print_r($row);exit;
                    $response->rows[$i]['cell'] = array($row['id'],
                        $row['created_date'],
                        $row['name_uz'],
                        $row['subject'],
                        $row['view_date']
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

    function mdetail($id)
    {
        $iddep = $this->session->userdata("iddep");
        if (!empty($id)) {
            $this->db->query("UPDATE depart_message SET view_date=CURRENT_DATE
                                        WHERE to_depart=$iddep AND id_message=$id");
        }
        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query("SELECT COUNT(*) AS count FROM messages where id=$id");
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if (($total_pages != 0) && ($page > $total_pages)) $page = $total_pages;

            $start = $limit * $page - $limit;

            $res = $this->db->query("SELECT id, message FROM messages where id=$id
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
                    $row['message']
                );
                $i++;
            }
            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function sentmesg()
    {
        header("Content-type: text/x-json");

        try {
            $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
            $limit = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : 15;
            $sidx = isset($_REQUEST['sidx']) ? $_REQUEST['sidx'] : 'created_date';
            $sord = isset($_REQUEST['sord']) ? $_REQUEST['sord'] : 'desc';
            $iddep = ($this->session->userdata['iddep']);
            $id_user = $this->session->userdata['admin_id'];

            $ufilter = "";
            $id_depart = isset($_POST['fid_depart-sent']) ? $_POST['fid_depart-sent'] : null;
            $created_date = isset($_POST['fcreated_date-sent']) ? $_POST['fcreated_date-sent'] : null;
            $created_date_gacha = isset($_POST['fcreated_date_gacha-sent']) ? $_POST['fcreated_date_gacha-sent'] : null;

            if (!empty($created_date) && !empty($created_date_gacha)) {
                $ufilter = $ufilter . " and m.created_date BETWEEN to_date('$created_date', 'dd.mm.yyyy') and to_date('$created_date_gacha', 'dd.mm.yyyy')";
            }

            if (!empty($id_depart)) {
                $ufilter = $ufilter . " and dm.to_depart='%$id_depart%' ";
            }
            $rows = $this->db->query("select count(*) from messages m
                                            left join depart_message dm on dm.id_message=m.id
                                            left join spr_depart sd on dm.to_depart=sd.id
                                            where m.id_user=$id_user
                                            {$ufilter}
                                            ");

            $count = $rows->row()->count;

            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }

            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;

            $hfilter = "select m.id, m.created_date, sd.name_uz, m.subject, m.message from messages m
                                            left join depart_message dm on dm.id_message=m.id
                                            left join spr_depart sd on dm.to_depart=sd.id
                                            where m.id_user=$id_user
                                            {$ufilter}
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
                        $row['created_date'],
                        $row['name_uz'],
                        $row['subject'],
                        $row['message']
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

    function newmesg() {

        $iddep = $this->session->userdata("iddep");
        $id_user = $this->session->userdata("admin_id");
        $to_depart = $_POST['new-id_depart'];
        $subject = $_POST['new-subject'];
        $message = $_POST['new-message'];

        $this->db->trans_begin();

        if(empty($subject)&&empty($message))
        { ?>
        Малумотларни тўлиқ тўлдириб кегин жўнатинг...;
        <?php
            exit;
        }
        else {

            $messages = array(
                'from_depart' => $iddep,
                'subject' => $subject,
                'message' => $message,
                'id_user' => $id_user
            );

            $this->db->insert('messages', $messages);
            $id_message = $this->db->insert_id();

            $depart_message = array(
                'to_depart' => $to_depart,
                'id_message' => $id_message
            );
            $this->db->insert('depart_message', $depart_message);

        }

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

    }

}