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
    @import "/css/jquery.searchFilter.css";
</style>


<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/grid.locale-uz.js"></script>
<!--<script type="text/javascript" src="js/js/grid.locale-ru.js"></script> -->
<script type="text/javascript" src="/js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="/js/grid.common.js"></script>
<script type="text/javascript" src="/js/jquery.searchFilter.js"></script>
<script type="text/javascript" src="/js/grid.formedit.js"></script>
<script type="text/javascript" src="/js/jqModal.js"></script>
<script type="text/javascript" src="/js/jqDnR.js"></script>

<script type="text/javascript">
    $(function () {
        var pager = $('#pager');
        $('#list').jqGrid({
            url:'/main/spr_json',
            datatype:'json',
            mtype:'POST',
            colNames:['Рақами', 'Номланиши(ўзбек)', 'Номланиши(рус)'],
            colModel:[
                {name:'id', index:'id', width:80, sorttype:'int'},
                {name:'name_uz', index:'name_uz', width:80, editable:true, edittype:'text'},
                {name:'name_ru', index:'name_ru', width:90, editable:true, edittype:'text'}
            ],
            rowNum:10,
            rowList:[2, 10, 20, 30],
            sortname:'id',
            loadonce:true,
            gridview:true,
            add:true,
            viewrecords:true,
            sortorder:"asc",
            editurl:'edit',
            height:210,
            pager:'#pager',
            caption:'Миллатлар маълумотномаси',
            autowidth:true

        });

        jQuery("#list").jqGrid('navGrid', '#pager', {add:true, del:true, edit:true, search:true}),
            jQuery("#list").jqGrid('filterToolbar', {stringResult:true, searchOnEnter:false, autosearch:true}),
        {}, //options
        {height:280, reloadAfterSubmit:false, name:"sdfsdf"}, // edit options
        {height:280, reloadAfterSubmit:false}, // add options
        {reloadAfterSubmit:false}, // del options
        {sField:true} // search options

    });

</script>
</body>
</html>