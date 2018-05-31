import React from 'react'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import { Button, DialogActions, Dialog, DialogContent, DialogContentText, DialogTitle, Icon } from '@material-ui/core'
import PageForm from '../page-form/page-form'

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
      alertOpen: false
    }
    this.handleClose = this.handleClose.bind(this)
  }
  componentDidMount () {
    this.setTitle()
  }
  setTitle () {
    this.props.title('Ajout d\'une page')
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
          aria-describedby='alert-dialog-description'>
          <DialogTitle id='alert-dialog-title'>
            <Icon color='error'>error</Icon>
            {'Une erreur est survenue'}
          </DialogTitle>
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
        <PageForm />
      </div>
    )
  }
}

const mapStateToProps = state => {
  return {
    status: state.postPageStatus
  }
}

export default withRouter(connect(mapStateToProps)(PageCreate))
