<style>
.pagin {
padding: 10px 0;
font:bold 11px/30px arial, serif;
}
.pagin * {
padding: 2px 6px;
color:#0A7EC5;
margin: 2px;
border-radius:3px;
}
.pagin a {
		border:solid 1px #8DC5E6;
		text-decoration:none;
		background:#F8FCFF;
		padding:6px 7px 5px;
}

.pagin span, a:hover, .pagin a:active,.pagin span.current {
		color:#FFFFFF;
		background:-moz-linear-gradient(top,#B4F6FF 1px,#63D0FE 1px,#58B0E7);
		    
}
.pagin span,.current{
	padding:8px 7px 7px;
}
.content{
	padding:10px;
	font:bold 12px/30px gegoria,arial,serif;
	border:1px dashed #0686A1;
	border-radius:5px;
	background:-moz-linear-gradient(top,#E2EEF0 1px,#CDE5EA 1px,#E2EEF0);
	margin-bottom:10px;
	text-align:left;
	line-height:20px;
}
.outer_div{
	margin:auto;
	width:600px;
}
#loader{
	position: absolute;
	text-align: center;
	top: 75px;
	width: 60%;
	display:none;
}

 
#claim {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#claim td, #claim th {
  border: 1px solid #ddd;
  padding: 8px;
}

#claim tr:nth-child(even){background-color: #f2f2f2;}

#claim tr:hover {background-color: #ddd;}

#claim th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #2596be;
  color: white;
}
</style>
<h3>Daftar Polis APPIK</h3>
 
</br>
<form name="form1" enctype="multipart/form-data" method="post" action="?cat=administrator&page=fmReport&act=1">
    <div class="container" style="width:100%">
        <div class="col-sm-3">
            <div class="form-group" >
                <input type="text" placeholder="Nama"  style="height:30px"  class="form-control" id="registrant_name" value="" name="registrant_name"> 
            </div> 
        </div>
             <div class="col-sm-3">
            <div class="form-group" >
                <input type="text" placeholder="No.Polis"  style="height:30px"  class="form-control" id="id_policy" value="" name="id_policy"> 
            </div> 
        </div>
        
           <div class="col-sm-3">
            <div class="form-group" >
                <input type="text" placeholder="Nama Group"  style="height:30px"  class="form-control" id="group_name" value="" name="group_name"> 
            </div> 
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <button  name="submit" type="submit" class="btn btn-primary">Submit</button> 
            </div>
        </div>
    </div> 
</form>
 <hr>
<?php
  error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
	/* Koneksi database*/
	include 'pages/web/paging.php'; //include pagination file  
	//pagination variables
	$hal = (isset($_REQUEST['hal']) && !empty($_REQUEST['hal']))?$_REQUEST['hal']:1;
	$per_hal = 5; //berapa banyak blok
	$adjacents  = 5;
	$offset = ($hal - 1) * $per_hal;
	$reload="?cat=administrator&page=fmMsReport";
	//Cari berapa banyak jumlah data*/
?>

</br>
  <a href="#" onClick="$('#registrasi').tableExport({type:'excel',escape:'false'});"> <img src="img/xls.png" width="24px"></a> 

 <table id="claim" width="30%" border="0" cellspacing="0" cellpadding="0" class="responsive table table-striped table-bordered">
<thead>
   
  <tr>
    <th>No.</td>
    <th>No.Polis</th>
    <th>Nama</th>
    <th>Kecamatan</th>
    <th>Kelurahan</th>
    <th>Komoditas</th>
    <th>Harga Pertanggungan</th>
    <th>Luas Area</th>
    <th>Premi</th>
     <th>JWP</th>
    <th>&nbsp;</th>
    </tr>
  </thead>
 <?php 
include_once("pages/db/adMsKlaim.php");
include_once("pages/db/adMsMemberPolicy.php");
include_once("pages/db/adTxRegistrasi.php"); 
ob_start();
 
