import { limitMenuReduced } from './variables'

const body = document.querySelector('body')

// Home River Animation

const changeSection = movingDown => {
  nextPosition = movingDown ? prevPosition + 1 : prevPosition - 1
  if (nextPosition !== 0 && nextPosition !== 7) {
    isMoving = true
    body.classList.remove(`section-${prevPosition}-active`)
    body.classList.add(`section-${nextPosition}-active`)
    setTimeout(() => {
      isMoving = false
      prevPosition = nextPosition
    }, 2000)
  }
}

let prevPosition = 1
let nextPosition = null
let isMoving = false

window.onwheel = event => {
  if (
    !body.classList.contains('nouveautesOpened') &&
    !body.classList.contains('menuOpened') &&
    !isMoving
  ) {
    changeSection(event.deltaY > 0)
  }
}

let startMovement = 0
let endMovement = 0

window.ontouchstart = event => {
  startMovement = event.changedTouches[0].pageY
}

window.ontouchend = event => {
  endMovement = event.changedTouches[0].pageY
  if (
    startMovement !== endMovement &&
    !body.classList.contains('nouveautesOpened') &&
    !body.classList.contains('menuOpened') &&
    !isMoving
  ) {
    changeSection(startMovement > endMovement)
  }
}

// Nouveautés

const title = document.querySelector('.nouveautes h2')

title.onclick = () => {
  if (window.innerWidth < limitMenuReduced) {
    body.classList.toggle('nouveautesOpened')
  }
}
