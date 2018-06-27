export const UPLOAD_FILE = 'UPLOAD_FILE'
export const UPLOAD_FILE_SUCCESS = 'UPLOAD_FILE_SUCCESS'
export const UPLOAD_FILE_FAILURE = 'UPLOAD_FILE_FAILURE'
export const GET_FILE = 'GET_FILE'
export const GET_FILE_SUCCESS = 'GET_FILE_SUCCESS'
export const GET_FILE_FAILURE = 'GET_FILE_FAILURE'
export const GET_FILES = 'GET_FILES'
export const GET_FILES_SUCCESS = 'GET_FILES_SUCCESS'
export const GET_FILES_FAILURE = 'GET_FILES_FAILURE'

const API_URL = '/api/'

export function getFiles () {
  return dispatch => {
    dispatch({ type: GET_FILES })

    return window.fetch(`${API_URL}file`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: GET_FILES_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: GET_FILES_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: GET_FILES_FAILURE, error }))
  }
}

export function getFile (fileId) {
  return dispatch => {
    dispatch({ type: GET_FILE, fileId })
    const url = `${API_URL}file/${fileId}`
    return window.fetch(url, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: GET_FILE_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: GET_FILE_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: GET_FILE_FAILURE, error }))
  }
}

export function uploadFile (file) {
  let data = new window.FormData()
  data.append('file', file)
  return dispatch => {
    dispatch({ type: UPLOAD_FILE, file })

    return window.fetch(`${API_URL}file/upload`, {
      method: 'POST',
      credentials: 'include',
      body: data
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: UPLOAD_FILE_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: UPLOAD_FILE_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: UPLOAD_FILE_FAILURE, data: error }))
  }
}
