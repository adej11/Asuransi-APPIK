  
<?php

include_once("pages/db/adTxRegistrasi.php");
include_once("pages/db/adMsKlaim.php");
include_once("pages/db/adTxApproval.php"); 
include_once("pages/db/adMsGroup.php");
include_once("pages/db/adTxPaymentClaim.php");
include_once("pages/db/adMsMemberPolicy.php");

ob_start();
  
$dataReg= new adTxRegistrasi();
$dataClaim= new adMsKlaim();
$dataGroup = new adMsGroup();
$dataPayment = new adTxPaymentClaim();
$dataPolicy = new adMsMemberPolicy();


$date=date('Y-m-d H:i:s'); 
$payId=$dataPayment->getId();  
 
foreach($dataClaim->getAllDataByClaimId($_GET['id']) as $res)
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
      
    if($dataPayment->input($payId,$_GET['id'],$_POST['description'],$res['insured_value'],$_POST['status'], $target_file,"1",$date,$_SESSION['login_user_id'])){
       if($dataClaim->updateStatusClaim('6',$_GET['id'])){
            echo "<script>window.location='?cat=administrator&page=vw_list_payment_claim'</script>"; 
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

$regId=$dataPolicy->getRegId($res['policy_number']);
   foreach($dataReg->getDataRegById($regId) as $result)
?>

<h5> <b>Payment Klaim  : </b></h5> </br>
  <div class="container" style="width:100%">
            <div class="col-sm-4">
                <div class="form-group" > 
                    <label for="name">Nama  : </label> <?php echo $result['registrant_name']; ?><br>
                    <label for="name">No.Polis  : </label> <?php echo $res['policy_number'];  ?><br>
                    <label for="name">Alamat  : </label><br> <?php echo $result['address']; ?>
                </div>
            </div>
                 <div class="col-sm-4">
                <div class="form-group" >
                    <label for="name">Harga Pertanggungan : </label> <?php echo $result['insured_price']; ?><br>
                    <label for="name">Premi  : </label> <?php echo $result['premi']; ?><br>
                    <label for="name">Komoditi  : </label> <?php echo $result['comodity_type']; ?><br>
                    
                </div>
            </div>
</div>
<form name="form1" enctype="multipart/form-data" method="post" action="?cat=administrator&page=fmPaymentClaim&act=1&id=<?php echo $_GET['id']; ?>">

<div class="container" style="width:100%">
  <div class="col-sm-6">
        <div class="form-group">
            <label for="text">Nilai Pertanggungan :</label> 
              <input  readonly  style="height:30px" name="insured_value" id="insured_value"  class="form-control" type="text"  value="Rp. <?php echo number_format($res['insured_value'],0,',','.'); ?>">
        </div> 
    </div> 
     <div class="col-sm-6">
        <div class="form-group">
            <label for="text">Periode :</label> 
              <input  readonly  style="height:30px"  class="form-control" type="text"  value="<?php 
                     $newYear = date('Y', strtotime(' + 1 years'));
                     echo date('d-m', strtotime($res['create_datetime']))."-".date('Y')." sampai ".
                     date('d-m', strtotime($res['create_datetime']))."-".$newYear;
                    ?>">
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
 