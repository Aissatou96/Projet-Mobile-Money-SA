import { Component, OnInit } from '@angular/core';
import {Router} from '@angular/router';
import {PagesAdmin} from '../../utils/pagesUrl';

@Component({
  selector: 'app-transaction',
  templateUrl: './transaction.page.html',
  styleUrls: ['./transaction.page.scss'],
})
export class TransactionPage implements OnInit {
  pages: any = [];
  today: Date = new Date();
  constructor(private router: Router) {
    this.pages = PagesAdmin;
  }

  ngOnInit() {
  }

}
