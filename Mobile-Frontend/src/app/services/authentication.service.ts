import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable, from } from 'rxjs';
import { map, switchMap, tap } from 'rxjs/operators';
import jwt_decode from 'jwt-decode';

import { Plugins } from '@capacitor/core';
import { Router } from '@angular/router';
const { Storage } = Plugins;

const TOKEN_KEY = 'my-token';

@Injectable({
  providedIn: 'root'
})
export class AuthenticationService {
  isAuthenticated: BehaviorSubject<boolean> = new BehaviorSubject<boolean>(null);
  token = '';
  myToken = '';
  myRole = '';
  decoded: any;
  url = 'http://127.0.0.1:8000/api';

  constructor(private http: HttpClient, private router: Router) {
    this.loadToken();
  }
  //Vérifier si l'user est déjà connecté ou pas?
  async loadToken(){
    const token = await Storage.get({key: TOKEN_KEY});
    if (token && token.value){
      this.isAuthenticated.next(true);
    }else{
      this.isAuthenticated.next(false);
    }
  }

  loggedIn(){
    return !! Storage.get({key: TOKEN_KEY});
  }
  // login
  login(credentials: {telephone, password}): Observable<any>{
    return this.http.post('http://127.0.0.1:8000/api/login', credentials).pipe(
      map((data: any) => data.token),
      switchMap(token => {
        return from(this.InfosSave(token));
      }),
      tap(_ => {
        this.isAuthenticated.next(true);
      })
    );
  }

  async InfosSave(token){
    this.myToken = token;
    let from = jwt_decode(token);
    this.myRole = from['roles'][0];
    await Storage.set({key: TOKEN_KEY, value: token});
    await Storage.set({key: 'role', value: from['roles']});
    await Storage.set({key: 'telephone', value: from['telephone']});

  }
  getToken(){
    return this.myToken;
  }

  getRole(){
    return this.myRole;
  }

  redirectToMe(role: string){
    if (role){
      this.router.navigateByUrl('/admin-system');
    }
  }

  logout(): Promise<void>{
    this.isAuthenticated.next(false);
    Storage.remove({key: 'role' });
    Storage.remove({key: 'telephone' });
    Storage.remove({key: 'intro-seen' });
    return Storage.remove({key: TOKEN_KEY});
  }
}
