import $ from 'jquery'
import moment from 'moment'
import { getParticipant } from './sample'
import {
  postParticipant,
  getLogin,
  getLogout,
  postLogin,
  getRegistered,
  postRegister } from './calendar-api.js'

/* Translations */

const _translations = JSON.parse($('.translations-json').html())

moment.locale(_translations.locale)

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

const itemConnection = $('.item.connection')
const itemParticipants = $('.item.participants')
const itemValidation = $('.item.validation')

/* Renders */

const youTemplate = _.template($('.you-template').html())
const registeredTemplate = _.template($('.registered-template').html())
const participantsTemplate = _.template($('.participants-template').html())
const youFormTemplate = _.template($('.you-form-template').html())
const himFormTemplate = _.template($('.him-form-template').html())

function updateYouRender () {
  $('.you-render').html(youTemplate({ you: _you }))
}

function updateRegisteredRender () {
  $('.registered-render').html(registeredTemplate({ registered: _registered }))
}

function updateParticipantsRender () {
  $('.participants-render').html(participantsTemplate({ participants: _participants, translations: _translations }))
}

function updateYouFormRender () {
  $('.you-form-render').html(youFormTemplate({ participant: _participant }))
}

function updateHimFormRender () {
  $('.him-form-render').html(himFormTemplate({ participant: _participant, registered: _registered, you: _you }))
}

/* Actions */

function afterLogin (user) {
  const participant = getParticipant()
  _you = { ...participant, ...user }
  _participants = [_you]
  updateYouRender()
  getRegistered().then(registered => {
    _registered = registered.map(obj => {
      return { ...participant, ...obj }
    })
    updateRegisteredRender()
    updateParticipantsRender()
    changeItem(itemParticipants)
  })
}

function formatParticipant (data) {
  let participant = getParticipant()
  data.map(obj => {
    participant[obj.name] = obj.value
  })
  participant.codco = parseInt(participant.codco)
  participant.datnaiss = moment(participant.datnaiss, 'DD/MM/YYYY').format()
  return participant
}

itemConnection.on('submit', '.panel.connection form', function (event) {
  event.preventDefault()
  postLogin({
    username: $('.username', this).val(),
    password: $('.password', this).val()
  }).then(user => {
    afterLogin(user)
  })
})

itemConnection.on('submit', '.panel.registration form', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  const user = formatParticipant(data)
  postRegister({
    contact: user
  }).then(user => {
    postLogin({
      username: user.username,
      password: user.password
    }).then(user => {
      afterLogin(user)
    })
  }).catch(error => {
    $('.catch-message', itemConnection).html(error)
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
      _participant = getParticipant()
      updateYouFormRender()
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

function validateParticipant (participant) {
  if (moment(participant.datnaiss).isValid()) {
    if (moment().diff(moment(participant.datnaiss), 'years') >= 16) {
      return { success: true }
    } else {
      if (participant.colt === 'enfan') {
        const people = [..._registered, _you]
        const filtered = people.filter(person => {
          return person.codco === parseInt(participant.colp)
        })
        const parent = filtered.shift()
        if (moment().diff(moment(parent.datnaiss), 'years') >= 18) {
          return { success: true }
        } else {
          return { error: 'Must be linked with a person who have more of 18 years.' }
        }
      } else {
        return { error: 'Must be considered as child.' }
      }
    }
  } else {
    return { error: 'Invalid date.' }
  }
}

function callbackSubmit (event, context, action, callback) {
  event.preventDefault()
  const data = context.serializeArray()
  const participant = formatParticipant(data)
  const validate = validateParticipant(participant)
  if (validate.success) {
    postParticipant(participant).then(res => {
      res.transport = participant.transport
      res.memo = participant.memo
      callback(res)
      updateYouRender()
      updateRegisteredRender()
      updateParticipants()
      $(`.panel.${action}`).slideUp(800, function () {
        $(this).hide()
        changeItem(itemParticipants)
      })
    })
  } else {
    $('.catch-message', itemParticipants).html(validate.error)
  }
}

itemParticipants.on('submit', '.panel.you form', function (event) {
  callbackSubmit(event, $(this), 'you', function (res) {
    _you = res
  })
})

itemParticipants.on('submit', '.panel.modify form', function (event) {
  callbackSubmit(event, $(this), 'modify', function (res) {
    _registered = _registered.map(obj => {
      if (obj.codco === res.codco) { return res }
      return obj
    })
  })
})

itemParticipants.on('submit', '.panel.add form', function (event) {
  callbackSubmit(event, $(this), 'add', function (res) {
    _registered.push(res)
  })
})

function updateParticipants () {
  let checked = []
  $('.participate-him.checked', itemParticipants).each(function () {
    checked.push(parseInt($(this).attr('data-id')))
  })
  _participants = _registered.filter(registered => {
    return checked.indexOf(registered.codco) >= 0
  })
  _participants.push(_you)
  updateParticipantsRender()
}

itemParticipants.on('click', '.participate-him', function (event) {
  event.preventDefault()
  updateParticipants()
})

itemParticipants.on('click', '.modify-you', function (event) {
  event.preventDefault()
  _participant = _you
  $('.panel', itemParticipants).hide()
  $(`.panel.you`, itemParticipants).show()
  updateYouFormRender()
  changeItem(itemParticipants)
})

itemParticipants.on('click', '.modify-him', function (event) {
  event.preventDefault()
  const selected = parseInt($(this).attr('data-id'))
  const participants = _registered.filter(registered => {
    return registered.codco === selected
  })
  _participant = participants.shift()
  $('.panel', itemParticipants).hide()
  $(`.panel.modify`, itemParticipants).show()
  updateHimFormRender()
  changeItem(itemParticipants)
})

itemParticipants.on('click', '.add-participant', function (event) {
  event.preventDefault()
  _participant = getParticipant()
  $('.panel', itemParticipants).hide()
  $(`.panel.add`, itemParticipants).show()
  updateHimFormRender()
  changeItem(itemParticipants)
})

function validateTransports () {
  let validate = true
  _participants.map(participant => {
    if (participant.transport === '') { validate = false }
  })
  return validate
}

itemParticipants.on('click', '.validate-participants', function (event) {
  event.preventDefault()
  const validate = validateTransports()
  if (!validate) {
    $('.right .catch-message').html(_translations.message.verify_transport)
  }
})
