import { Component, OnInit } from '@angular/core';
import {PagesAdmin, PagesUser} from '../../utils/pagesUrl';
import {Router} from '@angular/router';
import {AuthenticationService} from '../../services/authentication.service';
import {TransactionService} from '../../services/transaction.service';
import {AlertController, LoadingController, ToastController} from '@ionic/angular';

@Component({
  selector: 'app-admin-system',
  templateUrl: './admin-system.page.html',
  styleUrls: ['./admin-system.page.scss'],
})
export class AdminSystemPage implements OnInit {
pagesA: any = [];
pagesU: any = [];
today: Date = new Date();
role: string;
solde = 0;
cacher = true;
  constructor(private router: Router,
              private authService: AuthenticationService,
              private transactionService: TransactionService,
              private alertController: AlertController,
              private loadingController: LoadingController,
              private toastController: ToastController
            ) {
    this.pagesA = PagesAdmin;
    this.pagesU = PagesUser;
    this.role = authService.getRole();
  }

  ngOnInit() {
  this.chargerSolde();
  }
  chargerSolde(){
    this.transactionService.getSolde().subscribe(
      (res) => {
        this.solde = res.data;
      }
    );
  }
  onItemClick(url: any) {
    this.router.navigateByUrl(url);
  }

  async logout() {
    console.log('deconnexion');
    const alert = await this.alertController.create({
      cssClass: 'my-custom-class',
      header: 'Confirm!',
      message: 'Êtes-vous sûr de vous déconnecter?',
      buttons: [
        {
          text: 'Cancel',
          role: 'cancel',
          cssClass: 'secondary',
          handler: (blah) => {
          }
        }, {
          text: 'Confirmer',
          handler: async () => {
            const loading = await this.loadingController.create({
              message: 'Please wait...',
            });
            await loading.present();
           this.router.navigateByUrl('/login');
          }
        }
      ]
    });

    await alert.present();

  }

  afficher(){
  this.cacher = !this.cacher;
  }
}
