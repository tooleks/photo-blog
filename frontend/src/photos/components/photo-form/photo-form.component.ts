import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {
    TitleService,
    ScrollerService,
    AuthProviderService,
    NotificatorService,
    NavigatorServiceProvider,
    NavigatorService,
    LockProcessServiceProvider,
    LockProcessService,
} from '../../../shared/services';
import {Photo, UploadedPhoto} from '../../../shared/models';
import {PhotoDataProviderService} from '../../services'

@Component({
    selector: 'photo-form',
    template: require('./photo-form.component.html'),
    styles: [require('./photo-form.component.css').toString()],
})
export class PhotoFormComponent {
    private photo:Photo;
    private navigator:NavigatorService;
    private lockProcess:LockProcessService;

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(TitleService) private title:TitleService,
                @Inject(ScrollerService) private scroller:ScrollerService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService,
                @Inject(PhotoDataProviderService) private photoDataProvider:PhotoDataProviderService,
                @Inject(NotificatorService) private notificator:NotificatorService,
                @Inject(NavigatorServiceProvider) navigatorServiceProvider:NavigatorServiceProvider,
                @Inject(LockProcessServiceProvider) lockProcessServiceProvider:LockProcessServiceProvider) {
        this.navigator = navigatorServiceProvider.getInstance();
        this.lockProcess = lockProcessServiceProvider.getInstance();
    }

    ngOnInit() {
        this.scroller.scrollToTop();

        this.title.setTitle('Add Photo');

        this.photo = new Photo;

        this.route.params.map((params) => params['id']).subscribe((id:number) => {
            if (!id) {
                return;
            }

            this.photoDataProvider.getById(id).then((photo:Photo) => {
                this.title.setTitle('Edit Photo');
                this.photo = photo;
                return photo;
            });
        });
    }

    private processSavePhoto = () => {
        let saver = this.photo.id
            ? this.photoDataProvider.updateById(this.photo.id, this.photo)
            : this.photoDataProvider.create(this.photo);

        return saver.then((photo:Photo) => {
            this.photo = photo;
            return photo;
        });
    };

    save = () => {
        return this.lockProcess.process(this.processSavePhoto).then((result:any) => {
            this.notificator.success('Photo was successfully saved.');
            this.navigator.navigate(['/photos']);
            return result;
        });
    };

    private processUploadPhoto = (file:FileList) => {
        let uploader = this.photo.id
            ? this.photoDataProvider.uploadById(this.photo.id, file)
            : this.photoDataProvider.upload(file);

        return uploader.then((uploadedPhoto:UploadedPhoto) => {
            this.photo.uploaded_photo_id = uploadedPhoto.id;
            this.photo.user_id = uploadedPhoto.user_id;
            this.photo.absolute_url = uploadedPhoto.absolute_url;
            this.photo.created_at = uploadedPhoto.absolute_url;
            this.photo.updated_at = uploadedPhoto.absolute_url;
            this.photo.thumbnails = uploadedPhoto.thumbnails;
            this.photo.exif = uploadedPhoto.exif;
            return uploadedPhoto;
        });
    };

    upload = (file:FileList) => {
        return this.lockProcess.process(this.processUploadPhoto, [file]).then((result:any) => {
            this.notificator.success('File was successfully uploaded.');
            return result;
        });
    };

    private processDeletePhoto = () => {
        let deleter:Promise<any> = this.photo.id
            ? this.photoDataProvider.deleteById(this.photo.id)
            : Promise.reject(new Error('You can\'n delete unsaved photo.'));

        return deleter;
    };

    deletePhoto = () => {
        return this.lockProcess.process(this.processDeletePhoto).then((result:any) => {
            this.notificator.success('Photo was successfully deleted.');
            this.navigator.navigate(['/photos']);
            return result;
        });
    };

    isLoading = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}
