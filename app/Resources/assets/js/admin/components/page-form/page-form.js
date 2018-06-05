import React from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { convertToRaw } from 'draft-js'
import immutable from 'object-path-immutable'
import draftToHtml from 'draftjs-to-html'
import ExpandMoreIcon from '@material-ui/icons/ExpandMore'
import WrapTextIcon from '@material-ui/icons/WrapText'
import SaveIcon from '@material-ui/icons/Save'
import DeleteIcon from '@material-ui/icons/Delete'
import { withStyles } from '@material-ui/core/styles'
import RichEditor from './RichEditor'
import { tileData } from './tileData'
import {
  Tab,
  Tabs,
  MenuItem,
  Menu,
  GridList,
  GridListTile,
  TextField,
  Button,
  Typography,
  Grid,
  ExpansionPanel,
  ExpansionPanelDetails,
  ExpansionPanelSummary,
  ExpansionPanelActions,
  Divider,
  Select,
  Dialog,
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle } from '@material-ui/core'

export class PageForm extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      locale: this.props.locale,
      page: this.props.page,
      versionCount: 0,
      submitDisabled: true,
      anchorMenuLayout: null,
      menuLayoutOpened: false,
      showDeleteAlert: false,
      indexTabs: [0, 0, 0, 0, 0]
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleInputFilter = this.handleInputFilter.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleOpenLayoutMenu = this.handleOpenLayoutMenu.bind(this)
    this.handleCloseLayoutMenu = this.handleCloseLayoutMenu.bind(this)
    this.handleChangeLayoutMenu = this.handleChangeLayoutMenu.bind(this)
    this.handleVersion = this.handleVersion.bind(this)
    this.handleParent = this.handleParent.bind(this)
    this.handleChangeTextArea = this.handleChangeTextArea.bind(this)
    this.handleDelete = this.handleDelete.bind(this)
    this.handleDeleteClose = this.handleDeleteClose.bind(this)
    this.handleDeleteConfirm = this.handleDeleteConfirm.bind(this)
    this.handleChangeTabs = this.handleChangeTabs.bind(this)
    this.handleAddSection = this.handleAddSection.bind(this)
  }

  handleChangeTabs (indexTabs, indexSection) {
    const state = immutable.set(this.state, `indexTabs.${indexSection}`, indexTabs)
    this.setState(state)
  }

  handleVersion (event) {
    this.setState({ versionCount: event.target.value })
    this.props.versionHandler(this.state.page, this.props.versions[event.target.value].version)
    event.preventDefault()
  }

  handleParent (event) {
    const parentKey = event.target.value
    this.setState((prevState) => {
      return {
        page: {
          ...prevState.page,
          parent_id: this.props.parents[parentKey].id
        },
        parentKey: parentKey
      }
    })
  }

  handleInputChange (event) {
    const state = immutable.set(this.state, event.target.name, event.target.value)
    this.setState(state)
  }

  handleChangeTextArea (editorState, indexSection) {
    const rawContentState = convertToRaw(editorState.getCurrentContent())
    const html = draftToHtml(rawContentState)
    const state = immutable.set(this.state, `page.content.sections.${indexSection}.body`, html)
    this.setState(state)
  }

  handleSubmit (event) {
    event.preventDefault()
    this.props.submitHandler(this.state.page)
  }

  handleInputFilter (event) {
    const re = /[0-9A-Za-z-]+/g
    if (!re.test(event.key)) {
      event.preventDefault()
    }
  }

  handleOpenLayoutMenu (indexSection, event) {
    this.setState({
      anchorMenuLayout: event.currentTarget,
      menuLayoutOpened: indexSection
    })
  }

  handleCloseLayoutMenu () {
    this.setState({
      anchorMenuLayout: null,
      menuLayoutOpened: false
    })
  }

  handleChangeLayoutMenu (layout, indexSection) {
    const indexSlide = this.state.indexTabs[indexSection]
    const state = immutable.set(this.state, `page.content.sections.${indexSection}.slides.${indexSlide}.layout`, layout)
    this.setState(state)
    this.handleCloseLayoutMenu()
  }

  handleDelete () {
    this.setState({ showDeleteAlert: true })
  }

  handleDeleteClose () {
    this.setState({ showDeleteAlert: false })
  }

  handleDeleteConfirm () {
    this.props.deleteHandler(this.state.page)
    this.setState({ showDeleteAlert: false })
  }

  handleAddSection () {
    const emptySection = PageForm.defaultProps.page.content.sections[0]
    const position = this.state.page.content.sections.length
    const state = immutable.insert(this.state, `page.content.sections`, emptySection, position)
    this.setState(state)
  }

  isSubmitEnabled () {
    const p = this.state.page
    if (p.title === '' || p.description === '' || p.url === '') {
      return false
    }
    if (p.locale !== 'fr' && !p.parent_id) {
      return false
    }
    if (p.locale === 'fr' && p.parent_id) {
      return false
    }
    return true
  }

  componentWillReceiveProps (nextProps) {
    if (nextProps.page) {
      const state = immutable.update(this.state, 'page', () => {
        return nextProps.page
      })
      this.setState(state)
    }
    if (nextProps.locale) {
      this.setState((prevState) => {
        return {
          page: {
            ...prevState.page,
            locale: nextProps.locale,
            parent_id: null
          }
        }
      })
    }
  }

  render () {
    const { classes } = this.props
    const { anchorMenuLayout } = this.state
    const versions = (this.props.versions.length > 0)
      ? this.props.versions.map((v, k) => {
        return (
          <MenuItem value={k} key={v.id}>{v.logged_at}</MenuItem>
        )
      })
      : null
    const parents = (this.props.parents.length > 0)
      ? this.props.parents.map((p, k) => {
        return (
          <MenuItem value={k} key={k}>{p.title}</MenuItem>
        )
      })
      : null
    return (
      <div className={classes.container}>
        {
          this.props.edit &&
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
        }
        <Typography variant='display1' className={classes.title}>
          SEO
        </Typography>
        <form className={classes.form}>
          <TextField
            required
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            name='page.title'
            label='Titre ligne 1'
            value={this.state.page.title}
            onChange={this.handleInputChange} />
          <TextField
            required
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            name='page.sub_title'
            label='Titre ligne 2'
            value={this.state.page.sub_title}
            onChange={this.handleInputChange} />
          <TextField
            required
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            name='page.url'
            label='Url'
            value={this.state.page.url}
            onChange={this.handleInputChange}
            onKeyPress={this.handleInputFilter} />
          <TextField
            required
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            multiline
            name='page.description'
            label='Meta-description'
            value={this.state.page.description}
            onChange={this.handleInputChange} />
          {
            !this.props.edit &&
            this.props.parents.length > 0 &&
            this.state.page.locale !== 'fr' &&
              <Select
                placeholder={'Page parente'}
                className={classes.option}
                value={this.state.parentKey}
                onChange={this.handleParent}
                inputProps={{
                  name: 'parent_key',
                  id: 'parent_key'
                }}>
                {parents}
              </Select>
          }
        </form>
        <Typography variant='display1' className={classes.title}>
          Contenu
        </Typography>
        <form className={classes.form}>
          <TextField
            autoComplete='off'
            InputLabelProps={{ shrink: true }}
            className={classes.textfield}
            fullWidth
            multiline
            name='page.content.intro'
            label='Introduction'
            value={this.state.page.content.intro}
            onChange={this.handleInputChange} />
          {
            this.state.page.content.sections.map((section, indexSection) => (
              <ExpansionPanel key={indexSection} className={classes.expansion}>
                <ExpansionPanelSummary expandIcon={<ExpandMoreIcon />}>
                  <Typography>
                    {
                      section.title === ''
                        ? 'Volet ' + (indexSection + 1)
                        : section.title
                    }
                  </Typography>
                </ExpansionPanelSummary>
                <ExpansionPanelDetails className={classes.details}>
                  <Grid container spacing={32}>
                    <Grid item xs={6}>
                      <TextField
                        required
                        autoComplete='off'
                        InputLabelProps={{ shrink: true }}
                        className={classes.textfield}
                        fullWidth
                        multiline
                        name={`page.content.sections.${indexSection}.title`}
                        label='Titre'
                        value={section.title}
                        onChange={this.handleInputChange} />
                      <RichEditor
                        indexSection={indexSection}
                        onChange={this.handleChangeTextArea} />
                    </Grid>
                    <Grid item xs={6}>
                      <Tabs
                        value={this.state.indexTabs[indexSection]}
                        onChange={(event, value) => this.handleChangeTabs(value, indexSection)}
                        indicatorColor='primary'
                        textColor='primary'
                        centered>
                        {
                          section.slides.map((slide, indexSlide) => (
                            <Tab key={indexSlide} label={`Slide ${indexSlide + 1}`} />
                          ))
                        }
                      </Tabs>
                      {
                        section.slides.map((slide, indexSlide) => (
                          <div key={indexSlide}>
                            {
                              this.state.indexTabs[indexSection] === indexSlide &&
                              <GridList className={classes.gridList} cols={2} rows={2}>
                                {
                                  tileData[slide.layout].map((tile, indexImage) => (
                                    <GridListTile key={tile.id} cols={tile.cols} rows={tile.rows}>
                                      <div style={{
                                        width: '100%',
                                        height: '100%',
                                        backgroundImage: `url('${tile.img}')`,
                                        backgroundSize: 'cover',
                                        backgroundPosition: 'center'
                                      }}>
                                        Coucou
                                      </div>
                                    </GridListTile>
                                  ))
                                }
                              </GridList>
                            }
                          </div>
                        ))
                      }
                      <div className={classes.options}>
                        <Button
                          variant='outlined'
                          onClick={event => { this.handleOpenLayoutMenu(indexSection, event) }}
                          className={classes.option}>
                          Disposition
                        </Button>
                        <Button
                          variant='outlined'
                          disabled={section.slides.length === 1}
                          className={classes.option}>
                          Supprimer
                        </Button>
                        <Button
                          variant='outlined'
                          color='primary'
                          className={classes.option}>
                          Ajouter
                        </Button>
                      </div>
                      <Menu
                        anchorEl={anchorMenuLayout}
                        open={this.state.menuLayoutOpened === indexSection}
                        onClose={this.handleCloseLayoutMenu}>
                        <MenuItem onClick={() => { this.handleChangeLayoutMenu('2', indexSection) }}>1 Image</MenuItem>
                        <MenuItem onClick={() => { this.handleChangeLayoutMenu('2-2', indexSection) }}>2 Images horizontales</MenuItem>
                        <MenuItem onClick={() => { this.handleChangeLayoutMenu('1-1', indexSection) }}>2 Images verticales</MenuItem>
                        <MenuItem onClick={() => { this.handleChangeLayoutMenu('2-1-1', indexSection) }}>3 Images (Horizontale en haut)</MenuItem>
                        <MenuItem onClick={() => { this.handleChangeLayoutMenu('1-1-2', indexSection) }}>3 Images (Horizontale en bas)</MenuItem>
                        <MenuItem onClick={() => { this.handleChangeLayoutMenu('1-1-1-1', indexSection) }}>4 Images</MenuItem>
                      </Menu>
                    </Grid>
                  </Grid>
                </ExpansionPanelDetails>
                <Divider />
                <ExpansionPanelActions>
                  <Button
                    disabled={this.state.page.content.sections.length === 1}>
                    Supprimer
                  </Button>
                </ExpansionPanelActions>
              </ExpansionPanel>
            ))
          }
        </form>
        <div className={classes.buttons}>
          <Button
            onClick={this.handleAddSection}
            className={classes.button}
            variant='fab'
            color='primary'>
            <WrapTextIcon />
          </Button>
          {
            this.props.edit &&
              <div>
                <Button
                  onClick={this.handleDelete}
                  className={classes.button}
                  variant='fab'
                  color='secondary'>
                  <DeleteIcon />
                </Button>
                <Dialog
                  open={this.state.showDeleteAlert}
                  onClose={this.handleDeleteClose}
                  aria-labelledby='alert-dialog-title'
                  aria-describedby='alert-dialog-description'>
                  <DialogTitle id='alert-dialog-title'>
                    {'Êtes-vous sure?'}
                  </DialogTitle>
                  <DialogContent>
                    <DialogContentText id='alert-dialog-description'>
                      Cette action est irréversible, souhaitez-vous continuer?
                    </DialogContentText>
                  </DialogContent>
                  <DialogActions>
                    <Button onClick={this.handleDeleteConfirm} color='secondary' autoFocus>Oui</Button>
                    <Button onClick={this.handleDeleteClose} color='primary' autoFocus>Annuler</Button>
                  </DialogActions>
                </Dialog>
              </div>
          }
          <Button
            disabled={!this.isSubmitEnabled()}
            onClick={this.handleSubmit}
            className={classes.button}
            variant='fab'
            color='secondary'>
            <SaveIcon />
          </Button>
        </div>
      </div>
    )
  }
}

const styles = theme => ({
  ...theme,
  paper: {
    padding: theme.myMarge
  },
  options: {
    marginTop: theme.myMarge,
    textAlign: 'center'
  },
  option: {
    marginRight: theme.myMarge / 3,
    marginLeft: theme.myMarge / 3
  },
  expansion: {
    marginBottom: theme.myMarge
  },
  gridList: {
    paddingTop: theme.myMarge
  }
})

const mapStateToProps = state => {
  return {
    status: state.postPageStatus,
    versions: state.pageVersions,
    version: state.pageVersion,
    locale: state.locale
  }
}

PageForm.propTypes = {
  classes: PropTypes.object.isRequired
}

PageForm.defaultProps = {
  parents: {},
  parentKey: 0,
  page: {
    locale: 'fr',
    title: '',
    sub_title: '',
    url: '',
    description: '',
    parent_id: null,
    content: {
      intro: '',
      sections: [
        {
          title: '',
          body: '',
          slides: [
            {
              layout: '1-1-2',
              images: []
            }
          ]
        }
      ]
    }
  }
}

export default compose(withStyles(styles), connect(mapStateToProps))(PageForm)
