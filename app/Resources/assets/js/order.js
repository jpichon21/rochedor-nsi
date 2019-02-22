import $ from 'jquery'
import moment from 'moment'
import { getContact, getDelivery } from './sample'
import { upFlashbag, upConfirmbox } from './popup'
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
  patchProduct,
  removeCartline,
  getCartCount
} from './order-api.js'
import { changeItem } from './page'

/* Cart */

const _cartId = parseInt($('.cart-json').html().trim())

/* Cancel Return */

const _order = JSON.parse($('.order-json').html().trim())
const _user = JSON.parse($('.user-json').html().trim())

const cancelReturn = _order !== false && _user !== false

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
let _country = 'FR'
let _dest = 'myAdd'

const itemResume = $('.item.resume')
const itemConnection = $('.item.connection')
const itemCard = $('.item.card')
const itemShipping = $('.item.shipping')
const itemPayment = $('.item.payment')

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

const welcomeTemplate = _.template($('.welcome-template').html())
const cartCountTemplate = _.template($('.cartCount-template').html())
const youTemplate = _.template($('.you-template').html())
const deliveryTemplate = _.template($('.delivery-template').html())
const totalTemplate = _.template($('.total-template').html())
const youFormTemplate = _.template($('.you-form-template').html())
const adlivFormTemplate = _.template($('.adliv-form-template').html())
const delayTemplate = _.template($('.delay-template').html())
const cartTemplate = _.template($('.cart-template').html())
const termsTemplate = _.template($('.terms-template').html())
const detailCartTemplate = _.template($('.detailCart-template').html())

function updateWelcomeRender () {
  if (_you.prenom !== undefined) {
    $('.welcome-render').html(welcomeTemplate({
      you: _you
    }))
  }
}

function updateCartCountRender () {
  getCartCount().then((res) => {
    $('.cartCount-render').html(cartCountTemplate({
      cartCount: res
    }))
  })
}

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

function updateTotalRender () {
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
    ],
    statutLabel: [
      i18n.trans('form.statut.par'),
      i18n.trans('form.statut.org'),
      i18n.trans('form.statut.pro')
    ],
    statut: [
      'par',
      'org',
      'pro'
    ]
  }))
}

function adlivUpdateFormRender () {
  $('.adliv-form-render').html(adlivFormTemplate({
    delivery: _delivery,
    countries: _countries
  }))
}

function updateDelayRender () {
  if (_delivery.paysliv !== '') {
    $('.delay-render').html(delayTemplate({
      country: _countries.find(country => {
        return country.codpays === _delivery.paysliv
      })
    }))
  }
}

function updateTermsRender () {
  $('.terms-render').html(termsTemplate({
    you: _you
  }))
}

function updateCartRender () {
  let _sum = 0
  $('.cart-render').html(cartTemplate({
    product: _total.product,
    sum: _sum
  }))
}

function updateDetailcartRender () {
  let _sum = 0
  $('.detailCart-render').html(detailCartTemplate({
    product: _total.product,
    country: _country,
    sum: _sum
  }))
}

/* Actions */

function afterLogin (user) {
  _delivery = getDelivery()
  _delivery.codcli = user.codcli
  _delivery.cartId = parseInt(_cartId)
  _you = user
  updateYouRender()
  updateTotalRender()
  updateCartRender()
  updateDetailcartRender()
  adlivUpdateForm('myAd')
  changeItem(itemCard)
}

function formatParticipant (data) {
  let participant = getContact()
  data.map(obj => {
    participant[obj.name] = obj.value
  })
  participant.codcli = parseInt(participant.codcli)
  return participant
}

itemResume.on('click', '.continue', function (event) {
  event.preventDefault()
  updateWelcomeRender()
  changeItem(itemConnection)
})

itemConnection.on('click', 'h2', function () {
  updateWelcomeRender()
})

