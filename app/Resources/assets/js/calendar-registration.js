import $ from 'jquery'
import { postLogin, getRegistered, getRetreat } from './calendar-api.js'

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

/* Button Radio */

$('.registered-render').on('click', '.button.radio', function (event) {
  event.preventDefault()
  $(this).toggleClass('checked')
})

/* Tunnel */

// Volet 1

const youTemplate = _.template($('.you-template').html())
const registeredTemplate = _.template($('.registered-template').html())
const retreatTemplate = _.template($('.retreat-template').html())

function updateYouRender (data) {
  $('.you-render').html(youTemplate({ you: data }))
}

function updateRegisteredRender (data) {
  $('.registered-render').html(registeredTemplate({ registered: data }))
}

function updateRetreatRender (data) {
  $('.registered-render').html(retreatTemplate({ retreat: data }))
}

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
  postLogin({
    username: $('.username', this).val(),
    password: $('.password', this).val()
  }).then(you => {
    updateYouRender(you)
    getRegistered().then(registered => {
      updateRegisteredRender(registered)
      changeItem($('.item.participants'))
    })
  })
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

// Right Column

getRetreat(3176).then(retreat => {
  console.log(retreat)
  // updateRetreatRender(retreat)
})
