<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 17A10_NNB_1
 * Date: 12.11.10
 * Time: 15:38
 * To change this template use File | Settings | File Templates.
 */

class MyController extends Controller{
    var $menu;

    function MyController(){
        parent::Controller();

    }

    function _build_menu(){
        $id_type = $this->session->userdata('admin_type');
        switch ($id_type) {
            case 1:
                $type = "admin";
                break;
            case 2:
                $type = "oper";
                break;
            case 3:
                $type = "opert";
                break;
            case 4:
                $type = "mon";
                break;
            case 5:
                $type = "pension";
                break;
        }

        //        $this->load->model("menu");
        

        $this->menus = $this->Menu->getParentMenus($type);
        foreach ($this->menus as &$menu) {
            $menu->childs = $this->Menu->getByParent($menu->id);
        }

        return $this->menus;
    }

    function _before($method){

    }

    function _after($method){

    }

    function _auth(){
        $user = $this->session->userdata('last');
        
        if (!$user) {
            redirect("/login");
        }
    }
}
 
