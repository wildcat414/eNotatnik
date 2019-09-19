import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Toast } from '@ionic-native/toast/ngx';

import { SharedGlobals } from 'src/app/sharedGlobals';
@Component({
  selector: 'app-logout',
  templateUrl: './logout.component.html',
  styleUrls: ['./logout.component.scss'],
})
export class LogoutComponent implements OnInit {

  constructor(private router: Router, private toast: Toast) { }

  ngOnInit() {
    SharedGlobals.userToken = "";
    SharedGlobals.userIsLoggedIn = false;

    this.toast.show('Użytkownik został wylogowany poprawnie!', '5000', 'center').subscribe(
      toast => {
        console.log(toast);
      }
    );
    
  }

  goToHome() {
    this.router.navigate(['/home']);
  }

}
