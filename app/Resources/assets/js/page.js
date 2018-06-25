import $ from 'jquery'
import 'magnific-popup-js'

/* Carousel */

function upElement (prev, next) {
  next.addClass('active fade')
  setTimeout(function () {
    prev.removeClass('active')
    next.removeClass('fade')
  }, 800)
}

function changeCarousel (reference) {
  let prev = $('.carousel.active')
  let next = $('[data-carousel-element="' + reference + '"]')
  if (!next.hasClass('active')) {
    upElement(prev, next)
  }
}

function changeSlide (direction) {
  let carousel = $('.carousel.active')
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
  let direction = $(this).hasClass('prev') ? 'prev' : 'next'
  changeSlide(direction)
})

const slidesImages = $('.carousel .slide .image')
if (slidesImages.length) { slidesImages.magnificPopup({ 'type': 'image' }) }

const slideIframes = $('.carousel .slide .iframe')
if (slideIframes.length) { slideIframes.magnificPopup({'type': 'iframe'}) }

/* Dropdowns */

function updateHeightDropdown () {
  let elmt = $('.dropdown .active')[0]
  elmt.style.maxHeight = elmt.scrollHeight + 'px'
}

$('.dropdown .item').on('click', function () {
  $('.dropdown .item').each(function () {
    this.style.maxHeight = null
    this.classList.remove('active')
  })
  this.classList.add('active')
  updateHeightDropdown()
  let reference = this.getAttribute('data-carousel-id')
  if (reference) {
    changeCarousel(reference)
  }
})

$(document).ready(function () {
  setTimeout(function () {
    $('.dropdown .item:first').trigger('click')
  }, 500)
})
