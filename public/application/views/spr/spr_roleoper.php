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
    //adminlar
    $resa = $this->db->query('SELECT a.id as id, b.fio as fio FROM log_admin a, admin_info b where a.id=b.id_log_admin order by fio');
    $resa = $resa->result();
    $valuea = "";
    foreach ($resa as $va) {
        $valuea .= $va->id . ":" . $va->fio . ";";
    }
    $valuea = substr($valuea, 0, strlen($valuea) - 1);
    //condition
    $resc = $this->db->query('SELECT id, name_uz FROM spr_condition');
    $resc = $resc->result();
    $valuec = "";
    foreach ($resc as $vc) {
        $valuec .= $vc->id . ":" . $vc->name_uz . ";";
    }
    $valuec = substr($valuec, 0, strlen($valuec) - 1);
    //role
    $resr = $this->db->query('SELECT id, name_uz FROM spr_type');
    $resr = $resr->result();
    $valuer = "";
    foreach ($resr as $vr) {
        $valuer .= $vr->id . ":" . $vr->name_uz . ";";
    }
    $valuer = substr($valuer, 0, strlen($valuer) - 1);

    ?>
    $(function () {
        var pager = $('#pager');
        $('#list').jqGrid({
            url:'/main/spr_json_roleoper',
            datatype:'json',
            mtype:'POST',
            colNames:['№', 'ФИО', 'Оператор Даражаси', 'Ҳолати'],
            colModel:[
                {name:'id', index:'id', width:10},
                {name:'admin_id', index:'admin_id', width:50, editable:true, edittype:"select", editoptions:{value:"<?php print $valuea;?>"}},
                {name:'type_id', index:'type_id', width:25, editable:true, edittype:"select", editoptions:{value:"<?php print $valuer;?>"}},
                {name:'con_id', index:'con_id', width:25, editable:true, edittype:"select", editoptions:{value:"<?php print $valuec;?>"}}
            ],
            rowNum:5000,
            rowList:[2, 10, 20, 30],
            sortname:'id',
            add:true,
            loadonce:true,
            gridview:true,
            viewrecords:true,
            sortorder:"asc",
            editurl:"edit_roleoper",
            height:400,
            pager:'#pager',
            caption:'Ҳудудлар маълумотномаси',
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