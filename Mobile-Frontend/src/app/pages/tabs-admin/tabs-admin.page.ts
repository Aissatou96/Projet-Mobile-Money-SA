import { Component, OnInit } from '@angular/core';
import { menuAdmin } from '../../utils/pagesUrl';
@Component({
  selector: 'app-tabs-admin',
  templateUrl: './tabs-admin.page.html',
  styleUrls: ['./tabs-admin.page.scss'],
})
export class TabsAdminPage implements OnInit {
  pages: any = [];
  constructor() {
    this.pages = menuAdmin;
  }

  ngOnInit() {}
}
