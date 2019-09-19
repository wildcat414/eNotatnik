import { Component, OnInit } from '@angular/core';
import { AlertController } from '@ionic/angular';
import { Router } from '@angular/router';

import { RestConnectService } from 'src/app/services/rest-connect.service';
import { DeviceStorageService } from 'src/app/services/device-storage.service';
import { SharedGlobals } from 'src/app/sharedGlobals';

@Component({
  selector: 'app-editor',
  templateUrl: './editor.component.html',
  styleUrls: ['./editor.component.scss'],
})
export class EditorComponent implements OnInit {

  notesContent: string = "";
  previousEdit: string = "";

  notesFromLocal: string = "";
  notesFromLocalEdit: string = "";

  constructor(
    public alertController: AlertController,
    private restConnectService: RestConnectService,
    private router: Router,
    private deviceStorageService: DeviceStorageService
    ) { }

  ngOnInit() {
    this.doGetNotes();
  }

  doGetNotes() {
    this.restConnectService.getNote().subscribe(data => {
      console.log(data);
      this.deviceStorageService.getNote(SharedGlobals.userId).then((dbnote: any) => {
        console.log(dbnote);
        if(dbnote.rows.length > 0) {
          this.notesFromLocal = dbnote.rows.item(0).content;
          this.notesFromLocalEdit = dbnote.rows.item(0).editedAt.toString();
        }
        if(data.status == "ok") {
          if(data.result.editedAt != null) {
            this.previousEdit = data.result.editedAt.toString() + "000";
            if(this.previousEdit > this.notesFromLocalEdit || this.notesFromLocalEdit == "") {
              this.notesContent = data.result.content;
            } else {
              this.notesContent = this.notesFromLocal;
              this.previousEdit = this.notesFromLocalEdit;
            }
          } else {
            this.notesContent = this.notesFromLocal;
            this.previousEdit = this.notesFromLocalEdit;
          }        
        } else {
          this.notesContent = this.notesFromLocal;
          this.previousEdit = this.notesFromLocalEdit;
        }
      }, error => {
        this.notesContent = data.result.content;
        this.previousEdit = data.result.editedAt.toString() + "000";
      });
    }, error2 => {
      this.deviceStorageService.getNote(SharedGlobals.userId).then((dbnote: any) => {
        console.log(dbnote);
        this.notesFromLocal = dbnote.rows.item(0).content;
        this.notesFromLocalEdit = dbnote.rows.item(0).editedAt.toString();
        this.notesContent = this.notesFromLocal;
        this.previousEdit = this.notesFromLocalEdit;
      });
    });
  }

  doSaveNotes() {
    this.restConnectService.updateNote(this.notesContent).subscribe(data => {
      console.log(data);
      this.previousEdit = new Date().getTime().toString();
      this.deviceStorageService.addNote(SharedGlobals.userId, this.notesContent, parseInt(this.previousEdit)).then(_ => {
        // ok
      }, error => {
        this.deviceStorageService.updateNote(SharedGlobals.userId, this.notesContent, parseInt(this.previousEdit));
      });
    }, error => {
      this.previousEdit = new Date().getTime().toString();
      this.deviceStorageService.addNote(SharedGlobals.userId, this.notesContent, parseInt(this.previousEdit)).then(_ => {
        // ok
      }, error => {
        this.deviceStorageService.updateNote(SharedGlobals.userId, this.notesContent, parseInt(this.previousEdit));
      });
    });
  }

}
