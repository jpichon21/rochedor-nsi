const popup = document.querySelector('.popup')
const message = popup.querySelector('.message')
const overlay = popup.querySelector('.overlay')
const buttonY = popup.querySelector('.button.yes')
const buttonN = popup.querySelector('.button.no')
const buttonC = popup.querySelector('.button.continue')
const buttonCC = popup.querySelector('.button.continueCart')

export const upFlashbag = html => {
  message.innerHTML = html
  popup.classList.remove('yes', 'no', 'cart', 'continueCart')
  popup.classList.add('active', 'continue')
  buttonC.addEventListener('click', () => { downPopup() })
}

export const upCartBox = html => {
  message.innerHTML = html
  popup.classList.remove('yes', 'no', 'continue')
  popup.classList.add('active', 'cart', 'continueCart')
  buttonCC.addEventListener('click', () => { downPopup() })
}

export const upConfirmbox = html => {
  return new Promise((resolve, reject) => {
    message.innerHTML = html
    popup.classList.remove('continue', 'cart', 'continueCart')
    popup.classList.add('active', 'yes', 'no')
    buttonY.addEventListener('click', () => { downPopup(); resolve() })
    buttonN.addEventListener('click', () => { downPopup(); reject(new Error()) })
  })
}

const downPopup = () => {
  popup.classList.remove('active')
}

overlay.addEventListener('click', () => { downPopup() })
