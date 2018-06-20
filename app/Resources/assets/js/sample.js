const participant = {
  nom: '',
  prenom: '',
  codco: null,
  ident: null,
  civil: '',
  civil2: null,
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
  colt: '',
  colp: null,
  transport: '',
  memo: ''
}

const clone = (obj) => {
  var tmp = JSON.stringify(obj)
  return JSON.parse(tmp)
}

export const getParticipant = () => {
  return clone(participant)
}
