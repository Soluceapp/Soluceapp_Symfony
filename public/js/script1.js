function sub_menu6() {window.location.assign("http://localhost:8000/oubli-pass");}
function sub_menu7() {window.location.assign("http://localhost:8000/connexion");}
function sub_menu8() {window.location.assign("http://localhost:8000");}
function sub_menu9() {window.location.assign("http://localhost:8000/registration");}
function sub_menu10() {window.location.assign("http://localhost:8000/logout");}
function sub_menu16() {console.log(alert("Mot de passe ou utilisateur incorrect."));}
function sub_menu32() {console.log(alert("Mot de passe : 12 caractères mini / 1 majuscule mini / 1 minuscule mini / pas d'espace "));}
function sub_menu34() {console.log(alert("Les données enregistrées dans ce site ne seront pas utilisées et Soluceapp respectent les règles du RGPD (Règlement général sur la protection des données) et la loi Informatique et Liberté n78-17 disponible sur le site https://www.cnil.fr/fr/la-loi-informatique-et-libertes. Vous avez la possibilité d’avoir accès à ces données, de rectification ou d’effacement des données et de retrait du consentement par mail. "));}

function sub_menu42() {window.location.assign("https://palissy.soluceapp.com");}

document.addEventListener('DOMContentLoaded', function() {
  var dropdownButtons = document.querySelectorAll('.dropbtn');

  dropdownButtons.forEach(function(button) {
    // Recherche le contenu du dropdown associé
    var dropdownContent = document.querySelector(`#${button.dataset.dropdownTarget}`);

    // Vérifie si le contenu est trouvé
    if (!dropdownContent) {
      console.error(`Contenu non trouvé pour le bouton avec data-dropdown-target="${button.dataset.dropdownTarget}"`);
      return; // Ignore ce bouton si le contenu est manquant
    }

    button.addEventListener('click', function(event) {
      event.stopPropagation();

      // Fermer les autres dropdowns
      document.querySelectorAll('.dropdown-content.show').forEach(function(content) {
        if (content !== dropdownContent) {
          content.classList.remove('show');
        }
      });

      // Basculer l'affichage du dropdown actuel
      dropdownContent.classList.toggle('show');
    });
  });

  // Ferme tous les menus si clic en dehors
  document.addEventListener('click', function() {
    document.querySelectorAll('.dropdown-content.show').forEach(function(content) {
      content.classList.remove('show');
    });
  });
});
