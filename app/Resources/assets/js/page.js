import $ from 'jquery'

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
