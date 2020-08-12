import $ from 'jquery'
import moment from 'moment'
import { getContact } from './sample'
import { upFlashbag, upFlashbagOk, upConfirmbox } from './popup'
import { upLoader, downLoader } from './loader'
import Inputmask from 'inputmask'
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
const _preferredCountries = JSON.parse($('.preferred-countries-json').html())

/* Variables */

let _you = {}
let _registered = []
let _went = []
let _participant = {}
let _participants = []
let _existingRef = ''
let _registrationBegan = false
let _hasOneParticipant = false

const itemConnection = $('.item.connection')
const itemParticipants = $('.item.participants')
const itemValidation = $('.item.validation')

window.addEventListener('beforeunload', (event) => {
  if (_registrationBegan) {
    event.returnValue = i18n.trans('registration.not_finished')
  }
})

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

function scrollTop () {
  setTimeout(() => {
    document.querySelector('.content').scroll({ top: 0, left: 0, behavior: 'smooth' })
  }, 200)
}

function scrollToElement ($element) {
  setTimeout(() => {
    // a bit bruteforced, but it works ...
    // for mobile
    if ($('body').hasClass('menuReduced')) {
      window.scrollTo({ top: $element.offset().top, left: 0, behavior: 'smooth' })
    }
    // for screen
    // watch out for the forced offset of -120 if you reuse this method
    document.querySelector('.content').scroll({ top: ($element.offset().top - 120), left: 0, behavior: 'smooth' })
  }, 200)
}

/* Button Radio */

$('.registered-render').on('click', '.button.radio', function (event) {
  event.preventDefault()
  $(this).toggleClass('checked')
})

/* Renders */

const youTemplate = _.template($('.you-template').html())
const registeredTemplate = _.template($('.registered-template').html())
const wentTemplate = _.template($('.went-template').html())
const participantsTemplate = _.template($('.participants-template').html())
const youFormTemplate = _.template($('.you-form-template').html())
const himFormTemplate = _.template($('.him-form-template').html())
const endMessageTemplate = _.template($('.end-message-template').html())

function updateYouRender () {
  $('.you-render').html(youTemplate({ you: _you }))
}

function updateRegisteredRender () {
  $('.registered-render').html(registeredTemplate({
    registered: _registered.filter(p => p.check)
  }))
  $('.went-render').html(wentTemplate({
    registered: _went
  }))
}

function updateParticipantsRender () {
  $('.participants-render').html(participantsTemplate({
    participants: _participants,
    transports: {
      'perso': i18n.trans('form.transport.perso'),
      'train': i18n.trans('form.transport.train'),
      'avion': i18n.trans('form.transport.avion')
    }
  }))
}

function updateEndMessageRender () {
  $('.end-message-render').html(endMessageTemplate({
    participants: _participants,
    lieux: {
      'viotte': i18n.trans('viotte'),
      'besancon-tgv': i18n.trans('besancon-tgv'),
      'ne-sait-pas': i18n.trans('ne-sait-pas')
    },
    transports: {
      'perso': i18n.trans('form.transport.perso'),
      'train': i18n.trans('form.transport.train'),
      'avion': i18n.trans('form.transport.avion')
    }
  }))
}

function updateYouFormRender (errors = [], updatedParticipant = {}, register = false) {
  $('.you-form-render').html(youFormTemplate({
    register: register,
    errors: errors,
    participant: {..._participant, ...updatedParticipant},
    countries: _countries,
    preferredCountries: _preferredCountries,
    civilites: [
      i18n.trans('form.civilite.mr'),
      i18n.trans('form.civilite.mme'),
      i18n.trans('form.civilite.abbe'),
      i18n.trans('form.civilite.frere'),
      i18n.trans('form.civilite.pere'),
      i18n.trans('form.civilite.soeur')
    ]
  }))
  Inputmask().mask(document.querySelectorAll('.datnaiss, .input.arriv'))
}

