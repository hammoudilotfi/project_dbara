import {Component, ViewEncapsulation} from '@angular/core';
import {MatDialogRef} from "@angular/material/dialog";
import {Router} from "@angular/router";
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {UploadImageService} from "../../services/upload-image.service";
import {NgxUiLoaderService} from "ngx-ui-loader";
import {ToastrService} from "ngx-toastr";

@Component({
  selector: 'app-pop-up',
  templateUrl: './pop-up.component.html',
  styleUrls: ['./pop-up.component.css'],

})
export class PopUpComponent{
  selectedFile: File | null = null;
  imageUrl: any
  constructor(private toast:ToastrService,private ngxLoader:NgxUiLoaderService,public dialogRef: MatDialogRef<PopUpComponent>,private router:Router, private http: HttpClient,private storageService:UploadImageService) {}
  onFileSelected(event: any) {
    this.selectedFile = event.target.files[0] as File;
  }
  async uploadImage() {
    if (!this.selectedFile) return;

    try {
      this.ngxLoader.start()
      this.imageUrl = await this.storageService.uploadImage(this.selectedFile);
      const apiEndpoint = 'http://127.0.0.1:8000/api/upload-image';
      const requestBody = { filename: this.imageUrl };
      const token = localStorage.getItem('jwt_token');
      const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);

      this.http.post(apiEndpoint, requestBody,{headers}).subscribe(response => {
this.toast.success("Image uploaded and API response:")
        this.ngxLoader.stop()
      });
    } catch (error) {
      console.error('Error uploading image:', error);
      this.toast.error("Error uploading image:")
    }
  }
  closeDialog(): void {
    this.dialogRef.close();
  }
  navigate(){
    this.dialogRef.close();
    this.router.navigate(['/jeux'])
  }
}
