import { Component } from '@angular/core';
import { Router, RouterEvent } from '@angular/router';
import { pages } from './utils/pagesUrl';
@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent {
  pages: any = [];

  public selectedPath = '';

  constructor(private router: Router) {
    this.pages = pages;
    this.router.events.subscribe((event: RouterEvent) => {
      if (event && event.url) {
        this.selectedPath = event.url;
        console.log(this.selectedPath);
      }
    });
  }

  ngOnInit() {}

  onItemClick(url: string) {
    this.router.navigate([url]);
    console.log(url);
  }
}
