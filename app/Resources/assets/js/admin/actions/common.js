export const INIT_STATUS = 'INIT_STATUS'
export const PUT_PAGE = 'PUT_PAGE'

export function initStatus () {
  return dispatch => {
    dispatch({ type: INIT_STATUS })
  }
}

export const SET_MESSAGE = 'SET_MESSAGE'
export const RESET_MESSAGE = 'RESET_MESSAGE'

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

export const SET_TITLE = 'SET_TITLE'
export function setTitle (title) {
  return dispatch => {
    dispatch({ type: SET_TITLE, ...{title: title} })
  }
}
