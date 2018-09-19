import $ from 'jquery'
import moment from 'moment'
import { getParticipant, getDelivery } from './sample'
import { placePayment } from './cart'
import { upFlashbag } from './popup'
import { upLoader, downLoader } from './loader'
import I18n from './i18n'
import {
  getLogin,
  getLogout,
  getData,
  postRegister,
  resetLogin,
  postLogin,
  postOrder,
  postEditCli,
  checkVat,
  checkZipcode,
  getPBXCode,
  getPaypalCode
} from './order-api.js'

/* Cart */

const _cartId = parseInt($('.cart-json').html().trim())

/* Translations */

let i18n = new I18n()

const _locale = $('.locale-json').html().trim()

moment.locale(_locale)

/* Countries */

const _countries = JSON.parse($('.countries-json').html().trim())

/* Variables */

let _you = {}
let _delivery = {}
let _total = {}

const itemConnection = $('.item.connection')
const itemCard = $('.item.card')
const itemShipping = $('.item.shipping')
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
const deliveryTemplate = _.template($('.delivery-template').html())
const totalTemplate = _.template($('.total-template').html())
const youFormTemplate = _.template($('.you-form-template').html())
const adlivFormTemplate = _.template($('.adliv-form-template').html())

function updateYouRender () {
  $('.you-render').html(youTemplate({
    you: _you
  }))
}

function updateDeliveryRender () {
  $('.delivery-render').html(deliveryTemplate({
    delivery: _delivery,
    you: _you
  }))
}

function updateCartRender () {
  $('.total-render').html(totalTemplate({
    total: _total
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

function adlivUpdateFormRender () {
  $('.adliv-form-render').html(adlivFormTemplate({
    delivery: _delivery,
    countries: _countries
  }))
}

getData(_cartId, 'myAdd', 'FR').then(data => {
  _total = data
  updateCartRender()
  updateDeliveryRender()
})

/* Actions */

function afterLogin (user, bypass) {
  _delivery = getDelivery()
  _delivery.codcli = user.codcli
  _delivery.cartId = parseInt(_cartId)
  _you = user
  $('.you-title').addClass('pointer')
  updateYouRender()
  updateCartRender()
  adlivUpdateForm('myAd')
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
  participant.codcli = parseInt(participant.codcli)
  return participant
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
})

itemConnection.on('submit', '.panel.registration form', function (event) {
  validateClient(event, $(this), participant => {
    upLoader()
    postRegister({
      client: {
        ...participant,
        rue: participant.adresse
      }
    }).then(user => {
      postLogin({
        username: user.username,
        password: participant.password
      }).then(user => {
        downLoader()
        afterLogin(user, true)
      }).catch(() => {
        downLoader()
        upFlashbag(i18n.trans('security.username_exists'))
      })
    }).catch(error => {
      downLoader()
      upFlashbag(error)
    })
  })
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
      upLoader()
      getLogin().then(user => {
        downLoader()
        afterLogin(user, false)
      })
      break
    case 'disconnect':
      getLogout()
      break
  }
  changeItem(itemConnection)
})

function validatePhone (phone, mobile) {
  return !(phone === '' && mobile === '')
}

function validatePro (societe, tvaintra) {
  return (societe === '' && tvaintra === '') || (societe !== '' && tvaintra !== '')
}

function validateTvaintra (tvaintra, country) {
  return new Promise((resolve, reject) => {
    checkVat(tvaintra, country).then(() => {
      resolve()
    }).catch(() => {
      reject(i18n.trans('form.message.zipcode_invalid'))
    })
  })
}

function validatePassword (password) {
  if (password.length < 8 && password.length !== 0) {
    return i18n.trans('security.password_too_small')
  }
  return true
}

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

itemCard.on('click', '.you-title', function (event) {
  if (_you.codcli) {
    changeItem(itemCard)
  }
})

function validateClient (event, context, callback) {
  event.preventDefault()
  const data = context.serializeArray()
  const participant = formatParticipant(data)
  const validatedPhone = validatePhone(participant.tel, participant.mobil)
  const validatedPro = validatePro(participant.societe, participant.tvaintra)
  const validatedPassword = validatePassword(participant.password)
  if (validatedPassword !== true) {
    downLoader()
    upFlashbag(validatedPassword)
    return
  }
  if (validatedPro) {
    if (validatedPhone) {
      if (participant.tvaintra !== '') {
        validateTvaintra(participant.tvaintra, participant.pays).then(() => {
          callback(participant)
        }).catch(() => {
          downLoader()
          upFlashbag(i18n.trans('form.message.tvaintra_invalid'))
        })
      } else {
        return new Promise((resolve, reject) => {
          checkZipcode(participant.pays, participant.cp, 'myAd').then(() => {
            resolve(callback(participant))
          }).catch(() => {
            downLoader()
            upFlashbag(i18n.trans('form.message.zipcode_invalid'))
          })
        })
      }
    } else {
      downLoader()
      upFlashbag(i18n.trans('form.message.phone_invalid'))
    }
  } else {
    downLoader()
    upFlashbag(i18n.trans('form.message.pro_invalid'))
  }
}

itemCard.on('submit', '.panel.modify form', function (event) {
  upLoader()
  validateClient(event, $(this), user => {
    postEditCli({
      client: {
        ...user,
        rue: user.adresse
      }
    }).then(client => {
      downLoader()
      _you = client
      updateYouRender()
      adlivUpdateForm('myAd')
      $(`.panel.modify`).slideUp(800, function () {
        $(this).hide()
        changeItem(itemCard)
      })
    }).catch(error => {
      downLoader()
      upFlashbag(error)
    })
  })
})

itemShipping.on('change', '.select-adliv', function (event) {
  event.preventDefault()
  const selected = $(this).val()
  adlivUpdateForm(selected)
})

function adlivUpdateForm (destliv) {
  switch (destliv) {
    case 'myAd':
      _delivery.adliv.adresse = _you.adresse
      _delivery.adliv.zipcode = _you.cp
      _delivery.adliv.city = _you.ville
      _delivery.paysliv = _you.pays
      break
    case 'Roche':
      _delivery.adliv.adresse = '1 Chemin du Muenot'
      _delivery.adliv.zipcode = '25000'
      _delivery.adliv.city = 'Besançon'
      _delivery.paysliv = 'FR'
      break
    case 'Font':
      _delivery.adliv.adresse = 'Route de Riunoguès'
      _delivery.adliv.zipcode = '66480'
      _delivery.adliv.city = 'Maureillas Las Illas'
      _delivery.paysliv = 'FR'
      break
    case 'Other':
      _delivery.adliv.adresse = ''
      _delivery.adliv.zipcode = ''
      _delivery.adliv.city = ''
      _delivery.paysliv = ''
      break
  }
  _delivery.destliv = destliv
  adlivUpdateFormRender()
}

function formatForm (data) {
  let form = {}
  data.map(obj => {
    form[obj.name] = obj.value
  })
  return form
}

const formAdliv = $('form.adliv', itemShipping)
const formGift = $('form.gift', itemShipping)

formGift.on('submit', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  const gift = formatForm(data)
  _delivery.memocmd = gift.note
})

formAdliv.on('submit', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  const delivery = formatForm(data)
  if (_delivery.destliv === 'Other') {
    _delivery.adliv.adresse = delivery.adresse
    _delivery.adliv.zipcode = delivery.zipcode
    _delivery.adliv.city = delivery.city
    _delivery.paysliv = delivery.paysliv
  }
  if (_delivery.destliv === 'myAd' && delivery.paysliv !== undefined) {
    _delivery.paysliv = delivery.paysliv
  }
})

