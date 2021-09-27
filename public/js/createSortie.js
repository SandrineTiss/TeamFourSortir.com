window.onload = () => {
    let ville = document.querySelector("#sortie_ville");

    ville.addEventListener("change", function(){
        let form = this.closest("form");
        let data = this.nom + "=" + this.value;
        console.log(this.value);
        fetch(form.action, {
            method: form.getAttribute("method"),
            body: data,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset: UTF-8"
            }
        })
            .then(response => response.text())
            .then(html => {
                let content = document.createElement("html");
                content.innerHTML = html;
                let nouveauSelect = content.querySelector("#sortie_lieu");
                console.log(nouveauSelect);
                document.querySelector("#sortie_lieu").replaceWith(nouveauSelect);
            })
    })
}
