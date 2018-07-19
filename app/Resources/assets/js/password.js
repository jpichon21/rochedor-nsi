import { upFlashbag } from './flashbag'
import I18n from './i18n'

let i18n = new I18n()

const form = document.querySelector('form')
const first = document.getElementById('form_password_first')
const second = document.getElementById('form_password_second')

form.addEventListener('submit', event => {
  if (first.value !== second.value) {
    event.preventDefault()
    upFlashbag(i18n.trans('security.password.must.identical'))
  } else if (first.value.length < 7) {
    event.preventDefault()
    upFlashbag(i18n.trans('security.password.must.height.char'))
  }
})