function updateHimFormRender (errors = [], updatedParticipant = {}) {
  $('.him-form-render').html(himFormTemplate({
    errors: errors,
    participant: {..._participant,...updatedParticipant},
    countries: _countries,
    preferredCountries: _preferredCountries,
    registered: _registered.filter(p => p.check),
    you: _you,
    civilites: [
      i18n.trans('form.civilite.mr'),
      i18n.trans('form.civilite.mme'),
      i18n.trans('form.civilite.abbe'),
      i18n.trans('form.civilite.frere'),
      i18n.trans('form.civilite.pere'),
      i18n.trans('form.civilite.soeur')
    ]
  }))
  Inputmask().mask(document.querySelectorAll('.datnaiss, .input.arriv'))
}

/* Actions */

function afterLogin (user) {
  const participant = getContact()
  _you = { ...participant, ...user }
  _participants = [_you]
  updateYouRender()
  $('.you-render').hide()
  getRegistered(_infos.idact).then(data => {
    _registrationBegan = true
    let attendees = data.attendees
    let registered = []
    _existingRef = data.alreadyRegisteredRef
    let alreadyRegisteredYou = data.alreadyRegisteredYou
    let alreadyRegistered = data.alreadyRegistered
    let alreadyRegisteredIds = []

    // Formattage des participants accompagnants (modif inscription)
    alreadyRegistered.forEach(function(element) {
      let participant = getContact()
      let transport = JSON.parse(element.jsco)
      participant.transport = transport.Arriv.Transport
      participant.lieu = transport.Arriv.Lieu
      participant.arriv = ''
      if (transport.Arriv.Heure !== '' || transport.Arriv.Mn !== '') {
        participant.arriv = transport.Arriv.Heure + ':' + transport.Arriv.Mn
      }
      participant.memo = transport.Arriv.Memo
      participant[element.name] = element.value
      participant.codco = parseInt(participant.codco)
      participant.datnaiss = moment(participant.datnaiss, 'DD/MM/YYYY').format()
      element.check = true
      registered.push({ ...participant, ...element })
      _participants.push({ ...participant, ...element })
      alreadyRegisteredIds.push(element.codco)
    })

    // Formattage des membres de la famille
    attendees.forEach(function(element) {
      if (element.coltyp === 'conjo' || element.coltyp === 'enfan' || element.coltyp === 'paren') {
        // Si l'utilisateur est déjà présent dans la liste des inscrits,
        if (alreadyRegisteredIds.includes(element.codco)) {
          element.added = true
        }
        registered.push(element)
      }
    })

    // Formattage de l'utilisateur courant (modif inscription)
    if (alreadyRegisteredYou) {
      $('.you-render').show()
      let transport = JSON.parse(alreadyRegisteredYou.jsco)
      _you.transport = transport.Arriv.Transport
      _you.lieu = transport.Arriv.Lieu
      _you.arriv = ''
      if (transport.Arriv.Heure !== '' || transport.Arriv.Mn !== '') {
        _you.arriv = transport.Arriv.Heure + ':' + transport.Arriv.Mn
      }
      _you.memo = transport.Arriv.Memo
    }

    _registered = registered.map(obj => {
      return { ...participant, ...obj }
    })
    _went = [...attendees]
    downLoader()
    updateRegisteredRender()
    updateParticipantsRender()
    changeItem(itemParticipants)
    scrollTop()
    if (_you.transport === '') {
      upFlashbag(i18n.trans('form.message.update_you'))
      $('.modify-you', itemParticipants).click()
    } else {
      itemParticipants.find('.validate-participants').removeClass('disabled')
    }
  })
}

function formatParticipant (data) {
  let participant = getContact()
  data.map(obj => {
    participant[obj.name] = obj.value
  })
  participant.codco = parseInt(participant.codco)
  participant.datnaiss = moment(participant.datnaiss, 'DD/MM/YYYY').format()

  return participant
}

$(document).on('change', '.input.prenom, .input.nom, .input.ville', function() {
  $(this).val(NomPropre($(this).val()))
})

