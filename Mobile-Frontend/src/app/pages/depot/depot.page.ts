import { Component, OnInit } from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {Client, Transaction} from '../../../model/Transaction';
import {AlertController, LoadingController, ToastController} from '@ionic/angular';
import {TransactionService} from '../../services/transaction.service';


@Component({
  selector: 'app-depot',
  templateUrl: './depot.page.html',
  styleUrls: ['./depot.page.scss'],
})
export class DepotPage implements OnInit {
  visible = true;
  depot: FormGroup;
  mytransaction: Transaction;
   frais = '';
   total = '';
  constructor(
    private fb: FormBuilder,
    private alertController: AlertController,
    private loadingController: LoadingController,
    private transaction: TransactionService,
    private toastCtrl: ToastController
  )
  {
    this.mytransaction = {} as Transaction;
    this.mytransaction.clientRetraits = {} as Client;
    this.mytransaction.clientEnvois = {} as Client;
  }


  ngOnInit() {
    this.depot = this.fb.group({
      montant: new FormControl('', Validators.required),
      clientEnvois: this.fb.group({
          cni: new FormControl('', Validators.required),
          lastname: new FormControl('', Validators.required),
          firstname: new FormControl('', Validators.required),
          phone: new FormControl('', Validators.required),
        }),
      clientRetraits: this.fb.group({
          lastname: new FormControl('', Validators.required),
          firstname: new FormControl('', Validators.required),
          phone: new FormControl('', Validators.required),
        })

    });
  }
  previous(){
    this.visible = false;
  }
  next(){
    this.visible = true;
  }
  async deposer() {
    this.mytransaction.clientEnvois = this.depot.value.clientEnvois;
    this.mytransaction.clientRetraits = this.depot.value.clientRetraits;
    this.mytransaction.montant = this.depot.value.montant;
    this.mytransaction.status = false;
    this.mytransaction.type = 'depot';

    const alert = await this.alertController.create({
      cssClass: 'my-custom-class',
      header: 'Confirmation',
      message: `<div class="infos">
                Emetteur<br><p>${this.mytransaction.clientEnvois.lastname} ${this.mytransaction.clientEnvois.firstname}</p> <br>
                T??l??phone<br><p>${this.mytransaction.clientEnvois.phone}</p> <br>
                N??CNI<br><p>${this.mytransaction.clientEnvois.cni}</p> <br>
                R??cepteur<br><p>${this.mytransaction.clientRetraits.lastname} ${this.mytransaction.clientRetraits.firstname}</p> <br>
                Montant<br><p>${this.mytransaction.montant}</p> <br>
                T??l??phone<br><p>${this.mytransaction.clientRetraits.phone}</p> <br>

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
            //console.log(this.mytransaction);
            this.transaction.addTransaction(this.mytransaction).subscribe(
              async (data) => {
                console.log(data);
                await loading.dismiss();
                const toast = await this.toastCtrl.create({
                  message: 'Succ??s.',
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

    await alert.present();

  }

  async calculer() {
    const loading = await this.loadingController.create({
      message: 'Please wait...',
    });
    await loading.present();
    this.transaction.calculerFrais(this.depot.value).subscribe(
      async (data) => {
        await loading.dismiss();
        this.frais = data.Frais;
        this.total = this.depot.value.montant + this.frais;
      }
    );
  }
}

