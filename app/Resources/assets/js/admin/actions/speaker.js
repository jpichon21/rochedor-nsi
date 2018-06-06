export const POST_SPEAKER = 'POST_SPEAKER'
export const POST_SPEAKER_SUCCESS = 'POST_SPEAKER_SUCCESS'
export const POST_SPEAKER_FAILURE = 'POST_SPEAKER_FAILURE'
export const GET_SPEAKERS = 'GET_SPEAKERS'
export const GET_SPEAKERS_SUCCESS = 'GET_SPEAKERS_SUCCESS'
export const GET_SPEAKERS_FAILURE = 'GET_SPEAKERS_FAILURE'
export const GET_SPEAKER = 'GET_SPEAKER'
export const GET_SPEAKER_SUCCESS = 'GET_SPEAKER_SUCCESS'
export const GET_SPEAKER_FAILURE = 'GET_SPEAKER_FAILURE'
export const GET_SPEAKER_VERSIONS = 'GET_SPEAKER_VERSIONS'
export const GET_SPEAKER_VERSIONS_SUCCESS = 'GET_SPEAKER_VERSIONS_SUCCESS'
export const GET_SPEAKER_VERSIONS_FAILURE = 'GET_SPEAKER_VERSIONS_FAILURE'
export const PUT_SPEAKER = 'PUT_SPEAKER'
export const PUT_SPEAKER_SUCCESS = 'PUT_SPEAKER_SUCCESS'
export const PUT_SPEAKER_FAILURE = 'PUT_SPEAKER_FAILURE'
export const DELETE_SPEAKER = 'DELETE_SPEAKER'
export const DELETE_SPEAKER_SUCCESS = 'DELETE_SPEAKER_SUCCESS'
export const DELETE_SPEAKER_FAILURE = 'DELETE_SPEAKER_FAILURE'
export const SET_SPEAKER_POSTION = 'SET_SPEAKER_POSTION'
export const SET_SPEAKER_POSTION_SUCCESS = 'SET_SPEAKER_POSTION_SUCCESS'
export const SET_SPEAKER_POSTION_FAILURE = 'SET_SPEAKER_POSTION_FAILURE'

const API_URL = '/api/'

export function postSpeaker (attributes) {
  return dispatch => {
    dispatch({ type: POST_SPEAKER, attributes })

    return window.fetch(`${API_URL}speaker`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'POST',
      credentials: 'include',
      body: JSON.stringify(attributes)
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: POST_SPEAKER_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: POST_SPEAKER_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: POST_SPEAKER_FAILURE, error }))
  }
}

export function getSpeakers (locale = 'fr') {
  return dispatch => {
    dispatch({ type: GET_SPEAKERS, locale })

    return window.fetch(`${API_URL}speaker?locale=${locale}`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: GET_SPEAKERS_SUCCESS, data: res })
      })
      .catch(error => dispatch({ type: GET_SPEAKERS_FAILURE, data: error }))
  }
}

export function getSpeaker (speakerId, version = null) {
  return dispatch => {
    dispatch({ type: GET_SPEAKER, speakerId })
    const url = (version) ? `${API_URL}speaker/${speakerId}/${version}` : `${API_URL}speaker/${speakerId}`
    return window.fetch(url, {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: GET_SPEAKER_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: GET_SPEAKER_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: GET_SPEAKER_FAILURE, error }))
  }
}

export function getSpeakerVersions (speakerId) {
  return dispatch => {
    dispatch({ type: GET_SPEAKER_VERSIONS, speakerId })

    return window.fetch(`${API_URL}speaker/${speakerId}/versions`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: GET_SPEAKER_VERSIONS_FAILURE, ...{ data: res } })
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: GET_SPEAKER_VERSIONS_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: GET_SPEAKER_VERSIONS_FAILURE, error }))
  }
}

export function putSpeaker (speaker) {
  return dispatch => {
    dispatch({ type: PUT_SPEAKER, speaker })

    return window.fetch(`${API_URL}speaker/${speaker.id}`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'PUT',
      credentials: 'include',
      body: JSON.stringify(speaker)
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: PUT_SPEAKER_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: PUT_SPEAKER_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: PUT_SPEAKER_FAILURE, error }))
  }
}

export function deleteSpeaker (speakerId) {
  return dispatch => {
    dispatch({ type: DELETE_SPEAKER, speakerId })
    return window.fetch(`${API_URL}speaker/${speakerId}`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'DELETE',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: DELETE_SPEAKER_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: DELETE_SPEAKER_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: DELETE_SPEAKER_FAILURE, error }))
  }
}

export function setSpeakerPosition (speakerId, position) {
  return dispatch => {
    dispatch({ type: SET_SPEAKER_POSTION, speakerId })

    return window.fetch(`${API_URL}speaker/${speakerId}/position/${position}`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'PUT',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: SET_SPEAKER_POSTION_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: SET_SPEAKER_POSTION_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: SET_SPEAKER_POSTION_FAILURE, error }))
  }
}
