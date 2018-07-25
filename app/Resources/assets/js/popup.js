const popup = document.querySelector('.popup')
const message = popup.querySelector('.message')
const overlay = popup.querySelector('.overlay')
const buttonY = popup.querySelector('.button.yes')
const buttonN = popup.querySelector('.button.no')
const buttonC = popup.querySelector('.button.continue')

export const upFlashbag = html => {
  message.innerHTML = html
  popup.classList.remove('confirmbox')
  popup.classList.add('active', 'flashbag')
  buttonC.addEventListener('click', () => { downPopup() })
}

export const upConfirmbox = html => {
  return new Promise((resolve, reject) => {
    message.innerHTML = html
    popup.classList.remove('flashbag')
    popup.classList.add('active', 'confirmbox')
    buttonY.addEventListener('click', () => { downPopup(); resolve() })
    buttonN.addEventListener('click', () => { downPopup(); reject(new Error()) })
  })
}

const downPopup = () => {
  popup.classList.remove('active')
}

overlay.addEventListener('click', () => { downPopup() })
