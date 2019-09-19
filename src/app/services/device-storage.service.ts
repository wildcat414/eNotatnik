import { Injectable } from '@angular/core';
import { SQLite, SQLiteObject } from '@ionic-native/sqlite/ngx';
import { AppPreferences } from '@ionic-native/app-preferences/ngx';
import { User } from '../models/user';

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
          .then(result => { console.log('Executed SQL'); console.log(query); console.log(params); resolve(result); })
          .catch(error => { console.log(error); reject(error); });
      })
      .catch(error => { console.log(error); reject(error); });
    });
  }

  initDatabaseUsers() {
    return this.executeQuery('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT, login TEXT, password TEXT, email TEXT, registeredAt INTEGER, token TEXT);', []);
  }

  initDatabaseHistory() {
    return this.executeQuery('CREATE TABLE IF NOT EXISTS history_logs (id INTEGER PRIMARY KEY AUTOINCREMENT, login TEXT, editedAt INTEGER, charDiff INTEGER);', []);
  }

  initDatabaseNotes() {
    return this.executeQuery('CREATE TABLE IF NOT EXISTS notes (userId INTEGER PRIMARY KEY, content TEXT, editedAt INTEGER);', []);
  }

  addNote(userId: number, content: string, editedAt: number) {
    return this.executeQuery('INSERT INTO notes(userId, content, editedAt) VALUES (?, ?, ?);', [userId, content, editedAt]);
  }

  updateNote(userId: number, content: string, editedAt: number) {
    return this.executeQuery('UPDATE notes SET content = ?, editedAt = ? WHERE userId = ?', [content, editedAt, userId]);
  }

  getNote(userId: number) {
    return this.executeQuery('SELECT * FROM notes WHERE userId = ?', [userId]);
  }

  deleteNote(userId: number) {
    return this.executeQuery('DELETE FROM notes WHERE userId = ?', [userId]);
  }

  addUser(user: User) {
    return this.executeQuery('INSERT INTO users(id, login, password, email, registeredAt, token) VALUES (?, ?, ?, ?, ?, ?)', [user.id, user.login, user.password, user.email, user.registeredAt, user.token]);
  }

  updateUser(user: User) {
    return this.executeQuery('UPDATE users SET login = ?, password = ?, email = ?, registeredAt = ?, token = ? WHERE id = ?', [user.login, user.password, user.email, user.registeredAt, user.token, user.id]);
  }

  getUser(userId: number) {
    return this.executeQuery('SELECT * FROM users WHERE id = ?', [userId]);
  }

  getLastUser() {
    return this.executeQuery('SELECT * FROM users ORDER BY id DESC LIMIT 1', []);
  }

  deleteUser(userId: number) {
    return this.executeQuery('DELETE FROM users WHERE id = ?', [userId]);
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
