import { Component } from '@angular/core';

import { Platform } from '@ionic/angular';
import { SplashScreen } from '@ionic-native/splash-screen/ngx';
import { StatusBar } from '@ionic-native/status-bar/ngx';

import { DeviceStorageService } from '../app/services/device-storage.service';
import { SharedGlobals } from '../app/sharedGlobals';

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss']
})
export class AppComponent {
  userIsLoggedIn: boolean = false;
  firstRun: boolean = true;

  public appPages = [];
  public appPagesAll = [
    {
      title: 'Home',
      url: '/home',
      icon: 'home',
      visible: 'both'
    },
    {
      title: 'Rejestracja',
      url: '/account/register',
      icon: 'person-add',
      visible: 'unlogged'
    },
    {
      title: 'Zaloguj się',
      url: '/account/login',
      icon: 'log-in',
      visible: 'unlogged'
    },
    {
      title: 'Notatnik',
      url: '/notes-editor/editor',
      icon: 'list',
      visible: 'logged'
    },
    {
      title: 'Historia',
      url: '/notes-editor/history',
      icon: 'time',
      visible: 'logged'
    },
    {
      title: 'Wyloguj się',
      url: '/account/logout',
      icon: 'log-out',
      visible: 'logged'
    }
  ];

  constructor(
    private platform: Platform,
    private splashScreen: SplashScreen,
    private statusBar: StatusBar,
    private deviceStorageService: DeviceStorageService
  ) {
    this.initializeApp();

    setInterval(()=>{ this.intervalAction(); }, 1000);

  }

  initializeApp() {
    this.platform.ready().then(() => {
      this.statusBar.styleDefault();
      this.splashScreen.hide();
      this.deviceStorageService.initDatabaseUsers();
      this.deviceStorageService.initDatabaseNotes();
      this.deviceStorageService.initDatabaseHistory();
    });
  }

  intervalAction() {
    if(this.userIsLoggedIn != SharedGlobals.userIsLoggedIn || this.firstRun) {
      if(this.firstRun) {
        this.firstRun = false;
        var stayLogged: boolean = false;
        this.deviceStorageService.getStayLoggedPreference().then(data => {
          stayLogged = data;
          if(stayLogged == true) {
            this.deviceStorageService.getLastUser().then((dbuser:any) => {
              SharedGlobals.userId = dbuser.rows.item(0).id;
              SharedGlobals.userIsLoggedIn = true;
              SharedGlobals.userToken = dbuser.rows.item(0).token;
            });
          }
        });
      }      
      this.refreshMenu();
    }
  }

  refreshMenu() {
    this.userIsLoggedIn = SharedGlobals.userIsLoggedIn;

    this.appPages = [];
    this.appPagesAll.forEach(element => {
      if(this.userIsLoggedIn) {
        if(element.visible == 'logged' || element.visible == 'both') {
          this.appPages.push(element);
        }
      } else {
        if(element.visible == 'unlogged' || element.visible == 'both') {
          this.appPages.push(element);
        }
      }
    })
  }
}
