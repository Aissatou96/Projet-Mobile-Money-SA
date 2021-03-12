import { Component, OnInit } from '@angular/core';
import {PagesAdmin} from '../../utils/pagesUrl';
import {Router} from '@angular/router';

@Component({
  selector: 'app-admin-system',
  templateUrl: './admin-system.page.html',
  styleUrls: ['./admin-system.page.scss'],
})
export class AdminSystemPage implements OnInit {
pages: any = [];
  today: Date = new Date();
  constructor(private router: Router) {
    this.pages = PagesAdmin;
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
