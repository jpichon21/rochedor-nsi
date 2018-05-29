/* PARAMS */

export const API_URL = '/api/'

/* ACTIONS */
export const DO_LOGIN = 'DO_LOGIN'
export const DO_LOGIN_SUCCESS = 'DO_LOGIN_SUCCESS'
export const DO_LOGIN_FAILURE = 'DO_LOGIN_FAILURE'

export function doLogin (username, password) {
  return dispatch => {
    dispatch({ type: DO_LOGIN, username, password })
    return window.fetch('/login', {
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

export const DO_LOGOUT = 'DO_LOGOUT'
export const DO_LOGOUT_SUCCESS = 'DO_LOGOUT_SUCCESS'
export const DO_LOGOUT_FAILURE = 'DO_LOGOUT_FAILURE'

export function doLogout () {
  return dispatch => {
    dispatch({ type: DO_LOGOUT })
    return window.fetch('/logout', {
      method: 'GET',
      credentials: 'include'
    })
      .then(res => res.json())
      .then(res => dispatch({ type: DO_LOGOUT_SUCCESS }))
      .catch(error => dispatch({ type: DO_LOGOUT_FAILURE, error }))
  }
}

export const DO_LOGINCHECK = 'DO_LOGINCHECK'
export const DO_LOGINCHECK_SUCCESS = 'DO_LOGINCHECK_SUCCESS'
export const DO_LOGINCHECK_FAILURE = 'DO_LOGINCHECK_FAILURE'

export function doCheckLogin () {
  return dispatch => {
    dispatch({ type: DO_LOGINCHECK })
    return window.fetch('/login', {
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
