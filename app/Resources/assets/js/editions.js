/* global flashbags */
import $ from 'jquery'
import JsBarcode from 'jsbarcode'
import { upCartBox } from './popup'
import I18n from './i18n'
import { patchProduct, getCartCount } from './order-api.js'

/* Fade */

$(document).ready(function () {
  $('body').fadeIn(100);
  $('body').removeClass('hidden');
});

/* Translations */

let i18n = new I18n()

JsBarcode('.barcode').init()

const themesForm = document.querySelector('form.filter-form-themes')
const filtersForm = document.querySelector('.filters form')

if (themesForm != null) {
  $('.buttons-filter .button.search').on('click', function (event) {
    event.preventDefault()
    $('form.filter-form-themes').submit()
  })
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

// use xhr to render the cart span number
const cartCountTemplate = _.template($('.cartCount-template').html())

function updateCartCountRender () {
  getCartCount().then((res) => {
    $('.cartCount-render').html(cartCountTemplate({
      cartCount: res
    }))
  })
}

updateCartCountRender()
// use xhr to add a product on the cart uploads flashbags and update cart Counter

$('.carousel .prev, .carousel .next').on('click', function () {
  let direction = $(this).hasClass('prev') ? 'prev' : 'next'
  changeThumb(direction)
})

// Au clic sur une miniature, affiche une image dans le carousel
$('.thumbnails .thumb').on('click', function () {
  if ($(this).hasClass('active')) {
    return
  }

  $('.thumbnails .thumb').removeClass('active')
  $(this).addClass('active')

  let prev = $('.carousel .slide.active')
  let next = $('.carousel .slide[data-slide="' + $(this).data('slide') + '"]')

  upElement(prev, next)
})

// Affiche une image dans le carousel
function upElement (prev, next) {
  next.addClass('active fade')
  setTimeout(function () {
    prev.removeClass('active')
    next.removeClass('fade')
  }, 800)
}

$('.product').on('click', '.description .cart', function (event) {
  addProduct(event, $(this).attr('data-id'))
})

$('.actions').on('click', '.addToCart', function (event) {
  addProduct(event, $(this).attr('data-id'))
})

function addProduct (event, product) {
  event.preventDefault()
  let data = {}
  data.productId = product
  data.typeAction = 'add'
  patchProduct(data)
    .then(() => {
      updateCartCountRender()
      upCartBox(i18n.trans('cart.product.added'))
    })
}

// Logo Baseline

const body = document.querySelector('body')
const content = document.querySelector('.content')


content.onscroll = function () {
  content.scrollTop > 20
    ? body.classList.add('scrollTop')
    : body.classList.remove('scrollTop')
}
