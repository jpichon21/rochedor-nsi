import { DO_LOGIN } from './actions'

export const rootReducer = (state, action) => {
  console.log(`Action: ${JSON.stringify(action)}`)
  switch (action.type) {
    case DO_LOGIN:
  }
  return state
}