$data= new adMsMemberPolicy(); 
$dataReg= new adTxRegistrasi(); 
 $dataClaim= new adMsKlaim(); 
	
 if(isset($_POST['submit'])){
  	$numrows = $dataClaim->getCountRowAllDataById($_POST['id_policy']);  //dapatkan jumlah data
	$total_hals = ceil($numrows/$per_hal);
	
?>


  <?php
  if(!empty($_POST['id_policy'])){
           	$no = 1;
	foreach($dataReg->getDataRegistrant($_POST['id_policy']) as $result){
	    	    if($result['id_policy']!=null){
	    	   
?>
<tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $result['id_policy']; ?></td>
    <td><?php echo $result['registrant_name']; ?></td>
    <td><?php echo $result['dis_name']; ?></td>
    <td><?php echo $result['subdis_name']; ?></td> 
    <td><?php echo $result['comodity_type']; ?></td> 
    <td><?php echo $result['insured_price']; ?></td> 
    <td><?php echo $result['land_boundary']; ?></td> 
     <td><?php echo $result['premi']; ?></td> 
     <td>
    <?php 
         $newYear = date('Y', strtotime(' + 1 years'));
         echo date('d-m', strtotime($result['create_datetime']))."-".date('Y')." sampai ".
         date('d-m', strtotime($result['create_datetime']))."-".$newYear;
    ?>
     </td> 
    <td><a href="?cat=administrator&page=vw_list_claim&edit=1&id=<?php echo $result['id_policy']; ?>&regid=<?php echo $idReg; ?>&claimid=<?php echo $result['id_claim']; ?>">Klaim</a></td>   
  </tr>
<?php
 
}

}
} if(!empty($_POST['registrant_name'])){
    	$no = 1;
    	foreach($dataReg->getDataRegistrantByName($_POST['registrant_name']) as $result){
	    	    if($result['id_policy']!=null){

?>
<tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $result['id_policy']; ?></td>
    <td><?php echo $result['registrant_name']; ?></td>
    <td><?php echo $result['dis_name']; ?></td>
    <td><?php echo $result['subdis_name']; ?></td> 
    <td><?php echo $result['comodity_type']; ?></td> 
    <td><?php echo $result['insured_price']; ?></td> 
    <td><?php echo $result['land_boundary']; ?></td> 
     <td><?php echo $result['premi']; ?></td> 
          <td>
    <?php 
         $newYear = date('Y', strtotime(' + 1 years'));
         echo date('d-m', strtotime($result['create_datetime']))."-".date('Y')." sampai ".
         date('d-m', strtotime($result['create_datetime']))."-".$newYear;
    ?>
     </td> 
    <td><a href="?cat=administrator&page=vw_list_claim&edit=1&id=<?php echo $result['id_policy']; ?>&regid=<?php echo $idReg; ?>&claimid=<?php echo $result['id_claim']; ?>">Klaim</a></td>   
  </tr>
<?php
 
}
}   
  } 
  
   if(!empty($_POST['group_name'])){
    	$no = 1;
    	foreach($dataReg->getDataRegistrantByGroupName($_POST['group_name']) as $result){
	    	    if($result['id_policy']!=null){

?>
<tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $result['id_policy']; ?></td>
    <td><?php echo $result['registrant_name']; ?></td>
    <td><?php echo $result['dis_name']; ?></td>
    <td><?php echo $result['subdis_name']; ?></td> 
    <td><?php echo $result['comodity_type']; ?></td> 
    <td><?php echo $result['insured_price']; ?></td> 
    <td><?php echo $result['land_boundary']; ?></td> 
     <td><?php echo $result['premi']; ?></td> 
          <td>
    <?php 
         $newYear = date('Y', strtotime(' + 1 years'));
         echo date('d-m', strtotime($result['create_datetime']))."-".date('Y')." sampai ".
         date('d-m', strtotime($result['create_datetime']))."-".$newYear;
    ?>
     </td> 
    <td><a href="?cat=administrator&page=vw_list_claim&edit=1&id=<?php echo $result['id_policy']; ?>&regid=<?php echo $idReg; ?>&claimid=<?php echo $result['id_claim']; ?>">Klaim</a></td>   
  </tr>
<?php
 
}
}   
  } 
}
?>
 

 </table> 

 
<?php
ob_start();
echo paginate($reload, $hal, $total_hals, $adjacents);   
?> 