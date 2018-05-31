import React from 'react'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import { TextField, Button, DialogActions, Dialog, DialogContent, DialogContentText, DialogTitle, Icon } from '@material-ui/core'
import { postPage } from '../../actions'

const errors = {
  403: 'Ce nom ou cette adresse est déjà utilisé, veuillez utiliser autre chose.'
}

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
        locale: 'fr'
      },
      alertOpen: false,
      submitDisabled: true
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleInputFilter = this.handleInputFilter.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleClose = this.handleClose.bind(this)
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
      disabled = (this.state.page.title === '' || this.state.page.description === '')
      this.setState({ submitDisabled: disabled })
    })
  }
  handleSubmit (event) {
    if (!this.state.loading && !this.state.submitDisabled) {
      this.props.dispatch(postPage(this.state.page)).then(() => {
        this.setState({alertOpen: (this.props.status >= 400)})
      })
    }
    event.preventDefault()
  }
  handleInputFilter (event) {
    const re = /[0-9A-Za-z-]+/g
    if (!re.test(event.key)) {
      event.preventDefault()
    }
  }
  handleClose () {
    this.setState({alertOpen: false})
  }
  render () {
    return (
      <div>
        <Dialog
          open={this.state.alertOpen}
          onClose={this.handleClose}
          aria-labelledby='alert-dialog-title'
          aria-describedby='alert-dialog-description'
        >
          <DialogTitle id='alert-dialog-title'><Icon color='error'>error</Icon>{'Une erreur est survenue'}</DialogTitle>
          <DialogContent>
            <DialogContentText id='alert-dialog-description'>
              {errors[this.props.status]}
            </DialogContentText>
          </DialogContent>
          <DialogActions>
            <Button onClick={this.handleClose} color='primary' autoFocus>
            Ok
            </Button>
          </DialogActions>
        </Dialog>
        <form noValidate onSubmit={this.handleSubmit}>
          <TextField required id='title' name='title' label='Titre ligne 1' value={this.state.page.title} onChange={this.handleInputChange} />
          <TextField id='sub_title' name='sub_title' label='Titre ligne 2' value={this.state.page.sub_title} onChange={this.handleInputChange} />
          <TextField id='url' name='url' label='Url' value={this.state.page.url} onChange={this.handleInputChange} onKeyPress={this.handleInputFilter} />
          <TextField multiline id='description' name='description' label='Meta-description' value={this.state.page.description} onChange={this.handleInputChange} />
          <Button variant='raised' color='primary' onClick={this.handleSubmit} disabled={this.state.submitDisabled || this.props.loading}>Créer la page</Button>
        </form>
      </div>
    )
  }
}

const mapStateToProps = state => {
  return {
    loading: state.loading,
    status: state.postPageStatus
  }
}

export default withRouter(connect(mapStateToProps)(PageCreate))
