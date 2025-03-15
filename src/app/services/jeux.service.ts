import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class JeuxService {
  baseApi = "http://localhost:8000/api";

  constructor(private http: HttpClient) {}

  getImage(){
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.get(`${this.baseApi}/get-images`,{headers})
  }
  addVote(body){
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.post(`${this.baseApi}/addvote`,body,{headers})
  }
  getVoteCountByImage(id){
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.get(`${this.baseApi}/getvote-by-image/${id}`,{headers})
  }


}
