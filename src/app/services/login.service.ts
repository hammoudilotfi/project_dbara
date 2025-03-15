import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {Observable} from "rxjs";

import {Login} from "../model/login";

@Injectable({
  providedIn: 'root'
})
export class LoginService {
  baseApi = " http://localhost:8000/api"

  constructor(private http: HttpClient) {
  }

  login(body: Login): Observable<Login> {
    return this.http.post<Login>(`${this.baseApi}/login_check`, body)
  }
  resetPassword(body){
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.post(`${this.baseApi}/reset-password`, body,{headers})
  }


}
