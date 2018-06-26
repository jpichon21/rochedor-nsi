import $ from 'jquery'
import Velocity from 'velocityjs'

/* Zoom Text */

function updateHeightDropdown () {
  var elmt = $('.dropdown .active')[0]
  if (elmt !== undefined) {
    elmt.style.maxHeight = elmt.scrollHeight + 'px'
  }
}

const $body = $('body')

$('.zoom').on('click', '.plus', function () {
  if ($body.hasClass('zoom-2x')) {
    $body
      .removeClass('zoom-2x')
      .addClass('zoom-4x')
  } else {
    $body.addClass('zoom-2x')
  }
  updateHeightDropdown()
})

$('.zoom').on('click', '.minus', function () {
  if ($body.hasClass('zoom-4x')) {
    $body
      .removeClass('zoom-4x')
      .addClass('zoom-2x')
  } else {
    $body.removeClass('zoom-2x')
  }
  updateHeightDropdown()
})

/* Menu */

$('[data-menu]').on('mouseover mouseout', function (event) {
  let reference = $(this).attr('data-menu')
  let element = $('[data-menu="' + reference + '"]')
  event.type === 'mouseover'
    ? element.addClass('active')
    : element.removeClass('active')
})

/* Selects */

$('form').on('change', '.select', function () {
  if ($(this).val() !== '') {
    $(this).addClass('white')
  }
})
