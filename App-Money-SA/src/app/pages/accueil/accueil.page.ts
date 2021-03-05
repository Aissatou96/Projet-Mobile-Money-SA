import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-accueil',
  templateUrl: './accueil.page.html',
  styleUrls: ['./accueil.page.scss'],
})
export class AccueilPage  {

  constructor(private router: Router) {}

  login(){
    this.router.navigateByUrl('/login');
  }

}
