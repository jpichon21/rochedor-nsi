import $ from 'jquery'

/* Dropdowns */

function changeItem (elmt) {
  $('.dropdown .item').each(function () {
    this.style.maxHeight = null
    this.classList.remove('active')
  })
  elmt[0].classList.add('active')
  elmt[0].style.maxHeight = elmt[0].scrollHeight + 'px'
}

$(document).ready(function () {
  setTimeout(function () {
    changeItem($('.dropdown .item:first'))
  }, 500)
})

/* Interaction */

// Volet 1

$('.item.connection').on('click', 'a', function (event) {
  event.preventDefault()
  $('.item.connection a').removeClass('active')
  $(this).addClass('active')
  const which = $(this).attr('href').substring(1)
  $('.item.connection .panel').hide()
  $(`.item.connection .panel.${which}`).show()
  changeItem($('.item.connection'))
})

$('form.connection').on('submit', function (event) {
  event.preventDefault()
  const username = $('.username', this).val()
  const password = $('.password', this).val()
  window.fetch('/login', {
    headers: { 'Content-Type': 'application/json' },
    method: 'POST',
    credentials: 'include',
    body: JSON.stringify({ username: username, password: password })
  })
    .then(res => {
      if (!res.ok) {
        res.json().then(res => {
          throw new Error(res.error)
        })
      }
      return res.json()
    })
    .then(res => {
      console.log(res)
    })
    .catch(error => console.log(error))
  changeItem($('.item.participants'))
})

$('form.registration').on('submit', function (event) {
  event.preventDefault()
  changeItem($('.item.participants'))
  updateModifyForm({})
})

// Volet 2

const modifyTemplate = _.template($('.modify-template').html())

function updateModifyForm (data) {
  $('.modify-render').html(modifyTemplate({
    participant: data
  }))
}

updateModifyForm({})
