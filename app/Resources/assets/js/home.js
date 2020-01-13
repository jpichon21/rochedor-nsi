import { limitMenuReduced } from './variables'
import { large } from './variables'

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
const content = document.querySelector('.nouveautes .cnews .text p')
const header = document.querySelector('.header')
const logo = document.querySelector('.logo')
var buttons = document.querySelectorAll('.cnews .button')

window.onresize = switchButtonClass();

function switchButtonClass(){
  if (window.innerWidth <= large) {
    for (var i = 0; i < buttons.length; ++i ) {
      buttons[i].classList.add('yellow');
      buttons[i].classList.remove('black');
    }
  }

  if (window.innerWidth > large) {
    for (var i = 0; i < buttons.length; ++i ) {
      console.log('test');
      buttons[i].classList.remove('yellow');
      buttons[i].classList.add('black');
    }
  }
}


if (title !== null) {
  title.onclick = () => {
    if (window.innerWidth < limitMenuReduced) {
      body.classList.toggle('nouveautesOpened')
      header.classList.add('headerVisible')
      logo.classList.add('logoVisible')
    }
  }
}

if (content !== null) {
  content.onclick = () => {
    if (window.innerWidth < limitMenuReduced) {
      body.classList.toggle('nouveautesOpened')
      header.classList.add('headerVisible')
      logo.classList.add('logoVisible')
    }
  }
}

const updateNewsNavPosition = () => {
 /* if($('.nouveautes > div.cnews.active > .text.with-link').length) {
    $('.nouveautes div.news_nav').css('bottom', '16px')
  }
  else {
    $('.nouveautes div.news_nav').css('bottom', '0')
  }*/
}
const changeNews = (direction = 'next') => {
  const current = $('.nouveautes > div.cnews.active').data('index')

  let next
  if (direction === 'next') {
    next = (current + 1) % $('.nouveautes > div.cnews').length
  } else if(current === 0) {
    next = $('.nouveautes > div.cnews').length - 1
  } else {
    next = (current - 1) % $('.nouveautes > div.cnews').length
  }

  /*$('.nouveautes').css('height', $('.nouveautes').prop('scrollHeight') + 'px')
  $('.nouveautes > .news_nav').fadeOut(100)
  $('.nouveautes > div[data-index=' + current + ']').fadeOut(300).delay(300).removeClass('active')
  $('.nouveautes > div[data-index=' + next + ']').delay(300).fadeIn(300).addClass('active')*/

  $('.nouveautes > div[data-index=' + current + ']').removeClass('active')
  $('.nouveautes > div[data-index=' + next + ']').addClass('active')
  $('.active > .text p').delay(1000).css({'opacity': '1','transition': '1s ease'})


  // edge case for nav
  
  setTimeout(() => {
    updateNewsNavPosition()
    $('.nouveautes > .news_nav').fadeIn(300)
  }, 150)
  setTimeout(() => $('.nouveautes').css('height', ''), 300)
}

$('.nouveautes > .news_nav .news_nav_next').on('click', e => {
  e.preventDefault()
  changeNews('next')
})
$('.nouveautes > .news_nav .news_nav_prev').on('click', e => {
  e.preventDefault()
  changeNews('prev')
})

updateNewsNavPosition()

