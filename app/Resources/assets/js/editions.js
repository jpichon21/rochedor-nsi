/* global flashbags */
import $ from 'jquery'
import JsBarcode from 'jsbarcode'
import 'jquery-zoom-js'
import { upFlashbag } from './popup'
import { limitMenuReduced } from './variables'

JsBarcode('.barcode').init()

$('.slide .image').zoom()
let zoom = true

const handleWindowResize = () => {
  if (window.innerWidth >= limitMenuReduced) {
    if (!zoom) {
      $('.slide .image').zoom()
      zoom = true
    }
  } else {
    if (zoom) {
      $('.slide .image').trigger('zoom.destroy')
      zoom = false
    }
  }
}

window.onresize = () => {
  handleWindowResize()
}

const themesForm = document.querySelector('.filter.themes form')
const filtersForm = document.querySelector('.filters form')

if (themesForm != null) {
  themesForm.onchange = event => {
    const themes = document.querySelectorAll('.filter.themes input:checked')
    const input = document.querySelector('.filter.themes input.value')
    let values = []
    themes.forEach(function (element) {
      values.push(element.value)
    })
    input.value = values.join('|')
    event.currentTarget.submit()
  }
}

if (filtersForm != null) {
  document.querySelector('.filters form').onchange = event => {
    event.currentTarget.submit()
  }
}

function changeThumb (direction) {
  let thumbnails = $('.thumbnails')
  let prev = $('.thumb.active', thumbnails)
  let next = prev[direction]()
  if (!next.length) {
    next = (direction === 'next')
      ? $('.thumb:first', thumbnails)
      : $('.thumb:last', thumbnails)
  }
  next.addClass('active')
  prev.removeClass('active')
}

$('.carousel .prev, .carousel .next').on('click', function () {
  let direction = $(this).hasClass('prev') ? 'prev' : 'next'
  changeThumb(direction)
})

// Displays flashbags

if (flashbags.length > 0) {
  upFlashbag(flashbags.join('<br>'))
}

// Logo Baseline

const body = document.querySelector('body')
const content = document.querySelector('.content')

content.onscroll = function () {
  content.scrollTop > 20
    ? body.classList.add('scrollTop')
    : body.classList.remove('scrollTop')
}
