import { Component, OnInit } from '@angular/core';
import {PagesAdmin, PagesUser} from '../../utils/pagesUrl';
import {Router} from '@angular/router';
import {AuthenticationService} from '../../services/authentication.service';
import {TransactionService} from '../../services/transaction.service';

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
  constructor(private router: Router, authService: AuthenticationService, private transactionService: TransactionService) {
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

  logout() {
    console.log('vous etes deconnecter');
  }
}
