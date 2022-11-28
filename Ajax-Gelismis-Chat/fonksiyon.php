<?php 
try  {
	$database = new PDO("mysql:host=localhost;dbname=phpuygulamalar;charset=utf8", "root","");
	$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	die($e->getMessege());
}

class chat {

    public $arkaplan;

    public $yazirenk;

    public function kisigetir ($database) {
        $kisigetir = $database->prepare("SELECT * FROM ajaxchat");
        $kisigetir->execute();

        while ($kisicek = $kisigetir->fetch(PDO::FETCH_ASSOC) ) {
            if ($kisicek['durum'] == 1) { ?>

               <span style="font-size:15px;" class="text-success"><?php echo $kisicek['ad'] ?> - Online</span><br> <?php
           
            } else { ?>

                <span style="font-size:15px;" class="text-danger"><?php echo $kisicek['ad'] ?> - Ofline</span><br> <?php
                
            }
        }
    }

    public function loginkontrol ($database,$kulad,$sifre) {
        $login = $database->prepare("SELECT * FROM ajaxchat WHERE ad = ? and sifre = ?");
        $login->bindParam(1,$kulad,PDO::PARAM_STR);
        $login->bindParam(2,$sifre,PDO::PARAM_STR);
        $login->execute();
        $logindurum = $login->fetch(PDO::FETCH_ASSOC);

        if ($login->rowCount() == 0) { ?>
            <div class="alert alert-danger">BİLGİLER HATALI</div> <?php
            header("refresh:2,url=index.php");
        } else { ?>
            <div class="alert alert-success">GİRİŞ YAPILIYOR....</div> <?php
            header("refresh:2,url=chat.php");

            setcookie("kisiad",$kulad);
            
        }
    }

    public function oturumkontrol ($database,$durum=false) {
        if (isset($_COOKIE['kisiad'])) {
            $kisiad = $_COOKIE['kisiad'];

            $oturum = $database->prepare("SELECT * FROM ajaxchat WHERE ad = ?");
            $oturum->bindParam(1,$kisiad,PDO::PARAM_STR);
            $oturum->execute();
            $oturumdurum = $oturum->fetch(PDO::FETCH_ASSOC);

            if ($oturum->rowCount() == 0) {
                
                header("Location:index.php");

            } else {

                if ($durum == true) {

                    header("Location:chat.php");
                  
                }
        
            }

        } else {

            if ($durum == false) {

                header("Location:index.php");
              
            }

        }
    }

    public function renkbak($database) {
        $kisiad = $_COOKIE['kisiad'];
        $renk = $database->prepare("SELECT * FROM ajaxchat WHERE ad = ?");
        $renk->bindParam(1,$kisiad,PDO::PARAM_STR);
        $renk->execute();
        $renkcek = $renk->fetch(PDO::FETCH_ASSOC);

        $this->arkaplan = $renkcek['arkarenk'];
        $this->yazirenk = $renkcek['yazirenk'];

    }

   
}

@$chat = $_GET['chat'];

