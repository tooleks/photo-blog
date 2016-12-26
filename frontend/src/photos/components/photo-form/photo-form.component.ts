import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {TitleService} from '../../../shared/services/title';
import {PhotoService} from '../../services/photo.service';
import {PhotoModel} from '../../models/photo-model';
import {UploadedPhotoModel} from '../../models/uploaded-photo-model';
import {NotificatorService} from '../../../shared/services/notificator/notificator.service';
import {SyncProcessService, SyncProcessServiceProvider} from '../../../shared/services/sync-process';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';

@Component({
    selector: 'photo-form',
    template: require('./photo-form.component.html'),
})
export class PhotoFormComponent {
    protected photo:PhotoModel;
    protected syncProcessService:SyncProcessService;
    protected navigatorService:NavigatorService;

    constructor(@Inject(ActivatedRoute) protected route:ActivatedRoute,
                @Inject(TitleService) protected titleService:TitleService,
                @Inject(PhotoService) protected photoService:PhotoService,
                @Inject(NotificatorService) protected notificatorService:NotificatorService,
                @Inject(SyncProcessServiceProvider) protected syncProcessServiceProvider:SyncProcessServiceProvider,
                @Inject(NavigatorServiceProvider) protected navigatorServiceProvider:NavigatorServiceProvider) {
        this.syncProcessService = this.syncProcessServiceProvider.getInstance();
        this.navigatorService = this.navigatorServiceProvider.getInstance();
    }

    ngOnInit() {
        this.photo = new PhotoModel;

        this.titleService.setTitle('Add Photo');

        this.route.params
            .map((params) => params['id'])
            .subscribe((id:number) => {
                if (!id) {
                    return;
                }

                this.titleService.setTitle('Edit Photo');

                this.photoService
                    .getById(id)
                    .then((photo:PhotoModel) => this.photo.setAttributes(photo));
            });
    }

    protected processSavePhoto = () => {
        let saver = this.photo.id
            ? this.photoService.updateById(this.photo.id, this.photo)
            : this.photoService.create(this.photo);

        return saver.then((photo:PhotoModel) => {
            this.photo.setAttributes(photo);
            return photo;
        });
    };

    save = () => {
        return this.syncProcessService
            .startProcess()
            .then(() => this.processSavePhoto())
            .then((result:any) => {
                this.syncProcessService.endProcess();
                this.notificatorService.success('Photo was successfully saved.');
                this.navigatorService.navigate(['/photos']);
                return result;
            })
            .catch(this.syncProcessService.handleErrors);
    };

    protected processUploadPhoto = (file:FileList) => {
        let uploader = this.photo.id
            ? this.photoService.uploadById(this.photo.id, file)
            : this.photoService.upload(file);

        return uploader.then((uploadedPhoto:UploadedPhotoModel) => {
            this.photo.setUploadedAttributes(uploadedPhoto);
            return uploadedPhoto;
        });
    };

    upload = (file:FileList) => {
        return this.syncProcessService
            .startProcess()
            .then(() => this.processUploadPhoto(file))
            .then((result:any) => {
                this.syncProcessService.endProcess();
                this.notificatorService.success('File was successfully uploaded.');
                return result;
            })
            .catch(this.syncProcessService.handleErrors);
    };

    protected processDeletePhoto = () => this.photoService.deleteById(this.photo.id);

    deletePhoto = () => {
        if (!this.photo.id) {
            return Promise.reject(new Error('Invalid photo id.'));
        }

        return this.syncProcessService
            .startProcess()
            .then(() => this.processDeletePhoto())
            .then((result:any) => {
                this.syncProcessService.endProcess();
                this.notificatorService.success('Photo was successfully deleted.');
                this.navigatorService.navigate(['/photos']);
                return result;
            })
            .catch(this.syncProcessService.handleErrors);
    };
}
