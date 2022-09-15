<?php
ob_start();
 
include_once("pages/db/adMsGroup.php");
 
$data = new adMsGroup(); 
ob_end_flush();
	
 if(isset($_POST['submit'])){
  if (empty($_POST["description"])) {
      	$description_error = "* Analisis/Disposisi harus diisi";
  }else{
   if( $data->updateStatusReg($_POST['status'],$_GET['id'])){
                     echo "<script>window.location='?cat=administrator&page=fmMsGroup'</script>"; 
        }
  }
 }
  foreach($data->getDataById($_GET['id']) as $res)
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
 <?php if($data->getStatusById($_GET['id'])=="1"){
  
 ?>
<form name="form1" enctype="multipart/form-data" method="post" action="?cat=administrator&page=fmDetailGroup&act=1&id=<?php echo $_GET['id']; ?>">

<div class="container" style="width:100%">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="text">Status :</label> 
            <select class="form-control" id="status" name="status">
                <option value="1" selected="selected">Draft </option> 
                <option value="2" >Complete</option> 
            </select>
        </div> 
    </div> 
    <div class="col-sm-12">  
        <div class="form-group">
            <label for="text">Keterangan :</label>
            <textarea   class="form-control" id="description" name="description" ></textarea>
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

 
<p></p>

<div class="col-md-12"> 
    <a href="?cat=administrator&page=fmTransaksi&edit=1&id=<?php echo $_GET['id']; ?>"><button  class="btn btn-success">Tambah Anggota</button></a>
</div>


<?php
}

include("pages/administrator/vw_per_group.php");
if(isset($_GET['del']))
{
	$ff=$data->hapus($_GET['id']);
	if($ff)
	{
		echo "<script>window.location='?cat=administrator&page=fmDetailGroup'</script>";
	}
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<style>
.error {color: #e32636;}
</style>