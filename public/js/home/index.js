'use strict';

$(function () {

$('.modal-open').on('click', function () {
$('#loginModal').fadeIn();
});


$('.modal .close').on('click', function () {
$('#loginModal').fadeOut();
});


$(document).on('click', function (e) {
if ($(e.target).is('#loginModal')) {
$('#loginModal').fadeOut();
}
});
});