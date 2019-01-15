import { limitMenuReduced } from './variables'

const body = document.querySelector('body')

// Home River Animation

const changeSection = nextPosition => {
  isMoving = true
  body.classList.remove(`section-${prevPosition}-active`)
  body.classList.add(`section-${nextPosition}-active`)
  setTimeout(() => {
    isMoving = false
    prevPosition = nextPosition
  }, 2000)
}

let prevPosition = 1
let nextPosition = null
let isMoving = false

window.onwheel = event => {
  if (!isMoving) {
    nextPosition = event.deltaY > 0 ? prevPosition + 1 : prevPosition - 1
    if (nextPosition !== 0 && nextPosition !== 7) {
      changeSection(nextPosition)
    }
  }
}

// Nouveautés

const title = document.querySelector('.nouveautes h2')

title.onclick = () => {
  if (window.innerWidth < limitMenuReduced) {
    body.classList.toggle('nouveautesOpened')
  }
}
