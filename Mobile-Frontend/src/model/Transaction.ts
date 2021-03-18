export interface Transaction{
  montant: number;
  status: boolean;
  type: string;
  clientEnvois: Client;
  clientRetraits: Client;
  code: string;
}

export class Transactions{
  transactions:[];
  data:[];

}

export interface Client{
  cni: string;
  lastname: string;
  firstname: string;
  phone: string;
}
