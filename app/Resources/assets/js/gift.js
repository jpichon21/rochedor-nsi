import $ from 'jquery'
import moment from 'moment'
import { getContact } from './sample'
import { upFlashbag } from './popup'
import { upLoader, downLoader } from './loader'
import Inputmask from 'inputmask'
import I18n from './i18n'
import {
  postRegister,
  postModify,
  getLogin,
  getLogout,
  postLogin,
  resetLogin,
  postGift } from './gift-api.js'

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
  Inputmask().mask(document.querySelectorAll('.datnaiss'))
}

function updateAmountRender () {
  $('.amount-render').html(amountTemplate({
    amount: _amount,
    allocation: _allocation,
    reduction: i18n.trans('gift.summary.reduction').replace('%reduction%', parseFloat(_amount * 0.66).toFixed(2))
  }))
}

/* Actions */

function afterLogin (user, bypass) {
  const contact = getContact()
  _you = { ...contact, ...user }
  updateYouRender()
  if (bypass) {
    changeItem(itemAmount)
  } else {
    changeItem(itemCard)
  }
}

function formatContact (data) {
  let contact = getContact()
  data.map(obj => {
    contact[obj.name] = obj.value
  })
  contact.codco = parseInt(contact.codco)
  contact.datnaiss = moment(contact.datnaiss, 'DD/MM/YYYY').format()
  return contact
}

function validatePassword (password) {
  if (password.length < 8 && password.length !== 0) {
    return i18n.trans('security.password_too_small')
  }
  return true
}

itemConnection.on('submit', '.panel.connection form', function (event) {
  event.preventDefault()
  upLoader()
  postLogin({
    username: $('.username', this).val(),
    password: $('.password', this).val()
  }).then(user => {
    downLoader()
    afterLogin(user, false)
  }).catch(() => {
    downLoader()
    upFlashbag(i18n.trans('security.bad_credentials'))
  })
})

itemConnection.on('submit', '.panel.reset form', function (event) {
  event.preventDefault()
  upLoader()
  resetLogin({
    email: $('.email', this).val(),
    firstname: $('.firstname', this).val(),
    lastname: $('.lastname', this).val()
  }).then(() => {
    downLoader()
    upFlashbag(i18n.trans('security.check_inbox'))
  })
    .catch((err) => {
      downLoader()
      upFlashbag(i18n.trans(err))
    })
})

itemConnection.on('click', '.cancel', function (event) {
  event.preventDefault()
  $('.panel.registration', itemConnection).slideUp(800, function () {
    $(this).hide()
  })
})

itemConnection.on('submit', '.panel.registration form', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  const contact = formatContact(data)
  upLoader()
  const validatedPassword = validatePassword(contact.password)
  if (validatedPassword !== true) {
    downLoader()
    upFlashbag(validatedPassword)
    return
  }
  if (validateDate(contact.datnaiss)) {
    if (validatePhone(contact.tel, contact.mobil)) {
      postRegister({
        contact: contact
      }).then(user => {
        postLogin({
          username: contact.username,
          password: contact.password
        }).then(user => {
          downLoader()
          afterLogin(user, true)
        }).catch((error) => {
          downLoader()
          upFlashbag(i18n.trans(error))
        })
      }).catch((error) => {
        downLoader()
        upFlashbag(i18n.trans(error))
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
  const which = $(this).attr('href').substring(1)
  switch (which) {
    case 'connection':
    case 'registration':
      $('.panel', itemConnection).hide()
      $(`.panel.${which}`, itemConnection).show()
      _you = getContact()
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

itemCard.on('click', '.cancel', function (event) {
  event.preventDefault()
  $('.panel.modify', itemCard).slideUp(800, function () {
    $('.panel', itemCard).hide()
  })
})

itemCard.on('submit', '.panel.modify form', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  const contact = formatContact(data)
  upLoader()
  const validatedPassword = validatePassword(contact.password)
  if (validatedPassword !== true) {
    downLoader()
    upFlashbag(validatedPassword)
    return
  }
  if (validateDate(contact.datnaiss)) {
    if (validatePhone(contact.tel, contact.mobil)) {
      postModify({
        contact: contact
      }).then(user => {
        downLoader()
        afterLogin({ ...user, password: contact.password }, false)
        $('.panel.modify').slideUp(800, function () {
          $(this).hide()
        })
      }).catch(error => {
        downLoader()
        upFlashbag(error)
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
  upLoader()
  postGift(_amount, _allocation.value, _modpaie, _note).then(data => {
    window.location.href = data
  }).catch(err => {
    downLoader()
    upFlashbag(err)
    console.error(err)
  })
})

itemConnection.on('click', '.panel.reset .cancel', function (event) {
  event.preventDefault()
  $('.panel.reset').slideUp(800, function () {
    $(this).hide()
    changeItem(itemConnection)
  })
})