// Fonction fournie par Hubert de LRDO pour formattage des noms/prénom
function NomPropre(SMot, Opt) {
  var Lig, C, C1, C2, C3, C4, C5, Mot, Mx, M1, M2
  if (!SMot) return ""
  Mx = SMot.length

  Mot = SMot.toLowerCase()
  Lig = Mot.substr(0,1).toUpperCase()
  for (C=1; C<Mx; C++) {
    C1 = Mot.substr(C-1,1)
    C2 = Mot.substr(C,1)
    if (C+1<Mx)  C3 = Mot.substr(C+1,1);  else  C3=" "
    if (C+2<Mx)  C4 = Mot.substr(C+2,1);  else  C4=" "
    if (C+3<Mx)  C5 = Mot.substr(C+3,1);  else  C5=" "
    M1 = C2 + C3 + C4;  M2 = M1 + C5
    if ("de du d' le la l' et ".indexOf(M1)>=0 || "rue des les ".indexOf(M2)>=0) {
      Lig=Lig + C2
    }else{
      if (" ,;./-'#".indexOf(C1)>=0)  Lig += C2.toUpperCase();  else  Lig += C2
    }
  }

  return Lig
}

// Fonction fournie par Hubert de LRDO pour formattage des noms/prénom
function CasseUniq(Mot) {
  var C, C1, nbMin=0, nbMaj=0
  var Mx = Mot.length
  for (C=0; C<Mx; C++) {
    C1 = Mot.substr(C,1)
    if (C1>="A" && C1<="Z")  nbMaj++
    if (C1>="a" && C1<="z")  nbMin++
  }
  return !(nbMin>0 && nbMaj>0)
}

itemConnection.on('submit', '.panel.connection form', function (event) {
  event.preventDefault()
  upLoader()
  postLogin({
    username: $('.username', this).val(),
    password: $('.password', this).val()
  }).then(user => {
    afterLogin(user)
    $('.intro').hide()
  }).catch(() => {
    downLoader()
    upFlashbag(i18n.trans('security.bad_credentials'))
  })
})

itemConnection.on('submit', '.panel.reset form', function (event) {
  event.preventDefault()
  upLoader()
  resetLogin({
    email: $('.username', this).val(),
    lastname: $('.lastname', this).val(),
    firstname: $('.firstname', this).val(),
    origin: 'inscription',
    idact: _infos.idact
  }).then(() => {
    downLoader()
    upFlashbagOk(i18n.trans('security.check_inbox'))
  }).catch((err) => {
    downLoader()
    upFlashbag(i18n.trans(`${err}`))
  })
})

itemConnection.on('click', '.panel.reset .cancel', function (event) {
  event.preventDefault()
  $('.panel.reset').slideUp(800, function () {
    $(this).hide()
    changeItem(itemConnection)
  })
})

itemConnection.on('click', '.panel.registration .cancel', function (event) {
  event.preventDefault()
  $('.panel.registration').slideUp(800, function () {
    $(this).hide()
    changeItem(itemConnection)
  })
})

itemConnection.on('change', '.transport', function () {
  $('.lieu-wrapper, .arriv-wrapper', itemConnection).toggleClass('hidden', $(this).val() !== 'train')
  changeItem(itemConnection)
})

itemConnection.on('submit', '.panel.registration form', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  const participant = formatParticipant(data)
  var errors = []
  errors = validateRegister(data)
  var error = null
  error = validatePassword(participant.password, true)
  if (error) {
    errors['password'] = error
    error = null
  }
  error = validateDate(participant.datnaiss)
  if (error) {
    errors['datnaiss'] = error
    error = null
  }
  error = validatePhone(participant.tel, participant.mobil)
  if (error) {
    errors['tel'] = error
  }

  updateYouFormRender(errors, {...participant}, true)
  if (Object.keys(errors).length === 0) {
    validateChild(participant, true).then(participant => {
      upLoader()
      postRegister({
        contact: participant
      }).then(user => {
        postLogin({
          username: user.username,
          password: participant.password
        }).then(user => {
          afterLogin({
            ...user,
            transport: participant.transport,
            arriv: participant.arriv,
            lieu: participant.lieu
          })
          $('.you-render').show()
          $('.intro').hide()
        }).catch(err => {
          downLoader()
          upFlashbag(i18n.trans(`${err}`))
        })
      }).catch(error => {
        downLoader()
        upFlashbag(i18n.trans(`${error}`))
      })
    }).catch(error => {
      if (error) {
        downLoader()
        upFlashbag(i18n.trans(`${error}`))
      }
    })
  }
})

