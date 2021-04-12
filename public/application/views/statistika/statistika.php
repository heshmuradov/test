<?php
  $iddep = $this->session->userdata('iddep');
                $dep = $this->db->query("select id, name_uz from spr_depart where id=$iddep OR par_id = $iddep")->result();
?>
<style type="text/css">
    tr.table_header td {
	border-top: 1px solid #94614e;
	border-bottom: 1px solid #94614e;
	background: transparent url(/images/table-header.png) repeat-x;
	height: 25px;
	font-size: 12px;
	font-family: arial, sans-serif;
	color:#333399;
	text-align:center;
	padding: 0px;
	width: 100px;
}
tr.table_header tr.toq td{
	border: 1px;
}

tr.qator td {
	font-family: tahoma;
	font-size: 12px;
	padding: 7px;
	line-height: 15px;
	border-bottom: 1px solid #e4e4e4;
	text-align: center;
}

tr.qator td.first {
	border-left: 1px solid #e4e4e4;
	border-right: 1px solid #e4e4e4;
	text-align: center;
	font-size: 11px;
	}

tr.qator td.first_report {
	border-left: 1px solid #e4e4e4;
	font-family: tahoma;
	font-size: 12px;
	font-weight: bold;
}

tr.qator td.last {
	border-right: 1px solid #e4e4e4;
	text-align: center;
	font-size: 11px;
}
</style>
<body>
<div id="asosiy">
    <?php $this->load->view("menu")?>
    <div id="vback">
        <select style="float:right; margin-bottom:10px; padding: .4em; display:block;"
            name="ddepart" id="ddepart"
            class="text ui-widget-content ui-corner-all">
    	<option value="0">Департамент</option>
        <?php foreach ($dep as $d1) : ?>
        <option value="<?php echo $d1->id; ?>"><?php echo $d1->name_uz; ?></option>
        <?php endforeach; ?>
    </select>
        <BR>
        <BR>
        <font color="#a52a2a">Умумий маълумотлар:</font>
        <BR>
        <BR>

       <table align="center" cellpadding="0" cellspacing="0">
           <tr class="table_header">
               <td>Мижоз : </td>
                <?php  foreach ($u_mijoz as $um) :?>
                <td >  <?php echo $um->gender;   ?> </td>
                <?php  endforeach; ?>
               <td> Жами</td>
            </tr>
           <tr class="qator">
               <td  class="first"> Сони:  </td>
                <?php $ums=0; foreach ($u_mijoz as $um) :?>
                <td class="first">  <?php echo $um->count;$ums=$ums+$um->count;   ?> </td>
                <?php  endforeach; ?>
               <td class="first"> <?php echo $ums ?></td>
            </tr>
        </table>
        <BR>
        <font color="#a52a2a">Ногиронлик гурухлари хакида  маълумот:</font> 
        <BR><BR>

        <table align="center" cellpadding="0" cellspacing="0">
           <tr class="table_header">
               <td>Гуруҳ</td>
                <?php  foreach ($guruh as $g) :?>
                <td class="first">
                        <?php
                                if($g->guruh==0){$g->guruh="Белгиланмаган";}
                                echo $g->guruh;
                        ?>
                </td>
                    <?php  endforeach; ?>
               <td class="first">Жами:</td>
           </tr>
           <tr class="qator">
               <td class="first">Эркак:</td>
                <?php $egu=0; foreach ($e_guruh as $eg) :?>
                <td class="first">  <?php echo $eg->count; $egu=$egu+$eg->count;   ?> </td>
                <?php  endforeach; ?>
               <td class="first"><?php echo $egu;?></td>
           </tr>
           <tr class="qator">
               <td class="first">Аёл:</td>
                <?php  $agu =0; foreach ($a_guruh as $ag) :?>
                <td class="first">  <?php echo $ag->count;   $agu=$agu+$ag->count; ?> </td>
                <?php  endforeach; ?>
               <td class="first"><?php echo $agu;?></td>
           </tr>
            <tr class="qator">
               <td class="first">Жами сони: </td>
                <?php  foreach ($guruh as $g) :?>
                <td class="first">  <?php echo $g->count;   ?> </td>
                <?php  endforeach; ?>
           </tr>
        </table>
    </div>
</div>

<!--<script type="text/javascript">-->
<!--    $("#ddepart").change(function () {-->
<!--                var depart = $(this).val();-->
<!--                $.ajax({-->
<!--                    url: "/stat/counts/" + depart,-->
<!--                    type: "POST",-->
<!--                    dataType: "json",-->
<!--                      success: function(data) {-->
<!--//                $('#jqgrid_div_right #nsp').html(data[0]['last_name']+' '+data[0]['first_name']+' '+data[0]['middle_name'])-->
<!--                $('#jqgrid_div_right #pindex').html(data[0]['post'])-->
<!--            }-->
<!--                });-->
<!--            });-->
<!---->
<!--</script>-->
</body>
</html>