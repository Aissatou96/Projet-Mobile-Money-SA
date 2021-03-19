import { Component, OnInit } from '@angular/core';
import {TransactionService} from '../../services/transaction.service';
import {AuthenticationService} from '../../services/authentication.service';

@Component({
  selector: 'app-commission',
  templateUrl: './commission.page.html',
  styleUrls: ['./commission.page.scss'],
})
export class CommissionPage implements OnInit {
  transactions: any;
  role: string;

  constructor(
                private transactionService: TransactionService,
                private authService: AuthenticationService
            )
  {
    this.role = authService.getRole();
  }

  ngOnInit() {
    this.transactions = this.transactionService.getAllTransactions(this.transactions).subscribe(
      (res)=>{
        this.transactions = res.data;
        console.log(res.data);
      }
    )
  }


}
