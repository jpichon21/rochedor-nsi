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

const isAtLRDO = (retreat) => retreat.site.abbr === 'RO'

let resultSet = query()
  .select()
  .from(retreatsData)
  .where(isAtLRDO)
  .execute()

setTimeout(() => {
  updateRetreats(resultSet)
}, 2000)
