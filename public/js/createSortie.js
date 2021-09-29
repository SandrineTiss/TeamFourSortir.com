       // Affichage d'une liste déroulante dynamique

        var httpRequest = new XMLHttpRequest()

       // écoute la sélection de la ville
        let ville = document.querySelector("#sortie_ville")
        ville.addEventListener("change", function (e) {
            e.preventDefault()
            let form = document.querySelector("form")
            let data = this.name + "=" + this.value

            // Communication avec le serveur
            httpRequest.open('POST', 'http://localhost/TeamFourSortirCom/public/sortie/creer', false)
            httpRequest.setRequestHeader('X-Requested-With', 'xmlhttprequest')

            // Envoie du formulaire
            var test = new FormData(form)
            httpRequest.send(test);

            // Récupération de la réponse serveur avec les lieux correspondant aux villes
            var resp = httpRequest.response

            // Conversion de la réponse
            let content = document.createElement("html");
            content.innerHTML = resp;

            // injection de la réponse dans l'élément souhaité
            let nouveauSelect = content.querySelector("#sortie_lieu");
            document.querySelector("#sortie_lieu").replaceWith(nouveauSelect);

        })



