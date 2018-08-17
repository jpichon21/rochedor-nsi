import $ from 'jquery'
import moment from 'moment'
import { getParticipant } from './sample'
import { upFlashbag, upConfirmbox } from './popup'
import { upLoader, downLoader } from './loader'
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
    downLoader()
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
  upLoader()
  postLogin({
    username: $('.username', this).val(),
    password: $('.password', this).val()
  }).then(user => {
    afterLogin(user)
  }).catch(() => {
    downLoader()
    upFlashbag(i18n.trans('security.bad_credentials'))
  })
})

itemConnection.on('submit', '.panel.reset form', function (event) {
  event.preventDefault()
  upLoader()
  resetLogin({
    email: $('.username', this).val()
  }).then(() => {
    downLoader()
    upFlashbag(i18n.trans('security.check_inbox'))
  }).catch((err) => {
    downLoader()
    upFlashbag(i18n.trans(`${err}`))
  })
})

itemConnection.on('submit', '.panel.registration form', function (event) {
  event.preventDefault()
  upLoader()
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
        }).catch(err => {
          downLoader()
          upFlashbag(i18n.trans(`${err}`))
        })
      }).catch(error => {
        downLoader()
        upFlashbag(i18n.trans(`${error}`))
      })
    } else {
      downLoader()
      upFlashbag(i18n.trans('form.message.phone_invalid'))
    }
  } else {
    downLoader()
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

itemParticipants.on('change', '.transport', function () {
  $('.navette-wrapper', itemParticipants).toggleClass('hidden', $(this).val() !== 'train')
  changeItem(itemParticipants)
})

itemParticipants.on('click', '.navette', function () {
  const boolean = $(this).toggleClass('checked').hasClass('checked')
  $('.navette-wrapper .checkbox', itemParticipants).val(boolean)
  $('.lieu-wrapper, .arriv-wrapper', itemParticipants).toggleClass('hidden', !boolean)
  changeItem(itemParticipants)
})

function validateDate (date) {
  return moment(date).isValid() && moment(date).isBefore(new Date())
}

function validatePhone (phone, mobile) {
  return !(phone === '' && mobile === '')
}

function validateChild (participant) {
  return new Promise((resolve, reject) => {
    if (moment().diff(moment(participant.datnaiss), 'years') >= 16) {
      resolve(participant)
    } else {
      if (participant.coltyp === 'enfan' || participant.coltyp === 'accom') {
        const people = [..._registered, _you]
        const filtered = people.filter(person => person.codco === parseInt(participant.colp))
        const parent = filtered.shift()
        if (moment().diff(moment(parent.datnaiss), 'years') >= 18) {
          upConfirmbox(i18n.trans('form.message.does_child_have_autpar')).then(() => {
            resolve({
              ...participant,
              aut16: 1,
              datAut16: moment().format()
            })
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
  })
}

function callbackSubmit (event, context, action, phoneControl, callback) {
  event.preventDefault()
  upLoader()
  const data = context.serializeArray()
  const participant = formatParticipant(data)
  const validatedDate = validateDate(participant.datnaiss)
  const validatedPhone = phoneControl ? validatePhone(participant.tel, participant.mobil) : true
  if (validatedDate) {
    if (validatedPhone) {
      validateChild(participant).then(participantValidated => {
        postParticipant(participantValidated).then(res => {
          const participantUpdated = { ...participantValidated, ...res }
          downLoader()
          callback(participantUpdated)
          updateYouRender()
          updateRegisteredRender()
          updateParticipants()
          $(`.panel.${action}`).slideUp(800, function () {
            $(this).hide()
            changeItem(itemParticipants)
          })
        }).catch(error => {
          if (error) {
            upFlashbag(i18n.trans(`${error}`))
          }
        })
      }).catch(error => {
        if (error) {
          downLoader()
          upFlashbag(i18n.trans(`${error}`))
        }
      })
    } else {
      downLoader()
      upFlashbag(i18n.trans('form.message.phone_invalid'))
    }
  } else {
    downLoader()
    upFlashbag(i18n.trans('form.message.date_invalid'))
  }
}

const panelYouForm = $('.panel.you form')
const panelHimForm = $('.panel.him form')
const panelAddForm = $('.panel.add form')

panelYouForm.on('submit', function (event) {
  callbackSubmit(event, $(this), 'you', true, function (res) {
    _you = res
  })
})

panelHimForm.on('submit', function (event) {
  callbackSubmit(event, $(this), 'him', false, function (res) {
    _registered = _registered.map(obj => {
      if (obj.codco === res.codco) { return res }
      return obj
    })
  })
})

panelHimForm.on('click', '.cancel', function (event) {
  event.preventDefault()
  $('.panel.him').slideUp(800, function () {
    $(this).hide()
  })
})

panelYouForm.on('click', '.cancel', function (event) {
  event.preventDefault()
  $('.panel.you').slideUp(800, function () {
    $(this).hide()
  })
})

panelAddForm.on('click', '.cancel', function (event) {
  event.preventDefault()
  $('.panel.cancel').slideUp(800, function () {
    $(this).hide()
  })
})

panelAddForm.on('submit', function (event) {
  callbackSubmit(event, $(this), 'add', false, function (res) {
    _registered.push(res)
  })
})

panelAddForm.on('change', '.select.colp', function () {
  const coltyp = $(this).closest('form').find('.select.coltyp').val()
  const colp = $(this).val()
  const people = [..._registered, _you]
  if (coltyp === 'conjo' || coltyp === 'enfan') {
    const filtered = people.filter(person => person.codco === parseInt(colp))
    const person = filtered.shift()
    let participant = getParticipant()
    participant.coltyp = coltyp
    participant.colp = colp
    participant.adresse = person.adresse
    participant.cp = person.cp
    participant.ville = person.ville
    participant.pays = person.pays
    _participant = participant
    updateHimFormRender()
  }
})

function updateParticipants () {
  _participants = _registered.filter(participant => participant.check)
  _participants.push(_you)
  updateParticipantsRender()
}

itemParticipants.on('click', '.participate-him', function (event) {
  event.preventDefault()
  const id = parseInt($(this).attr('data-id'))
  _registered = _registered.map(participant => {
    if (participant.codco === id) {
      if (!participant.check) {
        if (validateParticipant(participant) !== true) {
          upFlashbag(i18n.trans('form.message.participant_not_valid'))
          modifyClick(event, 'him', updateHimFormRender, () => {
            const selected = parseInt($(this).attr('data-id'))
            const participants = _registered.filter(registered => registered.codco === selected)
            _participant = participants.shift()
          })
          $(this).removeClass('checked')
          return participant
        }
      }
      participant.check = !participant.check
    }
    return participant
  })
  updateParticipants()
})

function modifyClick (event, action, callUpdater, callFunction) {
  event.preventDefault()
  callFunction()
  callUpdater()
  $('.panel', itemParticipants).hide()
  $(`.panel.${action}`, itemParticipants).show()
  changeItem(itemParticipants)
  setTimeout(() => {
    const content = document.querySelector('.content')
    const panel = content.querySelector(`.panel.${action}`)
    content.scroll({ top: panel.offsetTop, left: 0, behavior: 'smooth' })
  }, 200)
}

itemParticipants.on('click', '.modify-you', function (event) {
  modifyClick(event, 'you', updateYouFormRender, () => {
    _participant = _you
  })
})

itemParticipants.on('click', '.modify-him', function (event) {
  modifyClick(event, 'him', updateHimFormRender, () => {
    const selected = parseInt($(this).attr('data-id'))
    const participants = _registered.filter(registered => registered.codco === selected)
    _participant = participants.shift()
  })
})

itemParticipants.on('click', '.add-participant', function (event) {
  modifyClick(event, 'add', updateHimFormRender, () => {
    _participant = getParticipant()
  })
})

itemParticipants.on('click', '.validate-participants', function (event) {
  event.preventDefault()
  const validate = validateParticipants()
  if (validate === true) {
    upLoader()
    postRegistered(_participants, _infos.idact).then(res => {
      let result = $('.result', itemValidation).html()
      result = result.replace('%entry_number%', res)
      $('.result', itemValidation).html(result)
      downLoader()
      changeItem(itemValidation)
    }).catch(error => {
      downLoader()
      upFlashbag(error)
    })
  } else {
    upFlashbag(validate)
  }
})

function validateParticipants () {
  let message = ''
  for (let p of _participants) {
    const validate = validateParticipant(p)
    if (validate !== true) {
      message += `${p.nom} ${p.prenom} ${validate} <br>`
    }
    if (message !== '') {
      return message
    }
  }
  return true
}

function validateParticipant (participant) {
  if (participant['colp'] === '' && participant['codco'] !== _you.codco) {
    return i18n.trans('form.message.not_accompanied')
  }

  if (participant['transport'] === '') {
    return i18n.trans('form.message.no_transport')
  }

  if (isYoung(participant) && !isWithAdult(participant)) {
    return i18n.trans('form.message.not_with_an_adult')
  }
  return true
}

function isWithAdult (participant) {
  for (let p of _participants) {
    if (p.codco === participant.codp && isAdult(p)) {
      return true
    }
  }
  return false
}

function isAdult (participant) {
  const now = new moment()
  const bdate = new moment(participant.datnaiss, moment.ISO_8601)
  return (now.diff(bdate, 'years') > 18)
}

function isYoung (participant) {
  const now = new moment()
  const bdate = new moment(participant.datnaiss, moment.ISO_8601)
  return (now.diff(bdate, 'years') <= 16)
}