itemConnection.on('click', 'a:not(.tooltip-password)', function (event) {
  event.preventDefault()
  const which = $(this).attr('href').substring(1)
  switch (which) {
    case 'connection':
    case 'registration':
      $('.panel', itemConnection).hide()
      $(`.panel.${which}`, itemConnection).show()
      _participant = getContact()
      updateYouFormRender([], [], true)
      setTimeout(() => {
        scrollToElement($(`.panel.${which}`))
      }, 200)
      break
    case 'reset':
      $('.panel.reset', itemConnection).show()
      setTimeout(() => {
        scrollToElement($('.panel.reset'))
      }, 200)
      break
    case 'continue':
      getLogin().then(user => afterLogin(user))
      $('.intro').hide()
      break
    case 'disconnect':
      getLogout(_locale)
      break
  }
  changeItem(itemConnection)
})

itemParticipants.on('change', '.transport', function () {
  $('.lieu-wrapper, .arriv-wrapper', itemParticipants).toggleClass('hidden', $(this).val() !== 'train')
  changeItem(itemParticipants)
})

itemParticipants.on('click', '.newfich', function (event) {
  event.preventDefault()
  const boolean = $(this).toggleClass('checked').hasClass('checked')
  $('.newfich-wrapper .checkbox', itemParticipants).val(boolean)
})

function validateDate (date) {
  if (moment(date).isValid() && moment(date).isBefore(new Date())) {
    return null
  }
  return i18n.trans('form.message.date_invalid')
}

function validatePhone (phone, mobile) {
  if (phone === '' && mobile === '') {
    return i18n.trans('form.message.phone_invalid')
  }
  return null
}

function validateChild (participant, isYou=false) {
  return new Promise((resolve, reject) => {
    // Pas de contrôle d'âge sur les conjoints (!)
    if (participant.coltyp === 'conjo' || moment(_infos.datdeb.date).diff(moment(participant.datnaiss), 'years') >= 16) {
      resolve(participant)
    } else {
      if (isYou) {
        upConfirmbox(i18n.trans('form.message.do_you_have_agreement')).then(() => {
          resolve({
            ...participant,
            aut16: 1,
            datAut16: moment().format()
          })
        }).catch(() => {
          window.history.back()
        })
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
            })
          } else {
            reject(i18n.trans('form.message.parent_must_be_adult'))
          }
        } else {
          reject(i18n.trans('form.message.child_must_come_with_adult'))
        }
      }
    }
  })
}

function validatePassword (password, notEmpty = false) {
  if (!password && !notEmpty) {
    return null
  }
  if (password.length < 8 && password.length !== 0) {
    return i18n.trans('security.password_too_small')
  }
  if (password === '' || (password === null && notEmpty)) {
    return i18n.trans('form.message.required')
  }
  return null
}
const REQUIRED_FIELDS_COMMON = ['civil', 'prenom', 'nom', 'transport']
const REQUIRED_FIELDS_HIM = ['coltyp', 'colp']
const REQUIRED_FIELDS_YOU = ['adresse', 'cp', 'ville', 'pays', 'email']
const REQUIRED_FIELDS_REGISTER = ['username']
function validateHim (data) {
  return validateRequired(data, [...REQUIRED_FIELDS_COMMON, ...REQUIRED_FIELDS_HIM])
}
function validateYou (data) {
  return validateRequired(data, [...REQUIRED_FIELDS_COMMON, ...REQUIRED_FIELDS_YOU])
}
function validateRegister (data) {
  return validateRequired(data, [...REQUIRED_FIELDS_COMMON, ...REQUIRED_FIELDS_YOU, ...REQUIRED_FIELDS_REGISTER])
}

