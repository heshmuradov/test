<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 12.11.10
 * Time: 15:11
 * To change this template use File | Settings | File Templates.
 */
 
class menu extends Model{

    var $table = "menu";

    function Menu(){
        parent::Model();

    }

    function getParentMenus($type){
        $t_check = array("admin", "oper", "mon", "opert", "pension");
        if (in_array($type, $t_check)) {
            $q = $this->db->query("SELECT * FROM menu WHERE $type = 1 AND par_id = 0 order by id");
            return $q->result();
        }
    }

    function getByParent($parent_id){
        $q = $this->db->query("SELECT * FROM menu WHERE  par_id = $parent_id order by name_uz ");
        return $q->result();
    }
    
}