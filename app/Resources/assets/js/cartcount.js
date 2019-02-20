import $ from 'jquery'
import { getCartCount } from './order-api.js'

const cartCountTemplate = _.template($('.cartCount-template').html())
function updateCartCountRender () {
  getCartCount().then((res) => {
    $('.cartCount-render').html(cartCountTemplate({
      cartCount: res
    }))
  })
}

updateCartCountRender()
