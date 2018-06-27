document.querySelector('.filter.themes form').onchange = event => {
  const themes = document.querySelectorAll('.filter.themes input:checked')
  const input = document.querySelector('.filter.themes input.value')
  let values = []
  themes.forEach(function (element) {
    values.push(element.value)
  })
  input.value = values.join('|')
  event.currentTarget.submit()
}

document.querySelector('.filters form').onchange = event => {
  event.currentTarget.submit()
}
