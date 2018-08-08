const loader = document.querySelector('.loader')

export const upLoader = () => {
  loader.classList.add('active')
}

export const downLoader = () => {
  loader.classList.remove('active')
}
