<?php
error_reporting(0);
 if(function_exists($_POST['F_ACTION'])) {
   $_POST['F_ACTION']();
 }
function fnUpload(){
    include_once("../pages/db/adTxRegistrasi.php"); 
include_once("../pages/db/adMsKlaim.php"); 
include_once("../pages/db/adMsMemberPolicy.php"); 


 $dataReg= new adTxRegistrasi();
$dataKlaim= new adMsKlaim(); 
$dataMember= new adMsMemberPolicy(); 
$date=date('Y-m-d H:i:s'); 
 $klaimId=$dataKlaim->getId();
 
$file = $_FILES['uploadedfile1']['name'];
$file1 = $_FILES['uploadedfile2']['name']; 
	
$target = "../attachment/".$file;
$target1 = "../attachment/".$file1; 

  
$upload=move_uploaded_file($_FILES['uploadedfile1']['tmp_name'], $target);
$upload1=move_uploaded_file($_FILES['uploadedfile2']['tmp_name'], $target1); 

if ($upload || $upload1 ){
 
          if($dataKlaim->input($klaimId,$_POST['id'],$_POST['report_date'],$_POST['reporter_name'],$_POST['phone_number'],$_POST['reporter_address'],
            $_POST['event_date'],$_POST['incident_place'],$_POST['postal_code'],$_POST['cause_of_incident']
            ,$_POST['chronology_incident'] ,$_POST['affected_area'],$_POST['insured_value'],$_POST['description'],$target,$target1,'0','0','1',"mobile",$date)){
           echo "success";
            } else{  echo "failed";}
}
else {
 echo "failed";

}
}
function fnLogin(){ 
	include_once("../pages/db/adMsUser.php");
   $id="";
	$user = new adMsUser();
	
	$username=$_POST['USER_NAME'];
	 $id=$user->getUserId($username);
	if($user->login($_POST['USER_NAME'],$_POST['PASSWORD'])){
		 	  if($id!=""){
		echo "success:".$id;
		 	  }else{
		 	     	echo "failed"; 
		 	  }
	}else{
		echo "failed";
	}
}

 
function fnRegister(){ 
	include_once("../pages/db/adMsUser.php");
    include_once("../pages/db/adMsMemberPolicy.php");
    
   $id="";
	$user = new adMsUser();
	$member = new adMsMemberPolicy();
	$date=date('Y-m-d H:i:s'); 
	
	if($member->isIdPolicyExist($_POST['ID']) >0){
	foreach($member->getDataMemberById($_POST['ID'])as $res)  
	
 	if($user->input($_POST['ID'],$_POST['ID'],$res['registrant_name'],$_POST['PASSWORD'],1,$date,"mobile")){ 
 		echo "success:".$_POST['ID'];
 	}else{
 		echo "failed";
 	} 
 	}else{
 	    echo "failed id";
	}
}
function fnGetDataClaim(){
	include_once("../pages/db/adMsKlaim.php");
	$dt = new adMsKlaim();
	
	$data = "{data:".json_encode($dt->getAllDataByIdPolicy($_POST['ID_POLICY']))."}";
	echo $data;
}

