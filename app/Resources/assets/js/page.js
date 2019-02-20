import { changeCarousel } from './carousel.js'

const body = document.querySelector('body')
const dropdown = document.querySelector('.dropdown')
const items = dropdown.querySelectorAll('.item')

const updateHeightDropdown = () => {
  let active = dropdown.querySelector('.item.active')
  active.style.maxHeight = active.scrollHeight + 'px'
}

export const changeItem = element => {
  if (element[0] !== undefined) {
    // If JQuery Object
    element = element[0]
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
}

items.forEach(item => {
  item.addEventListener('click', () => {
    if (body.classList.contains('order')) {
      if (item.classList.contains('canOpen')) {
        // Open Only If Previous Steps
        changeItem(item)
      }
    } else {
      changeItem(item)
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
