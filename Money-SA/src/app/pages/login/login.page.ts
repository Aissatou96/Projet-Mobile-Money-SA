import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { AlertController, LoadingController } from '@ionic/angular';
import { Router } from '@angular/router';
import { AuthenticationService } from '../../services/authentication.service';
import { TokenService } from 'src/app/services/token.service';
import jwt_decode from "jwt-decode";


@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {

  credentials: FormGroup;
  token: any;

  constructor(
    private fb: FormBuilder,
    private authService: AuthenticationService,
    private alertController: AlertController,
    private router: Router,
    private loadingController: LoadingController,
    private tokenService: TokenService
  ) { }

  ngOnInit() {
    this.credentials = this.fb.group({
      telephone: ['', [Validators.required]],
      password: ['', [Validators.required, Validators.minLength(6)]],
    });
    this.credentials.patchValue({
      telephone: '+33 3 51 44 16 83',
      password:'passer123'
    });
  }

   login() {
    this.authService.login(this.credentials.value).subscribe(
      (res) => {
        //this.tokenService.saveToken(res);
      this.tokenService.getToken().then((result)=>{
        //console.log(result);
        this.token = result;
       let data =jwt_decode(this.token);
       this.tokenService.SaveInfos(data);
       if(data['roles'][0] === "ROLE_AdminSystem"){
         this.router.navigateByUrl('/admin');
       }
      })


      }
    );
  }

  // Easy access for form fields
  get telephone() {
    return this.credentials.get('telephone');
  }

  get password() {
    return this.credentials.get('password');
  }


}