function noEmptyFields (data) {
  const emptyFileds = data.filter(obj => obj === undefined || obj === '')
  return emptyFileds.length === 0
}

function valideDelivery (delivery) {
  return new Promise((resolve, reject) => {
    if (noEmptyFields([delivery.adliv.adresse, delivery.adliv.zipcode, delivery.adliv.city, delivery.paysliv])) {
      checkZipcode(delivery.paysliv, delivery.adliv.zipcode, delivery.destliv).then(() => {
        resolve(delivery)
      }).catch(() => {
        reject(i18n.trans('form.message.zipcode_invalid'))
      })
    } else {
      reject(i18n.trans('form.message.delivery_invalid'))
    }
  })
}

itemShipping.on('click', '.continue', function (event) {
  event.preventDefault()
  formAdliv.submit()
  formGift.submit()
  upLoader()
  valideDelivery(_delivery).then(delivery => {
    getData(_cartId, delivery.destliv, delivery.paysliv).then(data => {
      downLoader()
      _total = data
      updateCartRender()
      updateDeliveryRender()
      changeItem(itemPayment)
    }).catch(error => {
      downLoader()
      if (error) {
        upFlashbag(error)
      }
    })
  }).catch(error => {
    downLoader()
    if (error) {
      upFlashbag(error)
    }
  })
})

function getPaysParsed (modpaie, pays) {
  if (modpaie === 'PBX') {
    return new Promise((resolve, reject) => {
      getPBXCode(pays).then(data => {
        resolve(data)
      }).catch(() => {
        reject(i18n.trans('form.message.zipcode_invalid'))
      })
    })
  } else {
    return new Promise((resolve, reject) => {
      getPaypalCode(pays).then(data => {
        resolve(data)
      }).catch(() => {
        reject(i18n.trans('form.message.zipcode_invalid'))
      })
    })
  }
}

itemPayment.on('submit', 'form.payment', function (event) {
  event.preventDefault()
  upLoader()
  getPaysParsed(_delivery.modpaie, _delivery.paysliv).then(paysparsed => {
    postOrder(_delivery).then(res => {
      downLoader()
      placePayment(
        res.modpaie,
        res.ttc,
        res.refcom,
        'truc',
        'Commande sur le site La Roche D\'Or',
        _you.email,
        paysparsed,
        _locale
      )
    }).catch(error => {
      downLoader()
      if (error) {
        upFlashbag(error)
      }
    })
  })
})

itemPayment.on('change', '.select-modpaie', function (event) {
  event.preventDefault()
  const data = $(this).val()
  _delivery.modpaie = data
})
