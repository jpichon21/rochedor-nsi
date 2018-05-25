import $ from 'jquery'
import moment from 'moment'
import 'clndr'
import 'datattables'

moment.locale('fr')

let dateToday = moment().format('LL')
let dateOneYear = moment().add(1, 'y').format('LL')

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
  let values = []
  $(this).find('input:checked').each(function (index) {
    values.push($(this).val())
  })
  let count = values.length
  $('.value .count', $(this)).text(count)
  $('.value div', $(this)).removeClass('active')
  if (count === 0) {
    $('.value .default', $(this)).addClass('active')
  } else if (count === 1) {
    $('.value .singular', $(this)).addClass('active')
  } else {
    $('.value .plural', $(this)).addClass('active')
  }
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

let retreatsData = [
  {
    dates: [
      '01-08-2018',
      '05-08-2018'
    ],
    site: {
      abbr: 'RO',
      color: '#00ff00'
    },
    event: 'Posuere nostrud posuere quis vero exercitationem quis viverra.',
    speaker: 'Père Bernanrd Miserez',
    type: {
      abbr: 'W',
      color: '#00B6E8'
    },
    duration: '2 jours',
    translation: 'en'
  },
  {
    dates: [
      '07-08-2018',
      '09-09-2018'
    ],
    site: {
      abbr: 'RO',
      color: '#00ff00'
    },
    event: 'Mollitia do adipisci massa et venenatis totam magni unde, laoreet.',
    speaker: 'Père Olivier Sournia',
    type: {
      abbr: 'F',
      color: '#E10076'
    },
    duration: '2 jours',
    translation: 'fr'
  },
  {
    dates: [
      '01-08-2018',
      '05-08-2018'
    ],
    site: {
      abbr: 'FT',
      color: '#ffff00'
    },
    event: 'Dolorum porta luctus quam hac, sociis, quidem irure habitant molestie!',
    speaker: 'Bruno Barral',
    type: {
      abbr: 'R',
      color: '#31BF31'
    },
    duration: '5 jours',
    translation: 'es'
  },
  {
    dates: [
      '07-12-2018',
      '09-12-2018'
    ],
    site: {
      abbr: 'RO',
      color: '#00ff00'
    },
    event: 'Ornare placerat mollis eros taciti nostrud cupidatat voluptate, adipisci venenatis.',
    speaker: 'Père Bernanrd Miserez',
    type: {
      abbr: 'W',
      color: '#00B6E8'
    },
    duration: '3 jours',
    translation: 'it'
  }
]

let retreatsTemplate = _.template($('.retreats-template').html())

function updateRetreats (data) {
  $('.retreats-table .body').html(retreatsTemplate({
    retreats: data
  }))
}

updateRetreats(retreatsData)
