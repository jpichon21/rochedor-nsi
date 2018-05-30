import React from 'react'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import { TextField, Select, MenuItem, Button } from '@material-ui/core';
import { postPage } from '../../actions'

export class PageCreate extends React.Component {
  constructor (props) {
    super(props)
    this.setTitle = this.setTitle.bind(this)
    this.state = {
      page: {
        title: '',
        sub_title: '',
        url: '',
        description: '',
        locale: 'fr',
        submitDisabled: true,
        parent: null
      },
      loading: false
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
  }
  componentDidMount () {
    this.setTitle()
  }
  setTitle () {
    this.props.title('Ajout d\'une page')
  }
  handleInputChange (event) {
    const value = event.target.value
    const name = event.target.name
    this.setState(prevState => {
      return {
        page: {
          ...prevState.page,
          [name]: value
        }
      }
    }, () => {
      let disabled = false
      disabled = ((this.state.page.locale !== 'fr' && this.state.page.parent === null) || this.state.page.title === '' || this.state.page.description === '')
      this.setState({ submitDisabled: disabled })
    })
  }
  handleSubmit (event) {
    if (!this.state.loading && !this.state.submitDisabled) {
      this.props.dispatch(postPage(this.state.page))
    }
    event.preventDefault()
  }
  render () {
    return (
      <div>
        <form noValidate onSubmit={this.handleSubmit}>
          <TextField required id='title' name='title' label='Titre ligne 1' value={this.state.page.title} onChange={this.handleInputChange} />
          <TextField id='sub_title' name='sub_title' label='Titre ligne 2' value={this.state.page.sub_title} onChange={this.handleInputChange} />
          <TextField id='url' name='url' label='Url' value={this.state.page.url} onChange={this.handleInputChange} />
          <TextField multiline id='description' name='description' label='Meta-description' value={this.state.page.description} onChange={this.handleInputChange} />
          <Select
            value={this.state.page.locale}
            onChange={this.handleInputChange}
            inputProps={{
              name: 'locale',
              id: 'locale'
            }}
          >
            <MenuItem value={'fr'}>fr</MenuItem>
            <MenuItem value={'en'}>en</MenuItem>
            <MenuItem value={'es'}>es</MenuItem>
            <MenuItem value={'de'}>de</MenuItem>
            <MenuItem value={'it'}>it</MenuItem>
          </Select>
          <Button variant='raised' color='primary' onClick={this.handleSubmit} disabled={this.state.submitDisabled}>Sauvegarder</Button>
        </form>
      </div>
    )
  }
}

const mapStateToProps = state => {
  return {
    loading: state.loading
  }
}

export default withRouter(connect(mapStateToProps)(PageCreate))
