import { Component, OnInit } from '@angular/core';
import { AlertController } from '@ionic/angular';
import { Router } from '@angular/router';

import { RestConnectService } from 'src/app/services/rest-connect.service';
import { User } from 'src/app/models/user';
import { SharedGlobals } from 'src/app/sharedGlobals';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss'],
})
export class LoginComponent implements OnInit {

  user: User = new User();  

  constructor(public alertController: AlertController, private restConnectService: RestConnectService, private router: Router) { }

  ngOnInit() {}

  doLogin() {
    console.log(this.user);
    this.restConnectService.authorizeUser(this.user.login, this.user.password).subscribe(data => {
      console.log(data);
      if(data.status == "ok") {
        this.user.token = data.result.userToken;
        this.presentAlert("Info", "Zalogowano pomyślnie. Możesz przejść do edycji notatek.", true);        
      } else {
        this.presentAlert("Info", "Logowanie nieudane. Spróbuj ponownie.", false);
      }
    })
  }

  async presentAlert(headerPar: string, messagePar: string, actionSuccess: boolean) {
    const alert = await this.alertController.create({
      header: headerPar,
      message: messagePar,
      buttons: [
        {
          text: 'OK',
          handler: (ev) => {
            if(actionSuccess) {
              SharedGlobals.userToken = this.user.token;
              SharedGlobals.userIsLoggedIn = true;
              this.router.navigate(['/notes-editor/editor']);
            }            
          }
        }
      ]
    });

    await alert.present();
  }

}
