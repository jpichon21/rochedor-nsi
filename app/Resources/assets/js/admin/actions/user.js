export const POST_CONTACT = 'POST_CONTACT'
export const POST_CONTACT_SUCCESS = 'POST_CONTACT_SUCCESS'
export const POST_CONTACT_FAILURE = 'POST_CONTACT_FAILURE'
export const GET_CONTACTS = 'GET_CONTACTS'
export const GET_CONTACTS_SUCCESS = 'GET_CONTACTS_SUCCESS'
export const GET_CONTACTS_FAILURE = 'GET_CONTACTS_FAILURE'
export const GET_CONTACT = 'GET_CONTACT'
export const GET_CONTACT_SUCCESS = 'GET_CONTACT_SUCCESS'
export const GET_CONTACT_FAILURE = 'GET_CONTACT_FAILURE'
export const PUT_CONTACT = 'PUT_CONTACT'
export const PUT_CONTACT_SUCCESS = 'PUT_CONTACT_SUCCESS'
export const PUT_CONTACT_FAILURE = 'PUT_CONTACT_FAILURE'
export const DELETE_CONTACT = 'DELETE_CONTACT'
export const DELETE_CONTACT_SUCCESS = 'DELETE_CONTACT_SUCCESS'
export const DELETE_CONTACT_FAILURE = 'DELETE_CONTACT_FAILURE'

const API_URL = '/api/'

export function postUser (attributes) {
  return dispatch => {
    dispatch({ type: POST_CONTACT, attributes })

    return window.fetch(`${API_URL}user`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'POST',
      credentials: 'include',
      body: JSON.stringify(attributes)
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: POST_CONTACT_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: POST_CONTACT_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: POST_CONTACT_FAILURE, error }))
  }
}

export function getUsers () {
  return dispatch => {
    dispatch({ type: GET_CONTACTS })

    return window.fetch(`${API_URL}user`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: GET_CONTACTS_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: GET_CONTACTS_FAILURE, data: error }))
  }
}

export function getUser (userId) {
  return dispatch => {
    dispatch({ type: GET_CONTACT, userId })
    return window.fetch(`${API_URL}user/${userId}`, {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: GET_CONTACT_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: GET_CONTACT_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: GET_CONTACT_FAILURE, error }))
  }
}

export function putUser (user) {
  return dispatch => {
    dispatch({ type: PUT_CONTACT, user })

    return window.fetch(`${API_URL}user/${user.id}`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'PUT',
      credentials: 'include',
      body: JSON.stringify(user)
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: PUT_CONTACT_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: PUT_CONTACT_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: PUT_CONTACT_FAILURE, error }))
  }
}

export function deleteUser (userId) {
  return dispatch => {
    dispatch({ type: DELETE_CONTACT, userId })
    return window.fetch(`${API_URL}user/${userId}`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'DELETE',
      credentials: 'include'
    })
      .then(res => {
        if (res.status >= 400) {
          const httpRes = res
          httpRes.json().then(res => {
            dispatch({ type: DELETE_CONTACT_FAILURE, ...{ data: res } })
            return res
          })
        }
        return res
      })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: DELETE_CONTACT_SUCCESS, data: res })
        return res
      })
      .catch(error => dispatch({ type: DELETE_CONTACT_FAILURE, error }))
  }
}
