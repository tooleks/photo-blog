import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {TitleService} from '../../../shared/services/title';
import {PhotoService} from '../../services/photo.service';
import {PhotoModel} from '../../models/photo-model';
import {UploadedPhotoModel} from '../../models/uploaded-photo-model';
import {NotificatorService} from '../../../shared/services/notificator/notificator.service';
import {LockerService, LockerServiceProvider} from '../../../shared/services/locker';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';

@Component({
    selector: 'photo-form',
    template: require('./photo-form.component.html'),
})
export class PhotoFormComponent {
    protected photo:PhotoModel;
    protected lockerService:LockerService;
    protected navigatorService:NavigatorService;

    constructor(@Inject(ActivatedRoute) protected route:ActivatedRoute,
                @Inject(TitleService) protected titleService:TitleService,
                @Inject(PhotoService) protected photoService:PhotoService,
                @Inject(NotificatorService) protected notificatorService:NotificatorService,
                @Inject(LockerServiceProvider) protected lockerServiceProvider:LockerServiceProvider,
                @Inject(NavigatorServiceProvider) protected navigatorServiceProvider:NavigatorServiceProvider) {
        this.lockerService = this.lockerServiceProvider.getInstance();
        this.navigatorService = this.navigatorServiceProvider.getInstance();
    }

    ngOnInit() {
        this.titleService.setTitle('Add Photo');

        this.photo = new PhotoModel;

        this.route.params
            .map((params) => params['id'])
            .subscribe((id:number) => {
                if (id) {
                    this.titleService.setTitle('Edit Photo');
                    this.photoService.getById(id).toPromise().then((photo:PhotoModel) => {
                        this.photo = photo;
                    });
                }
            });
    }

    savePhoto() {
        if (this.lockerService.isLocked()) return new Promise((resolve, reject) => reject());
        else this.lockerService.lock();
        let saver = this.photo.id ? this.photoService.updateById(this.photo.id, this.photo) : this.photoService.create(this.photo);
        return saver.toPromise().then((photo:PhotoModel) => {
            this.lockerService.unlock();
            this.photo = photo;
            this.notificatorService.success('Record was successfully saved.');
            this.navigatorService.navigate(['/photos']);
            return photo;
        }).catch((error:any) => {
            this.lockerService.unlock();
            return error;
        });
    }

    uploadPhoto(file:FileList) {
        if (this.lockerService.isLocked()) return new Promise((resolve, reject) => reject());
        else this.lockerService.lock();
        let uploader = this.photo.id ? this.photoService.uploadById(this.photo.id, file) : this.photoService.upload(file);
        return uploader.toPromise().then((uploadedPhoto:UploadedPhotoModel) => {
            this.lockerService.unlock();
            this.photo.uploaded_photo_id = uploadedPhoto.id;
            this.photo.absolute_url = uploadedPhoto.absolute_url;
            this.photo.thumbnails = uploadedPhoto.thumbnails;
            this.notificatorService.success('File was successfully uploaded.');
            return uploadedPhoto;
        }).catch((error:any) => {
            this.lockerService.unlock();
            return error;
        });
    }

    deletePhoto() {
        if (this.photo.id) {
            if (this.lockerService.isLocked()) return new Promise((resolve, reject) => reject());
            else this.lockerService.lock();
            return this.photoService.deleteById(this.photo.id).toPromise().then((result:any) => {
                this.navigatorService.navigate(['/photos']);
                return result;
            }).catch((error:any) => {
                this.lockerService.unlock();
                return error;
            });
        } else {
            return new Promise((resolve, reject) => reject());
        }
    }
}
