<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Reports extends MyController
{
    function Reports()
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

        $data['u_mijoz'] = $this->db->query("select m.gender, sg.name_uz as gender,  count(*) as count from mijoz m
                                                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                                                            left join spr_gender sg on sg.id=m.gender
                                                                                            where m.id_depart=$iddep
																							and m.selected in (0,1)
                                                                                            and h.OLD=0
                                                                                            group by m.gender, sg.name_uz")->result();
        $data['guruh'] = $this->db->query(" select h.guruh, count(*) as count  from mijoz m
                                                                                                   left join mijoz_ill_history h On m.id=h.id_mijoz
                                                                                                   where m.id_depart=$iddep
																								   and m.selected in (0,1)
                                                                                                   and h.OLD=0
                                                                                                   group by  h.guruh order by h.guruh ASC ")->result();
        $data['e_guruh'] = $this->db->query(" select h.guruh, count(*) as count  from mijoz m
                                                                                                   left join mijoz_ill_history h On m.id=h.id_mijoz
                                                                                                   where m.id_depart=$iddep
																								   and m.selected in (0,1)
                                                                                                   and m.gender=1
                                                                                                   and h.OLD=0
                                                                                                   group by  h.guruh order by h.guruh ASC ")->result();
         $data['a_guruh'] = $this->db->query(" select h.guruh, count(*) as count  from mijoz m
                                                                                                   left join mijoz_ill_history h On m.id=h.id_mijoz
                                                                                                   where m.id_depart=$iddep
																								   and m.selected in (0,1)
                                                                                                   and m.gender=2
                                                                                                   and h.OLD=0
                                                                                                   group by  h.guruh order by h.guruh ASC ")->result();
      
        $data['g_mijoz'] = $this->db->query(" select sg.id,  sg.name_uz, h.guruh, count(*) from mijoz m
                                                                                           left join mijoz_ill_history h On m.id=h.id_mijoz
                                                                                           left join spr_gender sg on m.gender=sg.id
                                                                                           where m.id_depart=$iddep
																						   and m.selected in (0,1)
                                                                                           and h.OLD=0
                                                                                           group by sg.id, sg.name_uz, h.guruh order by h.guruh,sg.id ASC ")->result();

        $this->load->view("header_index");
        $this->load->view("/reports/report", $data);
        }
        else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function counts($depart)
    {
        $data = array();
        $data['menus'] = $this->_build_menu();
        $iddep = $this->session->userdata("iddep");
        $data['cc'] = $this->db->query('SELECT count(*)  FROM mijoz WHERE id_depart='.$depart)->result();
    }

}