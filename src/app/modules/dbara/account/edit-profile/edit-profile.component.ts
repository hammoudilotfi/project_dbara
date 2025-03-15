import {Component, isStandalone, OnInit} from '@angular/core';
import {NgxUiLoaderService} from "ngx-ui-loader";
import {LoginService} from "../../../../services/login.service";
import {ActivatedRoute, Router} from "@angular/router";
import {ToastrService} from "ngx-toastr";
import {AccountService} from "../../../../services/account.service";

@Component({
  selector: 'app-edit-profile',
  templateUrl: './edit-profile.component.html',
  styleUrls: ['./edit-profile.component.css']
})
export class EditProfileComponent implements OnInit{
  data
  id
  constructor( private ngxLoader:NgxUiLoaderService, private accountService: AccountService,private router: Router, private toastr:ToastrService,private activeRoute:ActivatedRoute) {
  }




  private getProfileData() {
    this.ngxLoader.start()
    this.accountService.getUser().subscribe(res=>{
      this.data=res
      this.ngxLoader.stop()
    })
  }

  submit(){
    this.ngxLoader.start()
    let objectTosebd={
      email:this.data.email,
      nom:this.data.nom,
      prenom:this.data.prenom,
      sexe:this.data.sexe,
      tel:this.data.tel,
      pin:this.data.pin,

    }
    this.accountService.putUser(objectTosebd,this.data.id).subscribe({
      next:res=>{
        this.ngxLoader.stop()
        this.toastr.success("Your profile Updated Successfully")
      },
      error: err =>{
        this.toastr.error("Error while Updating Profile",err)
    },
      complete: () => {
        this.router.navigate(['/my-account']);
      },
    })
  }

  ngOnInit() {
      this.getProfileData();
  }

  protected readonly isStandalone = isStandalone;
}
