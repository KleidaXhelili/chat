document.addEventListener("DOMContentLoaded", function () {
  //contrôle d'existence
  //liste des falsy : 0, null, false
  if (document.getElementById("avatar")) {
    //écoute de l'événement change
    document.getElementById("avatar").addEventListener("change", function (e) {
      console.log(e.target.files);
      let fichier = e.target.files[0];
      let reader = new FileReader();
      if (fichier) {
        reader.readAsDataURL(fichier);
        reader.onload = function (event) {
          document
            .getElementById("preview")
            .setAttribute("src", event.target.result);
        };
      }
    });

    //Drag and drop(glisser-déposer)
    document.querySelector("html").addEventListener("dragover", function (e) {
      e.preventDefault();
      e.stopPropagation();
      document.getElementById("preview").style.border = "5px dashed blue";
    });
    document.querySelector("html").addEventListener("dragleave", function (e) {
      e.preventDefault();
      e.stopPropagation();
      document.getElementById("preview").style.border = "none";
    });
    document.querySelector("html").addEventListener("drop", function (e) {
      e.preventDefault();
      e.stopPropagation();
      document.getElementById("preview").style.border = "none";
    });
    document.querySelector("#preview").addEventListener("drop", function (e) {
      //je récupère ce qui est déposé au moment du drag and drop
      let fichier = e.dataTransfer.files;
      //j'alimente l'input de type file avec ce fichier
      document.getElementById("avatar").files = fichier;
      //je déclenche l'événement 'change'
      let evenement = new CustomEvent("change");
      document.getElementById("avatar").dispatchEvent(evenement);
    });
  }
  //---------Gestion du tchat en Ajax-----------//

  let lastID = 0; // Dernier ID du message à afficher
  let timer_messages = null; //Timer pour la mise à jour de la conversation
  let timer_users = null; // Timer pour la màj des utilisateurs

  function ajax(parametres, traitement) {
    let url = "inc/process.php";

    let params = new FormData();
    for (let i in parametres) {
      params.append(i, parametres[i]);
    }
    let args = {
      method: "POST",
      body: params,
    };
    fetch(url, args)
      .then(function (reponse) {
        return reponse.json();
      })
      .then(traitement);
  }

  function getUsers() {
    ajax({ action: "getUsers" }, function (datas) {
      //   console.log(datas.users);
      let html =
        datas.nbUsers + " utilisateur" + (datas.nbUsers > 1 ? "s" : "");

      if (datas.nbUsers > 0) {
        for (let i = 0; i < datas.nbUsers; i++) {
          html += `<div class="row mt-2">
                <div class="col-4">
                <img src="avatars/${datas.users[i].avatar}" alt="${datas.users[i].login}" class="img-fluid">
                </div>
                <div class="col-8">
                ${datas.users[i].login}
                </div>
                </div>`;
        }
      }
      document.getElementById("users").innerHTML = html;
    });
  }

  function getMessages(option) {
    if (typeof option != 'undefined' && option == 'start') {
        ajax({
            action : 'getIdMemoire'
        }, function(datas){
            lastID = datas.idMemoire;
            getMessages();
        });
    }
    ajax(
      {
        action: "getMessages",
        lastID: lastID,
      },
      function (datas) {
        if (datas.nbMessages > 0) {
          let html = '';
          for (let i = 0; i < datas.nbMessages; i++) {
            html += `
                <div class="d-flex align-items-center">
                       <p>${datas.messages[i].date_message}</p>
                       <p class="ml-2 avatar">
                            <img src="avatars/${datas.messages[i].avatar}" alt="${datas.messages[i].login}" class="img-fluid">
                       </p>
                       <p class="ml-2">${datas.messages[i].login} > </p>
                       <p class="ml-2">${datas.messages[i].message}</p>
                    </div>
                `;
            lastID = datas.messages[i].id_message;
          }
          document.getElementById("conversation").innerHTML += html;
          document.getElementById("conversation").scrollTop = document.getElementById("conversation").scrollHeight;
        }
      }
    );
  }

  function getLastId() {
    ajax({ action: "getLastId" }, function (datas) {
    //   console.log(datas.lastID);
      lastID = datas.lastID;
      getMessages('start');
    });
  }

  //Je vérifie que je suis sur la page du tchat
  if (document.getElementById("conversation")) {

    getUsers();
    timer_users = setInterval(getUsers, 4000);
    getLastId();
    timer_messages = setInterval(getMessages, 2000);

    document
      .getElementById("formulaire")
      .addEventListener("submit", function (e) {
        e.preventDefault();
        let message = document.getElementById("phrase").value;
        if (message.trim() != "") {
          clearInterval(timer_messages); //je stoppe le timer qui rafraîchit les messages
          ajax(
            {
              action: "addMessage",
              message: message,
            },
            function (datas) {
              getMessages();
              timer_messages = setInterval(getMessages, 2000); //on redémarre le timer
            }
          );
        }
        document.getElementById("phrase").value = "";
      });
  }
});
