import { Component, OnInit } from '@angular/core';
import { AlertController } from '@ionic/angular';
import { Router } from '@angular/router';

import { RestConnectService } from 'src/app/services/rest-connect.service';
import { User } from 'src/app/models/user';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss'],
})
export class RegisterComponent implements OnInit {

  userToRegister: User = new User();

  constructor(public alertController: AlertController, private restConnectService: RestConnectService, private router: Router) { }

  ngOnInit() {
    
  }

  doRegister() {
    console.log(this.userToRegister);
    this.restConnectService.registerUser(this.userToRegister.login, this.userToRegister.password, this.userToRegister.email).subscribe(data => {
      console.log(data);
      if(data.status == "ok") {
        this.presentAlert("Info", "Rejestracja pomyślna. Możesz się teraz zalogować.", true);
      } else {
        this.presentAlert("Info", "Rejestracja nieudana. Spróbuj ponownie.", false);
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
              this.router.navigate(['/account/login']);
            }            
          }
        }
      ]
    });

    await alert.present();
  }

}
