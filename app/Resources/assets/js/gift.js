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
import {limitMenuReduced} from './variables'

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
let _dateDebVir = ''
let _virPeriod = ''

const itemConnection = $('.item.connection')
const itemCard = $('.item.card')
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

function changeItem(elmt) {
  $('.dropdown .item').each(function () {
    this.style.maxHeight = null
    this.classList.remove('active')
  })
  elmt.forEach(function (item) {
    item.addClass('active')
    item.css('maxHeight', item.prop('scrollHeight') + 'px')
  })
}

$(document).ready(function () {
  $('.dropdown .item').each(function () {
    if (this.classList.contains('amount') || this.classList.contains('allocation') || this.classList.contains('payment')) {
      this.style.maxHeight = this.scrollHeight + 'px'
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

function updateYouFormRender () {
  $('.you-form-render').html(youFormTemplate({
    you: _you,
    countries: _countries,
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

function updateAmountRender () {
  $('.amount-render').html(amountTemplate({
    reduction: i18n.trans('gift.summary.reduction').replace('%reduction%', parseFloat(_amount * 0.66).toFixed(2))
  }))
}

/* Actions */

$('.item.amount h2, .item.allocation h2, .item.payment h2').on('click', function () {
  changeItem([itemAmount, itemAllocation, itemPayment])
})

/* CHOIX DU MONTANT */

itemAmount.on('click', '.button.radio', function (event) {
  event.preventDefault()
  $('.panel.amount .button.radio').removeClass('checked')
  const amount = $(this).addClass('checked').attr('href').substring(1)
  let $amountTextAmount = $('.panel.amount .input.amount')
  if ($(this).hasClass('other')) {
    $amountTextAmount.parent().removeClass('hidden')
    $amountTextAmount.val('').focus()
  }
  else {
    $amountTextAmount.parent().addClass('hidden')
    $amountTextAmount.val(amount)
    _amount = $amountTextAmount.val()
    updateAmountRender()
  }
})

itemAmount.on('keyup', '.input.amount', function () {
  _amount = $(this).val()
  updateAmountRender()
})

/* CHOIX DE L'ALLOCATION */

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

  if (_modpaie === 'VIR' || _modpaie === 'VIRREG') {
    itemPrelevement.removeClass('hidden')
  }
  else {
    itemPrelevement.addClass('hidden')
  }
})

itemPayment.on('change', '.select-modpaie', function (event) {
  event.preventDefault()
  _modpaie = $(this).val()
})

itemPayment.on('submit', '.panel.payment form', function (event) {
  event.preventDefault()

  if (_modpaie === 'VIR' || _modpaie === 'VIRREG') {
    Inputmask().mask(document.querySelectorAll('.date_virement'))
    itemPrelevement.find('.virement').removeClass('hidden')
    if (_modpaie === 'VIRREG') {
      itemPrelevement.find('.select-period').removeClass('hidden')
      itemPrelevement.find('.virement-reg').removeClass('hidden')
      itemPrelevement.find('.virement').addClass('hidden')
    }
    changeItem([itemPrelevement])
  }
  else {
    changeItem([itemConnection])
  }
})

/* PRELEVEMENT */

itemPrelevement.on('submit', 'form', function (event) {
  event.preventDefault()
  _dateDebVir = moment(itemPrelevement.find('.date_virement').val(), 'DD/MM/YYYY').format()
  _virPeriod = itemPrelevement.find('.select-period').val()

  changeItem([itemConnection])
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
  postGift(_amount, _allocation.value, _modpaie, _note, _dateDebVir, _virPeriod).then(data => {
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

itemConnection.on('click', '.back', function (event) {
  event.preventDefault()
  changeItem([itemAmount, itemAllocation, itemPayment])
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
      getLogout(_locale)
      break
  }
  changeItem([itemConnection])
})

function validateDate (date) {
  return moment(date).isValid()
}

function validatePhone (phone, mobile) {
  return !(phone === '' && mobile === '')
}
//
// itemCard.on('click', '.continue', function (event) {
//   event.preventDefault()
//   changeItem([itemAmount])
// })
//
// itemCard.on('click', '.modify-you', function (event) {
//   event.preventDefault()
//   $('.panel', itemCard).hide()
//   $('.panel.modify', itemCard).show()
//   updateYouFormRender()
//   changeItem([itemCard])
// })
//
// itemCard.on('click', '.cancel', function (event) {
//   event.preventDefault()
//   $('.panel.modify', itemCard).slideUp(800, function () {
//     $('.panel', itemCard).hide()
//   })
// })
//
// itemCard.on('submit', '.panel.modify form', function (event) {
//   event.preventDefault()
//   const data = $(this).serializeArray()
//   const contact = formatContact(data)
//   upLoader()
//   const validatedPassword = validatePassword(contact.password)
//   if (validatedPassword !== true) {
//     downLoader()
//     upFlashbag(validatedPassword)
//     return
//   }
//   if (validateDate(contact.datnaiss)) {
//     if (validatePhone(contact.tel, contact.mobil)) {
//       postModify({
//         contact: contact
//       }).then(user => {
//         downLoader()
//         afterLogin({ ...user, password: contact.password }, false)
//         $('.panel.modify').slideUp(800, function () {
//           backToTop()
//           $(this).hide()
//         })
//       }).catch(error => {
//         downLoader()
//         upFlashbag(error)
//       })
//     } else {
//       downLoader()
//       upFlashbag(i18n.trans('form.message.phone_invalid'))
//     }
//   } else {
//     downLoader()
//     upFlashbag(i18n.trans('form.message.date_invalid'))
//   }
// })

itemConnection.on('click', '.panel.reset .cancel', function (event) {
  event.preventDefault()
  $('.panel.reset').slideUp(800, function () {
    $(this).hide()
    backToTop()
    changeItem([itemConnection])
  })
})
