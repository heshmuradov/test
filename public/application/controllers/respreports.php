<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Respreports extends MyController
{
    function Respreports()
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

        $data['u_mijoz'] = $this->db->query("select m.gender, sg.name_uz as gender,  count(*) as count from mijoz m
                                                                                            left join mijoz_ill_history h On m.id=h.id_mijoz
                                                                                            left join spr_gender sg on sg.id=m.gender
                                                                                            where m.id_depart=$iddep
                                                                                            and h.OLD=0
																							and m.selected in (0,1)
                                                                                            group by m.gender, sg.name_uz")->result();
        $data['guruh'] = $this->db->query(" select h.guruh, count(*) as count  from mijoz m
                                                                                                   left join mijoz_ill_history h On m.id=h.id_mijoz
                                                                                                   where m.id_depart=$iddep
                                                                                                   and h.OLD=0
																								   and m.selected in (0,1)
                                                                                                   group by  h.guruh order by h.guruh ASC ")->result();
        $data['e_guruh'] = $this->db->query(" select h.guruh, count(*) as count  from mijoz m
                                                                                                   left join mijoz_ill_history h On m.id=h.id_mijoz
                                                                                                   where m.id_depart=$iddep
                                                                                                   and m.gender=1
                                                                                                   and h.OLD=0
																								   and m.selected in (0,1)
                                                                                                   group by  h.guruh order by h.guruh ASC ")->result();
         $data['a_guruh'] = $this->db->query(" select h.guruh, count(*) as count  from mijoz m
                                                                                                   left join mijoz_ill_history h On m.id=h.id_mijoz
                                                                                                   where m.id_depart=$iddep
                                                                                                   and m.gender=2
                                                                                                   and h.OLD=0
																								   and m.selected in (0,1)
                                                                                                   group by  h.guruh order by h.guruh ASC ")->result();
      
        $data['g_mijoz'] = $this->db->query(" select sg.id,  sg.name_uz, h.guruh, count(*) from mijoz m
                                                                                           left join mijoz_ill_history h On m.id=h.id_mijoz
                                                                                           left join spr_gender sg on m.gender=sg.id
                                                                                           where m.id_depart=$iddep
                                                                                           and h.OLD=0
																						   and m.selected in (0,1)
                                                                                           group by sg.id, sg.name_uz, h.guruh order by h.guruh,sg.id ASC ")->result();

        $this->load->view("header_index");
        $this->load->view("/reports/respreports", $data);
        }
        else {
            $this->session->sess_destroy();
            redirect("/login");
        }
    }

    function resp1b()
    {
        $kvartal = $_POST["kvartal"];
        if ($kvartal==0)
            {$data["sana0"]='2012 йил 1 январ '; $data["sana"]='2013 йил 1 январ ';}
        elseif ($kvartal==1)
            {$data["sana0"]='2013 йил 1 январ '; $data["sana"]='2013 йил 1 апрел ';}
        elseif ($kvartal==2)
            {$data["sana0"]='2013 йил 1 январ '; $data["sana"]='2013 йил 1 июль ';}
        elseif ($kvartal==3)
            {$data["sana0"]='2013 йил 1 январ '; $data["sana"]='2013 йил 1 октябр ';}
        elseif ($kvartal==5)
            {$data["sana0"]='2013 йил 1 январ '; $data["sana"]=date("Y-m-d");}
        else
            {$data["sana0"]='2013 йил 1 январ '; $data["sana"]='2014 йил 1 январь ';}

        $data["respotchot10"]=$this->db->query("select * from respotchot10($kvartal)")->result();
        $this->load->view("/reports/respotchot/respotchot10", $data);
    }
    function resp2b()
    {
        $kvartal = $_POST["kvartal"];
        if ($kvartal==0)
        {$data["sana0"]='01.01.2012'; $data["sana"]='01.01.2013 ';}
        elseif ($kvartal==1)
        {$data["sana0"]='01.01.2013'; $data["sana"]='01.04.2013';}
        elseif ($kvartal==2)
        {$data["sana0"]='01.01.2013'; $data["sana"]='01.07.2013';}
        elseif ($kvartal==3)
        {$data["sana0"]='01.01.2013'; $data["sana"]='01.10.2013';}
        elseif ($kvartal==5)
        {$data["sana0"]='01.01.2013'; $data["sana"]=date("d.m.Y");}
        else
        {$data["sana0"]='01.01.2013'; $data["sana"]='01.01.2014';}

        $data["respotchot11"]=$this->db->query("select * from respotchot11($kvartal)")->result();
        $this->load->view("/reports/respotchot/respotchot11", $data);
    }

    function resp3b()
    {
        $kvartal = $_POST["kvartal"];
        if ($kvartal==0)
        { $data["sana"]='01.01.2013 ';}
        elseif ($kvartal==1)
        { $data["sana"]='01.04.2013';}
        elseif ($kvartal==2)
        { $data["sana"]='01.07.2013';}
        elseif ($kvartal==3)
        { $data["sana"]='01.10.2013';}
        elseif ($kvartal==5)
        { $data["sana"]=date("d.m.Y");}
        else
        { $data["sana"]='01.01.2014';}

        $data["respotchot12"]=$this->db->query("select * from respotchot12($kvartal)")->result();
        $this->load->view("/reports/respotchot/respotchot12", $data);
    }
    function resp4b()
    {
        $kvartal = $_POST["kvartal"];
        if ($kvartal==0)
        { $data["sana"]='01.01.2013 ';}
        elseif ($kvartal==1)
        { $data["sana"]='01.04.2013';}
        elseif ($kvartal==2)
        { $data["sana"]='01.07.2013';}
        elseif ($kvartal==3)
        { $data["sana"]='01.10.2013';}
        elseif ($kvartal==5)
        { $data["sana"]=date("d.m.Y");}
        else
        { $data["sana"]='01.01.2014';}

        $data["respotchot13"]=$this->db->query("select * from respotchot13($kvartal)")->result();
        $this->load->view("/reports/respotchot/respotchot13", $data);
    }

    function resp5b()
    {
        $kvartal = $_POST["kvartal"];
        if ($kvartal==0)
        { $data["sana"]='01.01.2013 ';}
        elseif ($kvartal==1)
        { $data["sana"]='01.04.2013';}
        elseif ($kvartal==2)
        { $data["sana"]='01.07.2013';}
        elseif ($kvartal==3)
        { $data["sana"]='01.10.2013';}
        elseif ($kvartal==5)
        { $data["sana"]=date("d.m.Y");}
        else
        { $data["sana"]='01.01.2014';}

        $data["respotchot15"]=$this->db->query("select * from respotchot15($kvartal)")->result();
        $this->load->view("/reports/respotchot/respotchot15", $data);
    }
    function resp6b()
    {
        $kvartal = $_POST["kvartal"];
        if ($kvartal==0)
        { $data["sana"]='2012 ЙИЛ мобайнида ';}
        elseif ($kvartal==1)
        { $data["sana"]='2012 ЙИЛ 3 Ой мобайнида ';}
        elseif ($kvartal==2)
        { $data["sana"]='2012 ЙИЛ 6 Ой мобайнида ';}
        elseif ($kvartal==3)
        { $data["sana"]='2012 ЙИЛ 9 Ой мобайнида ';}
        elseif ($kvartal==5)
        { $data["sana"]='2012 ЙИЛ Бошидан  Жорий кунгача ';}
        else
        { $data["sana"]='2013 ЙИЛ мобайнида ';}

        $data["respotchot16"]=$this->db->query("select * from respotchot16($kvartal)")->result();
        $this->load->view("/reports/respotchot/respotchot16", $data);
    }

    function resp7b()
    {
        $kvartal = $_POST["kvartal"];
        if ($kvartal==0)
        { $data["sana"]='2012 ЙИЛ мобайнида ';}
        elseif ($kvartal==1)
        { $data["sana"]='2012 ЙИЛ 3 Ой мобайнида ';}
        elseif ($kvartal==2)
        { $data["sana"]='2012 ЙИЛ 6 Ой мобайнида ';}
        elseif ($kvartal==3)
        { $data["sana"]='2012 ЙИЛ 9 Ой мобайнида ';}
        elseif ($kvartal==5)
        { $data["sana"]='2012 ЙИЛ Бошидан  Жорий кунгача ';}
        else
        { $data["sana"]='2013 ЙИЛ мобайнида ';}

        $data["respotchot17"]=$this->db->query("select * from respotchot17($kvartal)")->result();
        $this->load->view("/reports/respotchot/respotchot17", $data);
    }
}