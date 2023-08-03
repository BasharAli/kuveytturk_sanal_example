<?php
		$Name=$_POST["CardHolderName"]; 
		$CardNumber=$_POST["CardNumber"]; // 16 haneli olarak 
		$CardExpireDateMonth=$_POST["CardExpireDateMonth"]; // iki hane olarak kartın ay bilgisi
		$CardExpireDateYear=$_POST["CardExpireDateYear"]; // kartın vade tarihinde yıl alanı, iki hane
		$CardCVV2=$_POST["CardCVV2"]; // kartın arka yüzündeki CVV2 kodu
        $MerchantOrderId = "01-eticaret";// İşyerinin belirlediği sipariş numarası
		$Amount = $_POST["Tutar"]; //Islem Tutari // Ornegin 1.00 TL için 100 kati yani 100 yazilmali
        $CustomerId = "";// Bankadaki müsteri numarası
        $MerchantId = ""; //Sanal pos mağaza numarası, başvuru onayıyla işyerine gönderilir. 
        $OkUrl = "http://localhost/KuveytTurk_Ornek_PHP/3_OdemeOnay.php"; //Basarili sonuç alinirsa, yönledirelecek sayfa
        $FailUrl ="http://localhost/KuveytTurk_Ornek_PHP/HataSayfasi.php";//Basarisiz sonuç alinirsa, yönledirelecek sayfa
        $UserName=""; // https://kurumsal.kuveytturk.com.tr adresinde Kullanıcı İşlemleri - Kullanıcı Ekle alanında işyeri tarafından olusturulan api rolünde kullanici adı
		$Password="";// api rolünde kullanici adının sifresi
		$HashedPassword = base64_encode(sha1($Password,"ISO-8859-9")); //md5($Password);	
        $HashData = base64_encode(sha1($MerchantId.$MerchantOrderId.$Amount.$OkUrl.$FailUrl.$UserName.$HashedPassword , "ISO-8859-9"));
		$xml= '<KuveytTurkVPosMessage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">'
				.'<APIVersion>1.0.0</APIVersion>'
				.'<OkUrl>'.$OkUrl.'</OkUrl>'
				.'<FailUrl>'.$FailUrl.'</FailUrl>'
				.'<HashData>'.$HashData.'</HashData>'
				.'<MerchantId>'.$MerchantId.'</MerchantId>'
				.'<CustomerId>'.$CustomerId.'</CustomerId>'
				.'<UserName>'.$UserName.'</UserName>'
				.'<CardNumber>'.$CardNumber.'</CardNumber>'
				.'<CardExpireDateYear>'.$CardExpireDateYear.'</CardExpireDateYear>'
				.'<CardExpireDateMonth>'.$CardExpireDateMonth.'</CardExpireDateMonth>'
				.'<CardCVV2>'.$CardCVV2.'</CardCVV2>'
				.'<CardHolderName>'.$Name.'</CardHolderName>'
				.'<CardType>MasterCard</CardType>'
				.'<BatchID>0</BatchID>'
				.'<TransactionType>Sale</TransactionType>'
				.'<InstallmentCount>0</InstallmentCount>'
				.'<Amount>'.$Amount.'</Amount>'
				.'<DisplayAmount>'.$Amount.'</DisplayAmount>'
				.'<CurrencyCode>0949</CurrencyCode>'
				.'<MerchantOrderId>'.$MerchantOrderId.'</MerchantOrderId>'
				.'<TransactionSecurity>3</TransactionSecurity>'
				.'</KuveytTurkVPosMessage>';

			
	 try {
			$ch = curl_init();  
			curl_setopt($ch, CURLOPT_SSLVERSION, 6);
			//curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_MAX_TLSv1_2); 
			//curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_0 | CURL_SSLVERSION_TLSv1_1 | CURL_SSLVERSION_TLSv1_2); // php 5.5.19+
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/xml', 'Content-length: '. strlen($xml)) );
			curl_setopt($ch, CURLOPT_POST, true); //POST Metodu kullanarak verileri g�nder  
			curl_setopt($ch, CURLOPT_HEADER, false); //Serverdan gelen Header bilgilerini �nemseme.  
			curl_setopt($ch, CURLOPT_URL,'https://sanalpos.kuveytturk.com.tr/ServiceGateWay/Home/ThreeDModelPayGate'); //Baglanacagi URL  
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	
		 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //Transfer sonu�larini al.
			$data = curl_exec($ch);  
			curl_close($ch);
		 }
		 catch (Exception $e) {
		 echo 'Caught exception: ',  $e->getMessage(), "\n";
		}


		 echo($data);
		 error_reporting(E_ALL); 
		 ini_set("display_errors", 1); 
?>

