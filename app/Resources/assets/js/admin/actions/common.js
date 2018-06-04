export const INIT_STATUS = 'INIT_STATUS'
export const SET_TITLE = 'SET_TITLE'
export const SET_MESSAGE = 'SET_MESSAGE'
export const RESET_MESSAGE = 'RESET_MESSAGE'
export const SET_LOCALE = 'SET_LOCALE'
export const SET_ALERT = 'SET_ALERT'
export const OPEN_ALERT = 'OPEN_ALERT'
export const CLOSE_ALERT = 'CLOSE_ALERT'

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
