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
      throw (new Error('unknown_error'))
    })
}

export const resetLogin = (data) => {
  return window.fetch('/password-request', {
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
  return window.fetch('/register', {
    headers: { 'Content-Type': 'application/json' },
    method: 'POST',
    credentials: 'include',
    body: JSON.stringify(data)
  })
    .catch(res => {
      console.error(res)
      throw (new Error('unknown_error'))
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
  return window.fetch('/xhr/calendar/attendee', {
    headers: { 'Content-Type': 'application/json' },
    method: 'POST',
    credentials: 'include',
    body: JSON.stringify({
      attendee: data
    })
  })
    .then(res => res.json())
    .then(res => {
      if (res.status !== 'ok') { throw res.message }
      return res.data
    })
    .catch(res => {
      throw (new Error('unknown_error'))
    })
}

export const getLogout = (locale) => {
  return window.fetch('/logout', {
    headers: { 'Content-Type': 'application/json' },
    method: 'GET',
    credentials: 'include'
  })
    .then(() => {
      window.location.replace('/' + locale + '/logout-message/roche')
    })
    .catch(res => {
      throw (new Error('unknown_error'))
    })
}

export const getLogin = () => {
  return window.fetch('/login', {
    method: 'GET',
    credentials: 'include'
  })
    .then(res => {
      if (!res.ok) { res.json().then(res => { throw res.message }) }
      return res.json()
    })
    .catch(res => {
      throw (new Error('unknown_error'))
    })
}

export const getRegistered = () => {
  return window.fetch('/xhr/calendar/attendees', {
    headers: { 'Content-Type': 'application/json' },
    method: 'GET',
    credentials: 'include'
  })
    .then(res => res.json())
    .then(res => {
      if (res.status !== 'ok') { throw res.message }
      return res.data
    })
    .catch(res => {
      throw (new Error('unknown_error'))
    })
}

export const postRegistered = (data, id) => {
  return window.fetch('/xhr/calendar/attendees', {
    headers: { 'Content-Type': 'application/json' },
    method: 'POST',
    credentials: 'include',
    body: JSON.stringify({
      attendees: data,
      activityId: id
    })
  })
    .then(res => res.json())
    .then(res => {
      if (res.status !== 'ok') { throw res.message }
      return res.data
    })
    .catch(res => {
      throw (new Error('unknown_error'))
    })
}
