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
    $dep = $this->db->query('SELECT id, name_uz FROM spr_depart ORDER by name_uz');
    $dep = $dep->result();
    $valued = "";
    foreach ($dep as $vd) {
        $valued .= $vd->id . ":" . $vd->name_uz . ";";
    }
    $valued = substr($valued, 0, strlen($valued) - 1);

    $reg = $this->db->query('SELECT id, name_uz FROM spr_region ORDER by name_uz');
    $reg = $reg->result();
    $valuer = "";
    foreach ($reg as $vr) {
        $valuer .= $vr->id . ":" . $vr->name_uz . ";";
    }
    $valuer = substr($valuer, 0, strlen($valuer) - 1);

    ?>
    $(function () {
        var pager = $('#pager');
        $('#list').jqGrid({
            url:'/main/spr_json_deprole',
            datatype:'json',
            mtype:'POST',
            colNames:['№', 'Департамент номи', 'Ҳудуд номи'],
            colModel:[
                {name:'id', index:'id', width:10},
                {name:'id_depart', index:'id_depart', width:80, editable:true, edittype:"select", editoptions:{value:"<?php print $valued;?>"}},
                {name:'id_region', index:'id_region', width:80, editable:true, edittype:"select", editoptions:{value:"<?php print $valuer;?>"}}
            ],
            rowNum:5000,
            rowList:[2, 10, 20, 30],
            sortname:'id',
            add:true,
            loadonce:true,
            gridview:true,
            viewrecords:true,
            sortorder:"asc",
            editurl:"edit_deprole",
            height:210,
            pager:'#pager',
            caption:'ВТЭК ларни Қайси худудларни бошқариш маълумотномаси',
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