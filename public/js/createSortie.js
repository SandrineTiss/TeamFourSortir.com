        var httpRequest = new XMLHttpRequest()
        console.log('YEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEES')
        let ville = document.querySelector("#sortie_ville")
        console.log(ville)
        let lieu = document.querySelector('#sortie_lieu')

        ville.addEventListener("change", function (e) {
            e.preventDefault()
            let form = document.querySelector("form")
            let data = this.name + "=" + this.value


            httpRequest.open('POST', 'http://localhost/TeamFourSortirCom/public/sortie/creer', false)
            httpRequest.setRequestHeader('X-Requested-With', 'xmlhttprequest')
            var test = new FormData(form)
            httpRequest.send(test);
            var resp = httpRequest.response

            let content = document.createElement("html");
            content.innerHTML = resp;
            let nouveauSelect = content.querySelector("#sortie_lieu");
            console.log(nouveauSelect);
            document.querySelector("#sortie_lieu").replaceWith(nouveauSelect);

        })



