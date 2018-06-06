import React, {Fragment} from 'react'
import { compose } from 'redux'
import PropTypes from 'prop-types'
import { connect } from 'react-redux'
import { convertToRaw } from 'draft-js'
import immutable from 'object-path-immutable'
import draftToHtml from 'draftjs-to-html'
import ExpandMoreIcon from '@material-ui/icons/ExpandMore'
import WrapTextIcon from '@material-ui/icons/WrapText'
import SaveIcon from '@material-ui/icons/Save'
import OnDemandVideoIcon from '@material-ui/icons/OndemandVideo'
import PhotoSizeSelectActualIcon from '@material-ui/icons/PhotoSizeSelectActual'
import DeleteIcon from '@material-ui/icons/Delete'
import { withStyles } from '@material-ui/core/styles'
import RichEditor from './RichEditor'
import { tileData } from './tileData'
import { uploadFile } from '../../actions'
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
  Popover,
  IconButton,
  CircularProgress,
  Select,
  Dialog,
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
  FormControl,
  InputLabel,
  Icon
} from '@material-ui/core'
import moment from 'moment'

export class PageForm extends React.Component {
  constructor (props) {
    super(props)
    this.state = {
      locale: this.props.locale,
      page: this.props.page,
      versionCount: 0,
      submitDisabled: true,
      anchorPopover: null,
      popoverOpened: false,
      anchorMenuLayout: null,
      menuLayoutOpened: false,
      showDeleteAlert: false,
      indexTabs: [],
      fileUploading: {
        isUploading: false
      },
      anchorVersion: null
    }
    this.handleInputChange = this.handleInputChange.bind(this)
    this.handleInputFilter = this.handleInputFilter.bind(this)
    this.handleSubmit = this.handleSubmit.bind(this)
    this.handleOpenPopover = this.handleOpenPopover.bind(this)
    this.handleClosePopover = this.handleClosePopover.bind(this)
    this.handleCloseVersion = this.handleCloseVersion.bind(this)
    this.handleChangePopover = this.handleChangePopover.bind(this)
    this.handleOpenLayoutMenu = this.handleOpenLayoutMenu.bind(this)
    this.handleCloseLayoutMenu = this.handleCloseLayoutMenu.bind(this)
    this.handleChangeLayoutMenu = this.handleChangeLayoutMenu.bind(this)
    this.handleVersion = this.handleVersion.bind(this)
    this.handleVersionOpen = this.handleVersionOpen.bind(this)
    this.handleParent = this.handleParent.bind(this)
    this.handleChangeTextArea = this.handleChangeTextArea.bind(this)
    this.handleDelete = this.handleDelete.bind(this)
    this.handleDeleteClose = this.handleDeleteClose.bind(this)
    this.handleDeleteConfirm = this.handleDeleteConfirm.bind(this)
    this.handleChangeTabs = this.handleChangeTabs.bind(this)
    this.handleInitTabs = this.handleInitTabs.bind(this)
    this.handleAddSection = this.handleAddSection.bind(this)
    this.handleDeleteSection = this.handleDeleteSection.bind(this)
    this.handleAddSlide = this.handleAddSlide.bind(this)
    this.handleDeleteSlide = this.handleDeleteSlide.bind(this)
    this.handleChangeFileUpload = this.handleChangeFileUpload.bind(this)
  }

  handleChangeTabs (indexTabs, indexSection) {
    const state = immutable.set(this.state, `indexTabs.${indexSection}`, indexTabs)
    this.setState(state)
  }

  handleInitTabs () {
    const indexTabs = this.state.page.content.sections.map(() => { return 0 })
    const state = immutable.set(this.state, `indexTabs`, indexTabs)
    this.setState(state)
  }

  handleVersion (event, key) {
    event.preventDefault()
    if (key === null) {
      this.props.versionHandler(this.state.page, null)
    } else {
      this.props.versionHandler(this.state.page, this.props.versions[key].version)
    }
    this.setState({ versionCount: key, anchorVersion: null })
  }

