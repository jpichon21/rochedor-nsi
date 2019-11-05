import $ from 'jquery'
import 'slick-carousel/slick/slick'
import { xxlarge, xlarge, large, medium } from './variables'

/* Intervenants */

var slickIntervenants = $('.slick').slick({
  slidesToShow: 5,
  arrows: false,
  responsive: [
    { breakpoint: xxlarge, settings: { slidesToShow: 4 } },
    { breakpoint: xlarge, settings: { slidesToShow: 3 } },
    { breakpoint: large, settings: { slidesToShow: 2 } },
    { breakpoint: medium, settings: { slidesToShow: 1, adaptiveHeight: true } }
  ]
})

$('.carousel .next').on('click', () => {
  slickIntervenants.slick('next')
})

$('.carousel .prev').on('click', () => {
  slickIntervenants.slick('prev')
})

$('.slide').on('click', () => {
  console.log("test")
  this.addClass('clicked')
})