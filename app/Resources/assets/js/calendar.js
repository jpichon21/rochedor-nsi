import $ from 'jquery'
import moment from 'moment'
import 'clndr'

/* Filters */

$('.filter')

  .on('click', function () {
    if ($(this).hasClass('active')) {
      $(this).removeClass('active')
    } else {
      $('.filter').removeClass('active')
      $(this).addClass('active')
    }
  })

  .on('mouseleave', function () {
    $(this).removeClass('active')
  })

  .on('change', function () {
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

/* Dates */

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
      $('.date-in').addClass('active').find('span').text(moment(target.date._i).format('LL'))
      $('.date-in-value').val(moment(target.date._i).format('YYYYMMDD'))
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
      $('.date-out').addClass('active').find('span').text(moment(target.date._i).format('LL'))
      $('.date-out-value').val(moment(target.date._i).format('YYYYMMDD'))
      $('.calendar-out').removeClass('active')
      $('.filter.dates').removeClass('active')
      updateFilters()
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

/* Table */

const retreatsTemplate = _.template($('.retreats-template').html())

function updateRetreats (data) {
  $('.retreats-table tbody').html(retreatsTemplate({
    retreats: data
  }))
}

const retreatsData = JSON.parse($('.retreats-data').html())
retreatsData.map(retreat => {
  retreat.dateIn = moment(retreat.dateIn)
  retreat.dateOut = moment(retreat.dateOut)
})
updateRetreats(retreatsData)

function applyFilters (filters) {
  const retreats = retreatsData.filter((retreat) => {
    if (filters['site'].length === 0) { return true }
    return filters['site'].indexOf(retreat.site.value) >= 0
  }).filter((retreat) => {
    if (filters['type'].length === 0) { return true }
    return filters['type'].indexOf(retreat.type.value.toString()) >= 0
  }).filter((retreat) => {
    if (filters['speaker'].length === 0) { return true }
    const speakers = retreat.speaker.filter(speaker => filters['speaker'].indexOf(speaker.value) >= 0)
    return speakers.length > 0
  }).filter((retreat) => {
    if (filters['translation'].length === 0) { return true }
    return filters['translation'].indexOf(retreat.translation) >= 0
  }).filter((retreat) => {
    if (filters['dateIn'].length === 0) { return true }
    return moment(retreat.dateIn).isAfter(filters['dateIn'])
  }).filter((retreat) => {
    if (filters['dateOut'].length === 0) { return true }
    return moment(retreat.dateOut).isBefore(filters['dateOut'])
  }).filter((retreat) => {
    if (filters['keywords'].length === 0) { return true }
    return retreat.event.toLowerCase().includes(filters['keywords'].toLowerCase())
  })
  updateRetreats(retreats)
}

function updateFilters () {
  let filtersActived = {
    site: [],
    type: [],
    dateIn: '',
    dateOut: '',
    speaker: [],
    translation: [],
    keywords: ''
  }
  $('.filter').each(function () {
    const values = $(this).serializeArray()
    $.each(values, function (key, elmt) {
      filtersActived[elmt.name].push(elmt.value)
    })
  })
  const dates = $('.filter-dates').serializeArray()
  $.each(dates, function (key, elmt) {
    filtersActived[elmt.name] = elmt.value
  })
  const keywords = $('.filter-keywords').serializeArray()
  $.each(keywords, function (key, elmt) {
    filtersActived[elmt.name] = elmt.value
  })
  applyFilters(filtersActived)
}

/* Keywords */

$('.filter-keywords').on('keyup', 'input', function () {
  updateFilters()
})

/* RAZ */

$('.filter-raz').on('click', function (event) {
  event.preventDefault()
  $('.filter')
    .find('input:checked')
    .each(function () {
      $(this).prop('checked', false)
    })
    .trigger('change')
  $('.filter-dates')
    .find('input')
    .each(function () {
      $(this).val('')
    })
  $('.date-in')
    .removeClass('active')
    .find('span')
    .text(dateToday)
  $('.date-out')
    .removeClass('active')
    .find('span')
    .text(dateOneYear)
  $('.filter-keywords').find('input')
    .val('')
    .trigger('keyup')
})
