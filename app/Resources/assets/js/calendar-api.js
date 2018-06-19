export const postLogin = (data) => {
  return window.fetch('/login', {
    headers: { 'Content-Type': 'application/json' },
    method: 'POST',
    credentials: 'include',
    body: JSON.stringify({
      username: data.username,
      password: data.password
    })
  })
    .then(res => {
      if (!res.ok) { res.json().then(res => { throw res.error }) }
      return res.json()
    })
    .catch(error => console.log(error))
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
      if (res.status !== 'ok') { throw res.error }
      return res.data
    })
    .catch(error => console.log(error))
}

export const getLogout = () => {
  return window.fetch('/logout', {
    headers: { 'Content-Type': 'application/json' },
    method: 'GET',
    credentials: 'include'
  })
    .then(() => {
      window.location.reload()
    })
    .catch(error => console.log(error))
}

export const getLogin = () => {
  return window.fetch('/login', {
    method: 'GET',
    credentials: 'include'
  })
    .then(res => {
      if (!res.ok) { res.json().then(res => { throw res.error }) }
      return res.json()
    })
    .catch(error => console.log(error))
}

export const getRegistered = () => {
  return window.fetch('/xhr/calendar/attendees', {
    headers: { 'Content-Type': 'application/json' },
    method: 'GET',
    credentials: 'include'
  })
    .then(res => res.json())
    .then(res => {
      if (res.status !== 'ok') { throw res.error }
      return res.data
    })
    .catch(error => console.log(error))
}

export const getRetreat = (id) => {
  return window.fetch(`/xhr/calendar/${id}`, {
    headers: { 'Content-Type': 'application/json' },
    method: 'GET',
    credentials: 'include'
  })
    .then(res => res.json())
    .then(res => {
      if (res.status !== 'ok') { throw res.error }
      return res.data
    })
    .catch(error => console.log(error))
}
