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
  GET_PAGE_VERSIONS,
  GET_PAGE_VERSIONS_SUCCESS,
  GET_PAGE_VERSIONS_FAILURE
} from '../actions'

export default function pageReducer (state, action) {
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
  }
  return state
}
