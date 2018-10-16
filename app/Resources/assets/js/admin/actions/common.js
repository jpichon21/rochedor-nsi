export const INIT_STATUS = 'INIT_STATUS'
export const SET_TITLE = 'SET_TITLE'
export const SET_MESSAGE = 'SET_MESSAGE'
export const RESET_MESSAGE = 'RESET_MESSAGE'
export const SET_LOCALE = 'SET_LOCALE'
export const SET_ALERT = 'SET_ALERT'
export const OPEN_ALERT = 'OPEN_ALERT'
export const CLOSE_ALERT = 'CLOSE_ALERT'
export const DO_LOGINCHECK = 'DO_LOGINCHECK'
export const DO_LOGINCHECK_SUCCESS = 'DO_LOGINCHECK_SUCCESS'
export const DO_LOGINCHECK_FAILURE = 'DO_LOGINCHECK_FAILURE'
export const DO_LOGOUT = 'DO_LOGOUT'
export const DO_LOGIN = 'DO_LOGIN'
export const DO_LOGIN_SUCCESS = 'DO_LOGIN_SUCCESS'
export const DO_LOGIN_FAILURE = 'DO_LOGIN_FAILURE'

export function initStatus () {
  return dispatch => {
    dispatch({ type: INIT_STATUS })
  }
}

export function setMessage (message, error = false) {
  return dispatch => {
    dispatch({ type: SET_MESSAGE, ...{message: message, error: error} })
  }
}

export function resetMessage () {
  return dispatch => {
    dispatch({ type: RESET_MESSAGE })
  }
}

export function setTitle (title) {
  return dispatch => {
    dispatch({ type: SET_TITLE, ...{title: title} })
  }
}

export function setLocale (locale) {
  return dispatch => {
    dispatch({ type: SET_LOCALE, ...{locale: locale} })
  }
}

export function setAlert (text) {
  return dispatch => {
    dispatch({ type: SET_ALERT, ...{alertText: text} })
  }
}
export function openAlert () {
  return dispatch => {
    dispatch({ type: OPEN_ALERT })
  }
}
export function closeAlert () {
  return dispatch => {
    dispatch({ type: CLOSE_ALERT })
  }
}
export function doCheckLogin () {
  return dispatch => {
    dispatch({ type: DO_LOGINCHECK })
    return window.fetch('/api/login', {
      method: 'GET',
      credentials: 'include'
    })
      .then(res => {
        if (!res.ok) {
          return res.json()
            .then(res => {
              throw new Error(res.error)
            })
        }
        return res.json()
      })
      .then(res => dispatch({ type: DO_LOGINCHECK_SUCCESS, ...res }))
      .catch(error => dispatch({ type: DO_LOGINCHECK_FAILURE, error: error.message }))
  }
}

export function doLogin (username, password) {
  return dispatch => {
    dispatch({ type: DO_LOGIN, username, password })
    return window.fetch('/api/login', {
      headers: { 'Content-Type': 'application/json' },
      method: 'POST',
      credentials: 'include',
      body: JSON.stringify({ username: username, password: password })
    })
      .then(res => {
        if (!res.ok) {
          return res.json()
            .then(res => {
              throw new Error(res.error)
            })
        }
        return res.json()
      })
      .then(res => dispatch({ type: DO_LOGIN_SUCCESS, ...res }))
      .catch(error => dispatch({ type: DO_LOGIN_FAILURE, error: error.message }))
  }
}

export function doLogout () {
  return dispatch => {
    dispatch({ type: DO_LOGOUT })
    return window.fetch('/logout', {
      method: 'GET',
      credentials: 'include'
    })
  }
}
