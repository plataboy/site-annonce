import $, { ajax } from 'jquery';


document.querySelectorAll('a.js-favoris').forEach(function (link) {

    link.addEventListener('click', addFavoris);

});

function addFavoris(event) {

    event.preventDefault(false)

    const url = this.href
    const icon = this.querySelector('i');

    axios({ method: 'get', url: url }).then(function (response) {


        if (icon.classList.contains('far') && response.data.in_favoris == true) {
            icon.classList.replace('far', 'fas');
            location.reload()



            console.log(response);

        } else if (icon.classList.contains('far') && response.data.in_favoris !== true) {

            alert('Veuillez vous commecter pour Ajouter aux favoris !');
            icon.classList.replace('far', 'far')
        } else {
            icon.classList.replace('fas', 'far');
            location.reload()
        }
    })
}


// zone de recherche page d'accueil


$("#ville").keyup(function () {
    var ville = document.querySelector("#ville").value;

    $.ajax({
        url: "/article",
        data: {
            'ville_input': ville
        },
        success: function (result) {
            if (ville !== "") {

                $("#result_ville").show().html(result['ville']);
                $("#result_ville ul li").click(function () {

                    $("#ville").val($(this).text());
                    $("#result_ville").css("display", "none");
                })

            } else {

                $("#result_ville").css("display", "none").html("");

            }



            console.log(result['ville'].indexOf(ville));


        }
    })


})















