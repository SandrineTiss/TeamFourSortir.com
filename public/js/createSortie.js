window.onload = () => {

    let ville = document.getElementById("sortie_ville");
    console.log(ville.value);
    console.log('bonjour')

    ville.addEventListener("change", function () {

        let form = this.closest("form");
        let data = "ville" + "=" + this.value;
            console.log('la valeur de data');
            console.log(data);

        fetch(form.action, {
            method: form.getAttribute("method"),
            body: data,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset:UTF-8"
            }
        })
            .then(response => response.text())
            .then(html => {
                let content = document.createElement("html");
                content.innerHTML = html;
                    console.log('je suis avant le nouveauSelect');
                let nouveauSelect = content.querySelector(".sortie_lieu");
                    console.log(nouveauSelect);
                    console.log('je suis le nouveauSelect');
                document.querySelector("select[id='sortie_lieu']").replaceWith(nouveauSelect);
            })
    })
}
