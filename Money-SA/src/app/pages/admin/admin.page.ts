import { Component, OnInit } from '@angular/core';
import { Router, RouterEvent } from '@angular/router';

@Component({
  selector: 'app-admin',
  templateUrl: './admin.page.html',
  styleUrls: ['./admin.page.scss'],
})
export class AdminPage implements OnInit {

  activePath = '';

  pages = [
    {
      name: 'Home',
      path: '/admin/accueil'
    },
    {
      name: 'Transactions',
      path: '/menu/transactions'
    },
    {
      name: 'Commissions',
      path: '/menu/commissions'
    },
    {
      name: 'Calculateur',
      path: '/menu/calculateur'
    }
  ]

  constructor(private router: Router) {
    this.router.events.subscribe((event: RouterEvent) => {
      this.activePath = event.url
    })
  }

  ngOnInit() {
  }


}
