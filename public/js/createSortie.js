
    // var result = document.querySelector('#sortie_lieu')
    // var form = document.querySelector('#sortie_ville')
    // form.addEventListener('change', function(e) {
    //
    //
    //     var httpRequest = new XMLHttpRequest()
    //
    //     httpRequest.open('GET', 'http://localhost/TeamFourSortirCom/public/sortie/creer', false)
    //     var data = new FormData()
    //     httpRequest.send(data)
    //
    //     result.innerHTML = httpRequest.responseText
    //
    //     console.log(httpRequest.responseText)


        // var input = document.querySelector('#monID')
        // data.append('#monID', input.value)
        // let ville =
        //     data.append('ville', 'ville')

        var httpRequest = new XMLHttpRequest()
        let ville = document.querySelector("#sortie_ville")
        let lieu = document.querySelector('#sortie_lieu')

        ville.addEventListener("change", function (e)
        {
            e.preventDefault()
            let form = document.querySelector("form")
            let data = this.name + "=" + this.value

            // fetch(form.action, {
            //     method: form.getAttribute("method"),
            //     body: data,
            //     headers: {
            //         "Content-Type": "application/x-www-form-urlencoded; charset:UTF-8"
            //     }
            httpRequest.open('POST', 'http://localhost/TeamFourSortirCom/public/sortie/creer', false)
            httpRequest.setRequestHeader('X-Requested-With', 'xmlhttprequest')
            var test = new FormData(form)
            httpRequest.send(data)
            lieu.innerHTML = httpRequest.responseText


        })


                // .then(response => response.text())
                // .then(html => {
                //     let content = document.createElement("html");
                //     content.innerHTML = html;
                //     console.log('je suis avant le nouveauSelect');
                //     let nouveauSelect = content.querySelector("#sortie_lieu");
                //     console.log(nouveauSelect);
                //     console.log('je suis le nouveauSelect');
                //     document.querySelector("#sortie_lieu").replaceWith(nouveauSelect);
                // })
                // .catch(error => {
                //     console.log(error);
                // })




