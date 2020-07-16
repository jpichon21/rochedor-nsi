import createFocusTrap from 'focus-trap'
import {
  limitMenuReduced,
  mobile
} from './variables'

window.onpageshow = event => {
  if (typeof window.performance != 'undefined') {
    var perfEntries = window.performance.getEntriesByType('navigation')
    if (perfEntries[0].type === 'back_forward') {
      window.location.reload()
    }
  }
}

// Zoom

const bodyClass = document.querySelector('body').classList
const dropdown = document.querySelector('.dropdown')

const updateHeightDropdown = () => {
  if (dropdown !== null) {
    let active = dropdown.querySelector('.active')
    active.style.maxHeight = active.scrollHeight + 'px'
  }
}

const handleClickZoomMinus = () => {
  const a = 'zoom-4x';
  const b = 'zoom-2x'
  if (bodyClass.contains(a)) {
    bodyClass.remove(a);
    bodyClass.add(b)
  } else {
    if (bodyClass.contains(b)) {
      bodyClass.remove(b)
    }
  }
  updateHeightDropdown()
}

const handleClickZoomPlus = () => {
  const a = 'zoom-2x';
  const b = 'zoom-4x'
  if (bodyClass.contains(a)) {
    bodyClass.remove(a);
    bodyClass.add(b)
  } else {
    if (!bodyClass.contains(b)) {
      bodyClass.add(a)
    }
  }
  updateHeightDropdown()
}

const zoomMinus = document.querySelector('.zoom .minus')
const zoomPlus = document.querySelector('.zoom .plus')

zoomMinus.onclick = () => {
  handleClickZoomMinus()
}
zoomPlus.onclick = () => {
  handleClickZoomPlus()
}

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
    event.type === 'mouseenter' ?
      element.classList.add('active') :
      element.classList.remove('active')
  })
}


const handleMenuClick = event => {
  const element = event.target;
  const parent = element.parentNode;
  const menu = parent.parentNode;
  const submenu = parent.querySelector('.sub-menu');
  if (event.type === 'click') {
    if (submenu.classList.contains('active')) {
      submenu.classList.remove('active');
    } else {
      const allSubMenus = menu.querySelectorAll('.sub-menu');
      allSubMenus.forEach(element => {
        element.classList.remove('active');
      });
      submenu.classList.toggle('active');
    }
  }
  /*const menu = event.target.parentNode
  const reference = menu.getAttribute('data-menu')
  let elements = document.querySelectorAll('[data-menu="' + reference + '"]')
  let elementsAll = document.querySelectorAll('[data-menu]');
  elementsAll.forEach(element => {
    element.classList.remove('active')
  })
  elements.forEach(element => {
    if (event.type === 'click') {
      element.classList.toggle('active')
    }
  })*/
}

const handleMenuEvent = event => {
  window.innerWidth >= limitMenuReduced ?
    handleMenuHover(event) :
    handleMenuClick(event)
}

const handleHeaderHover = event => {
  if (window.innerWidth >= limitMenuReduced) {
    event.type === 'mouseenter' ?
      body.classList.add('menuOpened') :
      body.classList.remove('menuOpened')
  }
}

const handleWindowResize = () => {
  if (window.innerWidth < limitMenuReduced) {
    // Sur mobile, cette classe empêche l'image de fond de recouvrir tout l'écran
    // Anomalie 45 : supprimer le fond noir.
    // if (window.innerWidth > mobile) {
    //   body.classList.add('menuReduced')
    // }
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
}

menus.forEach(menu => {
  menu.onmouseleave = event => handleMenuEvent(event)
  menu.onmouseenter = event => handleMenuEvent(event)
  menu.onclick = event => handleMenuEvent(event)
})

header.onmouseleave = event => handleHeaderHover(event)
header.onmouseenter = event => handleHeaderHover(event)

window.onresize = () => {
  handleWindowResize()
  updateHeightDropdown()
}

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

// Tabs

let formLock = false

function getForm(element) {
  return element.tagName === 'FORM' ? element : getForm(element.parentElement)
}

document.onkeydown = event => {
  if (event.which === 9 && !formLock) {
    event.preventDefault()
  }
}

let focusTrap

document.onclick = event => {
  const element = event.target
  if (
    element &&
    !element.classList.contains('submit') &&
    (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA')
  ) {
    const form = getForm(element)
    if (formLock) {
      focusTrap.deactivate()
    }
    focusTrap = createFocusTrap(form, {
      onActivate: () => {
        formLock = true
      },
      onDeactivate: () => {
        formLock = false
      },
      clickOutsideDeactivates: true,
      returnFocusOnDeactivate: null
    })
    form.onsubmit = () => {
      focusTrap.deactivate()
    }
    focusTrap.activate()
  }
}

// Action filters

const showFilters = document.querySelector('.show-filters')
const hideFilters = document.querySelector('.hide-filters')
const filters = document.querySelector('.filters')

if (showFilters != null) {
  showFilters.onclick = function () {
    filters.classList.toggle('active')
  }
}

if (hideFilters != null) {
  hideFilters.onclick = function () {
    filters.classList.remove('active')
  }
}

// Toggle password
content.onclick = event => {
  if (event.target.classList.contains('toggle-password')) {
    event.preventDefault()
    togglePasswordVisibility(event.target.previousElementSibling)
  }
}

function togglePasswordVisibility (el) {
  if (el.getAttribute('type') === 'password') {
    el.setAttribute('type', 'text')
  } else {
    el.setAttribute('type', 'password')
  }
}
