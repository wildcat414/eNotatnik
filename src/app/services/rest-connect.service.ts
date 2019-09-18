import { Injectable } from '@angular/core';
import { Http, Response, RequestOptions, Headers, ResponseContentType } from '@angular/http';
import { HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { map, retry, timeout, catchError } from 'rxjs/operators';
import { SharedGlobals } from '../sharedGlobals';
import { User } from '../models/user';

@Injectable({
  providedIn: 'root'
})
export class RestConnectService {
  baseRestApiUrl:string = 'http://wildcatdev.eu/api/enotatnik';

  constructor(private http: Http) { }

  registerUser(loginPar: string, passwordPar: string, emailPar: string): Observable<any> {
    let headers = new Headers({ 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Authorization-Token': SharedGlobals.masterToken  });
    let options = new RequestOptions({ headers: headers });
    console.log("REST registerUser");
    return this.http.post(this.baseRestApiUrl + '/user-registration.php', JSON.stringify({ login: loginPar, password: passwordPar, email: emailPar }), options).pipe(
      timeout(SharedGlobals.defaultTimeout),
			map((res: Response) => res.json()),
      catchError(this.handleError));
  }

  authorizeUser(loginPar: string, passwordPar: string): Observable<any> {
    let headers = new Headers({ 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Authorization-Token': SharedGlobals.masterToken  });
    let options = new RequestOptions({ headers: headers });
    console.log("REST authorizeUser");
    return this.http.post(this.baseRestApiUrl + '/user-authorization.php', JSON.stringify({ login: loginPar, password: passwordPar }), options).pipe(
      timeout(SharedGlobals.defaultTimeout),
			map((res: Response) => res.json()),
      catchError(this.handleError));
  }

  remindPassword(loginPar: string): Observable<any> {
    let headers = new Headers({ 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Authorization-Token': SharedGlobals.masterToken  });
    let options = new RequestOptions({ headers: headers });
    console.log("REST remindPassword");
    return this.http.post(this.baseRestApiUrl + '/password-remind.php', JSON.stringify({ login: loginPar }), options).pipe(
      timeout(SharedGlobals.defaultTimeout),
			map((res: Response) => res.json()),
      catchError(this.handleError));
  }

  getNote(): Observable<any> {
    console.log(SharedGlobals.userToken);
    let headers = new Headers({ 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Authorization-Token': SharedGlobals.userToken  });
    let options = new RequestOptions({ headers: headers });
    console.log("REST getNote");
    return this.http.get(this.baseRestApiUrl + '/get-note.php', options).pipe(
      timeout(SharedGlobals.defaultTimeout),
			map((res: Response) => res.json()),
      catchError(this.handleError));
  }

  updateNote(contentPar: string): Observable<any> {
    let headers = new Headers({ 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Authorization-Token': SharedGlobals.userToken  });
    let options = new RequestOptions({ headers: headers });
    console.log("REST updateNote");
    return this.http.put(this.baseRestApiUrl + '/update-note.php', JSON.stringify({ noteContent: contentPar }), options).pipe(
      timeout(SharedGlobals.defaultTimeout),
			map((res: Response) => res.json()),
      catchError(this.handleError));
  }

  getNoteHistory(): Observable<any> {
    let headers = new Headers({ 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Authorization-Token': SharedGlobals.userToken  });
    let options = new RequestOptions({ headers: headers });
    console.log("REST getNoteHistory");
    return this.http.get(this.baseRestApiUrl + '/get-note-history.php', options).pipe(
      timeout(SharedGlobals.defaultTimeout),
			map((res: Response) => res.json()),
      catchError(this.handleError));
  }

  private handleError(error: HttpErrorResponse) {
    if (error.error instanceof ErrorEvent) {
      // A client-side or network error occurred. Handle it accordingly.
      console.error('An unexpected client-side error occurred:', error.error.message);
    } else {
      // The backend returned an unsuccessful response code.
      // The response body may contain clues as to what went wrong,
      console.error(
        `Backend returned code ${error.status}, ` +
        `details: ${error.error}`);
    }
    // return an observable with a user-facing error message
    return throwError(
      'Something bad happened; please try again later.');
  }
  
}
