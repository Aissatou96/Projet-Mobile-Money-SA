import { Component, OnInit } from '@angular/core';
import { FormGroup } from '@angular/forms';

@Component({
  selector: 'app-depot',
  templateUrl: './depot.page.html',
  styleUrls: ['./depot.page.scss'],
})
export class DepotPage implements OnInit {

  depot: FormGroup;

  constructor() { }

  ngOnInit() {
  }

  deposer(){
    
  }
}
