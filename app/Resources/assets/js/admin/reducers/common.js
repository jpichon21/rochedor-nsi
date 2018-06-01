import {
  INIT_STATUS,
  SET_MESSAGE,
  RESET_MESSAGE,
  SET_TITLE
} from '../actions'

export default function commmonReducer (state, action) {
  console.log(action)
  switch (action.type) {
    case INIT_STATUS:
      return {
        ...state,
        status: '',
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
  }
  return state
}
