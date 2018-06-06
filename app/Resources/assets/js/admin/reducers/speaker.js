import {
  GET_SPEAKERS,
  GET_SPEAKERS_SUCCESS,
  GET_SPEAKERS_FAILURE,
  POST_SPEAKER,
  POST_SPEAKER_SUCCESS,
  POST_SPEAKER_FAILURE,
  PUT_SPEAKER,
  PUT_SPEAKER_SUCCESS,
  PUT_SPEAKER_FAILURE,
  DELETE_SPEAKER,
  DELETE_SPEAKER_SUCCESS,
  DELETE_SPEAKER_FAILURE,
  GET_SPEAKER,
  GET_SPEAKER_SUCCESS,
  GET_SPEAKER_FAILURE,
  GET_SPEAKER_VERSIONS,
  GET_SPEAKER_VERSIONS_SUCCESS,
  GET_SPEAKER_VERSIONS_FAILURE,
  SET_SPEAKER_POSTION,
  SET_SPEAKER_POSTION_SUCCESS,
  SET_SPEAKER_POSTION_FAILURE

} from '../actions'

export default function speakerReducer (state, action) {
  switch (action.type) {
    case GET_SPEAKERS:
    case GET_SPEAKER_VERSIONS:
    case POST_SPEAKER:
    case PUT_SPEAKER:
    case GET_SPEAKER:
    case SET_SPEAKER_POSTION:
      return {
        ...state,
        loading: true
      }
    case GET_SPEAKERS_SUCCESS:
    case SET_SPEAKER_POSTION_SUCCESS:
      return {
        ...state,
        loading: false,
        speakers: action.data
      }
    case PUT_SPEAKER_SUCCESS:
    case GET_SPEAKERS_FAILURE:
    case GET_SPEAKER_VERSIONS_SUCCESS:
    case GET_SPEAKER_VERSIONS_FAILURE:
    case POST_SPEAKER_SUCCESS:
    case POST_SPEAKER_FAILURE:
    case PUT_SPEAKER_FAILURE:
    case DELETE_SPEAKER_SUCCESS:
    case DELETE_SPEAKER_FAILURE:
    case GET_SPEAKER_SUCCESS:
    case GET_SPEAKER_FAILURE:
    case SET_SPEAKER_POSTION_FAILURE:
      return {
        ...state,
        loading: false
      }
  }
  return state
}
