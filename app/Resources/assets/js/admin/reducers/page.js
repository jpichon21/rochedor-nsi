import {
  GET_PAGES,
  GET_PAGES_SUCCESS,
  GET_PAGES_FAILURE,
  POST_PAGE,
  POST_PAGE_SUCCESS,
  POST_PAGE_FAILURE,
  PUT_PAGE,
  PUT_PAGE_SUCCESS,
  PUT_PAGE_FAILURE,
  DELETE_PAGE,
  DELETE_PAGE_SUCCESS,
  DELETE_PAGE_FAILURE,
  GET_PAGE,
  GET_PAGE_SUCCESS,
  GET_PAGE_FAILURE,
  GET_PAGE_VERSIONS,
  GET_PAGE_VERSIONS_SUCCESS,
  GET_PAGE_VERSIONS_FAILURE,
  GET_PAGE_TRANSLATIONS,
  GET_PAGE_TRANSLATIONS_SUCCESS,
  GET_PAGE_TRANSLATIONS_FAILURE

} from '../actions'

export default function pageReducer (state, action) {
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
        loading: false,
        error: action.data.error
      }
    case GET_PAGE_VERSIONS:
      return {
        ...state,
        loading: true
      }
    case GET_PAGE_VERSIONS_SUCCESS:
      return {
        ...state,
        pageVersions: [...action.data],
        loading: false
      }
    case GET_PAGE_VERSIONS_FAILURE:
      return {
        ...state,
        loading: false,
        error: action.data.error
      }
    case GET_PAGE_TRANSLATIONS:
      return {
        ...state,
        loading: true
      }
    case GET_PAGE_TRANSLATIONS_SUCCESS:
      return {
        ...state,
        pageTranslations: [...action.data],
        loading: false
      }
    case GET_PAGE_TRANSLATIONS_FAILURE:
      return {
        ...state,
        loading: false,
        error: action.data.error
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
        error: action.data.error,
        loading: false
      }
    case PUT_PAGE:
      return {
        ...state,
        status: '',
        loading: true
      }
    case PUT_PAGE_SUCCESS:
      return {
        ...state,
        status: 'ok',
        loading: false
      }
    case PUT_PAGE_FAILURE:
      return {
        ...state,
        status: action.data.message,
        error: action.data.error,
        loading: false
      }
    case DELETE_PAGE:
      return {
        ...state,
        status: '',
        loading: true
      }
    case DELETE_PAGE_SUCCESS:
      return {
        ...state,
        status: action.data.message,
        loading: false
      }
    case DELETE_PAGE_FAILURE:
      return {
        ...state,
        status: action.data.message,
        error: action.data.error,
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
        error: action.data.error,
        loading: false
      }
  }
  return state
}
