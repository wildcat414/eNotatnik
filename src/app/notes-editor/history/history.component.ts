import { Component, OnInit } from '@angular/core';
import { HistoryLog } from 'src/app/models/history-log';

@Component({
  selector: 'app-history',
  templateUrl: './history.component.html',
  styleUrls: ['./history.component.scss'],
})
export class HistoryComponent implements OnInit {

  items: HistoryLog[] = [];

  constructor() {
    for (let i = 1; i < 11; i++) {
      this.items.push({
        id: i,
        login: "a",
        editedAt: 0,
        charDiff: 10
      });
    }
  }

  ngOnInit() {}

}
