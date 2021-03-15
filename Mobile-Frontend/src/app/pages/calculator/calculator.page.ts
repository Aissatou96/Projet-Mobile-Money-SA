import { Component, OnInit } from '@angular/core';
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';
import {AlertController, LoadingController} from '@ionic/angular';
import {TransactionService} from '../../services/transaction.service';

@Component({
  selector: 'app-calculator',
  templateUrl: './calculator.page.html',
  styleUrls: ['./calculator.page.scss'],
})
export class CalculatorPage implements OnInit {
  calculator: FormGroup;
  frais = '';
  constructor(
    private fb: FormBuilder,
    private alertController: AlertController,
    private transaction: TransactionService,
    private loadingController: LoadingController
  ) { }

  ngOnInit() {
    this.calculator = this.fb.group({
      depot: new FormControl('', Validators.required),
      montant: new FormControl('', Validators.required)
    });
  }

  async calculerFrais(){
    const loading = await this.loadingController.create();
    await loading.present();
    this.transaction.calculerFrais(this.calculator.value.montant).subscribe(
        async (data) => {
          await  loading.dismiss();
        }
    );
  }


  async afficherFrais() {
    const alert = await this.alertController.create({
      cssClass: 'my-custom-class',
      header: 'Calculateur',
      /*message: `
                  Pour une transaction de ${this.calculator.value.montant}, le frais est ègal à: ${this.calculator.value.frais}
                `,*/
      buttons: [
        {
          text: 'Retour',
          role: 'cancel',
          cssClass: 'secondary'
        }
      ]
    });

    await alert.present();
  }
}
