import $ from 'jquery'
import moment from 'moment'
import 'clndr'
import query from 'sql-js'
import { retreatsData } from './sample'

moment.locale('fr')

const dateToday = moment().format('LL')
const dateOneYear = moment().add(1, 'y').format('LL')

$('.date-in span').text(dateToday)
$('.date-out span').text(dateOneYear)

$('.calendar-in').clndr({
  moment: moment,
  template: $('.calendar-template').html(),
  clickEvents: {
    click: function (target) {
      $('.date-in').text('Du ' + moment(target.date._i).format('LL')).addClass('active')
      $('.calendar-in').removeClass('active')
      $('.calendar-out').addClass('active')
    }
  }
})

$('.calendar-out').clndr({
  moment: moment,
  template: $('.calendar-template').html(),
  clickEvents: {
    click: function (target) {
      $('.date-out').text('Au ' + moment(target.date._i).format('LL')).addClass('active')
      $('.calendar-out').removeClass('active')
      $('.filter.dates').removeClass('active')
    }
  }
})

$('.date-in').on('click', function () {
  $('.calendar-in').toggleClass('active')
  $('.calendar-out').removeClass('active')
})

$('.date-out').on('click', function () {
  $('.calendar-out').toggleClass('active')
})

$('.filter').on('click', function () {
  if ($(this).hasClass('active')) {
    if (!$(this).hasClass('dates')) {
      $(this).removeClass('active')
    }
  } else {
    $('.filter').removeClass('active')
    $(this).addClass('active')
  }
})

$('.filter').on('change', function () {
  const values = []
  $(this).find('input:checked').each(function (index) {
    values.push($(this).val())
  })
  const count = values.length
  $('.value .count', $(this)).text(count)
  $('.value div', $(this)).removeClass('active')
  if (count === 0) {
    $('.value .default', $(this)).addClass('active')
  } else if (count === 1) {
    $('.value .singular', $(this)).addClass('active')
  } else {
    $('.value .plural', $(this)).addClass('active')
  }
  updateFilters()
})

$('.filters .keywords input').on('focus', function () {
  if ($(this).val() === 'Mot clé') {
    $(this).val('').addClass('active')
  }
})

$('.filters .keywords input').on('blur', function () {
  if ($(this).val() === '') {
    $(this).val('Mot clé').removeClass('active')
  }
})

const retreatsTemplate = _.template($('.retreats-template').html())

function updateRetreats (data) {
  $('.retreats-table tbody').html(retreatsTemplate({
    retreats: data
  }))
}

updateRetreats(retreatsData)

function applyFilters (filters) {
  const retreats = retreatsData.filter((retreat) => {
    if (filters['site'].length === 0) { return true }
    return filters['site'].indexOf(retreat.site.abbr) >= 0
  }).filter((retreat) => {
    if (filters['type'].length === 0) { return true }
    return filters['type'].indexOf(retreat.type.value) >= 0
  }).filter((retreat) => {
    if (filters['speaker'].length === 0) { return true }
    return filters['speaker'].indexOf(retreat.speaker.value) >= 0
  }).filter((retreat) => {
    if (filters['translation'].length === 0) { return true }
    return filters['translation'].indexOf(retreat.translation) >= 0
  })
  console.log(retreats)
  updateRetreats(retreats)
}

function updateFilters () {
  let filtersActived = {
    site: [],
    type: [],
    speaker: [],
    translation: []
  }
  $('.filter').each(function () {
    const values = $(this).serializeArray()
    $.each(values, function (key, elmt) {
      filtersActived[elmt.name].push(elmt.value)
    })
  })
  console.log(filtersActived)
  applyFilters(filtersActived)
}
