const Nightmare = require('nightmare')
const addContext = require('mochawesome/addContext')
const sinon = require('sinon')
const request = require('request')
const chai = require('chai')
const should = chai.should()

const imgDir = './tests/reports/screenshots/inscription'

describe('Load Page', function () {
  this.timeout('30s')
  let nightmare = null
  beforeEach(() => {
    nightmare = new Nightmare({ show: true })
  })
  describe('L\'inscription doit se dérouler sans erreur', () => {
    it('Les actions suivantes doivent se derouler sans timout', done => {
      nightmare
        .goto('http://127.0.0.1:8001/contact-ft')
        .wait('.panel.active')
        .type('#contact_name', 'Martin')
        .type('#contact_surname', 'Martin')
        .type('#contact_adress', '10 Rue des Martingales ')
        .type('#contact_city', 'Saint-Martin')
        .type('#contact_zipcode', '978000')
        .type('#contact_mail', 'Martin@gmail.com')
        .type('#contact_body', 'Je suis monsieur Martin')
        .click('#contact_save')
        .end()
        .then(function (result) { done() })
        .catch(done)
    })
    describe('Le mail doit etre reçu', () => {
      it('Il s\'agit du bonne email', (done) => {
        request('http://127.0.0.1:8083/api/emails', function (err, res, body) {
          res.statusCode.should.eql(200)
          res.headers['content-type'].should.contain('application/json')
          body = JSON.parse(body)
          body[0].subject.should.eql('contact.ro.foradmin.subjectMartin Martin')
          body[0].headers.from.should.eql('Logomotion <technique@logomotionfr>')
          done()
        })
      })
    })
  })
})
