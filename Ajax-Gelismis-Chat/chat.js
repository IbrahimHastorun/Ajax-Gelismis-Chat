$(document).ready(function() {

    setInterval(function() {
        $("#konusmalar").load("fonksiyon.php?chat=oku");
    },2000); // ==> 2 saniyede bir yenile .

    setInterval(function() {
        $("#yaziyor").load("fonksiyon.php?chat=dosyaoku");
    },2000);

    $("#gonder").keyup(function(e) { // keyup() ==> klavye hareketlerini yakala

        var text = $("#gonder").val();
        var karakter = $("#gonder").attr("maxlength");
        var uzunluk = text.length;

        // 13 sayisi makine dilinde enter tuşuna karşılık gelir.
        // 149 sayisi makine dilinde f5 tuşuna karşılık gelir.
        if (e.keyCode == 13) {
            if (uzunluk > 5 && uzunluk < karakter) {
                $.ajax({
                    type : "POST",
                    url : "fonksiyon.php?chat=ekle",
                    data : $("#mesajgonder").serialize(),
                    success : function() {
                        $("#gonder").val("");
                        $("#konusmalar").load("fonksiyon.php?chat=oku");
                        $("#konusmalar").scrollTop($("#konusmalar")[0].scrollHeight);
                    }
                });
            } else {
                $("#gonder").val("");
            }    
        }
    });

    $("#sohbetayar a").click(function() {
        var gelendeger = $(this).attr("sectionId");

        $.ajax({
            type : "POST",
            url : "fonksiyon.php?chat=sohbetayar",
            data : {"secenek":gelendeger},
            success : function(result) {
                $("#sohbetayar").html(result).fadeIn();
                setInterval(function() {
                    window.location.reload();
                },2000);
            }
        });
    });

    $("#arkabtn").click(function() {
        $.ajax({
            type : "POST",
            url : "fonksiyon.php?chat=arkarenk",
            data : $("#arkaplan").serialize(),
            success : function(result) {
                $("#arkaplandegistir").html(result).fadeIn();
                setInterval(function() {
                    window.location.reload();
                },2000);
            }
        });
    });

    $("#yazirenkbtn").click(function() {
        $.ajax({
            type : "POST",
            url : "fonksiyon.php?chat=yazirenk",
            data : $("#yazirenk").serialize(),
            success : function(result) {
                $("#yazirenkdegistir").html(result).fadeIn();
                setInterval(function() {
                    window.location.reload();
                },2000);
            }
        });
    });

    var sayac = 0;

    $('body').delegate('#gonder',"keyup change",function(e) {
        var text = $("#gonder").val();
        var uzunluk = text.length;
        var uyead = $("#gonder").attr("sectionId");

        if (uzunluk > 0 && sayac == 0 ) {

            $.ajax({
                type : "GET",
                url : "fonksiyon.php?chat=ortak",
                data : {"uyead":uyead},
                success : function(result) {      
                    setInterval(function() {
                        $("#yaziyor").load("fonksiyon.php?chat=dosyaoku");
                    },2000);
                    sayac = 1;
                }
            });
            
        } else if (uzunluk == 0 || e.keyCode == 13){

            $.ajax({
                type : "GET",
                url : "fonksiyon.php?chat=ortak",
                data : {"temizle":uyead},
                success : function(result) {        
                    setInterval(function() {
                        $("#yaziyor").load("fonksiyon.php?chat=dosyaoku");
                    },2000);
                    sayac = 0;
                }
            });
            
        }
    });

});