export default class I18n {
  constructor (locale = null) {
    if (locale === null) {
      locale = this.guessLocale()
    }
    this.load(locale)
  }
  load (locale) {
    window.fetch(`/xhr/translations/${locale}`).then(res => {
      res.json().then(res => {
        this.messages = res
      })
    })
  }

  trans (key) {
    return (this.messages[key]) ? this.messages[key] : key
  }

  getMessages () {
    return this.messages
  }

  guessLocale () {
    const html = document.getElementsByTagName('html')[0]
    if (html.hasAttribute('lang')) {
      return html.getAttribute('lang')
    }
    let locale
    if (navigator.languages && navigator.languages.length) {
      locale = navigator.languages[0]
    } else if (navigator.userLanguage) {
      locale = navigator.userLanguage
    } else {
      locale = navigator.language
    }
    return locale.substring(0, 2)
  }
}
