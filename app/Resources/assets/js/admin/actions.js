/* PARAMS */

export const API_URL = '/api/'

/* ACTIONS */
export const GET_PAGES = 'GET_PAGES'
export const GET_PAGES_SUCCESS = 'GET_PAGES_SUCCESS'
export const GET_PAGES_FAILURE = 'GET_PAGES_FAILURE'

export function getPages (locale = 'fr') {
  return dispatch => {
    dispatch({ type: GET_PAGES, locale })

    return window.fetch(`${API_URL}pages?locale=${locale}`, {
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
