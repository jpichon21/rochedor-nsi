import {
  GET_PAGES,
  GET_PAGES_SUCCESS,
  GET_PAGES_FAILURE,
  POST_PAGE,
  POST_PAGE_SUCCESS,
  POST_PAGE_FAILURE,
  GET_PAGE,
  GET_PAGE_SUCCESS,
  GET_PAGE_FAILURE,
  INIT_STATUS,
  SET_MESSAGE,
  RESET_MESSAGE,
  SET_TITLE
} from './actions'

export const rootReducer = (state, action) => {
  console.log(action)
  switch (action.type) {
    case GET_PAGES:
      return {
        ...state,
        loading: true
      }
    case GET_PAGES_SUCCESS:
      return {
        ...state,
        pages: [...action.data],
        loading: false
      }
    case GET_PAGES_FAILURE:
      return {
        ...state,
        loading: false
      }
    case POST_PAGE:
      return {
        ...state,
        status: '',
        loading: true
      }
    case POST_PAGE_SUCCESS:
      return {
        ...state,
        status: 'ok',
        loading: false
      }
    case POST_PAGE_FAILURE:
      return {
        ...state,
        status: action.data.message,
        loading: false
      }
    case GET_PAGE:
      return {
        ...state,
        loading: true
      }
    case GET_PAGE_SUCCESS:
      return {
        ...state,
        page: action.data,
        status: 'ok',
        loading: false
      }
    case GET_PAGE_FAILURE:
      return {
        ...state,
        page: null,
        status: action.data.message,
        loading: false
      }
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
