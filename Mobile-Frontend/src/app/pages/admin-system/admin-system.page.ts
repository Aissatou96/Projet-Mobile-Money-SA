import { Component, OnInit } from '@angular/core';
import {PagesAdmin, PagesUser} from '../../utils/pagesUrl';
import {Router} from '@angular/router';
import {AuthenticationService} from '../../services/authentication.service';

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
  constructor(private router: Router, authService: AuthenticationService) {
    this.pagesA = PagesAdmin;
    this.pagesU = PagesUser;
    this.role = authService.getRole();
  }

  ngOnInit() {
  }

  onItemClick(url: any) {
    this.router.navigateByUrl(url);
  }

  logout() {
    console.log('vous etes deconnecter');
  }
}
