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
    $res = $this->db->query('SELECT id, name_uz FROM spr_region');
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
            url:'/main/spr_json_region',
            datatype:'json',
            mtype:'POST',
            colNames:['№', 'ЮТТ коди', 'Ҳудуд номи(ўз)', 'Ҳудуд номи(рус)'],
            colModel:[
                {name:'id', index:'id', width:10},
                {name:'par_id', index:'par_id', width:20, editable:true, edittype:"select", editoptions:{value:"<?php print $value;?>"}},
                {name:'name_uz', index:'name_uz', width:80, editable:true, edittype:'text'},
                {name:'name_ru', index:'name_ru', width:80, editable:true, edittype:'text'}
            ],
            rowNum:5000,
            rowList:[2, 10, 20, 30],
            sortname:'id',
            add:true,
            loadonce:true,
            gridview:true,
            viewrecords:true,
            sortorder:"asc",
            editurl:"edit_region",
            height:210,
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