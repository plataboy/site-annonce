import $, { ajax } from 'jquery';


document.querySelectorAll('a.js-favoris').forEach(function (link) {

    link.addEventListener('click', addFavoris);

});

getDepartement()
getVille();






//  Get Departement

function getDepartement() {

    getDisabledField("#select_departement", true)
    getDisabledField("#ville", true)


    $("#select_region").change(function () {
        getDisabledField("#select_departement", false)
        getDisabledField("#ville", false)
        $.ajax({
            url: "/departement",
            success: function (data) {
                $("#select_region option").prop('selected', function (index, curren) {

                    if (curren) {
                        var val = $(this).val();
                        $("#select_departement").html(data['departement'][0]['name'])
                        for (var i = 0; i < data['departement'].length; i++) {
                            if (val == data['departement'][i]['codeRegion']) {
                                //  console.log(data['departement'][i]['codeDepartement']);
                                $("#select_departement").append("<option value='" + data['departement'][i]['codeDepartement'] + "'>" + data['departement'][i]['name'] + "</option>");
                                console.log(data['departement'][i]['name']);
                            }
                        }
                    }
                })
            },
        })
    })
}

// zone de recherche recherche une ville


function getVille() {

    $("#ville").keyup(function () {
        var ville = document.querySelector("#ville").value;
        $("#select_departement option").prop('selected', function (index, curren) {
            if (curren) {

                var codeDepartements = $(this).val();
                var NomDepartements = $(this).text();

                $.ajax({
                    url: "/article",
                    data: {
                        'ville_input': ville,
                        'codeDepartement': codeDepartements
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
                    }
                })
            }
        })
    })
}



// fonction permet d'ajouter aux favoris
function addFavoris(event) {

    event.preventDefault(false)

    const url = this.href
    const icon = this.querySelector('i');

    axios({ method: 'get', url: url }).then(function (response) {

        if (icon.classList.contains('far') && response.data.in_favoris == true) {
            icon.classList.replace('far', 'fas');
            location.reload()
        } else if (icon.classList.contains('far') && response.data.in_favoris !== true) {

            alert('Veuillez vous commecter pour Ajouter aux favoris !');
            icon.classList.replace('far', 'far')
        } else {
            icon.classList.replace('fas', 'far');
            location.reload()
        }
    })
}


function getDisabledField(selecteur, boolean) {

    $(selecteur).prop('disabled', boolean)

}








