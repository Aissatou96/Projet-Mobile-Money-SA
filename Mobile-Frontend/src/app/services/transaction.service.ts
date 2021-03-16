import { Injectable } from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Transaction} from '../../model/Transaction';
import {Observable} from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class TransactionService {
url = 'http://127.0.0.1:8000/api';
  constructor(
    private http: HttpClient
  ) { }

  addTransaction(transaction: any): Observable<any> {
    return this.http.post<any>(`${this.url}/transac`, transaction);
  }
  calculerFrais(value: any): Observable<any>{
    return  this.http.post(`${this.url}/transac/calcul`, value);
  }

  getTransaction(value: any): Observable<any>{
    return  this.http.post(`${this.url}/transac/recup`, value);
  }

  getSolde(): Observable<any>{
  return  this.http.get<any>(`${this.url}/comptes/solde`);
}

}
