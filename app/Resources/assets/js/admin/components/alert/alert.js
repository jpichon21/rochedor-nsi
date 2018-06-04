import React from 'react'
import { Button, DialogActions, Dialog, DialogContent, DialogContentText, DialogTitle, Icon } from '@material-ui/core'
import { t } from '../../translations'

export default class Alert extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      open: false
    }
    this.handleClose = this.handleClose.bind(this)
  }
  handleClose () {
    this.props.onClose()
  }
  render () {
    return (
      <Dialog
        open={this.props.open}
        onClose={this.handleClose}
        aria-labelledby='alert-dialog-title'
        aria-describedby='alert-dialog-description'>
        <DialogTitle id='alert-dialog-title'>
          <Icon color='error'>error</Icon>
          {'Une erreur est survenue'}
        </DialogTitle>
        <DialogContent>
          <DialogContentText id='alert-dialog-description'>
            {t.t(this.props.content)}
          </DialogContentText>
        </DialogContent>
        <DialogActions>
          <Button onClick={this.handleClose} color='primary' autoFocus>Ok</Button>
        </DialogActions>
      </Dialog>

    )
  }
}

Alert.defaultProps = {
  open: false,
  content: ''
}
