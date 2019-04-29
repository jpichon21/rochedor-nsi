import moment from 'moment'
import { getClient, getDelivery } from './sample'
import { upFlashbag, upConfirmbox } from './popup'
import { upLoader, downLoader } from './loader'
import { changeItem } from './page'
import { serializeArray } from './youmightnotneedjquery'
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
  getCartCount,
  checkMail
} from './order-api.js'


/* Cancel Return */

const _order = JSON.parse(document.querySelector('.order-json').innerHTML.trim())
const _user = JSON.parse(document.querySelector('.user-json').innerHTML.trim())

const cancelReturn = _order !== false && _user !== false

/* Translations */

let i18n = new I18n()

moment.locale(_locale)

/* Countries */

const _countries = JSON.parse(document.querySelector('.countries-json').innerHTML.trim())

/* Variables */

let _you = {}
_you.conData = false
_you.conNews = false
let _delivery = {}
let _total = {}
let _country = 'FR'
let _dest = 'myAdd'
let _RegisterFormSubmit = false

const itemResume = document.querySelector('.item.resume')
const itemConnection = document.querySelector('.item.connection')
const itemCard = document.querySelector('.item.card')
const itemShipping = document.querySelector('.item.shipping')
const itemPayment = document.querySelector('.item.payment')

/* Templates */

const templateWelcome = _.template(document.querySelector('.welcome-template').innerHTML)
const templateCartCount = _.template(document.querySelector('.cartCount-template').innerHTML)
const templateYou = _.template(document.querySelector('.you-template').innerHTML)
const templateDelivery = _.template(document.querySelector('.delivery-template').innerHTML)
const templateTotal = _.template(document.querySelector('.total-template').innerHTML)
const templateYouForm = _.template(document.querySelector('.you-form-template').innerHTML)
const templateConsentForm = _.template(document.querySelector('.consent-form-template').innerHTML)
const templateAdlivForm = _.template(document.querySelector('.adliv-form-template').innerHTML)
const templateDelay = _.template(document.querySelector('.delay-template').innerHTML)
const templateTerms = _.template(document.querySelector('.terms-template').innerHTML)
const templateCart = _.template(document.querySelector('.cart-template').innerHTML)
const templateDetailCart = _.template(document.querySelector('.detailCart-template').innerHTML)

/* Renders */

const renderWelcome = document.querySelector('.welcome-render')
const renderCartCounts = document.querySelectorAll('.cartCount-render')
const renderYou = document.querySelector('.you-render')
const renderDelivery = document.querySelector('.delivery-render')
const renderTotal = document.querySelector('.total-render')
const renderYouForms = document.querySelectorAll('.you-form-render')
const renderConsentForm = document.querySelector('.consent-form-render')
const renderAdlivForm = document.querySelector('.adliv-form-render')
const renderDelay = document.querySelector('.delay-render')
const renderTerms = document.querySelector('.terms-render')
const renderCart = document.querySelector('.cart-render')
const renderDetailCart = document.querySelector('.detailCart-render')

/* Updaters */

const updateWelcomeRender = () => {
  if (_you.prenom !== undefined) {
    renderWelcome.innerHTML = templateWelcome({
      you: _you
    })
  }
}

const updateCartCountRender = () => {
  getCartCount().then((res) => {
    renderCartCounts.forEach(renderCartCount => {
      renderCartCount.innerHTML = templateCartCount({
        cartCount: res
      })
    })
  })
}

const updateYouRender = () => {
  renderYou.innerHTML = templateYou({
    you: _you
  })
}

const updateDeliveryRender = () => {
  renderDelivery.innerHTML = templateDelivery({
    delivery: _delivery,
    you: _you
  })
}

const updateTotalRender = () => {
  renderTotal.innerHTML = templateTotal({
    total: _total
  })
}

const updateYouFormRender = () => {
  renderYouForms.forEach(renderYouForm => {
    renderYouForm.innerHTML = templateYouForm({
      client: _you,
      countries: _countries,
      civilites: [
        i18n.trans('form.civilite.mr'),
        i18n.trans('form.civilite.mme'),
        i18n.trans('form.civilite.abbe'),
        i18n.trans('form.civilite.frere'),
        i18n.trans('form.civilite.pere'),
        i18n.trans('form.civilite.soeur')
      ]
    })
  })
}

const updateConsentFormRender = () => {
  renderConsentForm.innerHTML = templateConsentForm({
    client: _you,
  })
}

const updateAdlivFormRender = () => {
  renderAdlivForm.innerHTML = templateAdlivForm({
    delivery: _delivery,
    countries: _countries
  })
}

const updateDelayRender = () => {
  if (_delivery.paysliv !== '') {
    renderDelay.innerHTML = templateDelay({
      country: _countries.find(country => {
        return country.codpays === _delivery.paysliv
      })
    })
  }
}

