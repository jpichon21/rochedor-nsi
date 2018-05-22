/* Nouveautés */

var slickNouveautes = $('.editions-nouveautes .slick').slick({
    slidesToShow: 1,
    arrows: false
})

changeSlickNouveautes = function (direction) {
    slickNouveautes.slick(direction)
}

/* Details */

$('.pictures .carrousel').zoom();

var slickDetails = $('.editions-details .slick').slick({
    slidesToShow: 1,
    arrows: false
})

changeSlickDetails = function (direction) {
    slickDetails.slick(direction)
}