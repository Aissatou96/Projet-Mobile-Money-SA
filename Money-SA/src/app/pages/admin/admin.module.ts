import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { AdminPageRoutingModule } from './admin-routing.module';

import { AdminPage } from './admin.page';
import { RouterModule, Routes } from '@angular/router';

const routes: Routes = [
  {
    path: 'admin',
    component: AdminPage,
    children: [
      {
        path: 'accueil',
        loadChildren: '../accueil/accueil.module#AccueilPageModule'
      },

      {
        path: 'tansactions',
        loadChildren:'../transactions/transaction.module#TransactionsPageModule'
      },

      {
        path: 'commissions',
        loadChildren:'../commissions/commissions.module#CommissionsPageModule'
      },

      {
        path: 'calculateur',
        loadChildren:'../calculateur/calculateur.module#CalculateurPageModule'
      },

      {
        path: '',
        loadChildren:'admin'
      }
    ]
  }
];

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    RouterModule.forChild(routes),
    AdminPageRoutingModule
  ],
  declarations: [AdminPage]
})
export class AdminPageModule {}
