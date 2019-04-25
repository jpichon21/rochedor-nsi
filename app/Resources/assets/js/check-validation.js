
const _path = document.querySelector('.path-json').innerHTML.trim()
const _basePath = document.querySelector('.basePath-json').innerHTML.trim()
var url = _basePath+_path
history.pushState(null, null, location.href)
    window.onpopstate = function () {
        window.location.href = url
    }