<?php
	class antsGeneralFunction {
		var $vrErrorMessage="";
		
		public function request($var) {
			if(isset($_REQUEST[$var])) {
				return $_REQUEST[$var];
			}

			return "";
		}

		public function post($var) {
			if(isset($_POST[$var])) {
				return $_POST[$var];
			}

			return "";
		}
		
		public function setErrorResult($errorMessage) {
			$_POST["error"]="1"; $_POST["error_message"]=$errorMessage;
		}
		
		public function fnGetDBConnection() {
			try{
				$dsn="mysql:host=localhost;dbname=kunjungan_edukasi";	
				$dbh = new PDO($dsn, "root", "");
				$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				return $dbh;
			}catch(PDOException $e){
				$this->vrErrorMessage=$e->getMessage();
				return null;
			}
		}
		
		public function generateKey($dbh, $vrTableName, $vrColumnName, $prefix, $digit) {
			$vrLengthKey=strlen($prefix)+($digit*1);
			$sql="select MAX(RIGHT($vrColumnName, $digit)) as LASTID from $vrTableName a 
			where a.$vrColumnName like '$prefix%' 
			and LENGTH(TRIM(a.$vrColumnName)) = ".$vrLengthKey;

			$sLastID="";
			foreach($dbh->query($sql) as $row) {
				$sLastID=$row["LASTID"];
			}

			$sLastID+=1;
			$strResult=$prefix.str_pad($sLastID, $digit, "0", STR_PAD_LEFT);
			return $strResult;
		}
	}
?>