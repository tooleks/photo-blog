import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {PhotoForm} from './models';
import {
    TitleService,
    AuthProviderService,
    NavigatorServiceProvider,
    NavigatorService,
    LockProcessServiceProvider,
    LockProcessService,
} from '../../../shared/services';
import {NoticesService} from '../../../common/notices';
import {PhotoDataProviderService} from '../../services'

@Component({
    selector: 'photo-form',
    templateUrl: 'photo-form.component.html',
    styleUrls: ['photo-form.component.css'],
})
export class PhotoFormComponent {
    private photo:PhotoForm;
    private navigator:NavigatorService;
    private lockProcess:LockProcessService;

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(TitleService) private title:TitleService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService,
                @Inject(PhotoDataProviderService) private photoDataProvider:PhotoDataProviderService,
                @Inject(NoticesService) private notices:NoticesService,
                @Inject(NavigatorServiceProvider) navigatorServiceProvider:NavigatorServiceProvider,
                @Inject(LockProcessServiceProvider) lockProcessServiceProvider:LockProcessServiceProvider) {
        this.navigator = navigatorServiceProvider.getInstance();
        this.lockProcess = lockProcessServiceProvider.getInstance();
    }

    ngOnInit() {
        this.title.setTitle('Add Photo');
        this.photo = new PhotoForm;
    }

    ngAfterViewInit() {
        this.route.params
            .map((params) => params['id'])
            .subscribe(this.loadById);
    }

    private processLoadById = (id:number) => {
        return id
            ? this.photoDataProvider.getById(id)
            : Promise.reject(new Error('Photo ID is not provided.'));
    };

    loadById = (id:number) => {
        return this.lockProcess.process(this.processLoadById, [id]).then((photo:any) => {
            this.photo.setSavedPhotoAttributes(photo);
            this.title.setTitle('Edit Photo');
            return photo;
        });
    };

    private processSavePhoto = () => {
        return this.photo.id
            ? this.photoDataProvider.updateById(this.photo.id, this.photo)
            : this.photoDataProvider.create(this.photo);
    };

    save = () => {
        return this.lockProcess.process(this.processSavePhoto).then((photo:any) => {
            this.photo.setSavedPhotoAttributes(photo);
            this.notices.success('Photo was successfully saved.');
            this.navigator.navigate(['/photos']);
            return photo;
        });
    };

    private processUploadPhoto = (file:FileList) => {
        return this.photo.id
            ? this.photoDataProvider.uploadById(this.photo.id, file)
            : this.photoDataProvider.upload(file);
    };

    upload = (file:FileList) => {
        return this.lockProcess.process(this.processUploadPhoto, [file]).then((photo:any) => {
            this.photo.setUploadedPhotoAttributes(photo);
            this.notices.success('File was successfully uploaded.');
            return photo;
        });
    };

    private processDeletePhoto = () => {
        return this.photo.id
            ? this.photoDataProvider.deleteById(this.photo.id)
            : Promise.reject(new Error('Photo ID is not provided.'));
    };

    deletePhoto = () => {
        return this.lockProcess.process(this.processDeletePhoto).then((result:any) => {
            this.notices.success('Photo was successfully deleted.');
            this.navigator.navigate(['/photos']);
            return result;
        });
    };

    isLoading = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}
