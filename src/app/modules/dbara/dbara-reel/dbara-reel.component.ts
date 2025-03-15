import {Component, OnInit, TemplateRef, ViewChild} from '@angular/core';
import {DomSanitizer, SafeResourceUrl} from "@angular/platform-browser";
import {DbaraLiveService} from "../../../services/dbara-live.service";
import {NgxUiLoaderService} from "ngx-ui-loader";
import {MatDialog} from "@angular/material/dialog";
import {DbaraReelService} from "../../../services/dbara-reel.service";

@Component({
  selector: 'app-dbara-reel',
  templateUrl: './dbara-reel.component.html',
  styleUrls: ['./dbara-reel.component.css']
})
export class DbaraReelComponent  implements OnInit{
  data
  currentVideoUrl: SafeResourceUrl;
  @ViewChild('videoDialog') videoDialog: TemplateRef<any>;
  constructor(private reelService:DbaraReelService,private ngxLoader:NgxUiLoaderService,public dialog: MatDialog,private sanitizer: DomSanitizer) {
  }
  openYoutubeDialog(videoId: string) {
    this.currentVideoUrl = this.getSafeUrl(videoId);
    this.dialog.open(this.videoDialog, {
      width: '800px',
      height: '500px',
    });
  }
  getData(){
    this.ngxLoader.start()
    this.reelService.getDbaraReelData().subscribe(
      {
        next:res =>{
          this.data=res
          console.log("data",this.data)
        },
        error:err => {
          console.log(err)
        },
        complete:()=>{
          this.ngxLoader.stop()
        }
      }
    )
  }
  getSafeUrl(videoId: string): SafeResourceUrl {
    return this.sanitizer.bypassSecurityTrustResourceUrl(`https://www.youtube.com/embed/${videoId}`);
  }

  ngOnInit() {
    this.getData()
  }

}
