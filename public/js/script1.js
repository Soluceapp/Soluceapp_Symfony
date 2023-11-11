function sub_menu1() {window.location.assign("https://palissy.soluceapp.com/html/accueil.html"); }
function sub_menu2() {window.location.assign("https://palissy.soluceapp.com/html/petitchevaux.html"); }
function sub_menu3() {window.location.assign("https://palissy.soluceapp.com/html/motcroises.html"); }
function sub_menu4() {window.location.assign("https://palissy.soluceapp.com/html/memory.html");}
function sub_menu5() {document.getElementById("myDropdown5").classList.toggle("show");}
function sub_menu6() {document.getElementById("myDropdown6").classList.toggle("show"); } 
function sub_menu7() {window.location.assign("../html/connect.php");}
function sub_menu8() {window.location.assign("https://palissy.soluceapp.com/html/motsmeles.html");}
function sub_menu10() {window.location.assign("https://palissy.soluceapp.com/html/musique.html");}
function sub_menu11() {window.location.assign("https://palissy.soluceapp.com/html/courtmetrage.html");}
function sub_menu12() {window.location.assign("https://palissy.soluceapp.com/html/escapem.html");}
function sub_menu13() {window.location.assign("https://palissy.soluceapp.com/html/entreprise_virtuelle.html");}
function sub_menu14() {window.location.assign("https://palissy.soluceapp.com/html/vervischapp.html");}
function sub_menu15() {window.location.assign("https://palissy.soluceapp.com/html/musee3D.html");}
function sub_menu16() {console.log(alert("Mot de passe ou utilisateur incorrect."));}
function sub_menu17() {window.location.assign("../vip/compta1.php");}
function sub_menu18() {window.location.assign("../vip/base.php");}
function sub_menu19() {window.location.assign("https://nero.l-educdenormandie.fr/");}
function sub_menu20() {console.log(alert("Action 2"));}
function sub_menu21() {window.location.assign("../script/config.php");}
function sub_menu22() {window.location.assign("../script/config1.php");}
function sub_menu23() {window.location.assign("../script/config2.php");}
function sub_menu24() {window.location.replace("../html/accueil.html"); }
function sub_menu25() {window.location.assign("../script/config4.php"); }
function sub_menu26() {window.location.assign("../script/config5.php"); }
function sub_menu27() {window.location.assign("../script/config3.php"); }
function sub_menu28() {window.location.assign("../vip/facture1.php"); }
function sub_menu29() {window.location.assign("../vip/droit1.php"); }
function sub_menu30() {window.location.assign("../script/testunitaire.php"); }
function sub_menu31() {window.location.assign("../html/connect2.php"); }
function sub_menu32() {console.log(alert("Mot de passe : 12 caractères mini / 1 majuscule mini / 1 minuscule mini / pas d'espace "));}
function sub_menu33() {window.location.assign("../html/connect3.php"); }
function sub_menu34() {console.log(alert("Les données enregistrées dans ce site ne seront pas utilisées et Soluceapp respectent les règles du RGPD (Règlement général sur la protection des données) et la loi Informatique et Liberté n78-17 disponible sur le site https://www.cnil.fr/fr/la-loi-informatique-et-libertes. Vous avez la possibilité d’avoir accès à ces données, de rectification ou d’effacement des données et de retrait du consentement. "));}

// Ferme dropdown si click à l'extérieur
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}


