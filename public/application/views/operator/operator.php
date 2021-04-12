<body>
<div id="asosiy">
    <?php $this->load->view("menu")?>
    <div id="vback">
        <table id="list" class="scroll"></table>
        <div id="pager" class="scroll" style="text-align:center;"></div>
    </div>
</div>

<style>
    @import "/css/new/jquery-ui-1.8.21.custom.css";
    @import "/css/ui_jqgrid.css";
</style>


<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/grid.locale-uz.js"></script>
<!--<script type="text/javascript" src="js/js/grid.locale-ru.js"></script> -->
<script type="text/javascript" src="/js/jquery.jqGrid.min.js"></script>
<!--	<script type="text/javascript" src="/js/jquery.jqGrid.js"></script>-->
<script type="text/javascript" src="/js/grid.subgrid.js"></script>
<script type="text/javascript">
    <?php
    $res = $this->db->query('SELECT id, name_uz FROM spr_depart order by name_uz');
    $res = $res->result();
    $value = "";
    foreach ($res as $v) {
        $value .= $v->id . ":" . $v->name_uz . ";";
    }
    $value = substr($value, 0, strlen($value) - 1);

    ?>
    $(function () {
        var pager = $('#pager');
        $('#list').jqGrid({
            url:'/operator/spr_json_operator',
            datatype:'json',
            mtype:'POST',
            colNames:['№', 'Логин', 'Парол', 'Департамент', 'ФИО'],
            colModel:[
                {name:'id', index:'id', width:10},
                {name:'login', index:'login', width:20, editable:true, edittype:'text'},
                {name:'password', index:'password', width:20, editable:true, edittype:'text'},
                {name:'id_depart', index:'id_depart', width:80, editable:true, edittype:"select", editoptions:{value:"<?php print $value;?>"}},
                {name:'fio', index:'fio', width:80, editable:true, edittype:'text'}
            ],
            rowNum:5000,
            rowList:[2, 10, 20, 30],
            sortname:'id',
            add:true,
            viewrecords:true,
            sortorder:"asc",
            editurl:"/operator/edit_operator",
            height:600,
            pager:'#pager',
            caption:'',
            autowidth:true,
            multiselect:false,
            subGrid:true,
            subGridRowExpanded:function (subgrid_id, row_id) {
                var subgrid_table_id, pager_id;
                subgrid_table_id = subgrid_id + "_t";
                pager_id = subgrid_id + "_p";
                var str = "<table id='" + subgrid_table_id + "' class='scroll'></table><div id='" + pager_id + "' class='scroll'></div>";
                $("#" + subgrid_id).html(str);
                jQuery("#" + subgrid_table_id).jqGrid({
                    url:"/operator/sub_json_operator?id=" + row_id,
                    datatype:"json",
                    colNames:['П.серия', 'П.номер', 'КТ берилган', 'АҚ муддати', 'Жинси', 'Туғилган санаси', 'Манзили', 'Телефон'],
                    colModel:[
                        {name:"pass_seriya", index:"pass_seriya", width:80, key:true, editable:true, edittype:'text'},
                        {name:"pass_code", index:"pass_code", width:80, editable:true, edittype:'text'},
                        {name:"k_p_berilgan", index:"k_p_berilgan", width:100, align:"right", editable:true, edittype:'text'},
                        {name:"a_p_muddat", index:"a_p_muddat", width:70, align:"right", editable:true, edittype:'text'},
                        {name:"gender", index:"gender", width:70, align:"right", editable:true, edittype:'text'},
                        {name:"date_birth", index:"date_birth", width:70, align:"right", editable:true, edittype:'text'},
                        {name:"address", index:"address", width:70, align:"right", editable:true, edittype:'text'},
                        {name:"phone", index:"phone", width:70, align:"right", editable:true, edittype:'text'}
                    ],
                    rowNum:20,
                    pager:pager_id,
                    sortname:'num',
                    sortorder:"asc",
                    editurl:"/operator/edit_sub_operator?idrow=" + row_id,
                    height:'100%',
                    autowidth:true
                });

                jQuery("#" + subgrid_table_id).jqGrid('navGrid', '#' + pager_id, {edit:true, add:false, del:true, search:false})
            }
        });
        jQuery("#list").jqGrid('navGrid', '#pager', {add:true, del:true, edit:true, search:true}),

        {}, //options
        {height:400, reloadAfterSubmit:false, name:"sdfsdf"}, // edit options
        {height:400, reloadAfterSubmit:false}, // add options
        {reloadAfterSubmit:false}, // del options
        {} // search options

    });

</script>
</body>
</html>