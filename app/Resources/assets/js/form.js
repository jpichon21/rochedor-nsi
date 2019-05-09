import I18n from './i18n'

let i18n = new I18n()
const form = document.querySelector('form')
const recaptcha = form.querySelector('.g-recaptcha')
const catchMessage = form.querySelector('.catch-message')

if (recaptcha != null) {
  form.addEventListener('submit', event => {
    const recaptchaResponse = recaptcha.querySelector('.g-recaptcha-response')
    if (recaptchaResponse.value === '') {
      event.preventDefault()
      catchMessage.textContent = i18n.trans('form.please.recaptcha')
    }
  })
}
