import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {PhotoService} from '../../services/photo.service';
import {PhotoModel} from '../../models/photo-model';
import {NotificatorService} from '../../../shared/services/notificator/notificator.service';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';

@Component({
    selector: 'photo-form',
    template: require('./photo-form.component.html'),
})
export class PhotoFormComponent {
    photo:PhotoModel = new PhotoModel;
    private navigatorService:NavigatorService;

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(PhotoService) private photoService:PhotoService,
                @Inject(NotificatorService) private notificatorService:NotificatorService,
                @Inject(NavigatorServiceProvider) private navigatorServiceProvider:NavigatorServiceProvider) {
        this.navigatorService = navigatorServiceProvider.getInstance();
    }

    ngOnInit() {
        this.photo = new PhotoModel;
    }

    private onSave(photo:PhotoModel) {
        this.photo = photo;
        this.notificatorService.success('Record was successfully saved.');
        this.navigatorService.navigate(['/photos']);
    }

    save() {
        if (!this.photo.id) {
            this.photoService
                .create(this.photo)
                .subscribe(this.onSave.bind(this));
        } else {
            this.photoService
                .updateById(this.photo.id, this.photo)
                .subscribe(this.onSave.bind(this));
        }
    }

    private onUpload(photo:PhotoModel) {
        this.photo.id = photo.id;
        this.photo.is_uploaded = photo.is_uploaded;
        this.photo.absolute_url = photo.absolute_url;
        this.photo.updated_at = photo.updated_at;
        this.photo.thumbnails = photo.thumbnails;
        this.notificatorService.success('File was successfully uploaded.');
    }

    upload(file:FileList) {
        if (this.photo.id) {
            this.photoService
                .uploadById(this.photo.id, file)
                .subscribe(this.onUpload.bind(this));
        } else {
            this.photoService
                .upload(file)
                .subscribe(this.onUpload.bind(this));
        }
    }
}
