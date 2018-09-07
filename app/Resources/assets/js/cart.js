const { environment } = require(`./environment.${process.env.PAYMENT_ENV}`)
const Sha512 = require('sha512')
const Buffer = require('buffer').Buffer

function host () {
  const host = window.location.hostname
  const protocol = window.location.protocol
  return `${protocol}//${host}`
}

export function placePayment (
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
      PBX_REPONDRE_A: `${host()}/${locale}/order/payment-notify/paybox`,
      PBX_EFFECTUE: `${host()}/${locale}/order/payment-return/paybox/success`,
      PBX_REFUSE: `${host()}/${locale}/order/payment-return/paybox/error`,
      PBX_ANNULE: `${host()}/${locale}/order/payment-return/paybox/cancel`,
      PBX_ATTENTE: `${host()}/${locale}/order/payment-return/paybox/waiting`,
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
    const key = Buffer(environment.pbx_key, 'hex')
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
      return: `${host()}/${locale}/order/payment-return/paypal/success`,
      cancel_return: `${host()}/${locale}/order/payment-return/paypal/cancel`,
      business: environment.pp_email,
      notify_url: `${host()}/${locale}/order/payment-notify/paypal`,
      email: email,
      lc: lang
    }
    const url = Object.keys(params).map(function (k) {
      return encodeURIComponent(k) + '=' + encodeURIComponent(params[k])
    }).join('&')
    window.location.href = environment.pp_url + '?' + url
  }
}
