import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import {AuthenticationService} from '../../services/authentication.service';
import { AlertController, LoadingController } from '@ionic/angular';


@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {

  credentials: FormGroup;
  token: any;

  constructor(
    private router: Router,
    private fb: FormBuilder,
    private authService: AuthenticationService,
    private alertCtrl: AlertController,
    private loadingCtrl: LoadingController
  ) {}

  ngOnInit() {
    this.credentials = this.fb.group({
      telephone: ['+33 3 51 44 16 83', [Validators.required, Validators.minLength(9)]],
      password: ['passer123', [Validators.required, Validators.minLength(6)]],
    });
  }

  async login() {
    const loading = await this.loadingCtrl.create();
    await loading.present();

    this.authService.login(this.credentials.value).subscribe(
      async(res) =>{
        await loading.dismiss();
        let role = this.authService.getRole();
         this.authService.RedirectMe(role);

      }, async(res) =>{
        console.log(res);

        await loading.dismiss();
        const alert = await this.alertCtrl.create({
          header: 'Login failed',
          message: res.error.error,
          buttons: ['OK']
        });
        await alert.present();
      }
    )
  }


  get telephone() {
    return this.credentials.get('telephone');
  }
  get password() {
    return this.credentials.get('password');
  }

}
