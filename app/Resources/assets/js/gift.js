import $ from 'jquery'
import moment from 'moment'
import { getParticipant } from './sample'
import { upFlashbag } from './popup'
import I18n from './i18n'
import {
  postRegister,
  postModify,
  getLogin,
  getLogout,
  postLogin,
  resetLogin,
  getCountryCode,
  postGift } from './gift-api.js'

const { environment } = require(`./environment.${process.env.NODE_ENV}`)
const Sha512 = require('sha512')
const Buffer = require('buffer').Buffer
/* Translations */

let i18n = new I18n()

const _locale = $('.locale-json').html().trim()

moment.locale(_locale)

/* Countries */

const _countries = JSON.parse($('.countries-json').html())

/* Variables */

let _you = {}
let _amount = 0
let _allocation = {}
let _modpaie = ''
let _note = ''

const itemConnection = $('.item.connection')
const itemCard = $('.item.card')
const itemAmount = $('.item.amount')
const itemAllocation = $('.item.allocation')
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

/* Renders */

const youTemplate = _.template($('.you-template').html())
const youFormTemplate = _.template($('.you-form-template').html())
const amountTemplate = _.template($('.amount-template').html())

function updateYouRender () {
  $('.you-render').html(youTemplate({ you: _you }))
}

function updateYouFormRender () {
  $('.you-form-render').html(youFormTemplate({
    you: _you,
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

function updateAmountRender () {
  $('.amount-render').html(amountTemplate({
    amount: _amount,
    allocation: _allocation
  }))
}

updateAmountRender()

/* Actions */

function afterLogin (user, bypass) {
  const participant = getParticipant()
  _you = { ...participant, ...user }
  updateYouRender()
  if (bypass) {
    changeItem(itemAmount)
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
  event.preventDefault()
  const data = $(this).serializeArray()
  const participant = formatParticipant(data)
  if (validateDate(participant.datnaiss)) {
    if (validatePhone(participant.tel, participant.mobil)) {
      postRegister({
        contact: participant
      }).then(user => {
        postLogin({
          username: user.username,
          password: user.password
        }).then(user => {
          afterLogin(user, true)
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

itemCard.on('click', '.continue', function (event) {
  event.preventDefault()
  changeItem(itemAmount)
})

itemCard.on('click', '.modify-you', function (event) {
  event.preventDefault()
  $('.panel', itemCard).hide()
  $('.panel.modify', itemCard).show()
  updateYouFormRender()
  changeItem(itemCard)
})

itemCard.on('submit', '.panel.modify form', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  const participant = formatParticipant(data)
  if (validateDate(participant.datnaiss)) {
    if (validatePhone(participant.tel, participant.mobil)) {
      postModify({
        contact: participant
      }).then(user => {
        afterLogin({ ...user, password: participant.password }, false)
        $('.panel.modify').slideUp(800, function () {
          $(this).hide()
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

$('.panel.amount').on('click', '.button.radio', function (event) {
  event.preventDefault()
  $('.panel.amount .button.radio').removeClass('checked')
  const amount = $(this).addClass('checked').attr('href').substring(1)
  $('.panel.amount .input.amount').val(amount)
})

$('.panel.amount').on('focus', '.input.amount', function (event) {
  event.preventDefault()
  $('.panel.amount .button.radio').removeClass('checked')
  $(this).val('')
})

itemAmount.on('submit', '.panel.amount form', function (event) {
  event.preventDefault()
  _amount = $('.panel.amount .input.amount').val()
  updateAmountRender()
  changeItem(itemAllocation)
})

itemAllocation.on('change', '.select-allocation', function (event) {
  event.preventDefault()
  _allocation = {
    name: $(this).find('option:selected').text(),
    value: $(this).val()
  }
})

itemAllocation.on('change', '.gift-note', function (event) {
  event.preventDefault()
  _note = $(this).val()
})

itemAllocation.on('submit', '.panel.allocation form', function (event) {
  event.preventDefault()
  updateAmountRender()
  changeItem(itemPayment)
})

itemPayment.on('change', '.select-modpaie', function (event) {
  event.preventDefault()
  _modpaie = $(this).val()
})

itemPayment.on('submit', '.panel.payment form', function (event) {
  event.preventDefault()
  getPaysParsed(_modpaie, _you.pays).then(country => {
    postGift(_amount, _allocation.value, _modpaie, _note).then(data => {
      placePayment(
        _modpaie,
        _amount,
        data.refdon,
        i18n.trans('gift.payment.title'),
        i18n.trans('gift.payment.title'),
        _you.email,
        country,
        _locale
      )
    }).catch(err => {
      console.error(err)
    })
  })
})

function host () {
  const host = window.location.hostname
  const protocol = window.location.protocol
  return `${protocol}//${host}`
}

function placePayment (
  method,
  amount,
  objectId,
  objectName,
  itemName,
  email,
  lang,
  locale
) {
  if (method === 'PBX') {
    const date = new Date()
    const params = {
      PBX_SITE: environment.pbx_site,
      PBX_RANG: environment.pbx_rang,
      PBX_IDENTIFIANT: environment.pbx_identifiant,
      PBX_TOTAL: amount * 100,
      PBX_DEVISE: 978,
      PBX_CMD: objectId,
      PBX_PORTEUR: email,
      PBX_REPONDRE_A: `${host()}/${locale}/gift/payment-notify/paybox`,
      PBX_EFFECTUE: `${host()}/${locale}/gift/payment-return/paybox/success`,
      PBX_REFUSE: `${host()}/${locale}/gift/payment-return/paybox/error`,
      PBX_ANNULE: `${host()}/${locale}/gift/payment-return/paybox/cancel`,
      PBX_ATTENTE: `${host()}/${locale}/gift/payment-return/paybox/waiting`,
      PBX_RETOUR: 'Amount:M;Ref:R;Auto:A;Erreur:E;Trans:T;Pays:I',
      PBX_HASH: 'SHA512',
      PBX_TIME: date.toISOString(),
      PBX_LANGUE: lang
    }
    const url = Object.keys(params).map(function (k) {
      return k + '=' + params[k]
    }).join('&')
    const encodedUrl = Object.keys(params).map(function (k) {
      return encodeURIComponent(k) + '=' + encodeURIComponent(params[k])
    }).join('&')
    const key = Buffer.from(environment.pbx_key, 'hex')
    const ider = Sha512.hmac(key)
    const id = ider.finalize(url)
    window.location.href = environment.pbx_url + '?' + encodedUrl + '&PBX_HMAC=' + id.toString('hex').toUpperCase()
  }
  if (method === 'PAYPAL') {
    const params = {
      amount: amount,
      cmd: '_xclick',
      currency_code: 'EUR',
      item_name: itemName,
      item_number: objectId,
      rm: 0,
      return: `${host()}/${locale}/gift/payment-return/paypal/success`,
      cancel_return: `${host()}/${locale}/gift/payment-return/paypal/cancel`,
      business: environment.pp_email,
      notify_url: `${host()}/${locale}/gift/payment-notify/paypal`,
      email: email,
      lc: lang
    }
    const url = Object.keys(params).map(function (k) {
      return encodeURIComponent(k) + '=' + encodeURIComponent(params[k])
    }).join('&')
    window.location.href = environment.pp_url + '?' + url
  }
}

function getPaysParsed (modpaie, pays) {
  return new Promise((resolve, reject) => {
    getCountryCode(pays, modpaie).then(data => {
      resolve(data)
    }).catch(() => {
      reject(i18n.trans('form.message.zipcode_invalid'))
    })
  })
}
