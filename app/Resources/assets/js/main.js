// Scrollbar

const content = document.querySelector('.content')

if (content !== null) {
  content.style.overflowY = 'scroll'
  content.style.width = 'calc(100% + ' + (content.offsetWidth - content.clientWidth) + 'px)'
}

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

const menus = document.querySelectorAll('[data-menu]')

const handleMenuHover = event => {
  const reference = event.target.getAttribute('data-menu')
  let elements = document.querySelectorAll('[data-menu="' + reference + '"]')
  elements.forEach(element => {
    event.type === 'mouseenter'
      ? element.classList.add('active')
      : element.classList.remove('active')
  })
}

menus.forEach(menu => {
  menu.addEventListener('mouseleave', event => handleMenuHover(event))
  menu.addEventListener('mouseenter', event => handleMenuHover(event))
})

// Selects

document.addEventListener('change', function (event) {
  const element = event.target
  if (element && element.classList.contains('select')) {
    if (element.value !== '') {
      element.classList.add('white')
    }
  }
})
