import I18n from './i18n'

let i18n = new I18n()

export const postLogin = (data) => {
  return window.fetch('/login', {
    headers: { 'Content-Type': 'application/json' },
    method: 'POST',
    credentials: 'include',
    body: JSON.stringify(data)
  })
    .then(res => {
      if (!res.ok) { res.json().then(res => { throw res.error }) }
      return res.json()
    })
    .catch(res => {
      throw (new Error(i18n.trans('Error: unknown_error')))
    })
}

export const resetLogin = (data) => {
  return window.fetch('/password-request?_locale=' + i18n.guessLocale(), {
    headers: { 'Content-Type': 'application/json' },
    method: 'POST',
    credentials: 'include',
    body: JSON.stringify(data)
  })
    .then(res => res.json())
    .then(res => {
      if (res.status !== 'ok') { throw res.message }
      return res.data
    })
}

export const postRegister = (data) => {
  return window.fetch('/register?_locale=' + i18n.guessLocale(), {
    headers: { 'Content-Type': 'application/json' },
    method: 'POST',
    credentials: 'include',
    body: JSON.stringify(data)
  })
    .catch(res => {
      throw (new Error(i18n.trans('Error: unknown_error')))
    })
    .then(res => res.json())
    .then(res => {
      if (res.status === 'ko') {
        throw res.message
      }
      return res
    })
}

export const postParticipant = (data) => {
  return window.fetch('/xhr/calendar/attendee?_locale=' + i18n.guessLocale(), {
    headers: { 'Content-Type': 'application/json' },
    method: 'POST',
    credentials: 'include',
    body: JSON.stringify({
      attendee: data
    })
  })
    .then(res => res.json())
    .then(res => {
      if (res.error !== undefined) {
        throw res.error
      }
      if (res.status !== 'ok') { throw res.message }
      return res.data
    })
    .catch(res => {
      if (typeof res === 'string') {
        throw (new Error(i18n.trans(res)))
      } else if (res.code === 403) {
        throw (new Error(i18n.trans('forbidden')))
      } else {
        throw (new Error(i18n.trans('Error: unknown_error')))
      }
    })
}

export const getLogout = (locale) => {
  return window.fetch('/logout?_locale=' + i18n.guessLocale(), {
    headers: { 'Content-Type': 'application/json' },
    method: 'GET',
    credentials: 'include'
  })
    .then(() => {
      window.location.replace('/' + locale + '/logout-message/calendar')
    })
    .catch(res => {
      throw (new Error(i18n.trans('Error: unknown_error')))
    })
}

export const getLogin = () => {
  return window.fetch('/login?_locale=' + i18n.guessLocale(), {
    method: 'GET',
    credentials: 'include'
  })
    .then(res => {
      if (!res.ok) { res.json().then(res => { throw res.message }) }
      if (res.status === 201) { throw new Error('not_logged_in') }
      return res.json()
    })
    .catch(res => {
      throw (new Error(i18n.trans('Error: unknown_error')))
    })
}

export const getRegistered = (activityId) => {
  return window.fetch('/xhr/calendar/attendees?_locale=' + i18n.guessLocale() + 'activityId=' + activityId, {
    headers: { 'Content-Type': 'application/json' },
    method: 'GET',
    credentials: 'include'
  })
    .then(res => res.json())
    .then(res => {
      if (res.error !== undefined) {
        throw res.error
      }
      if (res.status !== 'ok') { throw res.message }
      return res.data
    })
    .catch(res => {
      if (res.code === 403) {
        throw (new Error(i18n.trans('forbidden')))
      } else {
        throw (new Error(i18n.trans('Error: unknown_error')))
      }
    })
}

export const postRegistered = (data, id, existingRef) => {
  return window.fetch('/xhr/calendar/attendees?_locale=' + i18n.guessLocale(), {
    headers: { 'Content-Type': 'application/json' },
    method: 'POST',
    credentials: 'include',
    body: JSON.stringify({
      attendees: data,
      activityId: id,
      existingRef: existingRef
    })
  })
    .then(res => res.json())
    .then(res => {
      if (res.error !== undefined) {
        throw res.error
      }
      if (res.status !== 'ok') { throw res.message }
      return res.data
    })
    .catch(err => {
      if (err.code === 403) {
        throw (new Error(i18n.trans('forbidden')))
      } else {
        throw (new Error(i18n.trans('Error: unknown_error')))
      }
    })
}
