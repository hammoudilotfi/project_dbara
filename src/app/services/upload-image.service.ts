import { Injectable } from '@angular/core';
import {AngularFireStorage} from "@angular/fire/compat/storage";


@Injectable({
  providedIn: 'root'
})
export class UploadImageService {
  constructor(private storage: AngularFireStorage) {}

  uploadImage(file: File): Promise<string> {
    const filePath = `images/${file.name}`;
    const storageRef = this.storage.ref(filePath);

    return new Promise<string>((resolve, reject) => {
      const task = storageRef.put(file);

      task.then(() => {
        storageRef.getDownloadURL().subscribe(url => {
          resolve(url);
          console.log(url)
        });
      }).catch(error => {
        reject(error);
      });
    });
  }
}
