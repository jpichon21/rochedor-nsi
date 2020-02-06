import $ from 'jquery'

const passwordPopupToggle = $("#passwordPopupToggle")

passwordPopupToggle.on('click', function (event) {
  event.preventDefault()
  console.log('click!');
  passwordPopup();
})

function passwordPopup(){
  target = document.getElementById(passwordPopup);
  target.addClass('popup-visible');
}

console.log('file loaded!');