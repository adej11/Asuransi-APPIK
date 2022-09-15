  
<?php
include_once("pages/db/adTxRegistrasi.php");
include_once("pages/db/adTxApproval.php"); 
include_once("pages/db/adMsGroup.php");
include_once("pages/db/adTxPaymentReg.php");
include_once("pages/db/adMsMemberPolicy.php");

ob_start();
 
$dataReg= new adTxRegistrasi();
$dataGroup = new adMsGroup();
$dataPayment = new adTxPaymentReg();
$dataPolicy = new adMsMemberPolicy();

$date=date('Y-m-d H:i:s'); 
$payId=$dataPayment->getId();  
 
foreach($dataGroup->showAllById($_GET['id']) as $res)
$uploaddir='attachment'; 
$dt_upload=date('Ymd');
$target_direktori = "$uploaddir/$dt_upload";
$target_file="";
if(isset($_FILES['attachment_file'])){
$file=$_FILES['attachment_file']['name']; 
$target_file = "$uploaddir/$dt_upload/$file";
}	
	
 if(isset($_POST['submit'])){
  if (empty($_POST["description"])) {
      	$description_error = "* Analisis/Disposisi harus diisi";
  }else{
    if (!(file_exists($target_direktori) && is_dir($target_direktori))) {
        $oldmask = umask(0);
        mkdir($target_direktori, 0777);
        umask($oldmask);
    }
    if (!empty($file)){
    if (move_uploaded_file($_FILES['attachment_file']['tmp_name'], $target_file))  {
      
    if($dataPayment->input($payId,$_GET['id'],$_POST['description'],$res['total_premi'],$_POST['status'], $target_file,"1",$date,$_SESSION['login_user_id'])){
       if( $dataGroup->updateStatusReg('6',$_GET['id'])){
          foreach( $dataReg->getAllMemberByGroup($_GET['id'])  as $resultReg){
              echo $dataPolicy->getId()." id reg ".$resultReg['id_registrant'];
             // input($id,$idReg,$date,$userId)
              $dataPolicy->input($dataPolicy->getId(),$resultReg['id_registrant'],$date,$_SESSION['login_user_id']);
          } 
               
            echo "<script>window.location='?cat=administrator&page=vw_list_payment'</script>"; 
        }
       }
      
    else { echo "Silahkan Coba Lagi update ".$payId.",".$_GET['id'].",".$_POST['description'].",".$res['total_premi'].",".$_POST['status'].",".
    $target_file.","."1".",".$date.",".$_SESSION['login_user_id'];  }
      
  }else{echo "Gagal Upload attachment bukti bayar";  }  
}else{
  $datafile_error = "* Bukti bayar harus di upload";
}
}

}
   foreach($dataGroup->getDataById($_GET['id']) as $result)
?>

<h5> <b>Group </b></h5> </br>
 
    <div class="container" style="width:100%">
            <div class="col-sm-4">
                <div class="form-group" >
                    
                    <label for="name">Nama Kelompok : </label> <?php echo $result['group_name']; ?><br>
                    <label for="name">Ketua Kelompok  : </label> <?php echo $result['leader']; ?><br> 
                </div>
            </div>
                 <div class="col-sm-8">
                <div class="form-group" >
                    
                    <label for="name">Keterangan  : </label> <br> 
                     <?php echo $result['description']; ?><br> 
                </div>
            </div>
    </div>
 <?php
include("pages/administrator/vw_per_group.php");
?>
  <hr>
 <h5> <b>Payment </b></h5> </br> 
<form name="form1" enctype="multipart/form-data" method="post" action="?cat=administrator&page=fmPaymentReg&act=1&id=<?php echo $_GET['id']; ?>">

<div class="container" style="width:100%">
  <div class="col-sm-6">
        <div class="form-group">
            <label for="text">Total Pertanggungan :</label> 
              <input  readonly  style="height:30px"  class="form-control" type="text"  value="Rp. <?php echo number_format($res['total_pertanggungan'],0,',','.'); ?>">
        </div> 
    </div> 
     <div class="col-sm-6">
        <div class="form-group">
            <label for="text">Total Premi :</label> 
              <input  readonly  style="height:30px"  class="form-control" type="text"  value="Rp. <?php echo number_format($res['total_premi'],0,',','.'); ?>">
        </div> 
    </div> 
    <div class="col-sm-6">
        <div class="form-group">
            <label for="text">Status :</label> 
            <select class="form-control" id="status" name="status">
                <option value="0" selected="selected">-Pilih Status- </option> 
                <option value="1" >Sudah Bayar</option>
                <option value="2" >Batal</option>
            </select>
        </div> 
    </div> 
     <div class="col-sm-6"> 
                <div class="form-group" >
                    <label for="text">Upload Foto Bukti Bayar :</label>
                    <input type="file" name="attachment_file" size="30" id="attachment_file" />  
                    <span class="error"><?php if (isset($datafile_error)) echo $datafile_error; ?> </span>
                </div>
            </div>
    <div class="col-sm-12">  
        <div class="form-group">
            <label for="text">Keterangan :</label>
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
 /**
include_once("pages/db/adNcsTxPersetujuan.php");
 
$dataTx = new adNcsTxPersetujuan(); 
	
ob_end_flush();

**/
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
 