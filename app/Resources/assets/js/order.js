import $ from 'jquery'
import moment from 'moment'
import { getParticipant, getDelivery } from './sample'
import { placePayment } from './cart'
import { upFlashbag } from './popup'
import I18n from './i18n'
import {
  getLogin,
  getLogout,
  getData,
  postRegister,
  resetLogin,
  postLogin,
  postOrder,
  checkZipcode
} from './order-api.js'

/* Cart */

const _cartId = parseInt($('.cart-data').html())

/* Translations */

let i18n = new I18n()

const _locale = $('.locale-json').html()

moment.locale(_locale)

/* Countries */

const _countries = JSON.parse($('.countries-json').html())

/* Variables */

let _you = {}
let _delivery = {}
let _cartInfo = {}

const itemConnection = $('.item.connection')
const itemCard = $('.item.card')
const itemShipping = $('.item.shipping')
const itemValidation = $('.item.validation')
const itemPayment = $('.item.payment')

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

$('.item-clients').on('click', '.button.radio', function (event) {
  event.preventDefault()
  $(this).toggleClass('checked')
})

/* Renders */

const youTemplate = _.template($('.you-template').html())
const cartTemplate = _.template($('.cart-template').html())
const deliveryTemplate = _.template($('.delivery-template').html())
const youFormTemplate = _.template($('.you-form-template').html())

function updateYouRender () {
  $('.you-render').html(youTemplate({
    you: _you
  }))
}

function updateCartRender () {
  $('.cart-render').html(cartTemplate({
    cartInfo: _cartInfo
  }))
}

function updateDeliveryRender () {
  $('.delivery-render').html(deliveryTemplate({
    delivery: _delivery,
    you: _you
  }))
}

