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
    var list_id;
    $(function () {
        var pager = $('#pager');
        $('#list').jqGrid({
            url:'/main/spr_sppser',
            datatype:'json',
            mtype:'POST',
            colNames:['Рақами', 'Номланиши(ўзбек)'],
            colModel:[
                {name:'id', index:'id', width:10},
                {name:'pass_seriya', index:'pass_seriya', width:150, editable:true, edittype:'text'}
            ],
            rowNum:100,
            rowList:[2, 10, 20, 30],
            sortname:'id',
            loadonce:true,
            gridview:true,
            viewrecords:true,
            sortorder:"asc",
            editurl:'edit_sppser',
            height:400,
            pager:'#pager',
            caption:'Паспорт сериялари маълумотномаси',
            autowidth:true
        });
        jQuery("#list").jqGrid('navGrid', '#pager', {add:true, del:true, edit:false, search:false}),
        jQuery("#list").jqGrid('filterToolbar', {stringResult:true, searchOnEnter:false, autosearch:true}),
        {}, //options
        {height:280, reloadAfterSubmit:false, name:"sdfsdf"}, // edit options
        {height:280, reloadAfterSubmit:false}, // add options
        {reloadAfterSubmit:false}, // del options
        {sField:true}, // search options

        jQuery('#list').jqGrid('navButtonAdd', '#pager', {id:'pager_excel', caption:'Юклаш', title:'Excelга юклаш', onClickButton:function (e) {
            try {
                jQuery("#list").jqGrid('excelExport', {tag:'excel', url:'/obj/passtoexcel'});
            } catch (e) {
                alert("Маълумоти керак бўлган ногирон танланмади. Ногиронни танланг!");
            }
        },
            buttonicon:'ui-icon-print'});

    });

</script>
</body>
</html>