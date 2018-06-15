import $ from 'jquery'

/* Dropdowns */

function updateHeightDropdown () {
  const elmt = $('.dropdown .active')
  elmt.css('maxHeight', elmt.prop('scrollHeight'))
}

function changeItem (elmt) {
  $('.dropdown .item').each(function () {
    $(this).css('maxHeight', null)
    $(this).removeClass('active')
  })
  elmt.addClass('active')
  updateHeightDropdown()
}

$(document).ready(function () {
  setTimeout(function () {
    changeItem($('.dropdown .item:first'))
  }, 500)
})

/* Interaction */

$('.item.connection').on('click', 'a', function (event) {
  event.preventDefault()
  $('.item.connection a').removeClass('active')
  $(this).addClass('active')
  const which = $(this).attr('href').substring(1)
  $('.item.connection .panel').hide()
  $(`.item.connection .panel.${which}`).show()
  updateHeightDropdown()
})
