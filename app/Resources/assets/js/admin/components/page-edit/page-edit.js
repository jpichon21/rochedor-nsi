import React from 'react'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import { TextField, Button, DialogActions, Dialog, DialogContent, DialogContentText, DialogTitle, Icon } from '@material-ui/core'
import { getPage, putPage } from '../../actions'
import PageForm from '../page-form/page-form'
import { t } from '../../translations'

export class PageEdit extends React.Component {
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
    this.onSubmit = this.onSubmit.bind(this)
    this.handleClose = this.handleClose.bind(this)
  }
  componentDidMount () {
    this.setTitle()
    const { match: { params } } = this.props
    this.props.dispatch(getPage(params.pageId))
  }
  setTitle () {
    this.props.title(`Modification de la page ${this.props.page.title}`)
  }
  handleClose () {
    this.setState({alertOpen: false})
  }
  componentWillReceiveProps (nextProps) {
    this.setState({ alertOpen: (nextProps.status !== 'ok' && nextProps.status !== null) })
  }
  onSubmit (page) {
    this.props.dispatch(putPage(page))
  }
  render () {
    return (
      <div>
        <Dialog
          open={this.state.alertOpen || false}
          onClose={this.handleClose}
          aria-labelledby='alert-dialog-title'
          aria-describedby='alert-dialog-description'
        >
          <DialogTitle id='alert-dialog-title'><Icon color='error'>error</Icon>{'Une erreur est survenue'}</DialogTitle>
          <DialogContent>
            <DialogContentText id='alert-dialog-description'>
              {t.t(this.props.status)}
            </DialogContentText>
          </DialogContent>
          <DialogActions>
            <Button onClick={this.handleClose} color='primary' autoFocus>
            Ok
            </Button>
          </DialogActions>
        </Dialog>
        <PageForm page={this.props.page} handleSubmit={this.onSubmit} />
      </div>
    )
  }
}

const mapStateToProps = state => {
  return {
    loading: state.loading,
    page: state.page,
    status: state.status
  }
}

export default withRouter(connect(mapStateToProps)(PageEdit))
