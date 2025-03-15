import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class CategoriesService {
  baseApi = "http://localhost:8000/api";

  constructor(private http: HttpClient) {}

  getCat() {
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);

    return this.http.get(`${this.baseApi}/getsubcategory`, { headers: headers });
  }

  getRecepieBycatId(id){
    const token = localStorage.getItem('jwt_token');
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);

    return this.http.get(`${this.baseApi}/getrecettebysubcategory/${id}`, { headers: headers });
  }

}