function fnGetDataApprovalClaim(){
	include_once("../pages/db/adMsKlaim.php");
	include_once("../pages/db/adTxApprovalClaim.php");
		
	$dt = new adMsKlaim();
	$dataAppv = new adTxApprovalClaim();
	
	
//	$data = "{data_approval:".json_encode($dataAppv->getData($_POST['ID_CLAIM']))."}";
//	echo $data;
  foreach($dataAppv->getData( $_POST['ID_CLAIM'])  as $res){
  
    echo "<b> Keterangan : </b>".  $res['description']." <br />";  
  }
}
function fnGetDataPaymentClaim(){
	include_once("../pages/db/adMsKlaim.php");
	include_once("../pages/db/adTxPaymentClaim.php");
		
	$dt = new adMsKlaim();
	$dataPayment = new adTxPaymentClaim();
	
	
//	$data = "{data_approval:".json_encode($dataPayment->getData($_POST['ID_CLAIM']))."}";
//	echo $data;
    foreach($dataPayment->getData( $_POST['ID_CLAIM'])  as $res){
    echo "<b> Tgl. Pembayaran : </b>".  date('d-m-Y',strtotime($res['create_datetime']))   ." <br />";  
    echo "<b> Jumlah Pembayaran : </b>".  $res['amount_payment']." <br />";  
    echo "<b> Keterangan : </b>".  $res['description']." <br />"; 
    }

}
function fnGetDataMember(){
	include_once("../pages/db/adTxRegistrasi.php");
	$dt = new adTxRegistrasi();
	
	$data_member = "{data_member:".json_encode($dt->getDataRegistrant($_POST['ID_POLICY']))."}";
	echo $data_member;
}
function fnGetDataMemberWeb(){
	include_once("../pages/db/adTxRegistrasi.php");
	$dt = new adTxRegistrasi();
	
	foreach( $dt->getDataRegistrant($_POST['ID_POLICY'])  as $res){
        echo "<b> Nama : </b>".  $res['registrant_name']   ." <br />";  
        echo "<b> NIK : </b>".  $res['identity_number']." <br />";  
        echo "<b> No.Handphone : </b>".  $res['phone_number']." <br />"; 
        echo "<b> Kecamatan : </b>".  $res['dis_name']." <br />";  
        echo "<b> Kelurahan : </b>".  $res['subdis_name']." <br />"; 
        echo "<b> Alamat : </b>".  $res['address']." <br />";
        echo "::::";
        echo "<b> Komoditas : </b>".  $res['comodity_type']." <br />"; 
        echo "<b> Luas Lahan : </b>".  $res['land_boundary']." <br />";  
        echo "<b> Harga Pertanggungan : </b>".  $res['insured_price']." <br />"; 
        echo "<b> Premi : </b>".  $res['premi']." <br />";  
         $newYear = date('Y', strtotime(' + 1 years'));
         
        echo "<b>JWP: </b>". date('d-m', strtotime($res['create_datetime']))."-".date('Y')." sampai ".
         date('d-m', strtotime($result['create_datetime']))."-".$newYear." <br />"; 
    }
	
//	$data_member = "{data_member:".json_encode($dt->getDataRegistrant($_POST['ID_POLICY']))."}";
//	echo $data_member;
}
/**
function fnGetType(){
	include_once("../pages/db/adNcsMsUnit.php");
	$unit = new adNcsMsUnit();
	
	$data = "{type:".json_encode($unit->tampil_data())."}";
	echo $data;
}
function fnGetStok(){
	include_once("../pages/db/adNcsMsStokDetail.php");
	$unit = new adNcsMsStokDetail();
	
	$data = "{stok:".json_encode($unit->tampil_data_stok_api())."}";
	echo $data;
}
function fnGetDataOutstanding(){
	include_once("../pages/db/adNcsTxRegistrasi.php");
	$dt = new adNcsTxRegistrasi();
	
	$data = "{data:".json_encode($dt->getDataApi($_POST['USER_NAME']))."}";
	echo $data;
}
function fnGetDataReport(){
	include_once("../pages/db/adNcsTxRegistrasi.php");
	$dt = new adNcsTxRegistrasi();
	
	$data = "{data:".json_encode($dt->getDataApiReport($_POST['user_id'],$_POST['begin_date'],$_POST['until_date']))."}";
	echo $data;
}
function fnGetDataFollowUpById(){
	include_once("../pages/db/adNcsTxFollowUp.php");
	$dt = new adNcsTxFollowUp();
	
	$data = "{followUp:".json_encode($dt->tampil_data_by_id($_POST['sales_id']))."}";
	echo $data;
}
function fnSendDataRegister(){ 
	 
		include_once("../pages/db/adNcsMsCustomer.php");
		include_once("../pages/db/adNcsTxRegistrasi.php");
		include_once("../pages/db/adNcsTxFollowUp.php");

		$dataCustomer = new adNcsMsCustomer();
		$dataReg = new adNcsTxRegistrasi();
		$dataFollowUp = new adNcsTxFollowUp();
	
	 if($dataCustomer->customerIsExist($_POST['phone_number'])){
		$date=date('Y-m-d H:i:s'); 
		$customer_id=$dataCustomer->getId();
		$sales_id=$dataReg->getId();
		 $sales_status="0";
			if($_POST["status"]=="PHP" || $_POST["status"]=="SPK"){
				$sales_status="1";
			}
		
			if($dataCustomer->input($customer_id,$_POST['customer_name'],$_POST['address'],$_POST['phone_number'],$_POST['status'],'FU1','0',
				$_POST['prospect_date'] ,$_POST['remark'],$date,$_POST['user_id'])){ 
	  
			if($dataReg->input($sales_id,$customer_id,$_POST['id_unit'],$_POST['status'],$_POST['remark'],$_POST['prospect_date'],
				$date,$_POST['user_id'],'FU1',$sales_status)){
			if($sales_status=="0"){	
					if($dataFollowUp->input($sales_id,$customer_id,'FU1','-',$_POST['follow_up_date'],'-',
					$_POST['user_id'],'0',$date,0)){ 
					echo "success";			
					}else{
					echo "Silahkan Coba Lagi".$sales_id.$customer_id;
					}
				}else{
					echo "success";
				}
				}else{
				echo "Silahkan Coba Lagi".$sales_id.$customer_id;
				} 
				}	
		else{echo "Silahkan Coba Lagi";}

		 }	else{
		include_once("../pages/db/adNcsMsConflict.php");
		$dataConflict = new adNcsMsConflict();

			$date=date('Y-m-d H:i:s'); 
			$id= $dataCustomer->getSalesIdIsExist($_POST['phone_number']);
			//echo "".$id;
				$dataConflict->input($_POST['user_id'],$id,$date);
			 $nama = $dataCustomer->getEmployeeByPhoneNumber($_POST['phone_number']);
			 echo "Data Dengan No.Handphone ini sudah pernah di Follow Up oleh ".$nama." ";
			 }

}


function fnSendDataFollowUp(){ 
	  
		include_once("../pages/db/adNcsTxRegistrasi.php");
		include_once("../pages/db/adNcsTxFollowUp.php"); 
		$dataReg = new adNcsTxRegistrasi();
		$dataFollowUp = new adNcsTxFollowUp();

	
	 $customer_id=$dataReg->getCustomerId($_POST['id_sales']);
	 
	$date=date('Y-m-d H:i:s');    	
	$nextProcess=""; $order=0;
	$sales_status="0";
	if($_POST["status"]=="PHP" || $_POST["status"]=="SPK"){
		$sales_status="1";
	}
	$process_status=$dataReg->getProcessStatusById($_POST['id_sales']);
		$nextProcess = $process_status;
		if($process_status == "PR" && $sales_status=="0"){
			$nextProcess="FU1"; $order=0;
		}else if($process_status == "FU1" && $sales_status=="0"){
			$nextProcess="FU2"; $order=1; $nextOrder=2;
		} else if($process_status == "FU2" && $sales_status=="0"){
			$nextProcess="FU3"; $order=2;
		} else if($process_status== "FU3" && $sales_status=="0"){
			$nextProcess="FU4"; $order=3;
		} else if($process_status == "FU4" && $sales_status=="0"){
			$nextProcess="FU5"; $order=4;
		} else if($process_status == "FU5" && $sales_status=="0"){
			$nextProcess="FU5"; $order=5;
		}

	
   // die ($process_status." ".$_POST['id_sales']);
	if($dataReg->updateStatus($_POST['id_sales'],$nextProcess,$sales_status,$_POST['status'])){  
		if($dataFollowUp->updateFollowUp($_POST['id_sales'],$process_status,$_POST['status'],$_POST['remark'],$_POST['follow_up_date'], $date,$_POST['user_id'], $order)){
			if($sales_status=="0"){	
				if($dataFollowUp->input($_POST['id_sales'],$customer_id,$nextProcess,'-',$_POST['follow_up_date2'],'-',$_POST['user_id'],$sales_status,$date, 1)){
					echo "success";	
				}else{
					if($process_status == "FU5"){
						echo "Maksimal Follow Up Ke 5";

					}else{
						echo "Silahkan Coba Lagi".$_POST['id_sales'].$nextProcess.$sales_status;
					}
				}
			}else{
				 echo "success";
			}
		}else{
		echo "Silahkan Coba Lagi".$_POST['id_sales']."ygini";
		}
	} else{echo "Silahkan Coba Lagi";}


}
***/
?>