import $ from 'jquery'
import moment from 'moment'
import { getParticipant } from './sample'
import { upFlashbag, upConfirmbox } from './popup'
import I18n from './i18n'
import {
  postParticipant,
  getLogin,
  getLogout,
  postLogin,
  resetLogin,
  getRegistered,
  postRegistered,
  postRegister } from './calendar-api.js'

/* Infos */

const _infos = JSON.parse($('.infos-json').html())

/* Translations */

let i18n = new I18n()

const _locale = $('.locale-json').html()

moment.locale(_locale)

/* Countries */

const _countries = JSON.parse($('.countries-json').html())

/* Variables */

let _you = {}
let _registered = []
let _participant = {}
let _participants = []

const itemConnection = $('.item.connection')
const itemParticipants = $('.item.participants')
const itemValidation = $('.item.validation')

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
    changeItem(itemConnection)
  }, 500)
})

/* Button Radio */

$('.registered-render').on('click', '.button.radio', function (event) {
  event.preventDefault()
  $(this).toggleClass('checked')
})

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
  $('.registered-render').html(registeredTemplate({
    registered: _registered
  }))
}

function updateParticipantsRender () {
  $('.participants-render').html(participantsTemplate({
    participants: _participants,
    transports: {
      'perso': i18n.trans('form.transport.perso'),
      'train': i18n.trans('form.transport.train'),
      'avion': i18n.trans('form.transport.avion'),
      'bus': i18n.trans('form.transport.bus')
    }
  }))
}

function updateYouFormRender () {
  $('.you-form-render').html(youFormTemplate({
    participant: _participant,
    countries: _countries,
    civilites: [
      i18n.trans('form.civilite.mr'),
      i18n.trans('form.civilite.mme'),
      i18n.trans('form.civilite.mlle'),
      i18n.trans('form.civilite.frere'),
      i18n.trans('form.civilite.pere'),
      i18n.trans('form.civilite.soeur')
    ]
  }))
}

function updateHimFormRender () {
  $('.him-form-render').html(himFormTemplate({
    participant: _participant,
    countries: _countries,
    registered: _registered,
    you: _you,
    civilites: [
      i18n.trans('form.civilite.mr'),
      i18n.trans('form.civilite.mme'),
      i18n.trans('form.civilite.mlle'),
      i18n.trans('form.civilite.frere'),
      i18n.trans('form.civilite.pere'),
      i18n.trans('form.civilite.soeur')
    ]
  }))
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
  }).catch(() => {
    upFlashbag(i18n.trans('security.bad_credentials'))
  })
})

itemConnection.on('submit', '.panel.reset form', function (event) {
  event.preventDefault()
  resetLogin({
    email: $('.username', this).val()
  }).then(() => {
    upFlashbag(i18n.trans('security.check_inbox'))
  })
})