function validateRequired (data, requiredFields) {
  var errors = []
  data.forEach(function (item) {
    if (requiredFields.includes(item.name) && item.value === '') {
      errors[item.name] = i18n.trans('form.message.required')
    }
  })
  requiredFields.forEach(function (item) {
    if (!data.find(el => el.name === item)) {
      errors[item] = i18n.trans('form.message.required')
    }
  })
  return errors
}

function callbackSubmit (event, context, action, phoneControl, callback) {
  event.preventDefault()
  const data = context.serializeArray()
  const participant = formatParticipant(data)
  var errors = []
  errors = action === 'you' ? validateYou(data) : validateHim(data)
  var error = null
  error = validatePassword(participant.password)
  if (error) {
    errors['password'] = error
    error = null
  }
  error = validateDate(participant.datnaiss)
  if (error) {
    errors['datnaiss'] = error
    error = null
  }
  error = phoneControl ? validatePhone(participant.tel, participant.mobil) : false
  if (error) {
    errors['tel'] = error
  }
  if (action === 'you') {
    updateYouFormRender(errors, {...participant})
  } else {
    updateHimFormRender(errors, {...participant})
  }
  if (Object.keys(errors).length === 0) {
    if (participant.password === '') {
      participant.password = null
    }
    validateChild(participant, action === 'you').then(participantValidated => {
      if (participantValidated.transport !== 'train') {
        participantValidated.lieu = ''
        participantValidated.arriv = ''
      }
      upLoader()
      getLogin().then((res) => {
        postParticipant(participantValidated).then(res => {
          const participantUpdated = { ...participantValidated, ...res }
          downLoader()
          callback(participantUpdated)
          updateYouRender()
          updateRegisteredRender()
          updateParticipants()
          $('.you-render').show()
          _hasOneParticipant = true
          addParticipant(event)
        }).catch(error => {
          if (error) {
            downLoader()
            upFlashbag(i18n.trans(`${error}`))
          }
        })
      }).catch(error => {
        downLoader()
        upFlashbag(i18n.trans('session_expired'))
      })
    }).catch(error => {
      if (error) {
        downLoader()
        upFlashbag(i18n.trans(`${error}`))
      }
    })
  }
}

const panelYouForm = $('.panel.you form')
const panelHimForm = $('.panel.him form')
const panelAddForm = $('.panel.add form')

panelYouForm.on('submit', function (event) {
  callbackSubmit(event, $(this), 'you', true, function (res) {
    _you = res
  })
  $('.you-render').show()
})

panelHimForm.on('submit', function (event) {
  callbackSubmit(event, $(this), 'him', false, function (res) {
    res.checked = true
    _registered = _registered.map(obj => {
      if (obj.codco === res.codco) { return res }
      return obj
    })
    _went = _went.map(p => {
      if (p.codco === res.codco) {
        p.added = true
      }
      return p
    })
  })
})

panelAddForm.on('submit', function (event) {
  callbackSubmit(event, $(this), 'add', false, function (res) {
    _registered.push(res)
  })
})

function closePanel (event, panel) {
  event.preventDefault()
  $(`.panel.${panel}`).slideUp(800, function () {
    $(this).hide()
  })
}

panelYouForm.on('click', '.cancel', function (event) {
  // Si l'utilisateur est le seul inscrit, on redirige sur la liste des retraites
  if (_participants.length === 1 && _you.codco === _participants[0].codco) {
    window.location.replace($(this).data('redirect-url'))
  }

  closePanel(event, 'you')
  $('.you-render').show()
})
panelHimForm.on('click', '.cancel', function (event) { closePanel(event, 'him') })
panelAddForm.on('click', '.cancel', function (event) { closePanel(event, 'add') })

