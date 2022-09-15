
<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
include_once("pages/db/adTxRegistrasi.php"); 
include_once("pages/db/adMsKlaim.php"); 
include_once("pages/db/adMsMemberPolicy.php"); 

ob_start();
 
$dataReg= new adTxRegistrasi();
$dataKlaim= new adMsKlaim(); 
$dataMember= new adMsMemberPolicy(); 
$date=date('Y-m-d H:i:s'); 


$uploaddir='attachment'; 
$dt_upload=date('Ymd');
$target_direktori = "$uploaddir/$dt_upload";
$target_file="";
if(isset($_FILES['attachment_file'])){
$attach_file=$_FILES['attachment_file']['name']; 
$target_file = "$uploaddir/$dt_upload/$attach_file";
}
if(isset($_FILES['attachment_letter'])){
$attach_letter=$_FILES['attachment_letter']['name']; 
$target_letter = "$uploaddir/$dt_upload/$attach_letter";
}
 $klaimId=$dataKlaim->getId();

  
if(isset($_POST['submit'])){
  if (!(file_exists($target_direktori) && is_dir($target_direktori))) {
                $oldmask = umask(0);
                mkdir($target_direktori, 0777);
                umask($oldmask);
            }
            if (!empty($attach_file)){
                if (move_uploaded_file($_FILES['attachment_file']['tmp_name'], $target_file))  {
                     if (move_uploaded_file($_FILES['attachment_letter']['tmp_name'], $target_letter))  {
                    $eventDate=$_POST['event_date'];
                    $eventTime=$_POST['event_time'];
            $combinedDT = date('Y-m-d H:i:s', strtotime("$eventDate $eventTime"));
            if($dataKlaim->input($klaimId,$_GET['id'],$_POST['report_date'],$_POST['reporter_name'],$_POST['phone_number'],$_POST['reporter_address'],
            $_POST['event_date'],$_POST['incident_place'],$_POST['postal_code'],$_POST['cause_of_incident']
            ,$_POST['chronology_incident'] ,$_POST['affected_area'],$_POST['insured_value'],$_POST['description'],$target_file,$target_letter,'0','0','1',$_SESSION['login_user_id'],$date)){
              echo "<script>window.location='?cat=administrator&page=fmKlaim'</script>"; 
            } else{ echo "Silahkan Coba Lagi!!";  }
                         
                     }else{echo "Gagal Upload attachment file";}
                }else{echo "Gagal Upload attachment surat";}
            }

    
}
  foreach($dataReg->getDataRegById($dataMember->getRegId($_GET['id'])) as $res)
?>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<h3>Detail Klaim</h3>
 <div class="container" style="width:100%">
            <div class="col-sm-6">
                <div class="form-group" >
                    <label for="name">Nama Kelompok : </label> <?php echo $res['group_name']; ?><br>
                    <label for="name">Nama  : </label> <?php echo $res['registrant_name']; ?><br>
                    <label for="name">No.Polis  : </label> <?php echo $_GET['id']; ?><br>
                    <label for="name">Alamat  : </label><br> <?php echo $res['address']; ?>
                </div>
            </div>
                 <div class="col-sm-6">
                <div class="form-group" >
                    <label for="name">Harga Pertanggungan : </label> <?php echo $res['insured_price']; ?><br>
                    <label for="name">Premi  : </label> <?php echo $res['premi']; ?><br>
                    <label for="name">Komoditi  : </label> <?php echo $res['comodity_type']; ?><br>
                    <label for="name">Luas Lahan  : </label> <?php echo $res['land_boundary']; ?><br>
                    <label for="name">Tanggal Periode  : </label><br>
                    <?php 
                     $newYear = date('Y', strtotime(' + 1 years'));
                     echo date('d-m', strtotime($res['create_datetime']))."-".date('Y')." sampai ".
                     date('d-m', strtotime($res['create_datetime']))."-".$newYear;
                    ?>
                </div>
            </div>
</div>
<?php

  foreach($dataKlaim->getAllDataByClaimId($_GET['claimid']) as $resultClaim)
