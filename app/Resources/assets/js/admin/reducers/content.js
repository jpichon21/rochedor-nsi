import {
  GET_CONTENTS,
  GET_CONTENTS_SUCCESS,
  GET_CONTENTS_FAILURE,
  PUT_CONTENT,
  PUT_CONTENT_SUCCESS,
  PUT_CONTENT_FAILURE,
  GET_CONTENT,
  GET_CONTENT_SUCCESS,
  GET_CONTENT_FAILURE,
  GET_CONTENT_VERSIONS,
  GET_CONTENT_VERSIONS_SUCCESS,
  GET_CONTENT_VERSIONS_FAILURE,
  GET_CONTENT_TRANSLATIONS,
  GET_CONTENT_TRANSLATIONS_SUCCESS,
  GET_CONTENT_TRANSLATIONS_FAILURE

} from '../actions'

export default function pageReducer (state, action) {
  switch (action.type) {
    case GET_CONTENTS:
      return {
        ...state,
        loading: true
      }
    case GET_CONTENTS_SUCCESS:
      return {
        ...state,
        pages: [...action.data],
        loading: false
      }
    case GET_CONTENTS_FAILURE:
      return {
        ...state,
        loading: false,
        error: action.data.error
      }
    case GET_CONTENT_VERSIONS:
      return {
        ...state,
        loading: true
      }
    case GET_CONTENT_VERSIONS_SUCCESS:
      return {
        ...state,
        pageVersions: [...action.data],
        loading: false
      }
    case GET_CONTENT_VERSIONS_FAILURE:
      return {
        ...state,
        loading: false,
        error: action.data.error
      }
    case GET_CONTENT_TRANSLATIONS:
      return {
        ...state,
        loading: true
      }
    case GET_CONTENT_TRANSLATIONS_SUCCESS:
      return {
        ...state,
        pageTranslations: [...action.data],
        loading: false
      }
    case GET_CONTENT_TRANSLATIONS_FAILURE:
      return {
        ...state,
        loading: false,
        error: action.data.error
      }
    case PUT_CONTENT:
      return {
        ...state,
        status: '',
        loading: true
      }
    case PUT_CONTENT_SUCCESS:
      return {
        ...state,
        status: action.data.message,
        loading: false
      }
    case PUT_CONTENT_FAILURE:
      return {
        ...state,
        status: action.data.message,
        error: action.data.error,
        loading: false
      }
    case GET_CONTENT:
      return {
        ...state,
        loading: true
      }
    case GET_CONTENT_SUCCESS:
      return {
        ...state,
        page: action.data,
        status: 'ok',
        loading: false
      }
    case GET_CONTENT_FAILURE:
      return {
        ...state,
        status: action.data.message,
        error: action.data.error,
        loading: false
      }
  }
  return state
}
