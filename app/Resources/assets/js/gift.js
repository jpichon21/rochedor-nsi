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
import { limitMenuReduced } from './variables'


/* Translations */

let i18n = new I18n()

const _locale = $('.locale-json').html().trim()

moment.locale(_locale)

/* Countries */

const _countries = JSON.parse($('.countries-json').html())
const _preferredCountries = JSON.parse($('.preferred-countries-json').html())

/* Variables */

let _you = {}
let _amount = 0
let _allocation = {}
let _modpaie = ''
let _note = ''
let _dateDebVir = ''
let _dateFinVir = ''
let _virPeriod = ''

const itemConnection = $('.item.connection')
const itemAmount = $('.item.amount')
const itemAllocation = $('.item.allocation')
const itemPayment = $('.item.payment')
const itemPrelevement = $('.item.prelevement')
const content = $('.content')


/* Dropdowns */
function backToTop () {
  if (window.innerWidth >= limitMenuReduced) {
    content[0].scroll({ top: 0, behavior: 'smooth' })
  } else {
    window.scroll({ top: 0, behavior: 'smooth' })
  }
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
    document.querySelector('.content').scroll({ top: ($element.offset().top), left: 0, behavior: 'smooth' })
  }, 200)
}

function scrollTop () {
  setTimeout(() => {
    document.querySelector('.content').scroll({ top: 0, left: 0, behavior: 'smooth' })
  }, 200)
}

function changeItem (elmt) {
  $('.dropdown .item').each(function () {
    this.style.maxHeight = null
    this.classList.remove('active')
  })
  elmt.forEach(function (item) {
    item.addClass('active')
    resizeItem(item)
  })
}

function resizeItem ($item) {
  $item.css('maxHeight', $item.prop('scrollHeight') + 'px')
}

$(document).ready(function () {
  $('.dropdown .item').each(function () {

    if (this.classList.contains('amount') || this.classList.contains('allocation') || this.classList.contains('payment')) {
      resizeItem($(this))
      this.classList.add('active')
    }
  })
})

/* Renders */

const youTemplate = _.template($('.you-template').html())
const youFormTemplate = _.template($('.you-form-template').html())
const amountTemplate = _.template($('.amount-template').html())

function updateYouRender () {
  $('.you-render').html(youTemplate({ you: _you }))
}

