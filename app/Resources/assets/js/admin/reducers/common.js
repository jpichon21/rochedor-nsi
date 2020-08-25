import {
  INIT_STATUS,
  SET_MESSAGE,
  RESET_MESSAGE,
  SET_TITLE,
  SET_LOCALE,
  SET_ALERT,
  OPEN_ALERT,
  CLOSE_ALERT,
  DO_LOGIN,
  DO_LOGIN_SUCCESS,
  DO_LOGIN_FAILURE,
  DO_LOGOUT,
  DO_LOGINCHECK_SUCCESS, DO_FORGOTTEN_PASSWORD_SUCCESS
} from '../actions'

export default function commmonReducer (state, action) {
  switch (action.type) {
    case INIT_STATUS:
      return {
        ...state,
        status: '',
        error: null,
        loading: false
      }
    case SET_MESSAGE:
      return {
        ...state,
        message: { message: action.message, error: action.error }
      }
    case RESET_MESSAGE:
      return {
        ...state,
        message: null
      }
    case SET_TITLE:
      return {
        ...state,
        title: action.title
      }
    case SET_LOCALE:
      console.log('SET_LOCALE')
      console.log(action.locale)
      return {
        ...state,
        locale: action.locale
      }
    case SET_ALERT:
      return {
        ...state,
        alertText: action.text
      }
    case OPEN_ALERT:
      return {
        ...state,
        alertOpen: true
      }
    case CLOSE_ALERT:
      return {
        ...state,
        alertOpen: false
      }
    case DO_LOGIN:
      return {
        ...state,
        isLogging: true
      }
    case DO_LOGINCHECK_SUCCESS:
    case DO_LOGIN_SUCCESS:
      return {
        ...state,
        roles: action.roles,
        username: action.username,
        fullname: action.name,
        isLogging: false,
        errorLoginMessage: null
      }
    case DO_LOGIN_FAILURE:
      return {
        ...state,
        isLogging: false,
        errorLoginMessage: action.error
      }
    case DO_LOGOUT:
      return {
        ...state,
        roles: null,
        username: null,
        fullname: null
      }
    case DO_FORGOTTEN_PASSWORD_SUCCESS:
      return {
        ...state,
        roles: action.roles,
        username: action.username,
        fullname: action.name,
        isLogging: false,
        errorLoginMessage: null
      }
  }
  return state
}
