export const postLogin = (data) => {
  return window.fetch('/shop/login', {
    headers: { 'Content-Type': 'application/json' },
    method: 'POST',
    credentials: 'include',
    body: JSON.stringify(data)
  })
    .then(res => {
      if (!res.ok) { res.json().then(res => { throw res.error }) }
      return res.json()
    })
}

export const resetLogin = (data) => {
  return window.fetch('/shop/password-request', {
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
  return window.fetch('/shop/register', {
    headers: { 'Content-Type': 'application/json' },
    method: 'POST',
    credentials: 'include',
    body: JSON.stringify(data)
  })
    .then(res => {
      if (!res.ok) { res.json().then(res => { throw res.error }) }
      return res.json()
    })
}

export const postEditCli = (data) => {
  return window.fetch('/shop/editcli', {
    headers: { 'Content-Type': 'application/json' },
    method: 'POST',
    credentials: 'include',
    body: JSON.stringify(data)
  })
    .then(res => {
      if (!res.ok) { res.json().then(res => { throw res.error }) }
      return res.json()
    })
}

export const getLogout = (locale) => {
  return window.fetch('/logout', {
    headers: { 'Content-Type': 'application/json' },
    method: 'GET',
    credentials: 'include'
  })
    .then(() => {
      window.location.replace('/' + locale + '/logout-message/edition')
    })
}

export const getLogin = () => {
  return window.fetch('/shop/login', {
    method: 'GET',
    credentials: 'include'
  })
    .then(res => {
      if (!res.ok) { res.json().then(res => { throw res.message }) }
      return res.json()
    })
}

export const postOrder = (data) => {
  return window.fetch('/xhr/order/delivery', {
    headers: { 'Content-Type': 'application/json' },
    method: 'POST',
    credentials: 'include',
    body: JSON.stringify({
      delivery: data
    })
  })
    .then(res => res.json())
    .then(res => {
      if (res.status !== 'ok') { throw res.message }
      return res.data
    })
}

export const patchProduct = (data) => {
  return window.fetch(`/xhr/cart/patch`, {
    headers: { 'Content-Type': 'application/json' },
    method: 'PATCH',
    credentials: 'include',
    body: JSON.stringify({
      data: data
    })
  })
    .then(res => res.json())
    .then(res => {
      if (res.status !== 'ok') { throw res.message }
      return true
    })
}

export const removeCartline = (cartId, codprd) => {
  return window.fetch(`/xhr/cart/remove/${cartId}/${codprd}`, {
    headers: { 'Content-Type': 'application/json' },
    method: 'DELETE',
    credentials: 'include'
  })
    .then(res => res.json())
    .then(res => {
      if (res.status !== 'ok') { throw res.message }
      return true
    })
}

export const getData = (cartId, paysliv, destliv) => {
  return window.fetch(`/xhr/order/taxes/${cartId}/${paysliv}/${destliv}`, {
    method: 'GET',
    credentials: 'include'
  })
    .then(res => res.json())
    .then(res => {
      if (res.status !== 'ok') { throw res.message }
      return res.data
    })
}

export const checkZipcode = (zipcode, country, destliv) => {
  return window.fetch(`/xhr/order/zipcode/${zipcode}/${country}/${destliv}`, {
    method: 'GET',
    credentials: 'include'
  })
    .then(res => res.json())
    .then(res => {
      if (res.status !== 'ok') { throw res.error }
      return res.data
    })
}

export const checkVat = (number) => {
  return window.fetch(`/xhr/order/vat/${number}`, {
    method: 'GET',
    credentials: 'include'
  })
    .then(res => res.json())
    .then(res => {
      if (res.status !== 'ok') { throw res.error }
      return res.data
    })
}