const updateTermsRender = () => {
  renderTerms.innerHTML = templateTerms({
    you: _you
  })
}

const updateCartRender = () => {
  let _sum = 0
  renderCart.innerHTML = templateCart({
    product: _total.product,
    sum: _sum
  })
}

const updateDetailcartRender = () => {
  let _sum = 0
  renderDetailCart.innerHTML = templateDetailCart({
    product: _total.product,
    country: _country,
    sum: _sum
  })
}

/* Actions */

const afterLogin = user => {
  _delivery = getDelivery()
  _delivery.codcli = user.codcli
  _delivery.cartId = parseInt(_cartId)
  _you = user
  updateYouRender()
  updateTotalRender()
  updateCartRender()
  updateDetailcartRender()
  updateAdlivForm('myAd')
  changeItem(itemCard).then(() => {
    updateWelcomeRender()
  })
}

const formatParticipant = data => {
  let participant = getClient()
  data.map(obj => {
    participant[obj.name] = obj.value
  })
  participant.codcli = parseInt(participant.codcli)
  return participant
}

itemResume.onclick = event => {
  if (
    event.target &&
    event.target.matches('.continue')
  ) {
    event.preventDefault()
    if(_RegisterFormSubmit === true) {
      updateWelcomeRender()
    } else {
      itemConnection.querySelectorAll('.panel').forEach(panel => panel.classList.remove('active'))
    }
    changeItem(itemConnection)
  }
  if (
    event.target &&
    event.target.matches('h2')
  ) {
    getData(_cartId, _country, _dest).then(data => {
      _total = data
      updateCartRender()
    })
    if(_RegisterFormSubmit === true) {
      updateCartRender()
    }
  }
}

itemConnection.onsubmit = event => {
  if (
    event.target &&
    event.target.matches('.panel.connection form')
  ) {
    event.preventDefault()
    upLoader()
    postLogin({
      username: event.target.querySelector('.username').value,
      password: event.target.querySelector('.password').value
    }).then(user => {
      downLoader()
      afterLogin(user)
    }).catch(() => {
      downLoader()
      upFlashbag(i18n.trans('security.bad_credentials'))
    })
  }
  if (
    event.target &&
    event.target.matches('.panel.reset form')
  ) {
    event.preventDefault()
    upLoader()
    resetLogin({
      email: event.target.querySelector('.email').value,
      firstname: event.target.querySelector('.firstname').value,
      lastname: event.target.querySelector('.lastname').value
    }).then(() => {
      downLoader()
      upFlashbag(i18n.trans('security.check_inbox'))
    }).catch((err) => {
      downLoader()
      upFlashbag(i18n.trans(err))
    })
  }
  if (
    event.target &&
    event.target.matches('.panel.registration form')
  ) {
    validateClient(event, event.target, participant => {
      if(_you.conData === true){
        postRegister({
        client: {
            ...participant,
            rue: participant.adresse,
            conNews: _you.conNews,
            conData: _you.conData
          }
        }).then(user => {
          postLogin({
            username: user.username,
            password: participant.password
          }).then(user => {
            downLoader()
            afterLogin(user)
          }).catch(loginError => {
            downLoader()
            upFlashbag(i18n.trans(`${loginError}`))
          })
        }).catch(registerError => {
          downLoader()
          upFlashbag(i18n.trans(`${registerError}`))
        })
      } else {
        checkMail({
            mail: {
              email: participant.email
            }
        }).then(() => {
          downLoader()
          afterLogin(participant)
        }).catch(mailError => {
          downLoader()
          upFlashbag(i18n.trans(`${mailError}`))
        })
      }
    })
    _RegisterFormSubmit = true
  }
}

