import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { NotesEditorRoutingModule } from './notes-editor-routing.module';
import { EditorComponent } from './editor/editor.component';
import { HistoryComponent } from './history/history.component';
import { IonicModule } from '@ionic/angular';

@NgModule({
  declarations: [
    EditorComponent,
    HistoryComponent
  ],
  imports: [
    CommonModule,
    NotesEditorRoutingModule,
    IonicModule,
    FormsModule
  ]
})
export class NotesEditorModule { }
