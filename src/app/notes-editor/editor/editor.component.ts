import { Component, OnInit } from '@angular/core';
import { AlertController } from '@ionic/angular';
import { Router } from '@angular/router';

import { RestConnectService } from 'src/app/services/rest-connect.service';

@Component({
  selector: 'app-editor',
  templateUrl: './editor.component.html',
  styleUrls: ['./editor.component.scss'],
})
export class EditorComponent implements OnInit {

  notesContent: string = "";
  previousEdit: string = "";

  constructor(public alertController: AlertController, private restConnectService: RestConnectService, private router: Router) { }

  ngOnInit() {
    this.doGetNotes();
  }

  doGetNotes() {
    this.restConnectService.getNote().subscribe(data => {
      console.log(data);
      if(data.status == "ok") {
        this.notesContent = data.result.content;
        if(data.result.editedAt != null) {
          this.previousEdit = data.result.editedAt.toString() + "000";
        } else {
          this.previousEdit = "(never)";
        }
        
      }
    })
  }

  doSaveNotes() {
    this.restConnectService.updateNote(this.notesContent).subscribe(data => {
      console.log(data);
      if(data.status == "ok") {
        this.previousEdit = new Date().getTime().toString() + "000";
      }
    })
  }

}
