import {
  UPLOAD_FILE,
  UPLOAD_FILE_SUCCESS,
  UPLOAD_FILE_FAILURE,
  GET_FILE,
  GET_FILE_SUCCESS,
  GET_FILE_FAILURE,
  GET_FILES,
  GET_FILES_SUCCESS,
  GET_FILES_FAILURE
} from '../actions'

export default function fileReducer (state, action) {
  switch (action.type) {
    case UPLOAD_FILE:
      return {
        ...state,
        loading: true
      }
    case UPLOAD_FILE_SUCCESS:
      return {
        ...state,
        uploadStatus: action.data,
        status: 'ok',
        loading: false
      }
    case UPLOAD_FILE_FAILURE:
      return {
        ...state,
        uploadStatus: null,
        status: action.data.message,
        error: action.data.error,
        loading: false
      }
    case GET_FILE:
      return {
        ...state,
        loading: true
      }
    case GET_FILE_SUCCESS:
      return {
        ...state,
        document: action.data,
        status: 'ok',
        loading: false
      }
    case GET_FILE_FAILURE:
      return {
        ...state,
        document: null,
        status: action.data.message,
        error: action.data.error,
        loading: false
      }
    case GET_FILES:
      return {
        ...state,
        loading: true
      }
    case GET_FILES_SUCCESS:
      return {
        ...state,
        documents: action.data,
        status: 'ok',
        loading: false
      }
    case GET_FILES_FAILURE:
      return {
        ...state,
        documents: null,
        status: action.data.message,
        error: action.data.error,
        loading: false
      }
  }
  return state
}
