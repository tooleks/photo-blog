import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {NoticesService} from '../../../lib';
import {
    TitleService,
    AuthProviderService,
    NavigatorServiceProvider,
    NavigatorService,
    ProcessLockerServiceProvider,
    ProcessLockerService,
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
    protected processLocker:ProcessLockerService;

    constructor(protected route:ActivatedRoute,
                protected title:TitleService,
                protected authProvider:AuthProviderService,
                protected photoDataProvider:PhotoDataProviderService,
                protected notices:NoticesService,
                navigatorServiceProvider:NavigatorServiceProvider,
                processLockerServiceProvider:ProcessLockerServiceProvider) {
        this.navigator = navigatorServiceProvider.getInstance();
        this.processLocker = processLockerServiceProvider.getInstance();
    }

    ngOnInit():void {
        this.photo = new Photo;
        this.title.setPageNameSegment('Add Photo');
        this.initParamsSubscribers();
    }

    protected initParamsSubscribers():void {
        this.route.params
            .map((params) => params['id'])
            .filter((id) => id)
            .subscribe((id) => this.loadById(id));
    }

    protected processLoadById(id:number):Promise<any> {
        return id
            ? this.photoDataProvider.getById(id)
            : Promise.reject(new Error('Photo ID is not provided.'));
    }

    loadById(id:number):Promise<any> {
        return this.processLocker.lock(() => this.processLoadById(id)).then((photo) => {
            this.photo.setSavedPhotoAttributes(photo);
            this.title.setPageNameSegment('Edit Photo');
            return photo;
        });
    }

    protected processSavePhoto():Promise<any> {
        return this.photo.id
            ? this.photoDataProvider.updateById(this.photo.id, this.photo)
            : this.photoDataProvider.create(this.photo);
    }

    save():Promise<any> {
        return this.processLocker.lock(() => this.processSavePhoto()).then((photo) => {
            this.photo.setSavedPhotoAttributes(photo);
            this.notices.success('Photo was successfully saved.');
            this.navigator.navigate(['/photos']);
            return photo;
        });
    }

    protected processUploadPhoto(file:FileList):Promise<any> {
        return this.photo.id
            ? this.photoDataProvider.uploadById(this.photo.id, file)
            : this.photoDataProvider.upload(file);
    }

    upload(file):Promise<any> {
        return this.processLocker.lock(() => this.processUploadPhoto(file)).then((photo) => {
            this.photo.setUploadedPhotoAttributes(photo);
            this.notices.success('File was successfully uploaded.');
            return photo;
        });
    }

    protected processDeletePhoto():Promise<any> {
        return this.photo.id
            ? this.photoDataProvider.deleteById(this.photo.id)
            : Promise.reject(new Error('Photo ID is not provided.'));
    }

    deletePhoto():Promise<any> {
        return this.processLocker.lock(() => this.processDeletePhoto()).then((result) => {
            this.notices.success('Photo was successfully deleted.');
            this.navigator.navigate(['/photos']);
            return result;
        });
    }

    isProcessing():boolean {
        return this.processLocker.isLocked();
    }
}