itemConnection.onclick = event => {
  if (
    event.target &&
    event.target.matches('a')
  ) {
    event.preventDefault()
    const which = event.target.getAttribute('href').substring(1)
    switch (which) {
      case 'connection':
        itemConnection.querySelectorAll('.panel').forEach(panel => panel.classList.remove('active'))
        itemConnection.querySelector(`.panel.${which}`).classList.add('active')
        changeItem(itemConnection)
        break
      case 'registration':
        _you = getClient()
        updateYouFormRender()
        itemConnection.querySelectorAll('.panel').forEach(panel => panel.classList.remove('active'))
        itemConnection.querySelector(`.panel.${which}`).classList.add('active')
        updateConsentFormRender()
        changeItem(itemConnection)
        break
      case 'reset':
        itemConnection.querySelector('.panel.reset').classList.add('active')
        changeItem(itemConnection)
        break
      case 'continue':
        upLoader()
        if(_you.conData === true || _userConData === 1) {
          getLogin().then(user => {
            downLoader()
            afterLogin(user)
          })
        } else {
          downLoader()
          changeItem(itemCard)
        }
        break
      case 'retry':
        window.location.reload()
      case 'disconnect':
        getLogout(_locale)
        break
    }
  }
  if (
    event.target &&
    event.target.matches('.newfich')
  ) {
    event.preventDefault()
    event.target.classList.toggle('checked')
    itemConnection.querySelector('.newfich-wrapper .checkbox').value = event.target.classList.contains('checked')
  }
  if (
    event.target &&
    event.target.matches('.conData')
  ) {
    event.preventDefault()
    event.target.classList.toggle('checked')
    itemConnection.querySelector('.conData .checkbox').value = event.target.classList.contains('checked')
    _you.conData = event.target.classList.contains('checked')
    updateYouFormRender()
    changeItem(itemConnection)
  }
  if (
    event.target &&
    event.target.matches('.conNews')
  ) {
    event.preventDefault()
    event.target.classList.toggle('checked')
    itemConnection.querySelector('.conNews .checkbox').value = event.target.classList.contains('checked')
    _you.conNews = event.target.classList.contains('checked')
  }
  if (
    event.target &&
    event.target.matches('.panel.reset .cancel')
  ) {
    event.preventDefault()
    itemConnection.querySelector('.panel.reset').classList.remove('active')
    changeItem(itemConnection)
  }
}

const validatePhone = (phone, mobile) => {
  return !(phone === '' && mobile === '')
}

const validateTvaintra = tvaintra => {
  return new Promise((resolve, reject) => {
    checkVat(tvaintra).then(() => {
      resolve()
    }).catch(() => {
      reject(i18n.trans('form.message.zipcode_invalid'))
    })
  })
}

const validatePassword = password => {
  return password.length < 8 && password.length !== 0
    ? i18n.trans('security.password_too_small')
    : true
}

itemCard.onclick = event => {
  if (
    event.target &&
    event.target.matches('.continue')
  ) {
    event.preventDefault()
    updateDelayRender()
    updateTermsRender()
    updateAdlivForm(document.querySelector('.select-adliv').value)
    changeItem(itemShipping).then(() => {
      itemCard.querySelector('.panel.modify').classList.remove('active')
    })
  }
  if (
    event.target &&
    event.target.matches('.modify-you')
  ) {
    event.preventDefault()
    updateYouFormRender()
    itemCard.querySelectorAll('.panel').forEach(panel => panel.classList.remove('active'))
    itemCard.querySelector('.panel.modify').classList.add('active')
    changeItem(itemCard)
  }
  if (
    event.target &&
    event.target.matches('.newfich')
  ) {
    event.preventDefault()
    event.target.classList.toggle('checked')
    itemCard.querySelector('.newfich-wrapper .checkbox').value = event.target.classList.contains('checked')
  }
  if (
    event.target &&
    event.target.matches('.conNews')
  ) {
    event.preventDefault()
    event.target.classList.toggle('checked')
    itemCard.querySelector('.conNews .checkbox').value = event.target.classList.contains('checked')
    _you.conNews = event.target.classList.contains('checked')
  }
}

