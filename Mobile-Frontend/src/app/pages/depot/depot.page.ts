import { Component, OnInit } from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {Client, Transaction} from '../../../model/Transaction';
import {AlertController, LoadingController} from '@ionic/angular';

@Component({
  selector: 'app-depot',
  templateUrl: './depot.page.html',
  styleUrls: ['./depot.page.scss'],
})
export class DepotPage implements OnInit {
  visible = true;
  depot: FormGroup;
  mytransaction: Transaction;
  constructor(private fb: FormBuilder, private alertController: AlertController, private loadingController: LoadingController) {
    this.mytransaction = {} as Transaction;
    this.mytransaction.clientRetraits = {} as Client;
    this.mytransaction.clientEnvois = {} as Client;
  }


  ngOnInit() {
    this.depot = this.fb.group({
      montant: new FormControl('10000', Validators.required),
      clientEnvois: this.fb.group({
          cni: new FormControl('12458258648', Validators.required),
          lastName: new FormControl('Dione', Validators.required),
          firstName: new FormControl('Assane', Validators.required),
          phone: new FormControl('766540364', Validators.required),
        }),
      clientRetraits: this.fb.group({
          lastName: new FormControl('Cissé', Validators.required),
          firstName: new FormControl('Aissatou', Validators.required),
          phone: new FormControl('786325494', Validators.required),
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
      message: 'Voulez vous continuer la transaction !',
      buttons: [
        {
          text: 'Annuler',
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
            console.log('Confirm Okay');
            setTimeout(() => {
              loading.dismiss();
            }, 2000);
          }
        }
      ]
    });

    await alert.present();

  }

}
