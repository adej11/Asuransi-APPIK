<!--NAVIGASI MENU UTAMA-->

<div class="leftmenu">
  <ul class="nav nav-tabs nav-stacked">
    <li class="active"><a href="dashboard.php"><span class="icon-align-justify"></span> Dashboard</a></li>
    
    <!--MENU ADMINISTRATOR-->
    <?php
	if($_SESSION['login_hash']=="administrator"){
	?>
       <li><a href="?cat=administrator&page=fmMsGroup">Pendaftaran</a></li> 
         <li><a href="?cat=administrator&page=vw_list_approval">Persetujuan Pendaftaran</a></li> 
                  <li><a href="?cat=administrator&page=vw_list_payment">Pembayaran</a></li> 
         <li><a href="?cat=administrator&page=fmKlaim">Klaim</a></li> 
                 <li><a href="?cat=administrator&page=vw_list_approval_claim">Persetujuan Klaim</a></li> 
                    <li><a href="?cat=administrator&page=vw_list_payment_claim">Pembayaran Klaim</a></li> 
         <li><a href="?cat=administrator&page=fmReport">Laporan</a></li> 
    <?php
	}elseif($_SESSION['login_hash']=="pemohon"){
	?>
         <li><a href="?cat=administrator&page=registrasi">Pengajuan Kunjungan</a></li> 
         <li><a href="?cat=administrator&page=vw_registrasi_user">Lihat Pengajuan Kunjungan</a></li>   
	<?php } ?>
  </ul>
</div>
<!--leftmenu-->

</div>
<!--mainleft--> 
<!-- END OF LEFT PANEL -->