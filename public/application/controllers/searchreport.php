<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 11.11.10
 * Time: 11:34
 * To change this template use File | Settings | File Templates.
 */

require_once('MyController.php');

class Searchreport extends MyController
{
    function Searchreport()
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

    function searchresult()
    {

        require_once 'functions.php';

        $iddep = $this->session->userdata("iddep");

        $filter = "";
        $familiya = isset($_POST['familiya-search']) ? $_POST['familiya-search'] : null;
        $name = isset($_POST['name-search']) ? $_POST['name-search'] : null;
        $bnn = isset($_POST['bnn']) ? $_POST['bnn'] : null;
        $date_birth = isset($_POST['date_birth-search']) ? $_POST['date_birth-search'] : null;
        $pass_code = isset($_POST['pass_code-search']) ? $_POST['pass_code-search'] : null;
        $pass_seriya = isset($_POST['pass_seriya-search']) ? $_POST['pass_seriya-search'] : null;
        $gender = isset($_POST['gender']) ? $_POST['gender'] : null;
        $id_place = isset($_POST['id_place-search']) ? $_POST['id_place-search'] : null;
        $id_born = isset($_POST['id_born-search']) ? $_POST['id_born-search'] : null;
        $guruh_search = isset($_POST['guruh_search']) ? $_POST['guruh_search'] : null;
        $id_sabab_search = isset($_POST['id_sabab_search']) ? $_POST['id_sabab_search'] : null;
        $depart_search = isset($_POST['depart_search']) ? $_POST['depart_search'] : null;
        $pg = $_POST['osubmit'];
        $monitoring = $this->session->userdata['admin_type'];

        if (!empty($familiya)) {
            $filter = $filter . " and upper(m.familiya) like upper('%$familiya%') ";
        }
        if (!empty($name)) {
            $filter = $filter . " and upper(m.name) like upper('%$name%') ";
        }
        if (!empty($pass_seriya)) {
            $filter = $filter . " and upper(m.pass_seriya) like upper('%$pass_seriya%') ";
        }
        if (!empty($pass_code)) {
            $filter = $filter . " and m.pass_code like '%$pass_code%' ";
        }
        if (!empty($gender)) {
            $filter = $filter . " and m.gender=$gender";
        }
        if (!empty($id_place)) {
            $filter = $filter . " and m.id_place=$id_place";
        }
        if (!empty($id_born)) {
            $filter = $filter . " and m.id_born=$id_born";
        }
        if (!empty($bnn)) {
            $filter = $filter . " and m.bn=$bnn-1";
        }
        if (!empty($date_birth)) {
            $filter = $filter . " and m.date_birth like '%$date_birth%'";
        }
        if (!empty($guruh_search)) {
            $filter = $filter . " and h.guruh=$guruh_search";
        }
        if (!empty($id_sabab_search)) {
            $filter = $filter . "  and h.id_sabab=$id_sabab_search";
        }

        if ($monitoring == 4) {
            if ($depart_search == 0) {
                $depart_sql = " and m.id_depart in (select id from spr_depart where id=$iddep or par_id=$iddep)";
            } else {
                $depart_sql = " and m.id_depart =$depart_search ";
            }
        }
        else {
            $depart_sql = ' and m.id_depart=' . $iddep;
        }

        //        $par_id_depart = $this->db->query("select par_id from spr_depart where id=$iddep")->result();
        //        $par_iddep = $par_id_depart[0]->par_id;
        //         if ($par_iddep == 2) {
        //            $dep = $this->db->query("select id from spr_depart where par_id=$iddep");
        //            $dep = $dep->result_array();
        //            $ddep = array();
        //            foreach ($dep as $item):
        //                $ddep[] = $item['id'];
        //            endforeach;
        //        }
        //        if (empty($ddep)) {
        //            $ddep = 5000;
        //        } else {
        //            $ddep = implode(", ", $ddep);
        //        }

        $data = array();
        $data["depart_name"] = $this->db->query("SELECT name_uz from spr_depart WHERE id=" . $iddep)->result();
        if ($pg == "rt") {
            $data["royhat_export"] = $this->db->query("SELECT  COALESCE(m.familiya,' ')||' '||COALESCE(m.NAME,' ')||' '|| COALESCE(m.middle,' ') as fio, m.date_birth,sg.name_uz as gender,
                                                                                           sr.name_uz as region, m.address, m.pass_seriya||' '||m.pass_code as pasport,
                                                                                           sd.name_uz as depart, sc.name_uz as checktype,
                                                                                           sm.name_uz as malumot, me.spec as kasbi, sw.name_uz as working,
                                                                                           si.code_9 as mkb9, smk.r as mkb10,
                                                                                           h.guruh, ss.sabab_uz, h.end_date, h.beg_date, srh.name_uz as rt  FROM mijoz m
                                                                                             left join spr_gender sg on m.gender=sg.id
                                                                                             left join spr_region sr on m.id_born=sr.id
                                                                                             left join spr_depart sd on m.id_depart=sd.id
                                                                                             left join history h on m.id=h.id_mijoz
                                                                                             left join spr_checktype sc on h.id_checktype=sc.id
                                                                                             left join spr_sabab ss on h.id_sabab=ss.id
                                                                                             left join spr_illness si on h.id_illness=si.id
                                                                                             left join spr_illness_10 smk on h.code_ill_10=smk.row_num
                                                                                             left join mijoz_edu me on m.id=me.id_mijoz
                                                                                             left join spr_malumot sm on me.id_malumot=sm.id
                                                                                             left join spr_work sw on me.working=sw.id
                                                                                             left join spr_royhat srh on h.id_royhat=srh.id
                                                                                             where h.OLD=0
																							 and m.selected in (0,1)
                                                                                             and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                             and h.end_date >=  CURRENT_DATE
                                                                                             {$depart_sql}
                                                                                             {$filter} 

")->result();
            $this->load->view("/reports/rt", $data);
        }
        if ($pg == "mt") {
            $data["royhat_export"] = $this->db->query("SELECT  COALESCE(m.familiya,' ')||' '||COALESCE(m.NAME,' ')||' '|| COALESCE(m.middle,' ') as fio, m.date_birth,sg.name_uz as gender,
                                                                                           sr.name_uz as region, m.address, m.pass_seriya||' '||m.pass_code as pasport,
                                                                                           sd.name_uz as depart, sc.name_uz as checktype,
                                                                                           sm.name_uz as malumot, me.spec as kasbi, sw.name_uz as working,
                                                                                           si.code_9 as mkb9, smk.r as mkb10,
                                                                                           h.guruh, ss.sabab_uz, h.end_date, h.beg_date, srh.name_uz as rt FROM mijoz m
                                                                                             left join spr_gender sg on m.gender=sg.id
                                                                                             left join spr_region sr on m.id_born=sr.id
                                                                                             left join spr_depart sd on m.id_depart=sd.id
                                                                                             left join history h on m.id=h.id_mijoz
                                                                                             left join spr_checktype sc on h.id_checktype=sc.id
                                                                                             left join spr_sabab ss on h.id_sabab=ss.id
                                                                                             left join spr_illness si on h.id_illness=si.id
                                                                                             left join spr_illness_10 smk on h.code_ill_10=smk.row_num
                                                                                             left join mijoz_edu me on m.id=me.id_mijoz
                                                                                             left join spr_malumot sm on me.id_malumot=sm.id
                                                                                             left join spr_work sw on me.working=sw.id
                                                                                              left join spr_royhat srh on h.id_royhat=srh.id
                                                                                             where h.OLD=0
																							 and m.selected in (0,1)
                                                                                             and h.id_royhat in (1,2,3,4,5,6,10,11,12,13)
                                                                                             and h.end_date <  CURRENT_DATE
                                                                                             {$depart_sql}
                                                                                             {$filter}

")->result();
            $this->load->view("/reports/rt", $data);
        }
    }
}