import $ from 'jquery'
import moment from 'moment'
import { getParticipant, getDelivery } from './sample'
import { placePayment } from './cart.js'
import {
  getLogin,
  getLogout,
  getData,
  postRegister,
  postLogin,
  postOrder,
  checkZipcode
} from './order-api.js'
const _infos = JSON.parse($('.infos-json').html())

/* Translations */

const _translations = JSON.parse($('.translations-json').html())
const _cartId = parseInt($('.cart-data').html(), 10)

moment.locale(_translations.locale)

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
    changeItem($('.dropdown .item:first'))
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

/* Variables */

let _you = {}
let _delivery = {}
let _cartInfo = {}
let _client = {}

const itemConnection = $('.item.connection')
const itemOrder = $('.item.order')
const itemValidation = $('.item.validation')
const itemCart = $('.item.cart')
const itemPayment = $('.item.payment')

/* Renders */

const youTemplate = _.template($('.you-template').html())
const cartTemplate = _.template($('.cart-template').html())
const deliveryTemplate = _.template($('.delivery-template').html())
const youFormTemplate = _.template($('.you-form-template').html())

function updateYouRender () {
  $('.you-render').html(youTemplate({ you: _you }))
}

// function updateClientsRender () {
//   $('.clients-render').html(cartTemplate({ delivery: _delivery, translations: _translations, cartInfo: _cartInfo }))
// }

function updateCartRender () {
  $('.cart-render').html(cartTemplate({ cartInfo: _cartInfo }))
}

function updateDeliveryRender () {
  $('.delivery-render').html(deliveryTemplate({ delivery: _delivery, you: _you }))
}

function updateYouFormRender () {
  $('.you-form-render').html(youFormTemplate({ client: _client }))
}

updateCartRender()
console.log('Cart')
updateDeliveryRender()
console.log('Delivery')

/* Actions */
itemOrder.on('change', '.select.adliv', function (event) {
  event.preventDefault()
  const data = $(this).val()
  let selectedVal = data
  _delivery.destliv = data
  if (selectedVal === 'Roche' ||  selectedVal === 'Font' || selectedVal === 'myAdd') {
    _delivery.adliv.adresse = _you.adresse
    _delivery.adliv.zipcode = _you.cp
    _delivery.adliv.city = _you.ville
    $(`.panel.adliv`, itemOrder).removeClass('active')
  } else {
    $(`.panel.adliv`, itemOrder).addClass('active')
  }
  if (selectedVal === 'myAdd' || selectedVal === 'Other') {
    $(`.panel.countryliv`, itemOrder).addClass('active')
  } else {
    $(`.panel.countryliv`, itemOrder).removeClass('active')
  }
  if (selectedVal === 'Roche' || selectedVal === 'Font') {
    _delivery.paysliv = 'FR'
  }
  changeItem(itemOrder)
})

itemPayment.on('click', '.button.submit.process-order', function (event) {
  _delivery.cartId = _cartId
  console.log(_cartId)
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

itemOrder.on('change', '.select.country', function (event) {
  event.preventDefault()
  const data = $(this).val()
  _delivery.paysliv = data
  console.log(_delivery)
})

itemPayment.on('change', '.select.modpaie', function (event) {
  event.preventDefault()
  const data = $(this).val()
  _delivery.modpaie = data
  console.log(data)
  console.log(_delivery)
})

itemOrder.on('submit', '.panel.adliv form', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  let dataVal = data
  _delivery.adliv.adresse = dataVal[0].value
  _delivery.adliv.zipcode = dataVal[1].value
  _delivery.adliv.city = dataVal[2].value
  console.log(_delivery)
})

itemOrder.on('submit', '.panel.adliv form', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  let dataVal = data
  _delivery.adliv.adresse = dataVal[0].value
  _delivery.adliv.zipcode = dataVal[1].value
  _delivery.adliv.city = dataVal[2].value
  console.log(_delivery)
})

