
<?php
include_once("pages/db/adTxRegistrasi.php");
include_once("pages/db/adTxApproval.php"); 
include_once("pages/db/adMsGroup.php");
 
ob_start();
 
$dataReg= new adTxRegistrasi();
$dataGroup = new adMsGroup();
$dataAppv = new adTxApproval();

$date=date('Y-m-d H:i:s'); 
$appvId=$dataAppv->getId();  
	
 if(isset($_POST['submit'])){
  if (empty($_POST["description"])) {
      	$description_error = "* Analisis/Disposisi harus diisi";
  }else{
      //($id,$idReg,$isApproved,$desc,$date,$user)
    if($dataAppv->input($appvId,$_GET['id'],$_POST['status'],$_POST['description'],'1',$date,$_SESSION['login_user_id'])){
       if( $dataGroup->updateStatusReg("5",$_GET['id'])){
                     echo "<script>window.location='?cat=administrator&page=vw_list_approval'</script>"; 
        } 
    } 
    else { echo "Silahkan Coba Lagi update ";  }
      
  }    
}

   foreach($dataGroup->getDataById($_GET['id']) as $res)
?>

 <h5> <b>Group </b></h5><br>
    <div class="container" style="width:100%">
            <div class="col-sm-4">
                <div class="form-group" >
                    
                    <label for="name">Nama Kelompok : </label> <?php echo $res['group_name']; ?><br>
                    <label for="name">Ketua Kelompok  : </label> <?php echo $res['leader']; ?><br> 
                </div>
            </div>
                 <div class="col-sm-8">
                <div class="form-group" >
                    
                    <label for="name">Keterangan  : </label> <br> 
                     <?php echo $res['description']; ?><br> 
                </div>
            </div>
    </div>
 <?php
include("pages/administrator/vw_per_group.php");
?>
  
    <hr>
<h5> <b>Approval </b></h5><br>
<form name="form1" enctype="multipart/form-data" method="post" action="?cat=administrator&page=fmApproval&act=1&id=<?php echo $_GET['id']; ?>">


<div class="container" style="width:100%">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="text">Status :</label> 
            <select class="form-control" id="status" name="status">
                <option value="1" selected="selected">Draft </option> 
                <option value="4" >Disetujui</option>
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
 