import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class AccountService {
  baseApi = "http://localhost:8000/api";

  constructor(private http: HttpClient) {}


  getUser(){
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.get(`${this.baseApi}/get_user`,{headers})
  }
  putUser(body,id){
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.put(`${this.baseApi}/updateuser/${id}`,body,{headers})
  }

}
