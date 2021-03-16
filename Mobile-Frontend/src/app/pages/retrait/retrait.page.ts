import { Component, OnInit } from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {Client, Transaction} from '../../../model/Transaction';
import {AlertController, LoadingController, ToastController} from '@ionic/angular';
import {TransactionService} from '../../services/transaction.service';

@Component({
  selector: 'app-retrait',
  templateUrl: './retrait.page.html',
  styleUrls: ['./retrait.page.scss'],
})
export class RetraitPage implements OnInit {
  clientEnvoi: any = [];
  mytransaction: Transaction;
  montant = '';
  dateEnvoi = '';
  visible = true;
  retrait: FormGroup;
  nomEmetteur = '';
  clientRetrait: any = [];

  constructor(private fb: FormBuilder, private alertController: AlertController,
              private loadingController: LoadingController,
              private transaction: TransactionService,
              private toastCtrl: ToastController
              ) {
                  this.mytransaction = {} as Transaction;
                  this.mytransaction.clientRetraits = {} as Client;
                  this.mytransaction.clientEnvois = {} as Client;
                }

  ngOnInit() {
    this.retrait = this.fb.group({
      code: ['', [Validators.required, Validators.minLength(5)]],
      cni: ['', [Validators.required, Validators.minLength(5)]],
      type: ['retrait', [Validators.required, Validators.minLength(5)]]
    });
  }

  async retirer() {
    console.log(this.retrait.value);

    const smg = await this.alertController.create({
      cssClass: 'my-custom-class',
      header: 'Confirmation',
      message: `
               <div class="infos">
                BENEFICIAIRE<br><p>${this.clientRetrait.nom}  </p><br>
                TELEPHONE<br><p>${this.clientRetrait.phone}</p><br>
                N°CNI<br><p>${this.retrait.value.cni}</p><br>
                MONTANT RECU<br><p>${this.montant}</p><br>
                EMETTEUR<br><p>${this.clientEnvoi.nom} </p><br>
                TELEPHONE<br><p>${this.clientEnvoi.phone}</p><br>
                </div>
               `,
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
            console.log("donne send",this.retrait.value);
            this.transaction.addTransaction(this.retrait.value).subscribe(
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
            );
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
    this.transaction.getTransaction(this.retrait.value).subscribe(
      async (data) => {
        this.clientEnvoi = data.transaction.clientEnvoi;
        this.clientRetrait = data.transaction.clientRetrait;
        this.dateEnvoi = data.transaction.dateEnvoi;
        this.montant = data.transaction.montant;
        await loading.dismiss();
        console.log(data);
      }
    );
  }
}