  handleParent (event) {
    const parentKey = event.target.value
    this.setState((prevState) => {
      return {
        ...prevState,
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

  handleOpenPopover (event, indexPopover) {
    this.setState({
      anchorPopover: event.currentTarget,
      popoverOpened: indexPopover
    })
  }

  handleClosePopover () {
    this.setState({
      anchorPopover: null,
      popoverOpened: false
    })
  }
  
  handleCloseVersion () {
    this.setState({anchorVersion: null})
  }
  
  handleVersionOpen (event) {
    this.setState({anchorVersion: event.currentTarget})
  }

  handleChangePopover (event, indexSection, indexSlide, indexImage) {
    const state = immutable.set(this.state, `page.content.sections.${indexSection}.slides.${indexSlide}.images.${indexImage}.video`, event.target.value)
    this.setState(state)
  }

  handleOpenLayoutMenu (event, indexSection) {
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
    this.setState(state, () => {
      this.handleCloseLayoutMenu()
    })
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
    this.setState(state, () => {
      this.handleInitTabs()
    })
  }

  handleDeleteSection (indexSection) {
    const state = immutable.del(this.state, `page.content.sections.${indexSection}`)
    this.setState(state, () => {
      this.handleInitTabs()
    })
  }

  handleAddSlide (indexSection) {
    const emptySlide = PageForm.defaultProps.page.content.sections[0].slides[0]
    const position = this.state.page.content.sections[indexSection].slides.length
    const state = immutable.insert(this.state, `page.content.sections.${indexSection}.slides`, emptySlide, position)
    this.setState(state, () => {
      this.handleInitTabs()
    })
  }

  handleDeleteSlide (indexSection) {
    const indexSlide = this.state.indexTabs[indexSection]
    const state = immutable.del(this.state, `page.content.sections.${indexSection}.slides.${indexSlide}`)
    this.setState(state, () => {
      this.handleInitTabs()
    })
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

  handleChangeFileUpload (event, indexSection, indexSlide, indexImage) {
    this.props.dispatch(uploadFile(event.target.files[0]))
    this.setState({
      fileUploading: {
        isUploading: true,
        indexSection: indexSection,
        indexSlide: indexSlide,
        indexImage: indexImage
      }
    })
  }

  componentWillMount () {
    this.handleInitTabs()
  }

  componentWillReceiveProps (nextProps) {
    if (nextProps.page) {
      const state = immutable.set(this.state, 'page', nextProps.page)
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
    if (nextProps.uploadStatus) {
      const fileUploading = this.state.fileUploading
      const pathImage = nextProps.uploadStatus.path
      const state = immutable.set(this.state, `page.content.sections.${fileUploading.indexSection}.slides.${fileUploading.indexSlide}.images.${fileUploading.indexImage}.url`, pathImage)
      this.setState(() => {
        return {
          ...state,
          fileUploading: {
            isUploading: false
          }
        }
      })
    }
  }

  render () {
    const { classes } = this.props
    const { anchorMenuLayout } = this.state
    const versions = this.props.versions
    const parents = (this.props.parents.length > 0)
      ? this.props.parents.map((p, k) => {
        return (
          <MenuItem value={k} key={k}>{p.title}</MenuItem>
        )
      })
      : null
    return (
      <div className={classes.container}>
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
            <FormControl style={{minWidth: 200}}>
              <InputLabel htmlFor={'parent'} shrink>Page parente</InputLabel>
              <Select
                id={'parent'}
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
            </FormControl>
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
                                      <div className={classes.tile} style={{backgroundImage: `url('${slide.images[tile.id].url}')`}}>
                                        {
                                          this.state.fileUploading.isUploading &&
                                          this.state.fileUploading.indexSection === indexSection &&
                                          this.state.fileUploading.indexSlide === indexSlide &&
                                          this.state.fileUploading.indexImage === indexImage
                                            ? <CircularProgress />
                                            : (
                                              <div>
                                                <IconButton
                                                  color={slide.images[tile.id].url === '' ? 'primary' : 'secondary'}>
                                                  <PhotoSizeSelectActualIcon />
                                                  <input
                                                    type='file'
                                                    className={classes.inputfile}
                                                    onChange={event => { this.handleChangeFileUpload(event, indexSection, indexSlide, indexImage) }} />
                                                </IconButton>
                                                <IconButton
                                                  color={slide.images[tile.id].video === '' ? 'primary' : 'secondary'}
                                                  onClick={event => { this.handleOpenPopover(event, `${indexSection}-${indexSlide}-${indexImage}`) }}>
                                                  <OnDemandVideoIcon />
                                                </IconButton>
                                                <Popover
                                                  open={this.state.popoverOpened === `${indexSection}-${indexSlide}-${indexImage}`}
                                                  anchorEl={this.state.anchorPopover}
                                                  onClose={this.handleClosePopover}>
                                                  <TextField
                                                    className={classes.popover}
                                                    autoComplete='off'
                                                    InputLabelProps={{ shrink: true }}
                                                    name='page.title'
                                                    label='URL Vidéo'
                                                    value={this.state.page.content.sections[indexSection].slides[indexSlide].images[indexImage].video}
                                                    onChange={(event) => { this.handleChangePopover(event, indexSection, indexSlide, indexImage) }} />
                                                </Popover>
                                              </div>
                                            )
                                        }
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
                          onClick={event => { this.handleOpenLayoutMenu(event, indexSection) }}
                          className={classes.option}>
                          Disposition
                        </Button>
                        <Button
                          variant='outlined'
                          onClick={() => { this.handleDeleteSlide(indexSection) }}
                          disabled={section.slides.length === 1}
                          className={classes.option}>
                          Supprimer
                        </Button>
                        <Button
                          variant='outlined'
                          onClick={() => { this.handleAddSlide(indexSection) }}
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
                    onClick={() => { this.handleDeleteSection(indexSection) }}
                    disabled={this.state.page.content.sections.length === 1}>
                    Supprimer
                  </Button>
                </ExpansionPanelActions>
              </ExpansionPanel>
            ))
          }
        </form>
        <div className={classes.buttons}>
          {
            this.props.edit &&
            (
              <Fragment>
                <Button
                  className={classes.button}
                  variant='fab'
                  color='primary'
                  aria-label='More'
                  aria-owns={this.state.anchorVersion ? 'long-menu' : null}
                  aria-haspopup='true'
                  onClick={this.handleVersionOpen}
                >
                  <Icon>history</Icon>
                </Button>
                <Menu
                  id='long-menu'
                  anchorEl={this.state.anchorVersion}
                  open={Boolean(this.state.anchorVersion)}
                  onClose={this.handleCloseVersion}
                  PaperProps={{
                    style: {
                      maxHeight: 40 * 4.5,
                      width: 200
                    }
                  }}
                >
                  <MenuItem key={null} selected={this.state.versionCount === null} onClick={event => this.handleVersion(event, null)}>Courante</MenuItem>
                  {Object.keys(versions).map((key) => (
                    <MenuItem key={key} selected={key === this.state.versionCount} onClick={event => this.handleVersion(event, key)}>
                      {moment(versions[key].logged_at).format('DD/MM/YYYY HH:mm:ss')}
                    </MenuItem>
                  ))}
                  }
                </Menu>
              </Fragment>

            )
          }
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
  },
  tile: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    width: '100%',
    height: '100%',
    backgroundSize: 'cover',
    backgroundPosition: 'center',
    backgroundColor: '#eeeeee'
  },
  inputfile: {
    position: 'absolute',
    width: '100%',
    height: '100%',
    cursor: 'pointer',
    opacity: 0
  },
  popover: {
    margin: theme.myMarge
  }
})

const mapStateToProps = state => {
  return {
    status: state.postPageStatus,
    versions: state.pageVersions,
    version: state.pageVersion,
    locale: state.locale,
    uploadStatus: state.uploadStatus
  }
}

PageForm.propTypes = {
  classes: PropTypes.object.isRequired
}

PageForm.defaultProps = {
  parents: {},
  parentKey: 0,
  versions: [],
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
              images: [
                { type: '', url: '', alt: '', video: '' },
                { type: '', url: '', alt: '', video: '' },
                { type: '', url: '', alt: '', video: '' },
                { type: '', url: '', alt: '', video: '' }
              ]
            }
          ]
        }
      ]
    }
  }
}

export default compose(withStyles(styles), connect(mapStateToProps))(PageForm)
