const participant = {
  nom: '',
  prenom: '',
  codco: '',
  ident: '',
  civil: '',
  civil2: '',
  adresse: '',
  cp: '',
  ville: '',
  pays: '',
  tel: '',
  mobil: '',
  email: '',
  profession: '',
  datnaiss: '',
  coltyp: '',
  colp: '',
  transport: '',
  memo: '',
  check: false
}

const clone = (obj) => {
  var tmp = JSON.stringify(obj)
  return JSON.parse(tmp)
}

export const getParticipant = () => {
  return clone(participant)
}
