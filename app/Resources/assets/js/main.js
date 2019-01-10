import { limitMenuReduced } from './variables'

// Zoom

const bodyClass = document.querySelector('body').classList
const dropdown = document.querySelector('.dropdown')

const updateHeightDropdown = () => {
  if (dropdown) {
    let active = dropdown.querySelector('.active')
    active.style.maxHeight = active.scrollHeight + 'px'
  }
}

const handleClickZoomMinus = () => {
  const a = 'zoom-4x'; const b = 'zoom-2x'
  if (bodyClass.contains(a)) { bodyClass.remove(a); bodyClass.add(b) } else { if (bodyClass.contains(b)) { bodyClass.remove(b) } }
  updateHeightDropdown()
}

const handleClickZoomPlus = () => {
  const a = 'zoom-2x'; const b = 'zoom-4x'
  if (bodyClass.contains(a)) { bodyClass.remove(a); bodyClass.add(b) } else { if (!bodyClass.contains(b)) { bodyClass.add(a) } }
  updateHeightDropdown()
}

const zoomMinus = document.querySelector('.zoom .minus')
const zoomPlus = document.querySelector('.zoom .plus')

zoomMinus.onclick = () => { handleClickZoomMinus() }
zoomPlus.onclick = () => { handleClickZoomPlus() }

// Menu

const body = document.querySelector('body')
const content = document.querySelector('.content')
const header = document.querySelector('.header')
const menus = document.querySelectorAll('[data-menu]')

const handleMenuHover = event => {
  const menu = event.target
  const reference = menu.getAttribute('data-menu')
  let elements = document.querySelectorAll('[data-menu="' + reference + '"]')
  elements.forEach(element => {
    event.type === 'mouseenter'
      ? element.classList.add('active')
      : element.classList.remove('active')
  })
}

const handleMenuClick = event => {
  const menu = event.target.parentNode
  const reference = menu.getAttribute('data-menu')
  let elements = document.querySelectorAll('[data-menu="' + reference + '"]')
  elements.forEach(element => {
    if (event.type === 'click') {
      element.classList.toggle('active')
    }
  })
}

const handleMenuEvent = event => {
  window.innerWidth >= limitMenuReduced
    ? handleMenuHover(event)
    : handleMenuClick(event)
}

const handleHeaderHover = event => {
  if (window.innerWidth >= limitMenuReduced) {
    event.type === 'mouseenter'
      ? body.classList.add('menuOpened')
      : body.classList.remove('menuOpened')
  }
}

const handleWindowResize = () => {
  if (window.innerWidth < limitMenuReduced) {
    body.classList.add('menuReduced')
    if (content !== null) {
      content.style.overflowY = 'auto'
      content.style.width = '100%'
    }
  } else {
    body.classList.remove('menuReduced')
    if (content !== null) {
      content.style.overflowY = 'scroll'
      content.style.width = 'calc(100% + ' + (content.offsetWidth - content.clientWidth) + 'px)'
    }
  }
  updateHeightDropdown()
}

menus.forEach(menu => {
  menu.onmouseleave = event => handleMenuEvent(event)
  menu.onmouseenter = event => handleMenuEvent(event)
  menu.onclick = event => handleMenuEvent(event)
})

header.onmouseleave = event => handleHeaderHover(event)
header.onmouseenter = event => handleHeaderHover(event)

window.onresize = () => handleWindowResize()

handleWindowResize()

const burger = header.querySelector('.burger')

burger.onclick = () => {
  body.classList.toggle('menuOpened')
}

// Selects

document.onchange = event => {
  const element = event.target
  if (element && element.classList.contains('select')) {
    if (element.value !== '') {
      element.classList.add('white')
    }
  }
}

// Action filters

const showFilters = document.querySelector('.show-filters')
const filters = document.querySelector('.filters')

if (showFilters != null) {
  showFilters.onclick = function () {
    filters.classList.toggle('active')
  }
}
