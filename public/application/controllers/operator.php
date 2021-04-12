<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 22.11.10
 * Time: 12:13
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Operator extends MyController
{
    function Operator()
    {
        parent::MyController();
    }

    function _before($method){
        $auth_method = array("index", "from");
		if(in_array($method, $auth_method)){
			$this->_auth();
		}
    }

    function index()
    {
	if ($this->session->userdata['admin_type'] == 1) {
        $data = array();
        $data['menus'] = $this->_build_menu();
        $this->load->view("header_index");
        $this->load->view("/operator/operator", $data);
		}
		 else {
		  $this->session->sess_destroy();
            redirect("/login");
    }
	}

	//Operatorlarni boshqarish
  //  function operator_ent(){
   //     $data = array();
    //    $data["menus"] = $this->_build_menu();
    //    $this->load->view("header_index");
    //    $this->load->view("/operator/operator", $data);
   // }

    function spr_json_operator()
    {
        header("Content-type: text/x-json");
        try {
            $page = $_REQUEST['page'];
            $limit = $_REQUEST['rows'];
            $sidx = $_REQUEST['sidx'];
            $sord = $_REQUEST['sord'];
            if(!$sidx) $sidx =1;
            $rows = $this->db->query('SELECT COUNT(id) AS count FROM log_admin');
            $count = $rows->row()->count;
            if( $count >0 ) {
                $total_pages = ceil($count/$limit);
            }
            else {
                $total_pages = 0;
            }
            if ($page > $total_pages) $page=$total_pages;
            $start = $limit*$page - $limit;
            $res = $this->db->query("select a.id, a.login, a.PASSWORD,
                                        d.name_uz, b.fio
                                     from  log_admin a , admin_info b, spr_depart d
                                     where a.id=b.id_log_admin
                                     and
                                        b.id_depart=d.id
                                     ORDER BY $sidx $sord LIMIT $limit OFFSET $start");
            $response = new \stdClass();;
            $i = 0;
            $response->page = $page;
            $response->total = $total_pages;
            $response->records = $count;
            $_rows = $res->result_array();
            $response->rows = array();
            foreach($_rows as $row){
                $response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array($row['id'],
                                                    $row['login'],
                                                    $row['password'],
                                                    $row['name_uz'],
                                                    $row['fio']);
                $i++;
            }

            echo json_encode($response);
        }
        catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function sub_json_operator()
    {
        header("Content-type: text/x-json");
        try {
            $id = $_REQUEST["id"];
            $res = $this->db->query("select b.pass_seriya,
                                        b.pass_code, b.date_birth,
                                        b.k_p_berilgan, b.a_p_muddat,
                                        b.gender, b.date_birth,
                                        b.address, b.phone
                                     from  log_admin a , admin_info b
                                     where a.id=b.id_log_admin and b.id_log_admin = $id");
            $response = new \stdClass();;
            $i = 0;
            $_rows = $res->result_array();
            $response->rows = array();

            foreach($_rows as $row){
                //$response->rows[$i]['id'] = $row['id'];
                $response->rows[$i]['cell'] = array(
                                                    $row['pass_seriya'],
                                                    $row['pass_code'],
                                                    $row['k_p_berilgan'],
                                                    $row['a_p_muddat'],
                                                    $row['gender'],
                                                    $row['date_birth'],
                                                    $row['address'],
                                                    $row['phone']);
                $i++;
            }

            echo json_encode($response);
        }
        catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }
    }

    function edit_operator(){
        try {
            $id = $_POST['id'];
            $login = $_POST['login'];
            $password = md5($_POST['password']);
            $iddepart = $_POST['id_depart'];
            $fio = $_POST['fio'];

          if ($_POST['oper']=="add"){
             $result = $this->db->query("INSERT INTO log_admin (id, login, password)
                                         VALUES (nextval('log_admin_id_seq'), '$login', '$password')");
             $result2 = $this->db->query("INSERT INTO admin_info (id_log_admin, fio, id_depart)
                                         VALUES (currval('log_admin_id_seq'), '$fio', '$iddepart')");
          }
          elseif($_POST['oper']=="edit")
          {
             $result = $this->db->query("UPDATE log_admin
                       SET login='$login', password='$password' WHERE id=$id");
             $result2 = $this->db->query("UPDATE admin_info
                       SET fio='$fio', id_depart='$iddepart' WHERE id_log_admin=$id");
          }
          elseif($_POST['oper']=="del")
          {
             $result = $this->db->query("DELETE from depart_role WHERE id=$id");
          }
        }
        catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }

    function edit_sub_operator(){
        try {
            $idrow = $_REQUEST["idrow"];
            $pass_seriya = $_POST['pass_seriya'];
            $pass_code = $_POST['pass_code'];
            $kpberilgan = $_POST['k_p_berilgan'];
            $apmuddat = $_POST['a_p_muddat'];
            $gender = $_POST['gender'];
            $date_birth = $_POST['date_birth'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];


          if($_POST['oper']=="edit")
          {
             $result = $this->db->query("UPDATE admin_info
                       SET pass_seriya='$pass_seriya', pass_code='$pass_code',
                           address='$address', phone='$phone',
                           date_birth='$date_birth', k_p_berilgan='$kpberilgan',
                           a_p_muddat='$apmuddat', gender='$gender'
                       WHERE id_log_admin=$idrow");
          }
          elseif($_POST['oper']=="del")
          {
             //$result = $this->db->query("DELETE from depart_role WHERE id=$id");
          }
        }
        catch (PDOException $e) {
            echo 'Database error: ' . $e->getMessage();
        }

    }
    //operator boshqarish tugadi

}