itemConnection.on('submit', '.panel.registration form', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  const participant = formatParticipant(data)
  const validatedDate = validateDate(participant.datnaiss)
  const validatedPhone = validatePhone(participant.tel, participant.mobil)
  if (validatedDate) {
    if (validatedPhone) {
      postRegister({
        contact: participant
      }).then(user => {
        postLogin({
          username: user.username,
          password: participant.password
        }).then(user => {
          afterLogin({
            ...user,
            transport: participant.transport
          })
        }).catch(() => {
          upFlashbag(i18n.trans('security.user_exist'))
        })
      }).catch(error => {
        upFlashbag(error)
      })
    } else {
      upFlashbag(i18n.trans('form.message.phone_invalid'))
    }
  } else {
    upFlashbag(i18n.trans('form.message.date_invalid'))
  }
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
    case 'reset':
      $('.panel.reset', itemConnection).show()
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

function validateDate (date) {
  return moment(date).isValid()
}

function validatePhone (phone, mobile) {
  return !(phone === '' && mobile === '')
}

function validateParticipant (participant) {
  return new Promise((resolve, reject) => {
    if (validatePhone(participant.tel, participant.mobil)) {
      if (validateDate(participant.datnaiss)) {
        if (moment().diff(moment(participant.datnaiss), 'years') >= 16) {
          resolve()
        } else {
          if (participant.coltyp === 'enfan' || participant.coltyp === 'accom') {
            const people = [..._registered, _you]
            const filtered = people.filter(person => {
              return person.codco === parseInt(participant.colp)
            })
            const parent = filtered.shift()
            if (moment().diff(moment(parent.datnaiss), 'years') >= 18) {
              upConfirmbox(i18n.trans('form.message.does_child_have_autpar')).then(() => {
                resolve()
              }).catch(() => {
                reject(i18n.trans('form.message.child_must_have_autpar'))
              })
            } else {
              reject(i18n.trans('form.message.parent_must_be_adult'))
            }
          } else {
            reject(i18n.trans('form.message.child_must_come_with_adult'))
          }
        }
      } else {
        reject(i18n.trans('form.message.date_invalid'))
      }
    } else {
      reject(i18n.trans('form.message.phone_invalid'))
    }
  })
}

function callbackSubmit (event, context, action, callback) {
  event.preventDefault()
  const data = context.serializeArray()
  const participant = formatParticipant(data)
  validateParticipant(participant).then(() => {
    postParticipant(participant).then(res => {
      const participantUpdated = { ...participant, ...res }
      callback(participantUpdated)
      updateYouRender()
      updateRegisteredRender()
      updateParticipants()
      $(`.panel.${action}`).slideUp(800, function () {
        $(this).hide()
        changeItem(itemParticipants)
      })
    })
  }).catch(error => {
    if (error) {
      upFlashbag(error)
    }
  })
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
  _participants = _registered.filter(participant => {
    return participant.check
  })
  _participants.push(_you)
  updateParticipantsRender()
}

itemParticipants.on('click', '.participate-him', function (event) {
  event.preventDefault()
  const id = parseInt($(this).attr('data-id'))
  _registered = _registered.map(participant => {
    if (participant.codco === id) {
      participant.check = !participant.check
    }
    return participant
  })
  updateParticipants()
})

itemParticipants.on('click', '.modify-you', function (event) {
  event.preventDefault()
  _participant = _you
  $('.panel', itemParticipants).hide()
  $(`.panel.you`, itemParticipants).show()
  updateYouFormRender()
  changeItem(itemParticipants)
  setTimeout(() => {
    const content = document.querySelector('.content')
    const panel = content.querySelector('.panel.you')
    content.scroll({ top: panel.offsetTop, left: 0, behavior: 'smooth' })
  }, 200)
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
  setTimeout(() => {
    const content = document.querySelector('.content')
    const panel = content.querySelector('.panel.modify')
    content.scroll({ top: panel.offsetTop, left: 0, behavior: 'smooth' })
  }, 200)
})

itemParticipants.on('click', '.add-participant', function (event) {
  event.preventDefault()
  _participant = getParticipant()
  $('.panel', itemParticipants).hide()
  $(`.panel.add`, itemParticipants).show()
  updateHimFormRender()
  changeItem(itemParticipants)
  setTimeout(() => {
    const content = document.querySelector('.content')
    const panel = content.querySelector('.panel.add')
    content.scroll({ top: panel.offsetTop, left: 0, behavior: 'smooth' })
  }, 200)
})

function validateTransports () {
  const whoAreWeWaiting = _participants.filter(participant => participant.transport === '')
  if (whoAreWeWaiting.length > 0) {
    return {
      whoAreWeWaitingRender: () => {
        let html = ''
        whoAreWeWaiting.map(who => {
          html += '<li>' + who.prenom + ' ' + who.nom + '</li>'
        })
        return html
      }
    }
  }
  return { success: true }
}

itemParticipants.on('click', '.validate-participants', function (event) {
  event.preventDefault()
  const validate = validateTransports()
  if (validate.success) {
    postRegistered(_participants, _infos.idact).then(res => {
      let result = $('.result', itemValidation).html()
      result = result.replace('%entry_number%', res)
      $('.result', itemValidation).html(result)
      changeItem(itemValidation)
    }).catch(error => {
      upFlashbag(error)
    })
  } else {
    upFlashbag(
      i18n.trans('form.message.verify_transport') +
      '<ul>' + validate.whoAreWeWaitingRender() + '</ul>'
    )
  }
})