itemConnection.on('submit', '.panel.connection form', function (event) {
  event.preventDefault()
  upLoader()
  postLogin({
    username: $('.username', this).val(),
    password: $('.password', this).val()
  }).then(user => {
    downLoader()
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
        afterLogin(user)
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
      _you = getContact()
      updateYouFormRender()
      break
    case 'reset':
      $('.panel.reset', itemConnection).show()
      break
    case 'continue':
      upLoader()
      getLogin().then(user => {
        downLoader()
        afterLogin(user)
      })
      break
    case 'disconnect':
      getLogout(_locale)
      break
  }
  changeItem(itemConnection)
})

function validatePhone (phone, mobile) {
  return !(phone === '' && mobile === '')
}

function validateTvaintra (tvaintra) {
  return new Promise((resolve, reject) => {
    checkVat(tvaintra).then(() => {
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
  updateTermsRender()
  adlivUpdateForm($('.select-adliv').val())
  updateDelayRender()
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
  const validatedPhone = validatePhone(participant.tel, participant.mobil)
  const validatedPassword = validatePassword(participant.password)
  if (validatedPassword !== true) {
    downLoader()
    upFlashbag(validatedPassword)
    return
  }
  if (validatedPhone) {
    if (participant.tvaintra !== '') {
      validateTvaintra(participant.tvaintra).then(() => {
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
        updateDelayRender()
        changeItem(itemShipping)
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
      _delivery.adliv.prenom = _you.prenom
      _delivery.adliv.nom = _you.nom
      _delivery.adliv.adresse = _you.adresse
      _delivery.adliv.zipcode = _you.cp
      _delivery.adliv.city = _you.ville
      _delivery.paysliv = _you.pays
      break
    case 'Roche':
      _delivery.adliv.prenom = ''
      _delivery.adliv.nom = ''
      _delivery.adliv.adresse = '1 Chemin du Muenot'
      _delivery.adliv.zipcode = '25000'
      _delivery.adliv.city = 'Besançon'
      _delivery.paysliv = 'FR'
      break
    case 'Font':
      _delivery.adliv.prenom = ''
      _delivery.adliv.nom = ''
      _delivery.adliv.adresse = 'Route de Riunoguès'
      _delivery.adliv.zipcode = '66480'
      _delivery.adliv.city = 'Maureillas Las Illas'
      _delivery.paysliv = 'FR'
      break
    case 'Other':
      _delivery.adliv.prenom = ''
      _delivery.adliv.nom = ''
      _delivery.adliv.adresse = ''
      _delivery.adliv.zipcode = ''
      _delivery.adliv.city = ''
      _delivery.paysliv = ''
      break
  }
  _delivery.destliv = destliv
  adlivUpdateFormRender()
  updateDelayRender()
  $('.panel.delay').toggleClass('active', destliv === 'myAd')
  $('.panel.adliv').toggleClass('active', destliv !== 'myAd')
  changeItem(itemShipping)
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
  if (_delivery.destliv !== 'myAd') {
    _delivery.adliv.prenom = delivery.prenom
    _delivery.adliv.nom = delivery.nom
  }
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
    if (noEmptyFields([
      delivery.adliv.prenom,
      delivery.adliv.nom,
      delivery.adliv.adresse,
      delivery.adliv.zipcode,
      delivery.adliv.city,
      delivery.paysliv
    ])) {
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
    _country = delivery.paysliv
    _dest = delivery.destliv
    getData(_cartId, _country, _dest).then(data => {
      downLoader()
      _total = data
      updateTotalRender()
      updateDeliveryRender()
      updateDetailcartRender()
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

const formPayment = $('form.payment', itemPayment)

itemPayment.on('click', '.pay', function (event) {
  event.preventDefault()
  if (_delivery.modpaie === '') {
    upFlashbag(i18n.trans('form.message.modpaie_invalid'))
  } else {
    formPayment.submit()
  }
})

itemPayment.on('submit', 'form.payment', function (event) {
  event.preventDefault()
  upLoader()
  postOrder(_delivery).then(res => {
    window.location.href = res
  }).catch(error => {
    downLoader()
    if (error) {
      upFlashbag(error)
    }
  })
})

itemPayment.on('change', '.select-modpaie', function (event) {
  event.preventDefault()
  const data = $(this).val()
  _delivery.modpaie = data
  $('.pay', itemPayment).removeClass('disabled')
})

itemConnection.on('click', '.newfich', function () {
  const boolean = $(this).toggleClass('checked').hasClass('checked')
  $('.newfich-wrapper .checkbox', itemConnection).val(boolean)
})

itemCard.on('click', '.newfich', function () {
  const boolean = $(this).toggleClass('checked').hasClass('checked')
  $('.newfich-wrapper .checkbox', itemCard).val(boolean)
})

itemConnection.on('click', '.panel.reset .cancel', function (event) {
  event.preventDefault()
  $('.panel.reset').slideUp(800, function () {
    $(this).hide()
    changeItem(itemConnection)
  })
})

if (cancelReturn) {
  _you = _user
  _delivery = _order
  _delivery.cartId = parseInt(_cartId)
  _delivery.dateenreg = ''
  _delivery.paysliv = _order.paysliv
  _delivery.destliv = _order.destliv
  _delivery.adliv = JSON.parse(_delivery.adliv)
  _delivery.modpaie = ''
  _country = _delivery.paysliv
  _dest = _delivery.destliv
  getData(_cartId, _country, _dest).then(data => {
    _total = data
    updateCartCountRender()
    updateYouRender()
    updateDeliveryRender()
    updateCartRender()
    updateDetailcartRender()
    updateTotalRender()
    updateTermsRender()
    changeItem(itemPayment)
  })
} else {
  getData(_cartId, _country, _dest).then(data => {
    _total = data
    updateCartCountRender()
    updateCartRender()
    changeItem(itemResume)
  })
}

itemResume.on('click', function () {
  getData(_cartId, _country, _dest)
    .then(data => {
      _total = data
      updateCartRender()
    })
})

$('.cart-render').on('click', '.patchproduct', function (event) {
  getCartData(event, $(this).attr('data-id'), $(this).attr('data-action'))
})

$('.cart-render').on('click', '.removecartline', function (event) {
  validateDeleteCartline(event, $(this).attr('data-id'))
})

$('.detailCart-render').on('click', '.patchproduct', function (event) {
  getCartData(event, $(this).attr('data-id'), $(this).attr('data-action'))
})

$('.detailCart-render').on('click', '.removecartline', function (event) {
  validateDeleteCartline(event, $(this).attr('data-id'))
})

function getCartData (event, product, action) {
  event.preventDefault()
  let data = {}
  data.productId = product
  data.typeAction = action
  patchProduct(data)
    .then(() => {
      getData(_cartId, _country, _dest)
        .then(data => {
          _total = data
          if (_total.product === undefined) {
            window.location.reload()
          } else {
            updateCartCountRender()
            updateCartRender()
            updateDetailcartRender()
            updateTotalRender()
          }
        })
    })
}

function deleteCartline (product) {
  let codprd = product
  removeCartline(_cartId, codprd)
    .then(() => {
      getData(_cartId, _country, _dest)
        .then(data => {
          _total = data
          if (_total.product === undefined) {
            window.location.reload()
          } else {
            updateCartCountRender()
            updateCartRender()
            updateDetailcartRender()
            updateTotalRender()
          }
        })
    })
}

function validateDeleteCartline (event, product) {
  event.preventDefault()
  upConfirmbox(i18n.trans('cart.product.wouldremove'))
    .then(() => {
      deleteCartline(product)
    }).catch(() => {
    })
}

$(itemShipping).on('change', '.select.country', function (event) {
  _delivery.paysliv = $(this).val()
  $('.panel.delay').addClass('active')
  updateDelayRender()
})
