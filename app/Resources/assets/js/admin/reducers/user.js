import {
  GET_CONTACTS,
  GET_CONTACTS_SUCCESS,
  GET_CONTACTS_FAILURE,
  POST_CONTACT,
  POST_CONTACT_SUCCESS,
  POST_CONTACT_FAILURE,
  PUT_CONTACT,
  PUT_CONTACT_SUCCESS,
  PUT_CONTACT_FAILURE,
  DELETE_CONTACT,
  DELETE_CONTACT_SUCCESS,
  DELETE_CONTACT_FAILURE,
  GET_CONTACT,
  GET_CONTACT_SUCCESS,
  GET_CONTACT_FAILURE

} from '../actions'

export default function userReducer (state, action) {
  switch (action.type) {
    case GET_CONTACTS:
    case POST_CONTACT:
    case DELETE_CONTACT:
    case PUT_CONTACT:
    case GET_CONTACT:
      return {
        ...state,
        loading: true
      }
    case GET_CONTACT_SUCCESS:
    case GET_CONTACTS_SUCCESS:
      return {
        ...state,
        loading: false
      }
    case PUT_CONTACT_SUCCESS:
    case GET_CONTACTS_FAILURE:
    case POST_CONTACT_SUCCESS:
    case POST_CONTACT_FAILURE:
    case PUT_CONTACT_FAILURE:
    case DELETE_CONTACT_SUCCESS:
    case DELETE_CONTACT_FAILURE:
    case GET_CONTACT_FAILURE:
      return {
        ...state,
        loading: false
      }
  }
  return state
}