switch ($chat) {
    case 'oku':

        $dosyayolu = fopen("konusmalar.txt","r");

        while (!feof($dosyayolu)) {
            $satir = fgets($dosyayolu);
            print($satir);
        }

        fclose($dosyayolu);
        
    break;

    case 'ekle':

        $kisiad = $_COOKIE['kisiad'];

        $mesajekleme = $database->prepare("SELECT * FROM ajaxchat WHERE ad = ?");
		$mesajekleme->bindParam(1,$kisiad,PDO::PARAM_STR);
		$mesajekleme->execute();
        $mesajeklemecek = $mesajekleme->fetch(PDO::FETCH_ASSOC);

        $mesaj = htmlspecialchars(strip_tags($_POST['mesaj']));

        $mesajtema = '<span class="pb-5" style="color:#'.$mesajeklemecek["yazirenk"].'"><kbd style="background-color:#'.$mesajeklemecek['arkarenk'].'">'.$kisiad.'</kbd>'.$mesaj.'</span><br>';

        $dosyayolu = fopen("konusmalar.txt","a");

        fwrite($dosyayolu,$mesajtema);

        fclose($dosyayolu);

    break;

    case 'logout' :

        $kisiad = $_COOKIE['kisiad'];

        $durumguncelle = $database->prepare("UPDATE ajaxchat set durum = 0 WHERE ad = ?");
		$durumguncelle->bindParam(1,$kisiad,PDO::PARAM_STR);
		$durumguncelle->execute();

        setcookie("kisiad",$kulad, time() - 1);
        header("Location:index.php");

    break;

    case 'sohbetayar' :

        @$istek = $_POST['secenek'];

        if ($istek == "temizle") {

            unlink("konusmalar.txt");
            touch("konusmalar.txt"); ?>
            <div class="alert alert-success mt-3">Sohbet Temizlendi</div> <?php
            
        } elseif ($istek == "kaydet") {

            copy("konusmalar.txt","arsiv-sohbet/".date("d.m.Y")."-konusmalar.txt"); ?>
            <div class="alert alert-success mt-3">Sohbet Başarıyla kaydedildi</div> <?php
           
        }else {
            header("Location:index.php");
        }

    break;

    case 'arkarenk' :

        $arkaplancode = $_POST['arkaplancode'];

        if ($arkaplancode) {

            $kisiad = $_COOKIE['kisiad'];
            $renk = $database->prepare("UPDATE ajaxchat set arkarenk = ? WHERE ad = ?");
            $renk->bindParam(1,$arkaplancode,PDO::PARAM_STR);
            $renk->bindParam(2,$kisiad,PDO::PARAM_STR);
            $renkdurum = $renk->execute();

            if ($renkdurum) { ?>
                <div class="alert alert-success mt-3">Arka Plan Renk Değiştirildi</div> <?php
            } else { ?>
                <div class="alert alert-danger mt-3">HATA</div> <?php      
            }
            
        } else {
            header("Location:index.php");
        }

    break;

    case 'yazirenk' :

        $yazirenkcode = $_POST['yazirenkcode'];

        if ($yazirenkcode) {

            $kisiad = $_COOKIE['kisiad'];
            $renk = $database->prepare("UPDATE ajaxchat set yazirenk = ? WHERE ad = ?");
            $renk->bindParam(1,$yazirenkcode,PDO::PARAM_STR);
            $renk->bindParam(2,$kisiad,PDO::PARAM_STR);
            $renkdurum = $renk->execute();

            if ($renkdurum) { ?>
                <div class="alert alert-success mt-3">Yazı Renk Değiştirildi</div> <?php
            } else { ?>
                <div class="alert alert-danger mt-3">HATA</div> <?php      
            }
            
        }else {
            header("Location:index.php");
        }


    break;

    case 'ortak' :

        if ($_GET['uyead'] != "") {
           $dosyayolu2 = fopen("kisiler.txt","a");
           $yaziyortema = '<span class="pb-5">'.$_GET["uyead"].' YAZIYOR...</span><br>';
           fwrite($dosyayolu2,$yaziyortema);
        }

        if ($_GET['temizle'] != "") {
            $dosyayolu3 = fopen("kisiler.txt","r");
            $oku = fread($dosyayolu3,filesize("kisiler.txt"));
            $str = str_replace('<span class="pb-5">'.$_GET["temizle"].' YAZIYOR...</span><br>',"",$oku);
            
            $dosyayolu4 = fopen("kisiler.txt","w");
            fwrite($dosyayolu4,$str);
            fclose($dosyayolu4);
        }


    break;

    case 'dosyaoku' :
        $dosyayolu = fopen("kisiler.txt","r");

        while (!feof($dosyayolu)) {
            $satir = fgets($dosyayolu);
            print($satir);
        }

        fclose($dosyayolu);
    break;
 
}



?>