import $ from 'jquery'
import Velocity from 'velocityjs'
import scrollify from 'jquery-scrollify'
import ScrollMagic from 'scrollmagic'
import 'animation.gsap'
import 'jquery.easing'

/* Scrollify */

$.scrollify({
  section: 'article',
  easing: 'easeInOutQuad',
  scrollSpeed: 2000
})

/* ScrollMagic */

function updateHeightWindow () {
  return $(window).height()
}

var hWindow = updateHeightWindow()

var controller = new ScrollMagic.Controller()

new ScrollMagic.Scene({ triggerElement: '.article-4', triggerHook: 'onLeave', duration: hWindow - 100 })
  .setTween(new TimelineMax().to('.swipe-background, .swipe-section', 1, { x: '-50%' }))
  .addTo(controller)

new ScrollMagic.Scene({ triggerElement: '.article-1', triggerHook: 'onLeave', duration: hWindow })
  .setTween(new TimelineMax().to('.overlay-begin, .scroll-down', 1, { opacity: 0 }))
  .addTo(controller)

function myScene (myHook, index) {
  return new ScrollMagic.Scene({ triggerElement: `.article-${index}`, triggerHook: myHook, duration: hWindow / 3 })
    .setTween(new TimelineMax().to(`.article-${index}`, 1, { opacity: myHook === 'onCenter' ? 1 : 0, ease: 'linear' }))
    .addTo(controller)
}

for (var i = 0; i <= 6; i++) { myScene('onEnter', i); myScene('onCenter', i); myScene('onLeave', i) }

$(window).resize(function () {
  hWindow = updateHeightWindow()
})
