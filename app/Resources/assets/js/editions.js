import $ from 'jquery'

/* Nouveautés */

var slickNouveautes = $('.editions-nouveautes .slick').slick({
  slidesToShow: 1,
  arrows: false
})

function changeSlickNouveautes (direction) {
  slickNouveautes.slick(direction)
}

/* Details */

$('.pictures .carrousel').zoom()

var slickDetails = $('.editions-details .slick').slick({
  slidesToShow: 1,
  arrows: false
})

function changeSlickDetails (direction) {
  slickDetails.slick(direction)
}
