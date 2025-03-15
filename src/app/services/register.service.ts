import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";
import {Register} from "../model/register";

@Injectable({
  providedIn: 'root'
})
export class RegisterService {
  baseApi = " http://localhost:8000/api"

  constructor(private http:HttpClient) { }
  postUser(body:Register):Observable<Register>{

    return this.http.post<Register>(`${this.baseApi}/register`,body)
  }

}
