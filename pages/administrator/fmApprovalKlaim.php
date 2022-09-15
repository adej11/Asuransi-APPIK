   
<?php

include_once("pages/db/adTxRegistrasi.php");
include_once("pages/db/adTxApprovalClaim.php"); 
include_once("pages/db/adMsGroup.php");
include_once("pages/db/adMsMemberPolicy.php");
include_once("pages/db/adMsKlaim.php");
 
ob_start();
 
$dataReg= new adTxRegistrasi();
$dataGroup = new adMsGroup();
$dataAppv = new adTxApprovalClaim();
$dataMember = new adMsMemberPolicy();
$dataKlaim = new adMsKlaim();

$regId=$dataMember->getRegId($_GET['id']);
$date=date('Y-m-d H:i:s'); 
$appvId=$dataAppv->getId();  
	
 if(isset($_POST['submit'])){
  if (empty($_POST["description"])) {
      	$description_error = "* Analisis/Disposisi harus diisi";
  }else{
      
    if($dataAppv->input($appvId,$_POST['id_claim'],$_POST['description'],$date,$_SESSION['login_user_id'])){
      if( $dataKlaim->updateStatusClaim($_POST['status'],$_POST['id_claim'])){
                    echo "<script>window.location='?cat=administrator&page=vw_list_approval_claim'</script>"; 
        } 
     
    } 
    else { echo "Silahkan Coba Lagi update ";  }
      
  }    
}

   foreach($dataKlaim->getAllDataByIdPolicy($_GET['id']) as $result)

   foreach($dataReg->getDataRegById($regId) as $res)
?>

<h3>Detail Klaim</h3>
 <div class="container" style="width:100%">
            <div class="col-sm-4">
                <div class="form-group" >
                    
                    <label for="name">Nama Kelompok : </label> <?php echo $res['group_name']; ?><br>
                    <label for="name">Nama  : </label> <?php echo $res['registrant_name']; ?><br>
                    <label for="name">No.Polis  : </label> <?php echo $_GET['id']; ?><br>
                    <label for="name">Alamat  : </label><br> <?php echo $res['address']; ?>
                </div>
            </div>
                 <div class="col-sm-4">
                <div class="form-group" >
                    <label for="name">Harga Pertanggungan : </label> <?php echo $res['insured_price']; ?><br>
                    <label for="name">Premi  : </label> <?php echo $res['premi']; ?><br>
                             <label for="name">Luas Lahan  : </label> <?php echo $res['land_boundary']; ?><br>
                    <label for="name">Komoditi  : </label> <?php echo $res['comodity_type']; ?><br>
                    <label for="name">Tanggal Periode  : </label><br>  <?php 
                     $newYear = date('Y', strtotime(' + 1 years'));
                     echo date('d-m', strtotime($res['create_datetime']))."-".date('Y')." sampai ".
                     date('d-m', strtotime($res['create_datetime']))."-".$newYear;
                    ?><br>
                      <label for="name">Penyebab Kejadian </label>  <?php echo $result['cause_of_incident']; ?><br> 
                </div>
            </div>
                   <div class="col-sm-4">
                <div class="form-group" >
                    <label for="name">Tanggal Lapor : </label> <?php echo $result['report_date']; ?><br>
                    <label for="name">Nama Pelapor  : </label> <?php echo $result['reporter_name']; ?><br>
                    <label for="name">Tanggal jam kejadian : </label> <?php echo $result['incident_date']; ?><br>
                    <label for="name">Lokasi kejadian  : </label>  <?php echo $result['incident_place']; ?><br>
                      <label for="name"> Lampiran File : </label> 
                </div>
                      <div class="col-sm-4">              
                    <a target = '_blank'  href= "<?php echo $resultClaim['attachment_url_1']; ?>"><img src="img/icon-file.png" width="24px"></a>
                    </div>
                      <div class="col-sm-4">
                     <a target = '_blank'  href= "<?php echo $resultClaim['attachment_url_2']; ?>"><img src="img/icon-file.png" width="24px"></a>
                    </div>
            </div>
            
            <div class="col-sm-12">
             <label for="name">Deskripsi Penyebab kerugian:<br> </label>  
             </div>
              <div class="col-sm-12">
           <?php echo $result['description']; ?><br>
           </div>
            <div class="col-sm-12">
             <label for="name">Kronologi Kejadian: </label> <br>
           </div>
            <div class="col-sm-12">
           <?php echo $result['chronology_of_incident']; ?><br>
           </div>
</div>
<hr>

<h5> <b>Approval Klaim</b></h5> 
<div id="map"></div>
<form name="form1" enctype="multipart/form-data" method="post" action="?cat=administrator&page=fmApprovalKlaim&act=1&id=<?php echo $_GET['id']; ?>">

<div class="container" style="width:100%">
    <div class="col-sm-6">
 	<input type="hidden"    style="height:30px"  class="form-control" id="id_claim" value="<?php  echo $result['id_claim'];  ?>" name="id_claim">
        <div class="form-group">
            <label for="text">Status :</label> 
            <select class="form-control" id="status" name="status">
                <option value="1" selected="selected">Draft </option> 
                <option value="5" >Disetujui</option>
                <option value="3" >Ditolak</option>
            </select>
        </div> 
    </div> 
    <div class="col-sm-12">  
        <div class="form-group">
            <label for="text">Analisa/Disposisi :</label>
            <textarea   class="form-control" id="description" name="description" > </textarea>
            <span class="error"><?php if (isset($description_error)) echo $description_error; ?> </span>
        </div>
    </div>  
     
    <div class="col-sm-12"> 
        <div class="col-sm-6"> 
            <div class="form-group"> 
                <button  name="submit" type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div> 
    </div>
</div> 
 
</form>


 
<?php
ob_start();

?> 
  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
  
			// ambil data kabupaten ketika data memilih provinsi
			$('body').on("change","#prov_id",function(){
				var id = $(this).val();
				var data = "id="+id+"&data=kabupaten";
				$.ajax({
					type: 'POST',
					url: "pages/administrator/getDaerah.php",
					data: data,
					success: function(hasil) {
						$("#city_id").html(hasil); 
					}
				});
			});
 
			// ambil data kecamatan/kota ketika data memilih kabupaten
			$('body').on("change","#city_id",function(){
				var id = $(this).val();
				var data = "id="+id+"&data=kecamatan";
				$.ajax({
					type: 'POST',
					url: "pages/administrator/getDaerah.php",
					data: data,
					success: function(hasil) {
						$("#district_id").html(hasil); 
					}
				});
			});
 
			// ambil data desa ketika data memilih kecamatan/kota
			$('body').on("change","#district_id",function(){
				var id = $(this).val();
				var data = "id="+id+"&data=desa";
				$.ajax({
					type: 'POST',
					url: "pages/administrator/getDaerah.php",
					data: data,
					success: function(hasil) {
						$("#subdistrict_id").html(hasil); 
					}
				});
			});
 
 
		});

       function initialize() {
      var map = new google.maps.Map(
        document.getElementById('map'), {
          center: new google.maps.LatLng(37.4419, -122.1419),
          zoom: 13,
          mapTypeId: google.maps.MapTypeId.ROADMAP
      });

      var marker = new google.maps.Marker({
            position: new google.maps.LatLng(37.4419, -122.1419),
            map: map
      });

    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>
 