const validateClient = (event, form, callback) => {
  event.preventDefault()
  const data = serializeArray(form)
  const participant = formatParticipant(data)
  const validatedPhone = validatePhone(participant.tel, participant.mobil)
  if (_you.conData === true) {
    const validatedPassword = validatePassword(participant.password)
    if (validatedPassword !== true) {
      downLoader()
      upFlashbag(validatedPassword)
      return
    }
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

itemCard.onsubmit = event => {
  if (
    event.target &&
    event.target.matches('.panel.modify form')
  ) {
    upLoader()
    validateClient(event, event.target, user => {
      if (_you.conData === true){
        postEditCli({
          client: {
            ...user,
            rue: user.adresse,
            conData: _you.conNews
          }
        }).then(client => {
          downLoader()
          _you = client
          updateYouRender()
          updateDelayRender()
          updateAdlivForm('myAd')
          changeItem(itemShipping).then(() => {
            itemCard.querySelector('.panel.modify').classList.remove('active')
          })
        }).catch(error => {
          downLoader()
          upFlashbag(error)
        })
      } else {
        downLoader()
        _you = user
        updateYouRender()
        updateDelayRender()
        updateAdlivForm('myAd')
        changeItem(itemShipping).then(() => {
          itemCard.querySelector('.panel.modify').classList.remove('active')
        })
      }
    })
  }
}

itemShipping.onchange = event => {
  if (
    event.target &&
    event.target.matches('.select-adliv')
  ) {
    event.preventDefault()
    updateAdlivForm(event.target.value)
    changeItem(itemShipping)
  }
  if (
    event.target &&
    event.target.matches('.select.country')
  ) {
    _delivery.paysliv = event.target.value
    itemShipping.querySelector('.panel.delay').classList.add('active')
    updateDelayRender()
    changeItem(itemShipping)
  }
}

const updateAdlivForm = destliv => {
  switch (destliv) {
    case 'myAd':
      _delivery.adliv.prenom = _you.prenom
      _delivery.adliv.nom = _you.nom
      _delivery.adliv.societe = _you.societe
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
  updateDelayRender()
  updateAdlivFormRender()
  itemShipping.querySelector('.panel.delay').classList.toggle('active', destliv === 'myAd')
  itemShipping.querySelector('.panel.adliv').classList.toggle('active', destliv !== 'myAd')
}

const formatForm = data => {
  let form = {}
  data.map(obj => {
    form[obj.name] = obj.value
  })
  return form
}

const submitFormGift = () => {
  const data = serializeArray(itemShipping.querySelector('form.gift'))
  const gift = formatForm(data)
  _delivery.memocmd = gift.note
}

const submitFormAdliv = () => {
  const data = serializeArray(itemShipping.querySelector('form.adliv'))
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
}

const noEmptyFields = data => {
  const emptyFileds = data.filter(obj => obj === undefined || obj === '')
  return emptyFileds.length === 0
}

const valideDelivery = delivery => {
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

itemShipping.onclick = event => {
  if (
    event.target &&
    event.target.matches('.continue')
  ) {
    event.preventDefault()
    submitFormGift()
    submitFormAdliv()
    upLoader()
    valideDelivery(_delivery).then(delivery => {
      getData(_cartId, delivery.paysliv, delivery.destliv).then(data => {
        downLoader()
        _total = data
        updateTotalRender()
        updateDeliveryRender()
        updateDetailcartRender()
        updateTermsRender()
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
  }
}

const submitFormPayment = () => {
  upLoader()
  if (_you.conData === false) {
    postRegister({
      client: {
          ..._you,
          rue: _you.adresse,
          conNews: _you.conNews,
          conData: _you.conData
        }
      }).then(user => {
          _delivery.email = _you.email
          _delivery.clientId = user.codcli
          postOrder(_delivery).then(response => {
            window.location.href = response
          }).catch(error => {
            downLoader()
            if (error) {
              upFlashbag(error)
            }
          })
      }).catch(error => {
        downLoader()
        upFlashbag(error)
      })
  } else {
    _delivery.email = _you.email
    postOrder(_delivery).then(res => {
      window.location.href = res
    }).catch(error => {
      downLoader()
      if (error) {
        upFlashbag(error)
      }
    })
  }
}

itemPayment.onclick = event => {
  if (
    event.target &&
    event.target.matches('.pay')
  ) {
    event.preventDefault()
    if (_delivery.modpaie === '') {
      upFlashbag(i18n.trans('form.message.modpaie_invalid'))
    } else {
      submitFormPayment()
    }
  }
}

itemPayment.onchange = event => {
  if (
    event.target &&
    event.target.matches('.select-modpaie')
  ) {
    event.preventDefault()
    _delivery.modpaie = event.target.value
    itemPayment.querySelector('.pay').classList.remove('disabled')
  }
}

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

renderCart.onclick = event => {
  if (
    event.target &&
    event.target.matches('.patchproduct')
  ) {
    getCartData(
      event,
      event.target.getAttribute('data-id'),
      event.target.getAttribute('data-action')
    )
  }
  if (
    event.target &&
    event.target.matches('.removecartline')
  ) {
    validateDeleteCartline(event, event.target.getAttribute('data-id'))
  }
}

renderDetailCart.onclick = event => {
  if (
    event.target &&
    event.target.matches('.patchproduct')
  ) {
    getCartData(
      event,
      event.target.getAttribute('data-id'),
      event.target.getAttribute('data-action')
    )
  }
  if (
    event.target &&
    event.target.matches('.removecartline')
  ) {
    validateDeleteCartline(event, event.target.getAttribute('data-id'))
  }
}

const getCartData = (event, product, action) => {
  event.preventDefault()
  let data = {}
  data.productId = product
  data.typeAction = action
  patchProduct(data).then(() => {
    getData(_cartId, _country, _dest).then(data => {
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

const deleteCartline = product => {
  removeCartline(_cartId, product).then(() => {
    getData(_cartId, _country, _dest).then(data => {
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

const validateDeleteCartline = (event, product) => {
  event.preventDefault()
  upConfirmbox(i18n.trans('cart.product.wouldremove')).then(() => {
    deleteCartline(product)
  }).catch(() => {
  })
}

window.addEventListener('pageshow', function (event) {
  const historyTraversal = event.persisted ||
                         (typeof window.performance !== 'undefined' &&
                              window.performance.navigation.type === 2)
  if (historyTraversal) {
    window.location.reload()
  }
})
