import { upFlashbag } from './popup'
import I18n from './i18n'

let i18n = new I18n()

const form = document.querySelector('form')
const first = document.getElementById('form_password_first')
const second = document.getElementById('form_password_second')

form.addEventListener('submit', event => {
  if (first.value !== second.value) {
    event.preventDefault()
    upFlashbag(i18n.trans('form.message.passwords_mismatch'))
  } else if (first.value.length < 7) {
    event.preventDefault()
    upFlashbag(i18n.trans('validation.password.length'))
  }
})
