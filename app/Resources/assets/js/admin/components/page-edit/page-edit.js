import React from 'react'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import { Button, DialogActions, Dialog, DialogContent, DialogContentText, DialogTitle, Icon } from '@material-ui/core'
import { getPage, putPage, setTitle } from '../../actions'
import PageForm from '../page-form/page-form'
import { t } from '../../translations'

export class PageEdit extends React.Component {
  constructor (props) {
    super(props)
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
    this.onVersionChange = this.onVersionChange.bind(this)
  }
  componentDidMount () {
    const { match: { params } } = this.props
    this.props.dispatch(getPage(params.pageId))
  }
  handleClose () {
    this.setState({alertOpen: false})
  }
  componentWillReceiveProps (nextProps) {
    this.props.dispatch(setTitle(`Modification de la page ${(nextProps.page) ? nextProps.page.title : ''}`))
    this.setState({ alertOpen: (nextProps.status !== 'ok' && nextProps.status !== null) })
  }
  onSubmit (page) {
    this.props.dispatch(putPage(page))
  }
  onVersionChange (page, version) {
    this.props.dispatch(getPage(page.id, version))
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
        <PageForm page={this.props.page} submitHandler={this.onSubmit} versionHandler={this.onVersionChange} edit />
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
