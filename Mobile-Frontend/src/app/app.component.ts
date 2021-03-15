import { Component } from '@angular/core';
import { Router, RouterEvent } from '@angular/router';
import {menuAdmin, menuUser} from './utils/pagesUrl';
import {AuthenticationService} from './services/authentication.service';
@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent {
  pagesA: any = [];
  pagesU: any = [];
  role: string;
  public selectedPath = '';

  constructor(private router: Router, authSercvice: AuthenticationService) {
    this.pagesA = menuAdmin;
    this.pagesU = menuUser;
    this.role = authSercvice.getRole();
    this.router.events.subscribe((event: RouterEvent) => {
      if (event && event.url) {
        this.selectedPath = event.url;
      }
    });
  }


  onItemClick(url: string) {
    this.router.navigate([url]);
  }
}
