import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { AnnulationDepotPage } from './annulation-depot.page';

const routes: Routes = [
  {
    path: '',
    component: AnnulationDepotPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class AnnulationDepotPageRoutingModule {}