itemOrder.on('click', '.button.payment', function (event) {
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
        console.log(data)
        console.log(_delivery)
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

itemOrder.on('click', '.button.gift', function (event) {
  event.preventDefault()
  $(`.panel.gift`, itemOrder).addClass('active')
  changeItem(itemOrder)
})

itemOrder.on('click', '.button.reset_gift', function (event) {
  event.preventDefault()
  delete _delivery.memocmd
  $(`.input.note`, itemOrder).value = ''
  console.log(_delivery)
  changeItem(itemOrder)
})

itemOrder.on('submit', '.form.gift', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  let dataVal = data
  _delivery.memocmd = dataVal[0].value
  $(`.panel.gift`, itemOrder).removeClass('active')
  changeItem(itemOrder)
  console.log(_delivery)
})

itemOrder.on('click', 'a', function (event) {
  event.preventDefault()
  $('a', itemOrder).removeClass('active')
  $(this).addClass('active')
  const which = $(this).attr('href').substring(1)
  switch (which) {
    case 'connection':
    case 'registration':
      $('.panel', itemOrder).hide()
      $(`.panel.${which}`, itemOrder).show()
      _client = getParticipant()
      updateYouFormRender()
      break
    case 'continue':
      getLogin().then(user => afterLogin(user))
      break
    case 'disconnect':
      getLogout()
      break
  }
  changeItem(itemPayment)
})

itemPayment.on('click', 'a', function (event) {
  event.preventDefault()
  $('a', itemPayment).removeClass('active')
  $(this).addClass('active')
  const which = $(this).attr('href').substring(1)
  switch (which) {
    case 'connection':
    case 'registration':
      $('.panel', itemPayment).hide()
      $(`.panel.${which}`, itemPayment).show()
      _client = getParticipant()
      updateYouFormRender()
      break
    case 'continue':
      getLogin().then(user => afterLogin(user))
      break
    case 'disconnect':
      getLogout()
      break
  }
  changeItem(itemOrder)
})

itemCart.on('click', 'a.button.continue', function (event) {
  event.preventDefault()
  $('a', itemCart).removeClass('active')
  $(this).addClass('active')
  const which = $(this).attr('href').substring(1)
  switch (which) {
    case 'connection':
    case 'registration':
      $('.item.cart', itemCart).hide()
      $(`.panel.${which}`, itemCart).show()
      _client = getParticipant()
      updateYouFormRender()
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

function afterLogin (user) {
  _delivery = getDelivery()
  _delivery.codcli = user.codcli
  console.log(_delivery)
  _you = user
  console.log(_you)
  updateYouRender()
  updateCartRender()
  changeItem(itemOrder)
}

function formatParticipant (data) {
  let client = getParticipant()
  data.map(obj => {
    client[obj.name] = obj.value
  })
  client.codco = parseInt(client.codco)
  client.datnaiss = moment(client.datnaiss, 'DD/MM/YYYY').format()
  return client
}

itemConnection.on('submit', '.panel.connection form', function (event) {
  event.preventDefault()
  postLogin({
    username: $('.username', this).val(),
    password: $('.password', this).val()
  }).then(user => {
    afterLogin(user)
  })
})

itemConnection.on('submit', '.panel.registration form', function (event) {
  event.preventDefault()
  const data = $(this).serializeArray()
  const client = formatParticipant(data)
  console.log(client)
  postRegister({
    client: client
  }).then(user => {
    postLogin({
      username: client.email,
      password: client.password
    }).then(user => {
      afterLogin(user)
    })
  }).catch(error => {
    $('.catch-message', itemConnection).html(error)
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
      _client = getParticipant()
      updateYouFormRender()
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

itemOrder.on('click', '.modify-you', function (event) {
  event.preventDefault()
  _client = _you
  $('.panel', itemOrder).hide()
  $(`.panel.you`, itemOrder).show()
  updateYouFormRender()
  changeItem(itemOrder)
})

// itemOrder.on('submit', '.form.input.button.submit') {
//   event.preventDefault()
//   const data = $(this).serializeArray()
//   const client = formatParticipant(data)
//   postRegister({
//     client: client
//   }).then(user => {
//     postLogin({
//       username: client.email,
//       password: client.password
//     }).then(user => {
//       afterLogin(user)
//     })
//   }).catch(error => {
//     $('.catch-message', itemConnection).html(error)
//   })
// }
