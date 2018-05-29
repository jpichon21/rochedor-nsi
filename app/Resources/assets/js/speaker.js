import $ from 'jquery'
import 'slick-carousel/slick/slick'

/* Intervenants */

var slickIntervenants = $('.intervenants .slick').slick({
  slidesToShow: 4,
  arrows: false
})

function changeSlickIntervenants (direction) {
  slickIntervenants.slick(direction)
}
