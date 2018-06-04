import reduceReducers from 'reduce-reducers'
import commonReducer from './common'
import pageReducer from './page'
import newsReducer from './news'

export default reduceReducers(commonReducer, pageReducer, newsReducer)
