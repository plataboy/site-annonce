import $ from 'jquery';


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






