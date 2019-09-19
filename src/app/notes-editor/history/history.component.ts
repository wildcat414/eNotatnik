import { Component, OnInit } from '@angular/core';
import { HistoryLog } from 'src/app/models/history-log';

import { RestConnectService } from 'src/app/services/rest-connect.service';
@Component({
  selector: 'app-history',
  templateUrl: './history.component.html',
  styleUrls: ['./history.component.scss'],
})
export class HistoryComponent implements OnInit {

  items: HistoryLog[] = [];

  constructor(private restConnectService: RestConnectService) { }

  ngOnInit() {
    this.restConnectService.getNoteHistory().subscribe(data => {
      console.log(data);
      if(data.status == "ok") {
        var temporaryItems = data.result;
        temporaryItems.forEach(element => {
          element.editedAt = element.editedAt * 1000;
        });
        this.items = temporaryItems;
      }
    })
  }

}
