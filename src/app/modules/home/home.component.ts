import {Component, OnChanges, OnInit, SimpleChanges} from '@angular/core';
import {RecetteService} from "../../services/recette.service";
import {Recette} from "../../model/recette";
import {NgxUiLoaderService} from "ngx-ui-loader";
import {ToastrService} from "ngx-toastr";
import {debounceTime, Subject, switchMap} from "rxjs";

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit,OnChanges{
  data
  searchResults = [];
  searchSubject = new Subject<string>();
constructor(private recetteService:RecetteService,private ngxLoader:NgxUiLoaderService, private toastr:ToastrService) {

}
searchChef(){
  this.searchSubject.pipe(
    debounceTime(300),
    switchMap(query => this.recetteService.searchByName(query))
  ).subscribe((results: any) => {
    this.searchResults = results;
  });
}
  onKeyUp(event: any) {
    const query = event.target.value;
    this.searchSubject.next(query);
  }
  getRecettes() {
    this.ngxLoader.start()
    this.recetteService.getAll().subscribe({
      next: (res:Recette[]) => {
        this.data = res.slice(0, 6);
        this.toastr.success("Welcome Home","WELCOME")
        console.log(this.data);
      },
      error: err => {
        this.toastr.error("Try To Login first","Error")
        console.log(err);
      },
      complete:()=>{
        this.ngxLoader.stop()
    }
    });
  }


ngOnInit() {
this.searchChef()
    this.getRecettes()
}
ngOnChanges(changes: SimpleChanges) {
  this.searchChef()
}
}
