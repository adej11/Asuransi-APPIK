<?php
ob_start();
 
include_once("pages/db/adMsGroup.php");
 
$data = new adMsGroup(); 
ob_end_flush();

if(isset($_POST['submit'])){
if (empty($_POST["group_name"])) {
	 
		$group_name_error = "* Nama kelompok harus diisi"; 
	 
	  }else{
		$date=date('Y-m-d H:i:s');
		$id=$data->getId();
		//group_id	group_name	description	leader_id	status	create_date	create_user_by

			if($data->input($id,$_POST['group_name'] ,$_POST['description'],'',1,$date,'')){	
					echo "<script>window.location='?cat=administrator&page=fmMsGroup'</script>";
			 }else{echo "Silahkan Coba Lagi";}
		}
}
?>
 <h3>Data Kelompok</h3>
 <form name="form1" method="post" action="?cat=administrator&page=fmMsGroup&act=1">
    <div class="container" style="width:100%">
        <div class="form-group" style="width:40%" >
            <label>Nama Kelompok</label>
            <input  class="form-control"  type="text" name="group_name" id="group_name">
             <span class="error"><?php if (isset($group_name_error)) echo $group_name_error; ?> </span> 
        </div>
        <div class="form-group" style="width:40%" >
          <label for="text">Keterangan :</label>
         <textarea   class="form-control" id="description" name="description"></textarea> 
         <span class="error"><?php if (isset($keterangan_error)) echo $keterangan_error; ?> </span> 
        </div>
        <p></p>
        <input type="submit" name="submit" id="submit" class="btn btn-primary" name="button" id="button" value="Daftar">
    </div>    
</form>
<p></p>
<?php
include("pages/administrator/vw_group.php");
if(isset($_GET['del']))
{
	$ff=$data->hapus($_GET['id']);
	if($ff)
	{
		echo "<script>window.location='?cat=administrator&page=fmMsGroup'</script>";
	}
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<style>
.error {color: #e32636;}
</style>