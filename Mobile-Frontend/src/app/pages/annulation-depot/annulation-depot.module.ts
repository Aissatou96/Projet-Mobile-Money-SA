import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { AnnulationDepotPageRoutingModule } from './annulation-depot-routing.module';

import { AnnulationDepotPage } from './annulation-depot.page';

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        IonicModule,
        AnnulationDepotPageRoutingModule,
        ReactiveFormsModule
    ],
  declarations: [AnnulationDepotPage]
})
export class AnnulationDepotPageModule {}
