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

var is_clicked = false;

$(document).on('click touch', '.slide', function() {
  $(this).toggleClass('targeted');
  var target = $(this).find('.description');
  target.toggleClass('clicked');
  is_clicked = true;
})

$(document).mouseout('.slide', function() {
    var target = $('.description');
    var slide = $('.slide');
    slide.removeClass('targeted');
    target.removeClass('clicked');
})

