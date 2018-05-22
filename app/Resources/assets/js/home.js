import $ from 'jquery'
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

function updateHeightWindow() {
    return $(window).height()
}

var hWindow = updateHeightWindow()

var controller = new ScrollMagic.Controller()

var swipe = new TimelineMax().to('.swipe-background, .swipe-section', 1, { x: '-50%' })
var begin = new TimelineMax().to('.overlay-begin, .scroll-down', 1, { opacity: 0 })

new ScrollMagic.Scene({
    triggerElement: '.article-4',
    triggerHook: 'onLeave',
    duration: hWindow - 100
})
    .setTween(swipe)
    .addTo(controller)

new ScrollMagic.Scene({
    triggerElement: '.article-1',
    triggerHook: 'onLeave',
    duration: '500'
})
    .setTween(begin)
    .addTo(controller)

$(window).resize(function () {
    hWindow = updateHeightWindow()
})
