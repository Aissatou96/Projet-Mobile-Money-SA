import { Component } from '@angular/core';
@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent {
  public appPages = [
    { title: 'Home', url: '/folder/home', icon: 'home' },
    { title: 'Transactions', url: '/folder/transactions', icon: 'paper-plane' },
    { title: 'Commissions', url: '/folder/commissions', icon: 'heart' },
    { title: 'Calculateur', url: '/folder/calculateur', icon: 'archive' },
  ];
  constructor() {}
}
