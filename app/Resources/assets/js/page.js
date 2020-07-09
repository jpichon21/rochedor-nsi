import { changeCarousel } from './carousel.js'
import $ from 'jquery'

window.onpageshow = event => {
  if (typeof window.performance != 'undefined') {
    var perfEntries = window.performance.getEntriesByType('navigation')
    if (perfEntries[0].type === 'back_forward') {
      window.location.reload()
    }
  }
}

$(document).ready(function () {
  $('body').fadeIn(300);
  $('body').removeClass('hidden');
});

const body = document.querySelector('body')
const dropdown = document.querySelector('.dropdown')
const items = dropdown.querySelectorAll('.item')

const updateHeightDropdown = () => {
  let active = dropdown.querySelector('.item.active')
  active.style.maxHeight = active.scrollHeight + 'px'
}

if (items.length === 1) {
  items.forEach(item => {
    item.classList.add('active')
  })
}

export const changeItem = element => {
  return new Promise((resolve, reject) => {
    if (element[0] !== undefined) {
      element = element[0] // If JQuery Object
    }
    let canOpen = true
    items.forEach(item => {
      item.style.maxHeight = null
      item.classList.remove('active')
      if (body.classList.contains('order')) {
        if (canOpen) {
          item.classList.add('canOpen')
          if (item === element) {
            canOpen = false
          }
        } else {
          item.classList.remove('canOpen')
        }
      }
    })
    element.classList.add('active')
    updateHeightDropdown()
    let reference = element.getAttribute('data-carousel-id')
    if (reference) {
      changeCarousel(reference)
    }
    setTimeout(() => { resolve() }, 800)
  })
}

items.forEach(item => {
  const h2 = item.querySelector('h2')
  h2.addEventListener('click', () => {
    if (!item.classList.contains('active')) {
      if (body.classList.contains('order')) {
        if (item.classList.contains('canOpen')) {
          changeItem(item)
        }
      } else {
        changeItem(item)
      }
    }
  })
})

document.addEventListener('DOMContentLoaded', () => {
  let item = window.location.hash !== ''
    ? dropdown.querySelector(`.item[data-slug-id="${window.location.hash}"]`)
    : dropdown.querySelector('.item.open')
  if (item !== null) {
    setTimeout(() => {
      changeItem(item)
    }, 500)
  }
})

const inputs = dropdown.querySelectorAll('.input')

inputs.forEach(input => {
  input.setAttribute('autocomplete', 'nope')
})
