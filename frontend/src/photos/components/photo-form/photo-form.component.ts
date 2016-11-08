import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {PhotoService} from '../../services/photo.service';
import {PhotoModel} from '../../models/photo-model';
import {NotificatorService} from '../../../shared/services/notificator/notificator.service';
import {LockerService, LockerServiceProvider} from '../../../shared/services/locker';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';

@Component({
    selector: 'photo-form',
    template: require('./photo-form.component.html'),
})
export class PhotoFormComponent {
    private photo:PhotoModel = new PhotoModel;
    private lockerService:LockerService;
    private navigatorService:NavigatorService;

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(PhotoService) private photoService:PhotoService,
                @Inject(NotificatorService) private notificatorService:NotificatorService,
                @Inject(LockerServiceProvider) private lockerServiceProvider:LockerServiceProvider,
                @Inject(NavigatorServiceProvider) private navigatorServiceProvider:NavigatorServiceProvider) {
        this.lockerService = this.lockerServiceProvider.getInstance();
        this.navigatorService = this.navigatorServiceProvider.getInstance();
    }

    ngOnInit() {
        this.photo = new PhotoModel;
    }

    private processSave(photo:PhotoModel) {
        let observer = photo.id ? this.photoService.updateById(photo.id, photo) : this.photoService.create(photo);
        return observer.toPromise();
    }

    private processUpload(photo:PhotoModel, file:FileList) {
        let observer = photo.id ? this.photoService.uploadById(photo.id, file) : this.photoService.upload(file);
        return observer.toPromise();
    }

    save() {
        return (new Promise((resolve, reject) => {
            this.lockerService.isLocked() ? reject() : this.lockerService.lock();
            this.processSave(this.photo)
                .then((photo:PhotoModel) => {
                    this.lockerService.unlock();
                    resolve(photo);
                })
                .catch((error:any) => {
                    this.lockerService.unlock();
                });
        })).then(this.onSave.bind(this));
    }

    onSave(photo:PhotoModel) {
        this.photo = photo;
        this.notificatorService.success('Record was successfully saved.');
        this.navigatorService.navigate(['/photos']);
        return photo;
    }

    upload(file:FileList) {
        return (new Promise((resolve, reject) => {
            this.lockerService.isLocked() ? reject() : this.lockerService.lock();
            this.processUpload(this.photo, file)
                .then((photo:PhotoModel) => {
                    this.lockerService.unlock();
                    resolve(photo);
                })
                .catch((error:any) => {
                    this.lockerService.unlock();
                });
        })).then(this.onUpload.bind(this));
    }

    onUpload(photo:PhotoModel) {
        this.photo.id = photo.id;
        this.photo.is_uploaded = photo.is_uploaded;
        this.photo.absolute_url = photo.absolute_url;
        this.photo.updated_at = photo.updated_at;
        this.photo.thumbnails = photo.thumbnails;
        this.notificatorService.success('File was successfully uploaded.');
        return photo;
    }
}