panelAddForm.on('change', '.select.colp', function () {
  const coltyp = $(this).closest('form').find('.select.coltyp').val()
  const colp = $(this).val()
  const people = [..._registered, _you]
  if (coltyp === 'conjo' || coltyp === 'enfan') {
    const filtered = people.filter(person => person.codco === parseInt(colp))
    const person = filtered.shift()
    let participant = getContact()
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
        upFlashbag(i18n.trans('form.message.update_participant'))
          modifyClick(event, 'him', updateHimFormRender, () => {
            const selected = parseInt($(this).attr('data-id'))
            const participants = _registered.filter(registered => registered.codco === selected)
            _participant = participants.shift()
          })
          $(this).removeClass('checked')
          return participant
      }
      participant.check = true
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

$(document).on('click', '.validateFormParticipant', function(event) {
  event.preventDefault()
  itemParticipants.find('.add-participant').trigger('click')
})

itemParticipants.on('click', '.add-participant', function (event) {
  if (panelYouForm.is(':visible')) {
    panelYouForm.trigger('submit')
  } else if (panelHimForm.is(':visible')) {
    panelHimForm.trigger('submit')
  } else if (panelAddForm.is(':visible')) {
    panelAddForm.trigger('submit')
  } else {
    modifyClick(event, 'add', updateHimFormRender, () => {
      _participant = getContact()
    })
    scrollToElement($('.him-form-render'))
  }
})

function addParticipant (event) {
  updateParticipantsRender()
  $('.panel', itemParticipants).show()
  $(`.panel.you`, itemParticipants).hide()
  $(`.panel.add`, itemParticipants).hide()
  $(`.panel.him`, itemParticipants).hide()
  // Une fois un participant ajouté, on peut valider la demande
  if (itemParticipants.find('.validate-participants').hasClass('disabled') && _hasOneParticipant === true) {
    itemParticipants.find('.validate-participants').removeClass('disabled')
  }
}
itemParticipants.on('click', '.validate-participants', function (event) {
  event.preventDefault()
  if ($(this).hasClass('disabled')) {
    upFlashbag(i18n.trans('calendar.registration.validation-tooltip'))
    return
  }

  const validate = validateParticipants()
  if (validate === true) {
    upLoader()
    setParent()
    getLogin().then((res) => {
      postRegistered(_participants, _infos.idact, _existingRef).then(res => {
        _registrationBegan = false
        let result = $('.result', itemValidation).html()
        result = result.replace('%entry_number%', res)
        $('.result', itemValidation).html(result)
        downLoader()
        updateEndMessageRender()
        setTimeout(function() {
          changeItem(itemValidation)
          scrollToElement(itemValidation)
        }, 300)
        _existingRef = ''
      }).catch(error => {
        downLoader()
        upFlashbag(error)
      })
    }).catch(error => {
      downLoader()
      upFlashbag(i18n.trans('session_expired'))
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

function setParent () {
  for (let p of _participants) {
    if (p.coltyp === 'conjo' || p.coltyp === 'enfan' || p.coltyp === 'accom') {
      const parent = _participants.find(element => {
        return element.codco === parseInt(p.colp)
      })
      parent.coltyp = 'paren'
      parent.colp = parent.codco
      break
    }
  }
}

function validateParticipant (participant) {
  if (participant['colp'] === '' && participant['codco'] !== _you.codco) {
    return i18n.trans('form.message.not_accompanied')
  }

  if (participant['transport'] === '') {
    return i18n.trans('form.message.no_transport')
  }

  if (isYoung(participant) && !isWithAdult(participant)) {
    let urlContact = '/' + _locale + '/contact-ro'
    if (_infos.sitact === 'Les Fontanilles') {
      urlContact = '/' + _locale + '/contact-ft'
    }
    let message = i18n.trans('form.message.not_with_an_adult')
    message = message.replace('%url_contact%', urlContact)
    message = message.replace('%lieu%', _infos.sitact)
    return message
  }
  return true
}

function isWithAdult (participant) {
  for (let p of _participants) {
    if (parseInt(p.codco) === parseInt(participant.colp) && isAdult(p)) {
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
