const Nightmare = require('nightmare')
const addContext = require('mochawesome/addContext')

const imgDir = './tests/reports/screenshots/'
const baseUrl = 'https://staging.rochedor.fr'
const viewports = [
  { width: 360, height: 640 },
  { width: 1366, height: 768 },
  { width: 1920, height: 1080 }
]
const pages = [
  { 'name': 'home-fr', 'url': '/fr' },
  { 'name': 'home-en', 'url': '/en' },
  { 'name': 'home-es', 'url': '/es' },
  { 'name': 'home-de', 'url': '/de' },
  { 'name': 'home-it', 'url': '/it' }
]
viewports.forEach((viewport) => {
  describe(`Captures ${viewport.width}x${viewport.height}`, function () {
    let nightmare = null
    beforeEach(() => {
      nightmare = new Nightmare({ waitTimeout: 2000 })
      nightmare.viewport(viewport.width, viewport.height)
    })
    pages.forEach((page) => {
      this.timeout('30s')
      const url = `${baseUrl}${page.url}`
      describe(`${page.name} ${url}`, () => {
        it(`la page ${url} doit se charger sans erreur`, function (done) {
          addContext(this, `./screenshots/${page.name}-${viewport.width}x${viewport.height}.png`)
          nightmare
            .goto(url)
            .wait('.logo')
            .screenshot(`${imgDir}${page.name}-${viewport.width}x${viewport.height}.png`)
            .end()
            .then(result => { done() })
            .catch(done)
        })
      })
    })
  })
})
