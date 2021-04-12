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
<script type="text/javascript">
    <?php
    $res = $this->db->query('SELECT id, k_name_uz FROM spr_kritic');
    $res = $res->result();
    $value = "";
    foreach ($res as $v) {
        $value .= $v->id . ":" . $v->k_name_uz . ";";
    }
    $value = substr($value, 0, strlen($value) - 1);

    ?>
    $(function () {
        var pager = $('#pager');
        $('#list').jqGrid({
            url:'/main/spr_json_kdaraja',
            datatype:'json',
            mtype:'POST',
            colNames:['№', 'Мезон рақами', 'Мезон номи', 'Даража номи(ўзбекча)', 'Даража номи(рус)', 'Босқичлар', 'Гуруҳ'],
            colModel:[
                {name:'id', index:'id', width:20, hidden:true},
                {name:'id_kritik', index:'id_kritik', width:30, hidden:true},
                {name:'k_name_uz', index:'k_name_uz', width:80, editable:true, edittype:"select", editoptions:{value:"<?php print $value;?>"}},
                {name:'reason_uz', index:'reason_uz', width:80, editable:true, edittype:'text'},
                {name:'reason_ru', index:'reason_ru', width:80, editable:true, edittype:'text'},
                {name:'step_id', index:'step_id', width:20, editable:true, edittype:'text'},
                {name:'guruh', index:'guruh', width:20, editable:true, edittype:'text'}
            ],
            //rowNum:10,
            rowList:[2, 10, 20, 30],
            sortname:'id',
            add:true,
            loadonce:true,
            gridview:true,
            viewrecords:true,
            sortorder:"asc",
            editurl:"edit_kdaraja",
            height:210,
            pager:'#pager',
            caption:'Критерия даражалари маълумотномаси',
            autowidth:true
        });

        jQuery("#list").jqGrid('navGrid', '#pager', {add:true, del:true, edit:true, search:true}),
            jQuery("#list").jqGrid('filterToolbar', {stringResult:true, searchOnEnter:false, autosearch:true}),
        {}, //options
        {height:280, reloadAfterSubmit:false, name:"sdfsdf"}, // edit options
        {height:280, reloadAfterSubmit:false}, // add options
        {reloadAfterSubmit:false}, // del options
        {} // search options

    });

</script>
</body>
</html>