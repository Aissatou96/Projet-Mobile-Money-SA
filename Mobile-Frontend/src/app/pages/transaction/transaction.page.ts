import { Component, OnInit } from '@angular/core';
import {Router} from '@angular/router';
import {PagesAdmin} from '../../utils/pagesUrl';
import {TransactionService} from '../../services/transaction.service';
import {AlertController, LoadingController} from '@ionic/angular';
import {AuthenticationService} from '../../services/authentication.service';

@Component({
  selector: 'app-transaction',
  templateUrl: './transaction.page.html',
  styleUrls: ['./transaction.page.scss'],
})
export class TransactionPage implements OnInit {
  pages: any = [];
  today: Date = new Date();
  transactions: any;
  role: string;
  constructor(
    private router: Router,
    private transactionService: TransactionService,
    private authService: AuthenticationService,
    private alertController: AlertController,
    private loadingController: LoadingController
  ) {

    this.pages = PagesAdmin;
    this.role = authService.getRole();

  }

  ngOnInit() {
    this.transactionService.getAllTransactions(this.transactions).subscribe(
      (res)=>{
        this.transactions = res.data;
      }
    )
  }

  async afficherTransaction(transaction: any) {
    const alert = await this.alertController.create({
      cssClass: 'my-custom-class',
      header: 'Confirm!',
      message: `
                     <ion-list>
                      <ion-item>
                        <ion-label>Date:${transaction.date}</ion-label>
                      </ion-item>
                      <ion-item>
                        <ion-label>Prenom Nom: ${transaction}</ion-label>
                      </ion-item>
                      <ion-item>
                        <ion-label></ion-label>
                      </ion-item>
                      <ion-item>
                        <ion-label></ion-label>
                      </ion-item>
                      <ion-item>
                        <ion-label></ion-label>
                      </ion-item>
                    </ion-list>
                `,
      buttons: ['OK']
    });

    await alert.present();
  }

  next() {

  }

  previous() {

  }
}
