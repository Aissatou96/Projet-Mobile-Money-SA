export interface Transaction{
  montant: number;
  status: boolean;
  type: string;
  clientEnvois: Client;
  clientRetraits: Client;
  code: string;
}

export interface Client{
  cni: string;
  lastName: string;
  firstName: string;
  phone: string;
}