function updateYouFormRender (errors = [], contact = {}) {
  $('.you-form-render').html(youFormTemplate({
    you: contact,
    errors: errors,
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
  Inputmask().mask(document.querySelectorAll('.datnaiss'))
}

const REQUIRED_FIELDS_COMMON = ['civil', 'prenom', 'nom']
const REQUIRED_FIELDS_YOU = ['adresse', 'cp', 'ville', 'pays', 'email']

function validateYou (data) {
  return validateRequired(data, [...REQUIRED_FIELDS_COMMON, ...REQUIRED_FIELDS_YOU])
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

function updateAmountRender () {
  if (!_amount) {
    $('.amount-render').html('')
  } else {
    $('.amount-render').html(amountTemplate({
      reduction: i18n.trans('gift.summary.reduction').replace('%reduction%', parseFloat(_amount * 0.33).toFixed(2))
    }))
  }

  resizeItem($('.item.amount.active'))
}

/* Actions */

/* CHOIX DU MONTANT */

itemAmount.on('click', '.button.radio', function (event) {
  event.preventDefault()
  $('.panel.amount .button.radio').removeClass('checked')
  const amount = $(this).val()

  let $amountTextAmount = $('.panel.amount .input.amount')
  if ($(this).hasClass('other')) {
    $amountTextAmount.parent().removeClass('hidden')
    $amountTextAmount.val('').focus()
    _amount = ''
  } else {
    $amountTextAmount.parent().addClass('hidden')
    $amountTextAmount.val(amount)
    _amount = $amountTextAmount.val()
  }
  $(this).addClass('checked')
  updateAmountRender()
})

itemAmount.on('keyup', '.input.amount', function () {
  $(this).val($(this).val().replace(',', '.'))
  if (!isNaN($(this).val())) {
    _amount = $(this).val()
    updateAmountRender()
  }
})

/* CHOIX DE L'ALLOCATION */

$(window).bind('pageshow', function() {
  _allocation = {
    name: $('.select-allocation').find('option:selected').text(),
    value: $('.select-allocation').val()
  }
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

/* CHOIX DU MOYEN DE PAIEMENT */

itemPayment.on('click', '.button.radio', function (event) {
  event.preventDefault()
  itemPayment.find('.button.radio').removeClass('checked')
  itemPayment.find('input[name="payment_method"]').val($(this).addClass('checked').attr('href').substring(1))
  _modpaie = itemPayment.find('input[name="payment_method"]').val()

  // Affiche la zone de saisie de la date du virement en cas de mode de paiement par virement
  if (_modpaie === 'VIR' || _modpaie === 'VPER') {
    itemPrelevement.removeClass('hidden')
  } else {
    itemPrelevement.addClass('hidden')
  }

  // Affiche le lien "Sécurité des dons en ligne" pour les paiements via PayPal/Paybox
  if (_modpaie === 'PAYPAL' || _modpaie === 'CB') {
    $('#secureDatasLink').removeClass('hidden')
  } else {
    $('#secureDatasLink').addClass('hidden')
  }
})

itemPayment.on('change', '.select-modpaie', function (event) {
  event.preventDefault()
  _modpaie = $(this).val()
})

itemPayment.on('submit', '.panel.payment form', function (event) {
  event.preventDefault()
  let toValidate = $('input[name="amount"], select.select-allocation, input[name="payment_method"]')
  let valid = true
  toValidate.each(function () {
    if ($(this).val() === '' || $(this).val() === null) {
      valid = false
    }
  })

  if (isNaN($('input[name="amount"]').val())) {
    valid = false
  }

  if (valid === false) {
    upFlashbag(i18n.trans('gift.invalid_form.amount'))
  } else {
    // init hidden
    itemPrelevement.find('.virement').addClass('hidden')
    itemPrelevement.find('.virement-reg').addClass('hidden')
    itemPrelevement.find('.virement-reg-fin').addClass('hidden')
    if (_modpaie === 'VIR' || _modpaie === 'VPER') {
      Inputmask().mask(document.querySelectorAll('.date_virement, .virement-reg-fin'))
      if (_modpaie === 'VPER') {
        itemPrelevement.find('.virement-reg').removeClass('hidden')
        itemPrelevement.find('.virement-reg-fin').removeClass('hidden')
      } else {
        itemPrelevement.find('.virement').removeClass('hidden')
        itemPrelevement.find('.virement-reg-fin').addClass('hidden')
      }
      scrollTop()
      changeItem([itemPrelevement])
      return
    }
    scrollTop()
    changeItem([itemConnection])
  }
})

/* PRELEVEMENT */

itemPrelevement.on('submit', 'form', function (event) {
  event.preventDefault()
  _dateDebVir = moment(itemPrelevement.find('.date_virement').val(), 'DD/MM/YYYY').format()
  if (itemPrelevement.find('input.virement-reg-fin').val() !== '') {
    _dateFinVir = moment(itemPrelevement.find('input.virement-reg-fin').val(), 'DD/MM/YYYY').format()
  }
  if (itemPrelevement.find('.select-period').val() !== null) {
    _virPeriod = itemPrelevement.find('.select-period').val()
  }

  let toValidate = $('input[name="date_virement"]')
  let valid = true
  if (_modpaie === 'VPER') {
    toValidate = $('input[name="date_virement"], select.select-period')
  }
  toValidate.each(function () {
    if ($(this).val() === '' || $(this).val() === null) {
      valid = false
    }
  })

  if (valid === false) {
    upFlashbag(i18n.trans('gift.invalid_form.virement'))
  } else {
    changeItem([itemConnection])
  }
})

itemPrelevement.on('click', '.back', function (event) {
  event.preventDefault()
  changeItem([itemAmount, itemAllocation, itemPayment])
})

/* CONNEXION / INSCRIPTION */

function afterLogin (user, bypass) {
  const contact = getContact()
  _you = { ...contact, ...user }
  updateYouRender()
  upLoader()
  postGift(_amount, _allocation.value, _modpaie, _note, _dateDebVir, _dateFinVir, _virPeriod).then(data => {
    window.location.href = data
  }).catch(err => {
    downLoader()
    upFlashbag(err)
    console.error(err)
  })
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

itemConnection.on('click', '.back', function (event) {
  event.preventDefault()
  changeItem([itemAmount, itemAllocation, itemPayment])
})

itemConnection.on('submit', '.panel.registration form', function (event) {
  event.preventDefault()
  upLoader()
  const data = $(this).serializeArray()
  const contact = formatContact(data)
  var errors = []
  errors = validateYou(data)
  var error = null
  error = validatePassword(contact.password)
  if (error) {
    errors['password'] = error
    error = null
  }
  error = validateDate(contact.datnaiss)
  if (error) {
    errors['datnaiss'] = error
    error = null
  }
  error = validatePhone(contact.tel, contact.mobil)
  if (error) {
    errors['tel'] = error
  }
  updateYouFormRender(errors, contact)
  if (Object.keys(errors).length === 0) {
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
  }
  else {
    downLoader()
  }
})

itemConnection.on('click', 'a', function (event) {
  event.preventDefault()
  if ($(this).hasClass('back')) {
    return
  }

  const which = $(this).attr('href') ? $(this).attr('href').substring(1) : ''
  switch (which) {
    case 'connection':
    case 'registration':
      $('.panel', itemConnection).hide()
      $(`.panel.${which}`, itemConnection).show()
      _you = getContact()
      updateYouFormRender()
      setTimeout(() => {
        scrollToElement($(`.panel.${which}`))
      }, 200)
      break
    case 'reset':
      $('.panel.reset', itemConnection).show()
      break
    case 'continue':
      getLogin().then(user => afterLogin(user, false))
      break
    case 'disconnect':
      getLogout(_locale)
      break
  }
  changeItem([itemConnection])
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

itemConnection.on('click', '.panel.reset .cancel', function (event) {
  event.preventDefault()
  $('.panel.reset').slideUp(800, function () {
    $(this).hide()
    backToTop()
    changeItem([itemConnection])
  })
})


const passwordPopupToggle = $("#passwordPopupToggle")

passwordPopupToggle.on('click', function (event) {
  event.preventDefault()
  passwordPopup();
})

function passwordPopup(){
  target = document.getElementById(passwordPopup);
  target.addClass('popup-visible');
}
