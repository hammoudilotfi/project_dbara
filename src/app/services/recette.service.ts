import { Injectable } from '@angular/core';
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {Recette} from "../model/recette";
import {Observable} from "rxjs";

@Injectable({
  providedIn: 'root'
})
export class RecetteService {
  baseApi = " http://localhost:8000/api"

  constructor(private http: HttpClient) {
  }

  getAll(): Observable<Recette[]> {
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.get<Recette[]>(`${this.baseApi}/getdbaretchef`,{headers})
  }

  getById(id): Observable<Recette[]> {
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.get<Recette[]>(`${this.baseApi}/showdbaretchef/${id}`,{headers})
  }
  searchByName(nom: string): Observable<any> {
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.get<any>(`${this.baseApi}/searchdbaretchef/${nom}`, { headers });
  }
  addPrefer(id){
    const body = {}
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.post<Recette[]>(`${this.baseApi}/dbaretchef/${id}/add-to-preferred`,body,{headers})
  }
  getWishlist(){
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.get<any>(`${this.baseApi}/getdbartiprefere`, { headers });
  }
  getChef(){
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.get<any>(`${this.baseApi}/getchef`, { headers });
  }
  addVote(body){
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.post(`${this.baseApi}/addnote`,body,{headers})
  }

  getVoteCountByImage(id){
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.get(`${this.baseApi}/getnote-by-dbaretelchef/${id}`,{headers})
  }

}
