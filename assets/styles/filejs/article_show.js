import $ from 'jquery';


$(document).ready(function () {

    $("#contact_button").click(function () {
        $("#contact").css("height", "15%").show();
        $("#contact_button").hide();

    })
});