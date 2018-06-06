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
  DO_LOGOUT_SUCCESS,
  DO_LOGINCHECK_SUCCESS
} from '../actions'

export default function commmonReducer (state, action) {
  switch (action.type) {
    case INIT_STATUS:
      return {
        ...state,
        status: '',
        error: null,
        loading: false,
        page: null
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
        isLogging: false,
        errorLoginMessage: null
      }
    case DO_LOGIN_FAILURE:
      return {
        ...state,
        isLogging: false,
        errorLoginMessage: action.error
      }
    case DO_LOGOUT_SUCCESS:
      return {
        ...state,
        roles: null,
        username: null
      }
  }
  return state
}
