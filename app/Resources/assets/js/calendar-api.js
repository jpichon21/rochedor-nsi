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
