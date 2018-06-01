import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { withRouter } from 'react-router-dom'
import { TextField, Button, Paper, Typography, Grid, Select, MenuItem, ExpansionPanel, ExpansionPanelDetails, ExpansionPanelSummary } from '@material-ui/core'
import ExpandMoreIcon from '@material-ui/icons/ExpandMore'
import { withStyles } from '@material-ui/core/styles'
import { getPages } from '../../actions'
import RichEditor from './RichEditor'

export class PageForm extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      locale: 'fr',
      versionCount: 0,
      submitDisabled: true,
      page: {
        title: '',
        sub_title: '',
        url: '',
        description: '',
        content: {
          intro: ''
        }
      }
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleInputFilter = this.handleInputFilter.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleLocaleChange = this.handleLocaleChange.bind(this)
    this.handleVersion = this.handleVersion.bind(this)
  }
  handleLocaleChange (event) {
    this.setState({ locale: event.target.value }, () => {
      this.props.dispatch(getPages(this.state.locale))
    })
  }
  handleVersion (event) {
    this.setState({versionCount: event.target.value})
    if (!this.state.loading) {
      this.props.versionHandler(this.state.page, this.props.versions[event.target.value].version)
    }
    event.preventDefault()
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
      this.props.submitHandler(this.state.page)
    }
    event.preventDefault()
  }
  handleInputFilter (event) {
    const re = /[0-9A-Za-z-]+/g
    if (!re.test(event.key)) {
      event.preventDefault()
    }
  }
  componentWillReceiveProps (nextProps) {
    if (nextProps.page) {
      this.setState({page: nextProps.page})
    }
  }
  render () {
    const { classes } = this.props
    const versions = (this.props.versions.length > 0)
      ? this.props.versions.map((v, k) => {
        return (
          <MenuItem value={k} key={v.id}>{v.logged_at}</MenuItem>
        )
      })
      : null
    return (
      <div className={classes.container}>
        <div className={classes.options}>
          <Select
            className={classes.option}
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
          {
            this.props.edit
              ? (
                <Select
                  className={classes.option}
                  value={this.state.versionCount}
                  onChange={this.handleVersion}
                  inputProps={{
                    name: 'historique',
                    id: 'version'
                  }}>
                  {versions}
                </Select>
              )
              : (
                ''
              )
          }
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
        </Paper>
        <Paper className={classes.paper}>
          <Typography variant='headline' className={classes.title} component='h2'>
            CONTENU
          </Typography>
          <form className={classes.form} noValidate onSubmit={this.handleSubmit}>
            <TextField
              autoComplete='off'
              InputLabelProps={{ shrink: true }}
              className={classes.textfield}
              fullWidth
              multiline
              id='intro'
              name='intro'
              label='Introduction'
              value={this.state.page.content.intro}
              onChange={this.handleInputChange} />
            <ExpansionPanel>
              <ExpansionPanelSummary expandIcon={<ExpandMoreIcon />}>
                <Typography>
                  Paragraphe 1
                </Typography>
              </ExpansionPanelSummary>
              <ExpansionPanelDetails className={classes.details}>
                <Grid container>
                  <Grid item xs={6}>
                    <RichEditor />
                  </Grid>
                </Grid>
              </ExpansionPanelDetails>
              <div className={classes.buttons}>
                <Button
                  className={classes.button}
                  variant='raised'
                  aria-label='Supprimer'>
                  Supprimer
                </Button>
                <Button
                  className={classes.button}
                  variant='raised'
                  color='secondary'
                  aria-label='Sauvegarder'
                  onClick={this.handleSubmit}>
                  Sauvegarder
                </Button>
              </div>
            </ExpansionPanel>
          </form>
          <div className={classes.buttons}>
            <Button
              className={classes.button}
              variant='raised'
              color='primary'>
                Ajouter
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
    status: state.postPageStatus,
    versions: state.pageVersions,
    version: state.pageVersion
  }
}

PageForm.propTypes = {
  classes: PropTypes.object.isRequired
}

export default withRouter(compose(withStyles(styles), connect(mapStateToProps))(PageForm))
