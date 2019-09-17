import { Component, OnInit } from '@angular/core';
import { AlertController } from '@ionic/angular';
import { User } from 'src/app/models/user';
import { RestConnectService } from 'src/app/services/rest-connect.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss'],
})
export class RegisterComponent implements OnInit {

  userToRegister: User = new User();

  constructor(public alertController: AlertController, private restConnectService: RestConnectService) { }

  ngOnInit() {
    
  }

  doRegister() {
    console.log(this.userToRegister);
    this.restConnectService.registerUser(this.userToRegister.login, this.userToRegister.password, this.userToRegister.email).subscribe(data => {
      console.log(data);
    })
  }

  async presentAlert(headerPar: string, messagePar: string) {
    const alert = await this.alertController.create({
      header: headerPar,
      message: messagePar,
      buttons: ['OK']
    });

    await alert.present();
  }

}
