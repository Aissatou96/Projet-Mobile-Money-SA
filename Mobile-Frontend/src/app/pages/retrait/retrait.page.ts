import { Component, OnInit } from '@angular/core';
import {FormBuilder, FormGroup, Validators} from '@angular/forms';
import {Transaction} from '../../../model/Transaction';
import {AlertController, LoadingController, ToastController} from '@ionic/angular';
import {TransactionService} from '../../services/transaction.service';

@Component({
  selector: 'app-retrait',
  templateUrl: './retrait.page.html',
  styleUrls: ['./retrait.page.scss'],
})
export class RetraitPage implements OnInit {
  clientEnvoi: any = [];
  montant = '';
  dateEnvoi = '';
   visible = true;
  credentials: FormGroup;
  nomEmetteur = '';
  clientRetrait: any = [];

  constructor(private fb: FormBuilder, private alertController: AlertController,
              private loadingController: LoadingController,
              private transaction: TransactionService,
              private toastCtrl: ToastController
              ) {
  }

  ngOnInit() {
    this.credentials = this.fb.group({
      code: ['', [Validators.required, Validators.minLength(5)]],
      cni: ['', [Validators.required, Validators.minLength(5)]],
      type: ['retrait', [Validators.required, Validators.minLength(5)]]
    });
  }

  async retirer() {
    const smg = await this.alertController.create({
      cssClass: 'my-custom-class',
      header: 'Confirmation',
      message: 'Voulez vous continuer la transaction !',
      buttons: [
        {
          text: 'Annuler',
          role: 'cancel',
          cssClass: 'secondary',
          handler: () => {
          }
        }, {
          text: 'Confirmer',
          handler: async () => {
            const loading = await this.loadingController.create({
              message: 'Please wait...',
            });
            await loading.present();
            console.log('Confirm Okay');
            setTimeout(() => {
              loading.dismiss();
            }, 2000);
          }
        }
      ]
    });

    await smg.present();
  }

  next() {
    this.visible = true;
  }
  previous(){
    this.visible = false;
  }

  async Rechercher() {
    const loading = await this.loadingController.create({
      message: 'Please wait...',
    });
    await loading.present();
    this.transaction.getTransaction(this.credentials.value).subscribe(
      async (data) => {
        this.clientEnvoi= data.transaction.clientEnvoi;
        this.clientRetrait = data.transaction.clientRetrait;
        this.dateEnvoi = data.transaction.dateEnvoi;
        this.montant = data.transaction.montant;
        await loading.dismiss();
        console.log(data);
      }
    );
  }
}
