export class Register {
  email: string;
  password: string;
  tel: string;
  pin: string;
  nom: string;
  prenom: string;
  sexe: string;

  constructor(
    email?: string,
    password?: string,
    tel?: string,
    pin?: string,
    nom?: string,
    prenom?: string,
    sexe?: string
  ) {
    this.email = email || '';
    this.password = password || '';
    this.tel = tel || '';
    this.pin = pin || "123";
    this.nom = nom || '';
    this.prenom = prenom || '';
    this.sexe = sexe || "user";
  }
}
