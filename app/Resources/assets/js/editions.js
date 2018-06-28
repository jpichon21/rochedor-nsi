import JsBarcode from 'jsbarcode'

JsBarcode('.barcode').init()

const themesForm = document.querySelector('.filter.themes form')
const filtersForm = document.querySelector('.filters form')

if (themesForm != null) {
  themesForm.onchange = event => {
    const themes = document.querySelectorAll('.filter.themes input:checked')
    const input = document.querySelector('.filter.themes input.value')
    let values = []
    themes.forEach(function (element) {
      values.push(element.value)
    })
    input.value = values.join('|')
    event.currentTarget.submit()
  }
}

if (filtersForm != null) {
  document.querySelector('.filters form').onchange = event => {
    event.currentTarget.submit()
  }
}
