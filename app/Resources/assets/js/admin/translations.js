import { I18n } from 'react-i18nify'

I18n.setTranslations({
  fr: {
    'Page not found': 'Page inexistante',
    'Route already exists': 'Ce nom ou cette adresse est déjà utilisé, veuillez utiliser autre chose.',
    'News not found': 'Cette nouveauté n\'existe pas',
    'File Exists': 'Le fichier existe déjà',
    'Invalid credentials': 'Identifiants incorrects'
  }
})

I18n.setLocale('fr')
export const t = I18n
