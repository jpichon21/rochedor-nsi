import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import { TextField, Button, Paper, Typography, Select, MenuItem } from '@material-ui/core'
import { withStyles } from '@material-ui/core/styles'
import { postPage, getPages } from '../../actions'

export class PageForm extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      locale: 'fr',
      page: {
        title: '',
        sub_title: '',
        url: '',
        description: '',
        locale: 'fr'
      },
      submitDisabled: true
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleInputFilter = this.handleInputFilter.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleLocaleChange = this.handleLocaleChange.bind(this)
  }
  handleLocaleChange (event) {
    this.setState({ locale: event.target.value }, () => {
      this.props.dispatch(getPages(this.state.locale))
    })
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
        this.setState({ alertOpen: (this.props.status >= 400) })
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
  render () {
    const { classes } = this.props
    return (
      <div className={classes.container}>
        <div className={classes.options}>
          <Select
            value={this.state.locale}
            onChange={this.handleLocaleChange}
            inputProps={{
              name: 'langue',
              id: 'locale'
            }}>
            <MenuItem value={'fr'}>FR</MenuItem>
            <MenuItem value={'en'}>EN</MenuItem>
            <MenuItem value={'es'}>ES</MenuItem>
            <MenuItem value={'de'}>DE</MenuItem>
            <MenuItem value={'it'}>IT</MenuItem>
          </Select>
        </div>
        <Paper className={classes.paper}>
          <Typography variant='headline' className={classes.title} component='h2'>
            SEO
          </Typography>
          <form className={classes.form} noValidate onSubmit={this.handleSubmit}>
            <TextField
              autoComplete='off'
              InputLabelProps={{ shrink: true }}
              className={classes.textfield}
              fullWidth
              required
              id='title'
              name='title'
              label='Titre ligne 1'
              value={this.state.page.title}
              onChange={this.handleInputChange} />
            <TextField
              autoComplete='off'
              InputLabelProps={{ shrink: true }}
              className={classes.textfield}
              fullWidth
              id='sub_title'
              name='sub_title'
              label='Titre ligne 2'
              value={this.state.page.sub_title}
              onChange={this.handleInputChange} />
            <TextField
              autoComplete='off'
              InputLabelProps={{ shrink: true }}
              className={classes.textfield}
              fullWidth
              id='url'
              name='url'
              label='Url'
              value={this.state.page.url}
              onChange={this.handleInputChange}
              onKeyPress={this.handleInputFilter} />
            <TextField
              autoComplete='off'
              InputLabelProps={{ shrink: true }}
              className={classes.textfield}
              fullWidth
              multiline
              id='description'
              name='description'
              label='Meta-description'
              value={this.state.page.description}
              onChange={this.handleInputChange} />
          </form>
          <div className={classes.buttons}>
            <Button
              className={classes.button}
              variant='raised'
              color='primary'
              aria-label='Sauvegarder'
              onClick={this.handleSubmit}
              disabled={this.state.submitDisabled || this.props.loading}>
                Sauvegarder
            </Button>
          </div>
        </Paper>
      </div>
    )
  }
}

const styles = theme => ({
  ...theme
})

const mapStateToProps = state => {
  return {
    loading: state.loading,
    status: state.postPageStatus
  }
}

PageForm.propTypes = {
  classes: PropTypes.object.isRequired
}

export default withRouter(compose(withStyles(styles), connect(mapStateToProps))(PageForm))
