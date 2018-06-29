import $ from 'jquery'
import JsBarcode from 'jsbarcode'

JsBarcode('.barcode').init()

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
