import { Injectable } from '@angular/core';
import { Storage } from '@ionic/storage';

@Injectable({
  providedIn: 'root'
})
export class TokenService {
  resp:any;
  constructor(
    private storage: Storage
  ) { }
 saveToken(res: any){
  this.storage.set('token', res['token']);
}
async getToken(){
   return await this.storage.get('token');
}
SaveInfos(data){
  this.storage.set('role', data['roles'][0]);
  this.storage.set('telephone', data['telephone']);

}

}
