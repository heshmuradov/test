<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Main extends MyController
{
    function Main()
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
        $title = "ВТЭК дастури ";
        $this->load->view("header_index", array("title" => $title));

        $data = array();
        $data["menus"] = $this->_build_menu();

        $this->load->view("main", $data);

    }


    function from()
    {
        $data = array();
        $data["menus"] = $this->_build_menu();
        $this->load->view("header_index");
        $this->load->view("main", $data);
    }

    function help()
    {
        $data = array();
        $data["menus"] = $this->_build_menu();
        $this->load->view("header_index");
        $this->load->view("help", $data);
    }
	
	function operator()
    {
        $data = array();
        $data['menus'] = $this->_build_menu();

//		$data['millat'] = $this->db->query('SELECT * FROM spr_millat')->result();
//		$data['region'] = $this->db->query('SELECT * FROM spr_region')->result();

        $this->load->view("header_index");
        $this->load->view("/operator/operator", $data);
    }

    function settings()
    {
        $data = array();
        $data["menus"] = $this->_build_menu();

        $idp = $this->session->userdata("iddep");
        $id = $this->session->userdata("admin_id");

        $k = $this->db->query("SELECT  login  FROM admin_info a, log_admin l
                                                    WHERE
                                                        a.id_depart=$idp
                                                    AND
                                                        a.id_log_admin=l.id
                                                    AND
                                  		                l.id = $id ")->result();
        $data["login"] = $k[0];
        $l = $k[0]->login;
        $this->load->view("header_index");
        $this->load->view("settings", $data);

        if (isset($_POST["new"])) {
            if (empty($_POST["new_pass"])) {
                echo "<center><font color='red' size=2px>Янги пароль киритилмади.</font></center></BR>";
            } else {
                $new_pass = md5($_POST['new_pass']);
                $result = $this->db->query("UPDATE log_admin SET password='$new_pass'  WHERE login = '$l' ");
                echo "<center><font color='red' size=2px>Пароль ўзгартирилди.</font></center></BR>";
                echo "<script>document.location.href='/main/settings';</script>\n";
                exit;
            }
        }

    }
    //Ортопедия маълумотномасини бошқариш
    function spr_ortoped()
    {
        if ($this->session->userdata['admin_type'] == 1) {
            $data = array();
            $data["menus"] = $this->_build_menu();
            $this->load->view("header_index");
            $this->load->view("/spr/spr_ortoped", $data);
        } else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function spr_json_ortoped()
    {

        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM spr_ortoped');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("SELECT * FROM spr_ortoped  ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $i = 0;
            $_rows = $res->result_array();
            $response->rows = array();
            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['name_uz'], $row['name_ru']);
                $i++;
            }

            echo json_encode($response);

        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_ortoped()
    {
        try {
            $id = $_POST['id'];
            $nameuz = $_POST['name_uz'];
            $nameru = $_POST['name_ru'];

            if ($_POST['oper'] == "add") {
                $result = $this->db->query("INSERT INTO spr_ortoped (name_uz, name_ru) VALUES ('$nameuz','$nameru') ");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE spr_ortoped SET name_uz='$nameuz', name_ru='$nameru' WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {
                $result = $this->db->query("DELETE from spr_ortoped WHERE id=$id");
            }

        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

    //Ортопедия справочниги тугади

//Kasalliklar ma'lumotnomasini boshqarish
    function spr_illness()
    {
        if ($this->session->userdata['admin_type'] == 1) {
            $data = array();
            $data["menus"] = $this->_build_menu();
            $this->load->view("header_index");
            $this->load->view("/spr/spr_ill", $data);
        } else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function spr_json_ill()
    {
        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM spr_illness');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("SELECT * FROM spr_illness ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $i = 0;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $_rows = $res->result_array();
            $response->rows = array();
            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['code_9'], $row['code_10'], $row['name_uz'], $row['name_ru']);
                $i++;
            }

            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_illness()
    {
        try {
            $id = $_POST['id'];
            $code9 = $_POST['code_9'];
            $code10 = $_POST['code_10'];
            $nameuz = $_POST['name_uz'];
            $nameru = $_POST['name_ru'];

            if ($_POST['oper'] == "add") {
                $result = $this->db->query("INSERT INTO spr_illness (id, code_9, code_10, name_uz, name_ru) VALUES (nextval('seq_id_illness'), '$code9', '$code10', '$nameuz','$nameru') ");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE spr_illness SET code_9='$code9', code_10='$code10', name_uz='$nameuz', name_ru='$nameru' WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {
                $result = $this->db->query("DELETE from spr_illness WHERE id=$id");
            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

//Kasalliklar ma'lumotnomasini boshqarish tugadi

//Foydalanuvchi turlari ma'lumotnomasini boshqarish
    function spr_type()
    {
        if ($this->session->userdata['admin_type'] == 1) {
            $data = array();
            $data["menus"] = $this->_build_menu();
            $this->load->view("header_index");
            $this->load->view("/spr/spr_type", $data);
        } else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function spr_json_type()
    {
        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM spr_type');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("SELECT * FROM spr_type ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $i = 0;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $_rows = $res->result_array();
            $response->rows = array();

            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['name_uz'], $row['name_ru']);
                $i++;
            }

            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_type()
    {
        try {
            $id = $_POST['id'];
            $nameuz = $_POST['name_uz'];
            $nameru = $_POST['name_ru'];

            if ($_POST['oper'] == "add") {
                $result = $this->db->query("INSERT INTO spr_type (id, name_uz, name_ru) VALUES (nextval('seq_id_type'), '$nameuz','$nameru') ");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE spr_type SET name_uz='$nameuz', name_ru='$nameru' WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {
                $result = $this->db->query("DELETE from spr_type WHERE id=$id");
            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

//Foydalanuvchilar ma'lumotnomasini boshqarish tugadi

//Foydalanuvchilar ma'lumotini boshqarish

    function spr_educ()
    {

        if ($this->session->userdata['admin_type'] == 1) {
            $data = array();
            $data["menus"] = $this->_build_menu();
            $this->load->view("header_index");
            $this->load->view("/spr/spr_educ", $data);
        } else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function spr_json_educ()
    {
        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM spr_malumot');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("SELECT * FROM spr_malumot ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $i = 0;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $_rows = $res->result_array();
            $response->rows = array();
            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['name_uz'], $row['name_ru']);
                $i++;
            }

            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_educ()
    {
        try {
            $id = $_POST['id'];
            $nameuz = $_POST['name_uz'];
            $nameru = $_POST['name_ru'];

            if ($_POST['oper'] == "add") {
                $result = $this->db->query("INSERT INTO spr_malumot (id, name_uz, name_ru) VALUES (nextval('spr_malumot_id_seq'), '$nameuz','$nameru') ");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE spr_malumot SET name_uz='$nameuz', name_ru='$nameru' WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {
                $result = $this->db->query("DELETE from spr_malumot WHERE id=$id");
            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

//Foydalanuvchilar ma'lumotini boshqarish tugadi

//Foydalanuvchilar ro'yxatini boshqarish

    function spr_royhat()
    {
        if ($this->session->userdata['admin_type'] == 1) {
            $data = array();
            $data["menus"] = $this->_build_menu();
            $this->load->view("header_index");
            $this->load->view("/spr/spr_royxat", $data);
        } else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function spr_json_royxat()
    {
        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM spr_royhat');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("SELECT *  FROM spr_royhat ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $i = 0;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $_rows = $res->result_array();
            $response->rows = array();
            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['name_uz'], $row['name_ru']);
                $i++;
            }

            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_royxat()
    {
        try {
            $id = $_POST['id'];
            $nameuz = $_POST['name_uz'];
            $nameru = $_POST['name_ru'];
            $reasonuz = $_POST['reason_uz'];
            $reasonru = $_POST['reason_ru'];

            if ($_POST['oper'] == "add") {
                $result = $this->db->query("INSERT INTO spr_royhat (id, name_uz, name_ru, reason_uz, reason_ru) VALUES (nextval('seq_id_royhat'), '$nameuz','$nameru')");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE spr_royhat SET name_uz='$nameuz', name_ru='$nameru' WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {
                $result = $this->db->query("DELETE from spr_royhat WHERE id=$id");
            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

//Foydalanuvchilar ro'yxatini boshqarish tugadi

//Foydalanuvchilar ro'yxati sabablarini boshqarish

    function spr_royhat_r()
    {
        if ($this->session->userdata['admin_type'] == 1) {
            $data = array();
            $data["menus"] = $this->_build_menu();
            $this->load->view("header_index");
            $this->load->view("/spr/spr_royhat_r", $data);
        } else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function spr_json_royxat_r()
    {
        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM spr_royhat_r');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("SELECT sr.id, sr.reason_uz, sr.reason_ru, s.name_uz
                                     FROM spr_royhat_r sr, spr_royhat s
                                     WHERE sr.id_royhat = s.id
                                     ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $i = 0;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $_rows = $res->result_array();
            $response->rows = array();
            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['reason_uz'], $row['reason_ru'], $row['name_uz']);
                $i++;
            }

            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_royhat_r()
    {
        try {
            $id = $_POST['id'];
            $idroyhat = $_POST['id_royhat'];
            $reasonuz = $_POST['reason_uz'];
            $reasonru = $_POST['reason_ru'];

            if ($_POST['oper'] == "add") {
                $result = $this->db->query("INSERT INTO spr_royhat_r (reason_uz, reason_ru, id_royhat) VALUES ('$reasonuz','$reasonru', '$idroyhat')");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE spr_royhat_r SET reason_uz='$reasonuz', reason_ru='$reasonru', id_royhat='$idroyhat' WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {
                $result = $this->db->query("DELETE from spr_royhat_r WHERE id=$id");
            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

//Foydalanuvchilar ro'yxatini boshqarish tugadi

//Kriteriyalarni boshqarish

    function spr_krit()
    {
        if ($this->session->userdata['admin_type'] == 1) {
            $data = array();
            $data["menus"] = $this->_build_menu();
            $this->load->view("header_index");
            $this->load->view("/spr/spr_krit", $data);
        } else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function spr_json_krit()
    {
        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM spr_kritic');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("SELECT * FROM spr_kritic ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $i = 0;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $_rows = $res->result_array();
            $response->rows = array();
            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['k_name_uz'], $row['k_name_ru']);
                $i++;
            }

            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_krit()
    {
        try {
            $id = $_POST['id'];
            $nameuz = $_POST['k_name_uz'];
            $nameru = $_POST['k_name_ru'];

            if ($_POST['oper'] == "add") {
                $result = $this->db->query("INSERT INTO spr_kritic (id, k_name_uz, k_name_ru) VALUES (nextval('seq_id_kritik'), '$nameuz','$nameru')");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE spr_kritic SET k_name_uz='$nameuz', k_name_ru='$nameru' WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {
                $result = $this->db->query("DELETE from spr_kritic WHERE id=$id");
            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

//Kriteriyalarni boshqarish tugadi

//Kriteriya darajalarini boshqarish

    function spr_kdaraja()
    {
        if ($this->session->userdata['admin_type'] == 1) {
            $data = array();
            $data["menus"] = $this->_build_menu();
            $this->load->view("header_index");
            $this->load->view("/spr/spr_kdaraja", $data);
        } else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function spr_json_kdaraja()
    {
        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id_kritik) AS count FROM spr_kdaraja');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("select  a.k_name_uz, b.* from spr_kritic a, spr_kdaraja b
                                     WHERE a.id = b.id_kritik
                                     ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $i = 0;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $_rows = $res->result_array();
            $response->rows = array();
            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['id_kritik'], $row['k_name_uz'], $row['reason_uz'], $row['reason_ru'], $row['step_id'], $row['guruh']);
                $i++;
            }

            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_kdaraja()
    {
        try {
            $id_kritik = $_POST['k_name_uz'];
            $nameuz = $_POST['reason_uz'];
            $nameru = $_POST['reason_ru'];
            $stepid = $_POST['step_id'];
            $guruh = $_POST['guruh'];
            $id = $_POST['id'];
            if ($_POST['oper'] == "add") {
                $result = $this->db->query("INSERT INTO spr_kdaraja (id_kritik, reason_uz, reason_ru, step_id, guruh, id)
                                            VALUES ('$id_kritik', '$nameuz','$nameru', '$stepid','$guruh', nextval('spr_kdaraja_id_seq'))");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE spr_kdaraja SET id_kritik='$id_kritik',
                                            reason_uz='$nameuz', reason_ru='$nameru', step_id='$stepid', guruh='$guruh'
                                            WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {
                $result = $this->db->query("DELETE from spr_kdaraja WHERE id=$id");
            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

//Kriteriyalarni boshqarish tugadi

//Tekshirish holatini boshqarish
    function spr_checktype()
    {
        if ($this->session->userdata['admin_type'] == 1) {
            $data = array();
            $data["menus"] = $this->_build_menu();
            $this->load->view("header_index");
            $this->load->view("/spr/spr_checktype", $data);
        } else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function spr_json_checktype()
    {

        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM spr_checktype');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("SELECT * FROM spr_checktype ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $i = 0;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $_rows = $res->result_array();
            $response->rows = array();

            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['name_uz'], $row['name_ru'], $row['step_id']);
                $i++;
            }

            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_checktype()
    {
        try {
            $id = $_POST['id'];
            $nameuz = $_POST['name_uz'];
            $nameru = $_POST['name_ru'];
            $stepid = $_POST['step_id'];

            if ($_POST['oper'] == "add") {
                $result = $this->db->query("INSERT INTO spr_checktype (id, name_uz, name_ru, step_id) VALUES (nextval('spr_checktype_id_seq'), '$nameuz','$nameru', '$stepid') ");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE spr_checktype SET name_uz='$nameuz', name_ru='$nameru', step_id='$stepid' WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {
                $result = $this->db->query("DELETE from spr_checktype WHERE id=$id");
            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

    //Tekshiriv holati tugadi

    //Departamentlar holatini boshqarish
    function spr_depart()
    {
        if ($this->session->userdata['admin_type'] == 1) {
            $data = array();
            $data["menus"] = $this->_build_menu();
            $this->load->view("header_index");
            $this->load->view("/spr/spr_depart", $data);
        } else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function spr_json_depart()
    {
        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM spr_depart');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("SELECT * FROM spr_depart ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $i = 0;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $_rows = $res->result_array();
            $response->rows = array();

            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['par_id'], $row['code'],
                    $row['name_uz'], $row['name_ru'], $row['address'], $row['chief'],);
                $i++;
            }

            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_depart()
    {
        try {
            $id = $_POST['id'];
            $parid = $_POST['par_id'];
            $nameuz = $_POST['name_uz'];
            $nameru = $_POST['name_ru'];
            $address = $_POST['address'];
            $chief = $_POST['chief'];
            $code = $_POST['code'];

            if ($_POST['oper'] == "add") {
                $result = $this->db->query("INSERT INTO spr_depart (id, par_id, code, name_uz, name_ru, address, chief)
                                         VALUES (nextval('spr_depart_id_seq'), '$parid', '$code', '$nameuz','$nameru', '$address', '$chief') ");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE spr_depart
                        SET par_id='$parid', code='$code', name_uz='$nameuz', name_ru='$nameru', address='$address', chief='$chief' WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {
                $result = $this->db->query("DELETE from spr_depart WHERE id=$id");
            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

    //Departamentlarni boshqarish tugadi
    //Hududlar holatini boshqarish
    function spr_region()
    {
        if ($this->session->userdata['admin_type'] == 1) {
            $data = array();
            $data["menus"] = $this->_build_menu();
            $this->load->view("header_index");
            $this->load->view("/spr/spr_region", $data);
        } else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function spr_json_region()
    {
        header("Content-type: text/x-json");
        try {
            //читаем параметры
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM spr_region');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("SELECT * FROM spr_region ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $i = 0;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $_rows = $res->result_array();
            $response->rows = array();

            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['par_id'],
                    $row['name_uz'], $row['name_ru']);
                $i++;
            }

            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_region()
    {
        try {
            $id = $_POST['id'];
            $parid = $_POST['par_id'];
            $nameuz = $_POST['name_uz'];
            $nameru = $_POST['name_ru'];

            if ($_POST['oper'] == "add") {
                $result = $this->db->query("INSERT INTO spr_region (id, par_id, name_uz, name_ru)
                                         VALUES (nextval('spr_region_id_seq'), '$parid', '$nameuz','$nameru')");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE spr_region
                        SET par_id='$parid', name_uz='$nameuz', name_ru='$nameru' WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {
                $result = $this->db->query("DELETE from spr_region WHERE id=$id");
            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

    //Hududlarni boshqarish tugadi
    //Departament va hududlarlar rollarini boshqarish
    function spr_deprole()
    {
        if ($this->session->userdata['admin_type'] == 1) {
            $data = array();
            $data["menus"] = $this->_build_menu();
            $this->load->view("header_index");
            $this->load->view("/spr/spr_deprole", $data);
        } else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function spr_json_deprole()
    {

        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM depart_role');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("select d.id, a.name_uz as depart_name, b.name_uz as region_name from spr_depart a, spr_region b, depart_role d
                                     WHERE d.id_depart = a.id and d.id_region=b.id
                                     ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $i = 0;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $_rows = $res->result_array();
            $response->rows = array();

            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['depart_name'], $row['region_name']);
                $i++;
            }

            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_deprole()
    {
        try {
            $id = $_POST['id'];
            $iddepart = $_POST['id_depart'];
            $idregion = $_POST['id_region'];

            if ($_POST['oper'] == "add") {
                $result = $this->db->query("INSERT INTO depart_role (id, id_depart, id_region)
                                         VALUES (nextval('depart_role_id_seq'), '$iddepart', '$idregion')");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE depart_role
                        SET id_depart='$iddepart', id_region='$idregion' WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {
                $result = $this->db->query("DELETE from depart_role WHERE id=$id");
            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

    //Hududlarni boshqarish tugadi
    //Operatorlar rollarini boshqarish
    function spr_roleoper()
    {
        if ($this->session->userdata['admin_type'] == 1) {
            $data = array();
            $data["menus"] = $this->_build_menu();
            $this->load->view("header_index");
            $this->load->view("/spr/spr_roleoper", $data);
        } else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function spr_json_roleoper()
    {

        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM role_admin');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("select a.id as id, b.fio as fio, e.name_uz as type_name, d.name_uz as con_name
                                                from role_admin a, admin_info b, spr_condition d, spr_type e
                                                where a.id_admin=b.id_log_admin
                                                and a.id_condition=d.id
                                                and a.id_type=e.id order by $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $i = 0;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $_rows = $res->result_array();
            $response->rows = array();
            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['fio'], $row['type_name'], $row['con_name']);
                $i++;
            }

            echo json_encode($response);
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_roleoper()
    {
        try {
            $id = $_POST['id'];
            $adminid = $_POST['admin_id'];
            $typeid = $_POST['type_id'];
            $conid = $_POST['con_id'];

            if ($_POST['oper'] == "add") {
                $result = $this->db->query("INSERT INTO role_admin (id, id_admin, id_type,id_condition)
                                         VALUES (nextval('role_admin_id_seq'), '$adminid', '$typeid','$conid')");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE role_admin
                        SET id_admin='$adminid', id_type='$typeid', id_condition='$conid' WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {
                $result = $this->db->query("DELETE from role_admin WHERE id=$id");
            }
        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

    //Hududlarni boshqarish tugadi

    //Ногиронлик маълумотномасини бошқариш
    function spr_sabab()
    {
        if ($this->session->userdata['admin_type'] == 1) {
            $data = array();
            $data["menus"] = $this->_build_menu();
            $this->load->view("header_index");
            $this->load->view("/spr/spr_sabab", $data);
        } else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function spr_pser()
    {
        $data = array();
        $data["menus"] = $this->_build_menu();
        $this->load->view("header_index");
        $this->load->view("/spr/spr_pser", $data);
    }

    function spr_ser()
    {
            $data = array();
            $data["menus"] = $this->_build_menu();
            $this->load->view("header_index");
            $this->load->view("/spr/spr_ser", $data);
    }

    function spr_sppser()
    {

        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM spr_pass_seriya');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("SELECT * FROM spr_pass_seriya  ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $i = 0;
            $_rows = $res->result_array();
            $response->rows = array();
            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['pass_seriya']);
                $i++;
            }

            echo json_encode($response);

        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }



    function spr_spser()
    {

        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM spr_spseriya');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("SELECT * FROM spr_spseriya  ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $i = 0;
            $_rows = $res->result_array();
            $response->rows = array();
            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['name_uz']);
                $i++;
            }

            echo json_encode($response);

        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_sppser()
    {
        try {
            $id = $_POST['id'];
            $pass_seriya = $_POST['pass_seriya'];

            if ($_POST['oper'] == "add") {
                $this->db->query("INSERT INTO spr_pass_seriya (pass_seriya) VALUES ('$pass_seriya')");
            }
			elseif ($_POST['oper'] == "del") {
                $result = $this->db->query("DELETE from spr_pass_seriya WHERE id=$id");
            }

        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

    function edit_spser()
    {
        try {
            $id = $_POST['id'];
            $name_uz = $_POST['name_uz'];

            if ($_POST['oper'] == "add") {
                $this->db->query("INSERT INTO spr_spseriya (name_uz) VALUES ('$name_uz')");
            }

        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }


    function spr_json_sabab()
    {

        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if (!$sidx) $sidx = 1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM spr_sabab');
            $count = $rows->row()->count;
            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - $limit;
            $res = $this->db->query("SELECT * FROM spr_sabab  ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $i = 0;
            $_rows = $res->result_array();
            $response->rows = array();
            foreach ($_rows as $row) {
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'], $row['sabab_uz'], $row['sabab_ru']);
                $i++;
            }

            echo json_encode($response);

        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_sabab()
    {
        try {
            $id = $_POST['id'];
            $nameuz = $_POST['sabab_uz'];
            $nameru = $_POST['sabab_ru'];

            if ($_POST['oper'] == "add") {
                $result = $this->db->query("INSERT INTO spr_sabab (sabab_uz, sabab_ru) VALUES ('$nameuz','$nameru')");
            } elseif ($_POST['oper'] == "edit") {
                $result = $this->db->query("UPDATE spr_sabab SET sabab_uz='$nameuz', sabab_ru='$nameru' WHERE id=$id");
            }
            elseif ($_POST['oper'] == "del") {
                $result = $this->db->query("DELETE from spr_sabab WHERE id=$id");
            }

        } catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }
    //Ногиронлик маълумотномасини бошқариш тугади
}