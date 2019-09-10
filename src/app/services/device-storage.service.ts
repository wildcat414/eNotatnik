import { Injectable } from '@angular/core';
import { SQLite, SQLiteObject } from '@ionic-native/sqlite/ngx';
import { AppPreferences } from '@ionic-native/app-preferences/ngx';

@Injectable({
  providedIn: 'root'
})
export class DeviceStorageService {

  constructor(private sqlite: SQLite, private appPreferences: AppPreferences) { }

  private executeQuery(query, params) {
    return new Promise((resolve, reject) => {
      this.sqlite.create({
        name: 'data.db',
        location: 'default'
      })
      .then((db: SQLiteObject) => {
        db.executeSql(query, params)
          .then(result => { console.log('Executed SQL'); resolve(result); })
          .catch(error => { console.log(error); reject(error); });
      })
      .catch(error => { console.log(error); reject(error); });
    });
  }

  initDatabase() {
    return this.executeQuery('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, login TEXT, password TEXT, email TEXT, registeredAt INTEGER, lastSeen INTEGER, token TEXT);', []);
  }

  saveThemePreference(themeName: string) {
    return this.appPreferences.store('dict1', 'theme', themeName);
  }

  getThemePreference() {
    return this.appPreferences.fetch('dict1', 'theme');
  }

  saveStayLoggedInPreference(stay: boolean) {
    return this.appPreferences.store('dict1', 'stayloggedin', stay);
  }

  getStayLoggedPreference() {
    return this.appPreferences.fetch('dict1', 'stayloggedin');
  }
  
}
