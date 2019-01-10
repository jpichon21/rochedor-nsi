import $ from 'jquery'
import Velocity from 'velocityjs'
import scrollify from 'jquery-scrollify'
import ScrollMagic from 'scrollmagic'
import 'animation.gsap'
import 'jquery.easing'
import { limitMenuReduced } from './variables'

// Scrollify

$.scrollify({
  section: 'article',
  easing: 'easeInOutCubic',
  scrollSpeed: 2000,
  updateHash: false
})

// ScrollMagic

function updateHeightWindow () {
  return $(window).height()
}

var hWindow = updateHeightWindow()

var controller = new ScrollMagic.Controller()

new ScrollMagic.Scene({ triggerElement: '.article-3', triggerHook: 'onLeave', duration: hWindow - 100 })
  .setTween(new TimelineMax().to('.swipe-background, .swipe-section', 1, { x: '-50%' }))
  .addTo(controller)

new ScrollMagic.Scene({ triggerElement: '.article-1', triggerHook: 'onLeave', duration: hWindow })
  .setTween(new TimelineMax().to('.overlay-begin', 1, { opacity: 0 }))
  .addTo(controller)

function myScene (myHook, index) {
  return new ScrollMagic.Scene({ triggerElement: `.article-${index}`, triggerHook: myHook, duration: hWindow / 3 })
    .setTween(new TimelineMax().to(`.article-${index}`, 1, { opacity: myHook === 'onCenter' ? 1 : 0 }))
    .addTo(controller)
}

for (var i = 1; i <= 6; i++) { myScene('onEnter', i); myScene('onCenter', i); myScene('onLeave', i) }

$(window).resize(function () {
  hWindow = updateHeightWindow()
})

// Nouveautés

$('.nouveautes h2').click(function () {
  if (window.innerWidth < limitMenuReduced) {
    $('body').toggleClass('nouveautesOpened')
  }
})
