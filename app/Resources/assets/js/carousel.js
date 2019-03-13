import $ from 'jquery'
import 'magnific-popup-js'

function upElement (prev, next) {
  next.addClass('active fade')
  setTimeout(function () {
    prev.removeClass('active')
    next.removeClass('fade')
  }, 800)
}

export function changeCarousel (reference) {
  let prev = $('.carousel.active')
  let next = $('[data-carousel-element="' + reference + '"]')
  if (!next.hasClass('active')) {
    upElement(prev, next)
  }
}

function changeSlide (carousel, direction) {
  let prev = $('.slide.active', carousel)
  let next = prev[direction]()
  if (!next.length) {
    next = (direction === 'next')
      ? $('.slide:first', carousel)
      : $('.slide:last', carousel)
  }
  upElement(prev, next)
}

$('.carousel .prev, .carousel .next').on('click', function () {
  const carousel = $(this).parent('.carousel')
  const direction = $(this).hasClass('prev') ? 'prev' : 'next'
  changeSlide(carousel, direction)
})

const images = $('.carousel .slide .image')
if (images.length) { images.magnificPopup({ 'type': 'image' }) }

const iframes = $('.carousel .slide .iframe')
if (iframes.length) { iframes.magnificPopup({ 'type': 'iframe' }) }
