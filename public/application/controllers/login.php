<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 10:53
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Login extends MyController
{
    function Login()
    {
        parent::MyController();

    }

    function index()
    {
        //        $enter = $this->input->post("enter");

        $this->load->view("header_login");
        $this->load->view("login-form");
    }

    function enter()
    {
        if (!$_POST["username"] || !$_POST["password"]) {
            ?>

        <script language="JavaScript">
            alert("Фойдаланувчи ёки пароль киритилмади");
            document.location.href = '/login';
        </script>
        <?php

        }
        $login = $this->input->post("username");
        $password = md5($this->input->post("password"));
        $query = $this->db->query("SELECT * FROM log_admin WHERE login='$login'");

        if (empty($query)) {
            ?>
        <script language="JavaScript">
            alert("Тизимда бундай фойдаланувчи мавжуд эмас");
            document.location.href = '/login';
        </script>

        <?php

        }
        else
        {
            $user = $query->row();

            if (!empty($user) && $user->password == $password) {
                $id = $user->id;
                $qc = $this->db->query("SELECT id_condition, id_type FROM role_admin WHERE id_admin='$id'");
                $cond = $qc->row();
                $id_condition = $cond->id_condition;
                $id_type = $cond->id_type;
                if ($id_condition == 0) {
                    $qq = $this->db->query("SELECT id_depart FROM admin_info WHERE id_log_admin='$id'");
                    $depart = $qq->row();
                    $id_depart = $depart->id_depart;
                    $user = array(
						"session_name" => "vtek",
						"session_id",
						"admin_type" => $id_type,
						"iddep"=>$id_depart,
						"admin_id" => $id,
						"last" => time(),
					);
                    $this->session->set_userdata($user);
                    redirect("/main");
                }
                else
                {
                    ?>
                <script language="JavaScript">
                    alert("Бу фойдаланувчи учун дастурга киришга рухсат йўқ. Администраторга мурожаат қилинсин.");
                    document.location.href = '/login';
                </script>
                <?php

                }

            }
            else
            {
                ?>
            <script language="JavaScript">
                alert("Киритилган пароль нотўғри");
                document.location.href = '/login';
            </script>
            <?php

            }
        }
    }

    function logout(){
        $this->session->sess_destroy();
        redirect("/login");
    }
}