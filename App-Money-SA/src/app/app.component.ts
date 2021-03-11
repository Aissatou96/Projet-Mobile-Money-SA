import { Component } from '@angular/core';
@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent {
  public appPages = [
    { title: 'Home', url: '/folder/home', icon: 'home' },
    { title: 'Transactions', url: '/transactions', icon: 'sync' },
    { title: 'Commissions', url: '/commissions', icon: 'apps' },
    { title: 'Calculateur', url: '/calculateur', icon: 'calculator' },
  ];
  constructor() {}
}
