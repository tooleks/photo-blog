import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {NoticesService} from '../../../lib';
import {
    TitleService,
    AuthProviderService,
    NavigatorServiceProvider,
    NavigatorService,
    LockProcessServiceProvider,
    LockProcessService,
} from '../../../shared';
import {PhotoDataProviderService} from '../../services'
import {Photo} from './models';

@Component({
    selector: 'photo-form',
    templateUrl: 'photo-form.component.html',
    styleUrls: ['photo-form.component.css'],
})
export class PhotoFormComponent implements OnInit {
    protected photo:Photo;
    protected navigator:NavigatorService;
    protected lockProcess:LockProcessService;

    constructor(protected route:ActivatedRoute,
                protected title:TitleService,
                protected authProvider:AuthProviderService,
                protected photoDataProvider:PhotoDataProviderService,
                protected notices:NoticesService,
                navigatorServiceProvider:NavigatorServiceProvider,
                lockProcessServiceProvider:LockProcessServiceProvider) {
        this.navigator = navigatorServiceProvider.getInstance();
        this.lockProcess = lockProcessServiceProvider.getInstance();
    }

    ngOnInit():void {
        this.photo = new Photo;
        this.title.setTitle('Add Photo');
        this.initParamsSubscribers();

    }

    protected initParamsSubscribers = ():void => {
        this.route.params
            .map((params) => params['id'])
            .filter((id:any) => id)
            .subscribe(this.loadById);
    };

    protected processLoadById = (id:number):Promise<any> => {
        return id
            ? this.photoDataProvider.getById(id)
            : Promise.reject(new Error('Photo ID is not provided.'));
    };

    loadById = (id:number):Promise<any> => {
        return this.lockProcess.process(this.processLoadById, [id]).then((photo:any) => {
            this.photo.setSavedPhotoAttributes(photo);
            this.title.setTitle('Edit Photo');
            return photo;
        });
    };

    protected processSavePhoto = ():Promise<any> => {
        return this.photo.id
            ? this.photoDataProvider.updateById(this.photo.id, this.photo)
            : this.photoDataProvider.create(this.photo);
    };

    save = ():Promise<any> => {
        return this.lockProcess.process(this.processSavePhoto).then((photo:any) => {
            this.photo.setSavedPhotoAttributes(photo);
            this.notices.success('Photo was successfully saved.');
            this.navigator.navigate(['/photos']);
            return photo;
        });
    };

    protected processUploadPhoto = (file:FileList):Promise<any> => {
        return this.photo.id
            ? this.photoDataProvider.uploadById(this.photo.id, file)
            : this.photoDataProvider.upload(file);
    };

    upload = (file:FileList):Promise<any> => {
        return this.lockProcess.process(this.processUploadPhoto, [file]).then((photo:any) => {
            this.photo.setUploadedPhotoAttributes(photo);
            this.notices.success('File was successfully uploaded.');
            return photo;
        });
    };

    protected processDeletePhoto = ():Promise<any> => {
        return this.photo.id
            ? this.photoDataProvider.deleteById(this.photo.id)
            : Promise.reject(new Error('Photo ID is not provided.'));
    };

    deletePhoto = ():Promise<any> => {
        return this.lockProcess.process(this.processDeletePhoto).then((result:any) => {
            this.notices.success('Photo was successfully deleted.');
            this.navigator.navigate(['/photos']);
            return result;
        });
    };

    isProcessing = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}
