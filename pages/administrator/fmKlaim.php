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
<h3>Klaim APPIK</h3>
 
</br>
<form name="form1" enctype="multipart/form-data" method="post" action="?cat=administrator&page=fmKlaim&act=1">
    <div class="container" style="width:100%">
        <div class="col-sm-6">
            <div class="form-group" >
                <input type="text" placeholder="No.Polis"  style="height:30px"  class="form-control" id="id_policy" value="" name="id_policy"> 
            </div> 
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <button  name="submit" type="submit" class="btn btn-primary">Submit</button> 
            </div>
        </div>
    </div> 
</form>
 <hr>


 <?php 
include_once("pages/db/adMsKlaim.php");
include_once("pages/db/adMsMemberPolicy.php");
include_once("pages/db/adTxRegistrasi.php"); 
ob_start();
 
$data= new adMsMemberPolicy(); 
$dataReg= new adTxRegistrasi(); 
 $dataClaim= new adMsKlaim(); 
	
 if(isset($_POST['submit'])){
  if (empty($_POST["id_policy"])) {
      	$id_policy_error = "* No.Polis harus diisi";
  }else{
      $num=$data->isIdPolicyExist($_POST['id_policy']);
      	$id_policy_error = "*". $num;
    if($data->isIdPolicyExist($_POST['id_policy'])==1){
        $idReg = $data->getRegId($_POST['id_policy']);
      	$no = 1;
      	
      error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
	/* Koneksi database*/
	include 'pages/web/paging.php'; //include pagination file  
	//pagination variables
	$hal = (isset($_REQUEST['hal']) && !empty($_REQUEST['hal']))?$_REQUEST['hal']:1;
	$per_hal = 5; //berapa banyak blok
	$adjacents  = 5;
	$offset = ($hal - 1) * $per_hal;
	$reload="?cat=administrator&page=fmMsGroup";
	//Cari berapa banyak jumlah data*/
	$numrows = $dataClaim->getCountRowAllDataById($_POST['id_policy']);  //dapatkan jumlah data
	
	$total_hals = ceil($numrows/$per_hal);
	
  foreach($dataReg->getDataRegById($idReg) as $res)
?>
     <div class="container" style="width:100%">
            <div class="col-sm-4">
                <div class="form-group" >
                    <label for="name">Nama  : </label> <?php echo $res['registrant_name']; ?><br>
                    <label for="name">No.Polis  : </label> <?php echo $_POST['id_policy']; ?><br>
                    <label for="name">Alamat  : </label><br> <?php echo $res['address']; ?>
        
                </div>
            </div>
                 <div class="col-sm-8">
                <div class="form-group" >
                                <label for="name">Harga Pertanggungan : </label> <?php echo $res['insured_price']; ?><br>
                    <label for="name">Premi  : </label> <?php echo $_POST['id_policy']; ?><br>
                    <label for="name">Komoditi  : </label> <?php echo $res['comodity_type']; ?><br>
        
                </div>
            </div>
    </div>  
 <div class="col-md-12"> 
    <a href="?cat=administrator&page=fmDetailKlaim&edit=1&claimid=&regid=<?php echo $idReg; ?>&id=<?php echo $_POST['id_policy']; ?>"><button  class="btn btn-success">Pengajuan Klaim</button></a>
</div>
</br>
 <table id="claim" width="30%" border="0" cellspacing="0" cellpadding="0" class="responsive table table-striped table-bordered">
<thead>
   
  <tr>
    <th>No.</td>
    <th>No.Polis</th>
    <th>Tgl.Lapor</th>
    <th>Pelapor</th>
      <th>Penyebab</th>
            <th>Tempat Kejadian</th>
    <th>&nbsp;</th>
    </tr>
  </thead>
  <?php
	foreach($dataClaim->getAllDataById($_POST['id_policy'],$offset,$per_hal) as $result){
	    	    if($result['policy_number']!=null){
?>
<tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo $result['policy_number']; ?></td>
    <td> 
    <?php 
    $newYear = date('Y', strtotime(' + 1 years'));
    echo date('d-m', strtotime($result['report_date']))."-".date('Y')." - ".
    date('d-m', strtotime($result['report_date']))."-".$newYear;
    ?>
    </td>
    <td><?php echo $result['reporter_name']; ?></td>
    <td><?php echo $result['cause_of_incident']; ?></td>
    <td><?php echo $result['incident_place']; ?></td> 
    <td><a href="?cat=administrator&page=fmDetailKlaim&edit=1&id=<?php echo $result['policy_number']; ?>&regid=<?php echo $idReg; ?>&claimid=<?php echo $result['id_claim']; ?>">Detail</a></td>   
  </tr>
<?php
 


}
}
?>
 </table> 
  <?php      echo paginate($reload, $hal, $total_hals, $adjacents);     
        
    } 
    else {  $id_policy_error= "No.Polis tidak terdaftar! ";  }
      
  }    
}
?>
 

<?php
ob_start();

?> 


 