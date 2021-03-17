import { Component, OnInit } from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {AlertController, LoadingController, ToastController} from '@ionic/angular';
import {TransactionService} from '../../services/transaction.service';

@Component({
  selector: 'app-annulation-depot',
  templateUrl: './annulation-depot.page.html',
  styleUrls: ['./annulation-depot.page.scss'],
})
export class AnnulationDepotPage implements OnInit {
  annulationForm: FormGroup;

  constructor(
    private fb: FormBuilder,
    private alertController: AlertController,
    private loadingController: LoadingController,
    private toastCtrl: ToastController,
    private transactionService: TransactionService
  ) { }

  ngOnInit() {
    this.annulationForm = this.fb.group({
      numero: new FormControl('', Validators.required)
    })
  }

  async annulerDepot() {
    const alert = await this.alertController.create({
      cssClass: 'my-custom-class',
      header: 'Confirm!',
      message: 'Voulez-vous supprimer la transaction?',
      buttons: [
        {
          text: 'Cancel',
          role: 'cancel',
          cssClass: 'secondary',
          handler: (blah) => {
          }
        }, {
          text: 'Okay',
          handler: async () => {
            const loading = await this.loadingController.create({
              message: 'Please wait...',
            });
            await loading.present();
            this.transactionService.annulerTransaction(this.annulationForm.value.numero).subscribe(
              async (data) => {
                console.log(data);
                await loading.dismiss();
                const toast = await this.toastCtrl.create({
                  message: 'Succès.',
                  position: 'middle',
                  duration: 2000
                });
                await toast.present();
              },
              async (error) => {
                await loading.dismiss();
                const toast = await this.toastCtrl.create({
                  message: error.error.message,
                  position: 'middle',
                  cssClass: 'error',
                  duration: 2000
                });
                await toast.present();
              }
            )
          }
        }
      ]
    });

    await alert.present();
  }
}
