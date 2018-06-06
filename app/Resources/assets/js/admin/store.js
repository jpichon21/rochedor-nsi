import { createStore, compose, applyMiddleware } from 'redux'
import thunk from 'redux-thunk'
import reduceReducers from './reducers'
export function configureStore (initialState) {
  const composeEnhancers = window.__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose
  return createStore(reduceReducers, initialState, composeEnhancers(applyMiddleware(thunk)))
}
