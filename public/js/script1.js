function sub_menu6() {window.location.assign("http://localhost:8000/oubli-pass");}
function sub_menu7() {window.location.assign("http://localhost:8000/connexion");}
function sub_menu8() {window.location.assign("http://localhost:8000");}
function sub_menu9() {window.location.assign("http://localhost:8000/registration");}
function sub_menu10() {window.location.assign("http://localhost:8000/logout");}
function sub_menu16() {console.log(alert("Mot de passe ou utilisateur incorrect."));}
function sub_menu32() {console.log(alert("Mot de passe : 12 caractères mini / 1 majuscule mini / 1 minuscule mini / pas d'espace "));}
function sub_menu34() {console.log(alert("Les données enregistrées dans ce site ne seront pas utilisées et Soluceapp respectent les règles du RGPD (Règlement général sur la protection des données) et la loi Informatique et Liberté n78-17 disponible sur le site https://www.cnil.fr/fr/la-loi-informatique-et-libertes. Vous avez la possibilité d’avoir accès à ces données, de rectification ou d’effacement des données et de retrait du consentement par mail. "));}

// Ferme dropdown si click à l'extérieur
document.addEventListener('DOMContentLoaded', function() {
  var dropdownButton = document.querySelector('.dropbtn');
  var dropdownContent = document.getElementById('myDropdown5');

  dropdownButton.addEventListener('click', function(event) {
    event.stopPropagation(); 

    
    dropdownButton.classList.toggle('hidden');

   
    dropdownContent.classList.toggle('show');
  });


  document.addEventListener('click', function(event) {
    if (!event.target.matches('.dropbtn')) {
    
      if (!dropdownButton.classList.contains('hidden')) {
        dropdownButton.classList.add('hidden');
        dropdownContent.classList.remove('show');
      }
    }
  });
});

