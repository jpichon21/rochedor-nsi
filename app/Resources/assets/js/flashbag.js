const flashbag = document.querySelector('.flashbag')
const message = flashbag.querySelector('.message')
const overlay = flashbag.querySelector('.overlay')
const button = flashbag.querySelector('.button')

export const upFlashbag = html => {
  message.innerHTML = html
  flashbag.classList.add('active')
}

const downFlashbag = () => {
  flashbag.classList.remove('active')
}

button.addEventListener('click', () => { downFlashbag() })
overlay.addEventListener('click', () => { downFlashbag() })
