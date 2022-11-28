<?php
require_once('fonksiyon.php');

$chatislemler = new chat;

$chatislemler->oturumkontrol($database,false);

$chatislemler->renkbak($database);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AJAX CHAT</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script type="text/javascript" src="chat.js"></script>
    <script type="text/javascript" src="jscolor.js"></script>
    <script>
        $(document).ready(function() {
            $("#genelayarlar").hide();

            $("#ozellikackapa").click(function() {
                $("#genelayarlar").slideToggle();
            });
        });
    </script>
    
    <style>
        body {
            background-color:#F3F3F3;     
        }
        
        #kivir {
            border-radius:10px;
            border:1px solid #E0E0E0;
            min-height:400px; 
        }
        
        #renk {
            border-radius:10px;
            border:1px solid #E0E0E0;
            min-height:50px; 
        }
    </style>
</head>
<body>
    <div class="container text-center " >
        <div class="row">
            <div class="col-md-6 bg-white mx-auto mt-5" id="kivir" >
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-secondary border-bottom p-2">
                            PHP JQUERY CHAT UYGULAMASI
                        </h3>
                    </div>
                    <div class="col-md-3 border-right text-left " style="min-height:350px;">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="text-white bg-info border-bottom text-center">
                                    KİŞİLER
                                </h5>
                            </div>           
                            <div class="col-md-12" style="min-height:290px;">
                                <?php
                                    $chatislemler->kisigetir($database);
                                ?>
                            </div>
                            <div class="col-md-12 bg-light text-center">
                                <a class="btn btn-warning btn-sm p-0 m-1" href="fonksiyon.php?chat=logout">Çıkış Yap</a>
                            </div>
                        </div>    
                    </div> 
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12 bg-white text-left" id="konusmalar" style="overflow-y: scroll; height:250px; width: auto;" >
                                  <!--YAZIŞMALAR-->
                            </div>
                            <div class="col-md-12">
                                <form id="mesajgonder">
                                    <textarea sectionId="<?php echo $_COOKIE['kisiad'] ?>" id="gonder" name="mesaj" maxlength = '100' cols="10" rows="3" class="form-control mt-2 " ></textarea>
                                </form>
                            </div>
                        </div>	
                    </div>	
                </div>
            </div>
        </div>
        <div class="row"> 
            <div class="col-md-6 bg-white mx-auto mt-2" id="renk" >
                <div class="row text-center">
                    <div class="col-md-12">
                        <div class="row text-left">
                            <div id="yaziyor" class="col-md-9 border-right bg-light text-danger p-1">

                            </div>
                            <div class="col-md-3 text-right">
                                <a id="ozellikackapa" class="btn btn-danger btn-sm p-1 m-1 text-white">Özellik Aç/Kapa</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="genelayarlar">
                    <div class="col-md-4 border-right" id="arkaplandegistir">
                        <form id="arkaplan">
                            Arkaplan Değiştir <br>
                            <input value="<?php echo $chatislemler->arkaplan ?>" class="form-control mt-1 jscolor" type="text" name="arkaplancode">
                            <input class="btn btn-success btn-sm mt-1 mb-1" type="button" id="arkabtn" value="DEĞİŞTİR">
                        </form>
                    </div>
                    <div class="col-md-4 border-right" id="yazirenkdegistir">
                        <form id="yazirenk">
                            Yazı renk Değiştir <br>
                            <input value="<?php echo $chatislemler->yazirenk ?>" class="form-control mt-1 jscolor" type="text" name="yazirenkcode">
                            <input class="btn btn-success btn-sm mt-1 mb-1" type="button" id="yazirenkbtn" value="DEĞİŞTİR">
                        </form>
                    </div>
                    <div class="col-md-4" id="sohbetayar">
                        Ayalar <br>
                        <a sectionId="temizle" class="btn btn-dark btn-sm mt-1 mb-1 text-white">Sohbeti Temizle</a>
                        <a sectionId="kaydet" class="btn btn-dark btn-sm mt-1 mb-1 text-white">Sohbeti Kaydet</a>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</body>
</html>