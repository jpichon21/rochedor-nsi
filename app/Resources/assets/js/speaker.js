import $ from 'jquery'
import 'slick-carousel/slick/slick'

/* Intervenants */

var slickIntervenants = $('.slick').slick({
  slidesToShow: 4,
  arrows: false
})

$('.carousel .prev, .carousel .next').on('click', () => {
  let direction = $(this).hasClass('prev') ? 'prev' : 'next'
  slickIntervenants.slick(direction)
})
