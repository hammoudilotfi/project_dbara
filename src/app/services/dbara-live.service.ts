import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {Recette} from "../model/recette";

@Injectable({
  providedIn: 'root'
})
export class DbaraLiveService {
  baseApi = "http://localhost:8000/api";

  constructor(private http: HttpClient) {}


  getDbaraLiveData(){
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.get<Recette[]>(`${this.baseApi}/getdbaralive`,{headers})
  }
}
