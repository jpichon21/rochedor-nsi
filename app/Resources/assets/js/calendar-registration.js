import $ from 'jquery'
import moment from 'moment'
import * as sample from './sample'
import { postLogin, getRegistered, getRetreat } from './calendar-api.js'

moment.locale('fr')

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

/* Variables */

let _you
let _registered
let _participant
let _participants
let _retreat

const itemConnection = $('.item.connection')
const itemParticipants = $('.item.participants')
const itemValidation = $('.item.validation')

const urlWindow = new URL(window.location.href)
const idRetreat = urlWindow.searchParams.get('id')

/* Renders */

const youTemplate = _.template($('.you-template').html())
const registeredTemplate = _.template($('.registered-template').html())
const participantsTemplate = _.template($('.participants-template').html())
const retreatTemplate = _.template($('.retreat-template').html())
const modifyTemplate = _.template($('.modify-template').html())

function updateYouRender () {
  $('.you-render').html(youTemplate({ you: _you }))
}

function updateRegisteredRender () {
  $('.registered-render').html(registeredTemplate({ registered: _registered }))
}

function updateParticipantsRender () {
  $('.participants-render').html(participantsTemplate({ participants: _participants }))
}

function updateRetreatRender () {
  $('.retreat-render').html(retreatTemplate({ retreat: _retreat }))
}

function updateModifyForm () {
  $('.modify-render').html(modifyTemplate({ participant: _participant }))
}

/* Actions */

itemConnection.on('click', 'a', function (event) {
  event.preventDefault()
  $('.item.connection a').removeClass('active')
  $(this).addClass('active')
  const which = $(this).attr('href').substring(1)
  $('.item.connection .panel').hide()
  $(`.item.connection .panel.${which}`).show()
  changeItem(itemConnection)
})

$('form.connection').on('submit', function (event) {
  event.preventDefault()
  postLogin({
    username: $('.username', this).val(),
    password: $('.password', this).val()
  }).then(you => {
    _you = you
    updateYouRender()
    getRegistered().then(registered => {
      _registered = registered
      updateParticipants()
      updateRegisteredRender()
      changeItem(itemParticipants)
    })
  })
})

$('form.registration').on('submit', function (event) {
  event.preventDefault()
  changeItem(itemParticipants)
})

function updateParticipants () {
  let checked = []
  $('.participate-him.checked').map((index, elmt) => {
    checked.push(parseInt(elmt.getAttribute('data-id')))
  })
  _participants = _registered.filter((registered) => {
    return checked.indexOf(registered.codco) >= 0
  })
  _participants.push(_you)
  updateParticipantsRender()
}

itemParticipants.on('click', '.participate-him', function (event) {
  event.preventDefault()
  updateParticipants()
})

itemParticipants.on('click', '.modify-him', function (event) {
  event.preventDefault()
  const selected = $(this).getAttribute('data-id')
  _participant = _registered.filter((registered) => {
    return registered.codco === selected
  })
  updateModifyForm()
  $('.item.participants .panel.registration').show()
  changeItem(itemParticipants)
})

itemParticipants.on('click', '.add-participant', function (event) {
  event.preventDefault()
  _participant = sample.participant
  updateModifyForm()
})

if (idRetreat > 0) {
  getRetreat(idRetreat).then(retreat => {
    _retreat = retreat
    _retreat.datdeb = moment(_retreat.datdeb).format('LL')
    _retreat.datfin = moment(_retreat.datfin).format('LL')
    updateRetreatRender()
  })
}
