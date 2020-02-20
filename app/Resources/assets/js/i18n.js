export default class I18n {
  constructor (locale = null) {
    if (locale === null) {
      locale = this.guessLocale()
    }
    this.load()
  }
  load () {
    this.messages = JSON.parse(document.getElementById('#translationsData').getAttribute('data-translations'))
  }

  trans (key) {
    if (this.messages) {
      return (this.messages[key]) ? this.messages[key] : key
    }
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
