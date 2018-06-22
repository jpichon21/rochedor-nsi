import {
  GET_NEWSSET,
  GET_NEWSSET_SUCCESS,
  GET_NEWSSET_FAILURE,
  POST_NEWS,
  POST_NEWS_SUCCESS,
  POST_NEWS_FAILURE,
  PUT_NEWS,
  PUT_NEWS_SUCCESS,
  PUT_NEWS_FAILURE,
  DELETE_NEWS,
  DELETE_NEWS_SUCCESS,
  DELETE_NEWS_FAILURE,
  GET_NEWS,
  GET_NEWS_SUCCESS,
  GET_NEWS_FAILURE,
  GET_NEWS_VERSIONS,
  GET_NEWS_VERSIONS_SUCCESS,
  GET_NEWS_VERSIONS_FAILURE,
  GET_NEWS_TRANSLATIONS,
  GET_NEWS_TRANSLATIONS_SUCCESS,
  GET_NEWS_TRANSLATIONS_FAILURE

} from '../actions'

export default function newsReducer (state, action) {
  switch (action.type) {
    case GET_NEWSSET:
      return {
        ...state,
        loading: true
      }
    case GET_NEWSSET_SUCCESS:
      return {
        ...state,
        newsSet: [...action.data],
        loading: false
      }
    case GET_NEWSSET_FAILURE:
      return {
        ...state,
        loading: false,
        error: action.data.error
      }
    case GET_NEWS_VERSIONS:
      return {
        ...state,
        loading: true
      }
    case GET_NEWS_VERSIONS_SUCCESS:
      return {
        ...state,
        newsVersions: [...action.data],
        loading: false
      }
    case GET_NEWS_VERSIONS_FAILURE:
      return {
        ...state,
        loading: false,
        error: action.data.error
      }
    case GET_NEWS_TRANSLATIONS:
      return {
        ...state,
        loading: true
      }
    case GET_NEWS_TRANSLATIONS_SUCCESS:
      return {
        ...state,
        newsTranslations: [...action.data],
        loading: false
      }
    case GET_NEWS_TRANSLATIONS_FAILURE:
      return {
        ...state,
        loading: false,
        error: action.data.error
      }
    case POST_NEWS:
      return {
        ...state,
        status: '',
        loading: true
      }
    case POST_NEWS_SUCCESS:
      return {
        ...state,
        status: 'ok',
        loading: false
      }
    case POST_NEWS_FAILURE:
      return {
        ...state,
        status: action.data.message,
        error: action.data.error,
        loading: false
      }
    case PUT_NEWS:
      return {
        ...state,
        status: '',
        loading: true
      }
    case PUT_NEWS_SUCCESS:
      return {
        ...state,
        status: action.data.message,
        loading: false
      }
    case PUT_NEWS_FAILURE:
      return {
        ...state,
        status: action.data.message,
        error: action.data.error,
        loading: false
      }
    case DELETE_NEWS:
      return {
        ...state,
        status: '',
        loading: true
      }
    case DELETE_NEWS_SUCCESS:
      return {
        ...state,
        status: action.data.message,
        loading: false
      }
    case DELETE_NEWS_FAILURE:
      return {
        ...state,
        status: action.data.message,
        error: action.data.error,
        loading: false
      }
    case GET_NEWS:
      return {
        ...state,
        loading: true
      }
    case GET_NEWS_SUCCESS:
      return {
        ...state,
        news: action.data,
        status: 'ok',
        loading: false
      }
    case GET_NEWS_FAILURE:
      return {
        ...state,
        news: null,
        status: action.data.message,
        error: action.data.error,
        loading: false
      }
  }
  return state
}
