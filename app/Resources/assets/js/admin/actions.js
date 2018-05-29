/* PARAMS */

export const API_URL = '/api/'

/* ACTIONS */
export const GET_PAGES = 'GET_PAGES'
export const GET_PAGES_SUCCESS = 'GET_PAGES_SUCCESS'
export const GET_PAGES_FAILURE = 'GET_PAGES_FAILURE'

export function getPages (terms = '', types = { tags: false, symbol: false, name: false }) {
  return dispatch => {
    dispatch({ type: GET_PAGES, terms, types })

    return window.fetch(`${API_URL}pages`, {
      headers: { 'Content-Type': 'application/json' },
      method: 'GET',
      credentials: 'include'
    })
      .then(res => res.json())
      .then(res => {
        if (res.error) throw res.error
        dispatch({ type: GET_PAGES_SUCCESS, data: res })
      })
      .catch(error => dispatch({ type: GET_PAGES_FAILURE, error }))
  }
}
