import React from 'react'
import { withRouter } from 'react-router-dom'

export class PageCreate extends React.Component {
  constructor (props) {
    super(props)
    this.setTitle = this.setTitle.bind(this)
  }
  componentDidMount () {
    this.setTitle()
  }
  setTitle () {
    this.props.title('Ajout d\'une page')
  }
  render () {
    return (
      <div>
        Formulaire de création
      </div>
    )
  }
}


export default withRouter(PageCreate)