import {
  PUT_HOME,
  PUT_HOME_SUCCESS,
  PUT_HOME_FAILURE,
  GET_HOME,
  GET_HOME_SUCCESS,
  GET_HOME_FAILURE,
  GET_HOME_VERSIONS,
  GET_HOME_VERSIONS_SUCCESS,
  GET_HOME_VERSIONS_FAILURE

} from '../actions'

export default function homeReducer (state, action) {
  switch (action.type) {
    case GET_HOME_VERSIONS:
    case GET_HOME:
    case PUT_HOME:
      return {
        ...state,
        loading: true
      }
    case GET_HOME_VERSIONS_SUCCESS:
      return {
        ...state,
        homeVersions: [...action.data],
        loading: false
      }
    case GET_HOME_SUCCESS:
    case PUT_HOME_SUCCESS:
      return {
        ...state,
        home: action.data,
        loading: false
      }
    case GET_HOME_VERSIONS_FAILURE:
    case PUT_HOME_FAILURE:
    case GET_HOME_FAILURE:
      return {
        ...state,
        loading: false
      }
  }
  return state
}
