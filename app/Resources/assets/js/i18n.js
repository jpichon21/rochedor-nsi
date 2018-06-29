export default class I18n {
  constructor (locale = 'fr') {
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
}
