import $ from 'jquery'

/* Carrousel */

function upElement (prev, next) {
  next.addClass('active fade')
  setTimeout(function () {
    prev.removeClass('active')
    next.removeClass('fade')
  }, 800)
}

function changeCarrousel (reference) {
  let prev = $('.carrousel.active')
  let next = $('[data-carrousel-element="' + reference + '"]')
  if (!next.hasClass('active')) {
    upElement(prev, next)
  }
}

function changeSlide (direction) {
  let carrousel = $('.carrousel.active')
  let prev = $('.slide.active', carrousel)
  let next = prev[direction]()
  if (!next.length) {
    next = (direction === 'next')
      ? $('.slide:first', carrousel)
      : $('.slide:last', carrousel)
  }
  upElement(prev, next)
}

$('.carrousel .slide a').magnificPopup({ type: 'image' })
$('.carrousel .slide .youtube').magnificPopup({ type: 'iframe' })

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
  let reference = this.getAttribute('data-carrousel-id')
  if (reference) {
    changeCarrousel(reference)
  }
})

$(document).ready(function () {
  setTimeout(function () {
    $('.dropdown .item:first').trigger('click')
  }, 500)
})