function updateYouFormRender () {
  $('.you-form-render').html(youFormTemplate({
    client: _you,
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

updateCartRender()
updateDeliveryRender()

/* Actions */

function afterLogin (user, bypass) {
  _delivery = getDelivery()
  _delivery.codcli = user.codcli
  _you = user
  updateYouRender()
  updateCartRender()
  if (bypass) {
    changeItem(itemShipping)
  } else {
    changeItem(itemCard)
  }
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
    afterLogin(user, false)
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
  validateClient(event, $(this), participant => {
    postRegister({
      client: {
        ...participant,
        rue: participant.adresse
      }
    }).then(user => {
      postLogin({
        username: user.email,
        password: participant.password
      }).then(user => {
        afterLogin(user, true)
      }).catch(() => {
        upFlashbag(i18n.trans('security.user_exist'))
      })
    }).catch(error => {
      upFlashbag(error)
    })
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
      _you = getParticipant()
      updateYouFormRender()
      break
    case 'reset':
      $('.panel.reset', itemConnection).show()
      break
    case 'continue':
      getLogin().then(user => afterLogin(user, false))
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

function validatePro (societe, tvaintra) {
  return (societe === '' && tvaintra === '') || (societe !== '' && tvaintra !== '')
}

/*

JUSQU'ICI TOUT FONCTIONNE :)

*/

itemCard.on('click', '.continue', function (event) {
  event.preventDefault()
  changeItem(itemShipping)
})

itemCard.on('click', '.modify-you', function (event) {
  event.preventDefault()
  $('.panel', itemCard).hide()
  $(`.panel.modify`, itemCard).show()
  updateYouFormRender()
  changeItem(itemCard)
})

function validateClient (event, context, callback) {
  event.preventDefault()
  const data = context.serializeArray()
  const participant = formatParticipant(data)
  const validatedDate = validateDate(participant.datnaiss)
  const validatedPhone = validatePhone(participant.tel, participant.mobil)
  const validatedPro = validatePro(participant.societe, participant.tvaintra)
  if (validatedDate) {
    if (validatedPro) {
      if (validatedPhone) {
        _you = participant
        callback(participant)
        updateYouRender()
      } else {
        upFlashbag(i18n.trans('form.message.phone_invalid'))
      }
    } else {
      upFlashbag(i18n.trans('form.message.pro_invalid'))
    }
  } else {
    upFlashbag(i18n.trans('form.message.date_invalid'))
  }
}

itemCard.on('submit', '.panel.modify form', function (event) {
  validateClient(event, $(this), user => {
    $(`.panel.modify`).slideUp(800, function () {
      $(this).hide()
      changeItem(itemCard)
    })
  })
})

itemShipping.on('change', '.select.adliv', function (event) {
  event.preventDefault()
  const data = $(this).val()
  let selectedVal = data
  _delivery.destliv = selectedVal
  if (selectedVal === 'Roche' || selectedVal === 'Font' || selectedVal === 'myAdd') {
    _delivery.adliv.adresse = _you.adresse
    _delivery.adliv.zipcode = _you.cp
    _delivery.adliv.city = _you.ville
    $(`.panel.adliv`, itemShipping).removeClass('active')
  } else {
    $(`.panel.adliv`, itemShipping).addClass('active')
  }
  if (selectedVal === 'myAdd' || selectedVal === 'Other') {
    $(`.panel.countryliv`, itemShipping).addClass('active')
  } else {
    $(`.panel.countryliv`, itemShipping).removeClass('active')
  }
  if (selectedVal === 'Roche' || selectedVal === 'Font') {
    _delivery.paysliv = 'FR'
  }
  changeItem(itemShipping)
})

itemPayment.on('click', '.button.submit.process-order', function (event) {
  _delivery.cartId = _cartId
  event.preventDefault()
  postOrder(_delivery).then(res => {
    let result = $('.result', itemValidation).html()
    result = result.replace('%entry_number%', res)
    $('.result', itemValidation).html(result)
    placePayment(_delivery.modpaie,
      _cartInfo.consumerPriceIT,
      result.refcom,
      'truc',
      `Commande sur le site La Roche D'Or`,
      _you.email,
      _delivery.paysliv.toLowerCase()
    )
    changeItem(itemValidation)
  }).catch(error => {
    $('.right .catch-message').html(error)
  })
})

itemShipping.on('change', '.select.country', function (event) {
  event.preventDefault()
  const data = $(this).val()
  _delivery.paysliv = data
})

itemPayment.on('change', '.select.modpaie', function (event) {
  event.preventDefault()
  const data = $(this).val()
  _delivery.modpaie = data
})

itemShipping.on('submit', '.panel.adliv form', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  let dataVal = data
  _delivery.adliv.adresse = dataVal[0].value
  _delivery.adliv.zipcode = dataVal[1].value
  _delivery.adliv.city = dataVal[2].value
})

itemShipping.on('submit', '.panel.adliv form', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  let dataVal = data
  _delivery.adliv.adresse = dataVal[0].value
  _delivery.adliv.zipcode = dataVal[1].value
  _delivery.adliv.city = dataVal[2].value
})

itemShipping.on('click', '.button.payment', function (event) {
  event.preventDefault()
  if (_delivery.destliv === 'myAdd') {
    var country = _delivery.paysliv
    var zipcode = _you.cp
    var destliv = _delivery.destliv
  } else {
    var country = _delivery.paysliv
    var zipcode = _delivery.adliv.zipcode
    var destliv = _delivery.destliv
  }
  if (checkZipcode(country, zipcode, destliv)) {
    if (valideDeliveryForm() === true) {
      getData(_cartId, _delivery.destliv, _delivery.paysliv).then(data => {
        _cartInfo = data
        updateCartRender()
        updateDeliveryRender()
        changeItem(itemPayment)
      }).catch(error => {
        $('.right .catch-message').html(error)
      })
    } else {
      $('.right .catch-message').html('erreur de formulaire')
    }
  } else {
    $('.right .catch-message').html('erreur de zipcode')
  }
})

function valideDeliveryForm () {
  if (_delivery.adliv.adresse === '' ||
    _delivery.adliv.zipcode === '' ||
    _delivery.adliv.city === '' ||
    _delivery.destliv === '' ||
    _delivery.paysliv === '') {
    return false
  }
  return true
}

itemShipping.on('click', '.button.gift', function (event) {
  event.preventDefault()
  $(`.panel.gift`, itemShipping).addClass('active')
  changeItem(itemShipping)
})

itemShipping.on('click', '.button.reset_gift', function (event) {
  event.preventDefault()
  delete _delivery.memocmd
  $(`.input.note`, itemShipping).value = ''
  changeItem(itemShipping)
})

itemShipping.on('submit', '.form.gift', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  let dataVal = data
  _delivery.memocmd = dataVal[0].value
  $(`.panel.gift`, itemShipping).removeClass('active')
  changeItem(itemShipping)
})

itemShipping.on('submit', '.form.tvaintra', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  let dataVal = data
  _delivery.tvaintra = dataVal[0].value
  changeItem(itemShipping)
})