?>
<form name="form1" enctype="multipart/form-data" method="post" action="?cat=administrator&page=fmDetailKlaim&act=1&id=<?php echo $_GET['id']; ?>&regid=<?php echo $_GET['regid']; ?>">
 
        <div class="container" style="width:100%">
            <div class="col-sm-6">
                <div class="form-group" >
                    <label for="email">Tanggal lapor :</label>
                    <input type="text"  style="height:30px"  class="form-control" id="report_date" value="<?php echo $resultClaim['report_date']; ?>" name="report_date">
                    <span class="error"><?php if (isset($report_date_error)) echo $report_date_error; ?> </span>
                </div>
            
                <div class="form-group" >
                    <label>Nama Pelapor :</label>
                    <input  style="height:30px"  class="form-control" type="text" name="reporter_name" id="reporter_name"
                    value="<?php echo $resultClaim['reporter_name']; ?>">
                    <span class="error"><?php if (isset($reporter_name_error)) echo $reporter_name_error; ?> </span>
                </div>
               <div class="form-group" >
                    <label>No.HP Pelapor :</label>
                    <input  style="height:30px"  class="form-control" type="text" name="phone_number" id="phone_number" value="<?php echo $resultClaim['reporter_phone_number']; ?>">
                    <span class="error"><?php if (isset($phone_number_error)) echo $phone_number_error; ?> </span>
                </div>
                <div class="form-group">
                    <label for="text">Alamat Pelapor:</label>
                    <textarea   class="form-control" id="reporter_address" name="reporter_address" > <?php echo $resultClaim['reporter_address']; ?></textarea>
                    <span class="error"><?php if (isset($reporter_address_error)) echo $reporter_address_error; ?> </span>
                </div>
        
            </div>
         
            <div class="col-sm-6">  
                 <?php 
                 if($resultClaim['incident_date']==''){
                     
                 ?>
                   <div class="col-sm-6"> 
                         <label>Tanggal Kejadian  :</label>
                    <div class="form-group" > 
                        <input  style="height:30px"  class="form-control" type="text" name="event_date" id="event_date" value="">
                        <span class="error"><?php if (isset($event_date_error)) echo $event_date_error; ?> </span>
                    </div>
                    </div>
                        <div class="col-sm-6"> 
                       <label>  Jam Kejadian :</label>
                    <div class="form-group" > 
                        <input  style="height:30px"  class="form-control" type="text" name="event_time" id="event_time">
                        <span class="error"><?php if (isset($event_time_error)) echo $event_time_error; ?> </span>
                    </div>
                </div>
                <?php
                 }else{ ?>
                 
                      <div class="form-group" > 
                        <label>Tanggal Kejadian  :</label>
                        <input  style="height:30px"  class="form-control" type="text" name="event_date" id="event_date" value="<?php echo $resultClaim['incident_date']; ?>">
                        <span class="error"><?php if (isset($event_date_error)) echo $event_date_error; ?> </span>
                    </div> <?php
                 }
                ?>
                  <div class="form-group">
                    <label for="text">Lokasi Kejadian:</label>
                    <textarea   class="form-control" id="incident_place" name="incident_place" ><?php echo $resultClaim['incident_place']; ?> </textarea>
                    <span class="error"><?php if (isset($incident_place_error)) echo $incident_place_error; ?> </span>
                </div>
                <div class="form-group" >
                    <label>Kode Pos :</label>
                    <input  style="height:30px"  class="form-control" type="text" name="postal_code" id="postal_code" value="<?php echo $resultClaim['postal_code']; ?>">
                    <span class="error"><?php if (isset($postal_code_error)) echo $postal_code_error; ?> </span>
                </div>
                 <?php 
                 if($resultClaim['cause_of_incident']==''){
                 ?>
                 <div class="form-group">
                <label for="text">Penyebab Kejadian :</label> 
                <select class="form-control" id="cause_of_incident" name="cause_of_incident">
                    <option value="" selected>Please Select </option> 
                    <option value="Bencana Alam" >Bencana Alam</option> 
                    <option value="Wabah Penyakit Ikan" >Wabah Penyakit Ikan</option> 
                    <option value="Lainnya" >Lainnya</option> 

                </select>
                </div>
                <?php }else{?>
                    <div class="form-group" >
                    <label>Penyebab Kejadian :</label>
                    <input  style="height:30px"  class="form-control" type="text" name="cause_of_incident" id="cause_of_incident" value="<?php echo $resultClaim['cause_of_incident']; ?>">
                      </div><?php
                }
                
                ?>
            </div>
                        
            <div class="col-sm-6">  
                <div class="form-group">
                    <label for="text">Jumlah pertanggungan (<?php echo $res['insured_price']; ?>) :</label>
                     <input  style="height:30px"  class="form-control" type="text" name="insured_value" id="insured_value" value="<?php echo $resultClaim['insured_value']; ?>">
                    <span class="error"><?php if (isset($insured_value_error)) echo $insured_value_error; ?> </span>
                </div>
            </div> 
               <div class="col-sm-6">  
                <div class="form-group">
                    <label for="text">Lahan Terdampak (<?php echo $res['land_area']; ?> m2) :</label>
                     <input  style="height:30px"  class="form-control" type="text" name="affected_area" id="affected_area" value="<?php echo $resultClaim['affected_area']; ?>">
                    <span class="error"><?php if (isset($affected_area_error)) echo $affected_area_error; ?> </span>
                </div>
            </div> 
            <div class="col-sm-12">  
                <div class="form-group">
                    <label for="text">Deskripsi Penyebab kerugian :</label>
                    <textarea   class="form-control" id="description" name="description" ><?php echo $resultClaim['description']; ?> </textarea>
                    <span class="error"><?php if (isset($description_error)) echo $description_error; ?> </span>
                </div>
            </div> 
        
             <div class="col-sm-12">  
                <div class="form-group">
                    <label for="text">Kronologi kejadian:</label>
                    <textarea   class="form-control" id="chronology_incident" name="chronology_incident" ><?php echo $resultClaim['chronology_of_incident']; ?> </textarea>
                    <span class="error"><?php if (isset($chronology_incident_error)) echo $chronology_incident_error; ?> </span>
                </div>
            </div> 
            <div class="col-sm-6"> 
                <div class="form-group" >
                    <label for="text">Attachment File :</label><?php
                      if($resultClaim['attachment_url_1']==''){
                 ?>
                    <input type="file" name="attachment_file" size="30" id="attachment_file" />  
                    <?php
                      }else{?>
                           <a target = '_blank'  href= "<?php echo $resultClaim['attachment_url_1']; ?>"><img src="img/icon-file.png" width="24px"></a>
                      <?php }
                    ?>
                    <span class="error"><?php if (isset($datafile_error)) echo $datafile_error; ?> </span>
                </div>
            </div>
          <div class="col-sm-6"> 
                <div class="form-group" >
                    <label for="text">Upload Surat Pengajuan:</label>
                    <?php
                      if($resultClaim['attachment_url_2']==''){
                 ?>
                    <input type="file" name="attachment_letter" size="30" id="attachment_letter" />  
                     <?php
                      }else{?>
                           <a target = '_blank'  href= "<?php echo $resultClaim['attachment_url_2']; ?>"><img src="img/icon-file.png" width="24px"></a>
                      <?php }
                    ?>
                    <span class="error"><?php if (isset($datafile_letter_error)) echo $datafile_letter_error; ?> </span>
                </div>
            </div>
            <hr>
           
            <div class="col-sm-12"> 
            <div class="col-sm-6"> <?php
             if($resultClaim['id_claim']==''){
                 ?>
                <div class="form-group"> 
                <button  name="submit" type="submit" class="btn btn-primary">Submit</button>
              </div>
              <?php
             }
              ?>
              
          </div>
        </div>
    </div> 
</form>
 

 
 
 
<?php
ob_start();

?> 

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script src="js/jquery-ui-date.js"></script>
<script>

$(document).ready(function(){
$.datepicker.setDefaults({
 	dateFormat: 'yy-mm-dd' 
});
 
$(function(){
 	 $("#event_date").datepicker();
	$("#report_date").datepicker();
 
});



$('#event_time').timepicker({
format: 'hh:ii',
        language:  'en',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
});


});
</script>
 