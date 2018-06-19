import $ from 'jquery'
import moment from 'moment'
import * as sample from './sample'
import {
  postParticipant,
  getLogin,
  getLogout,
  postLogin,
  getRegistered,
  getRetreat } from './calendar-api.js'

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

function updateModifyRender () {
  $('.modify-render').html(modifyTemplate({ participant: _participant }))
}

function updateAddRender () {
  $('.add-render').html(modifyTemplate({ participant: _participant }))
}

/* Actions */

function afterLogin (user) {
  _you = user
  _participants = [_you]
  updateYouRender()
  getRegistered().then(registered => {
    _registered = registered
    updateRegisteredRender()
    updateParticipantsRender()
    changeItem(itemParticipants)
  })
}

function formatParticipant (data) {
  let participant = sample.participant
  data.map((obj) => {
    participant[obj.name] = obj.value
  })
  participant.codco = parseInt(participant.codco)
  participant.datnaiss = moment().format()
  return participant
}

itemConnection.on('submit', 'form.connection', function (event) {
  event.preventDefault()
  postLogin({
    username: $('.username', this).val(),
    password: $('.password', this).val()
  }).then(user => afterLogin(user))
})

itemConnection.on('submit', 'form.registration', function (event) {
  event.preventDefault()
  changeItem(itemParticipants)
})

itemParticipants.on('submit', 'form.modify', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  const participant = formatParticipant(data)
  postParticipant(participant).then(res => {
    _registered.map(obj => {
      if (obj.codco === res.codco) { return res }
      return obj
    })
    updateRegisteredRender()
    updateParticipantsRender()
    $('.panel', itemParticipants).hide()
    changeItem(itemParticipants)
  })
})

itemParticipants.on('submit', 'form.add', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  const participant = formatParticipant(data)
  postParticipant(participant).then(res => {
    _registered.push(res)
    updateRegisteredRender()
    updateParticipantsRender()
    $('.panel', itemParticipants).hide()
    changeItem(itemParticipants)
  })
})

itemConnection.on('click', 'a', function (event) {
  event.preventDefault()
  $('a', itemConnection).removeClass('active')
  $(this).addClass('active')
  const which = $(this).attr('href').substring(1)
  switch (which) {
    case 'connection':
    case 'registration':
      $('.panel', itemConnection).hide()
      $(`.panel.${which}`, itemConnection).show()
      break
    case 'continue':
      getLogin().then(user => afterLogin(user))
      break
    case 'disconnect':
      getLogout()
      break
  }
  changeItem(itemConnection)
})

itemParticipants.on('click', '.participate-him', function (event) {
  event.preventDefault()
  let checked = []
  $('.participate-him.checked', itemParticipants).each(() => {
    checked.push(parseInt($(this).attr('data-id')))
  })
  _participants = _registered.filter(registered => {
    return checked.indexOf(registered.codco) >= 0
  })
  _participants.push(_you)
  updateParticipantsRender()
})

itemParticipants.on('click', '.modify-you', function (event) {
  event.preventDefault()
  _participant = _you
  _participant.datnaiss = moment(_participant.datnaiss).format('L')
  $('.panel', itemParticipants).hide()
  $(`.panel.modify`, itemParticipants).show()
  updateModifyRender()
  changeItem(itemParticipants)
})

itemParticipants.on('click', '.modify-him', function (event) {
  event.preventDefault()
  const selected = parseInt($(this).attr('data-id'))
  const participants = _registered.filter(registered => {
    return registered.codco === selected
  })
  _participant = participants.shift()
  _participant.datnaiss = moment(_participant.datnaiss).format('LL')
  $('.panel', itemParticipants).hide()
  $(`.panel.modify`, itemParticipants).show()
  updateModifyRender()
  changeItem(itemParticipants)
})

itemParticipants.on('click', '.add-participant', function (event) {
  event.preventDefault()
  _participant = sample.participant
  $('.panel', itemParticipants).hide()
  $(`.panel.add`, itemParticipants).show()
  updateAddRender()
})

if (idRetreat > 0) {
  getRetreat(idRetreat).then(retreat => {
    _retreat = retreat
    _retreat.datdeb = moment(_retreat.datdeb).format('LL')
    _retreat.datfin = moment(_retreat.datfin).format('LL')
    updateRetreatRender()
  })
}
