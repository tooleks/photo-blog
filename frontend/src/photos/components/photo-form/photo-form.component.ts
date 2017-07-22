import {Component, OnInit, OnDestroy, AfterViewInit} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {EnvironmentDetectorService} from '../../../core';
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
    styles: [require('./photo-form.component.css').toString()],
})
export class PhotoFormComponent implements OnInit, OnDestroy, AfterViewInit {
    photo: Photo;
    protected navigator: NavigatorService;
    protected processLocker: ProcessLockerService;

    protected idQueryParamSubscriber: any = null;

    constructor(protected route: ActivatedRoute,
                protected title: TitleService,
                protected authProvider: AuthProviderService,
                protected photoDataProvider: PhotoDataProviderService,
                protected notices: NoticesService,
                protected environmentDetector: EnvironmentDetectorService,
                navigatorServiceProvider: NavigatorServiceProvider,
                processLockerServiceProvider: ProcessLockerServiceProvider) {
        this.navigator = navigatorServiceProvider.getInstance();
        this.processLocker = processLockerServiceProvider.getInstance();
    }

    ngOnInit(): void {
        this.photo = new Photo;
        this.title.setPageNameSegment('Add Photo');
        if (!this.authProvider.isAuthenticated()) {
            this.navigator.navigateToSignIn();
        }
    }

    ngOnDestroy(): void {
        if (this.idQueryParamSubscriber !== null) {
            this.idQueryParamSubscriber.unsubscribe();
            this.idQueryParamSubscriber = null;
        }
    }

    ngAfterViewInit(): void {
        this.initParamsSubscribers();
    }

    protected initParamsSubscribers(): void {
        this.idQueryParamSubscriber = this.route.params
            .map((params) => params['id'])
            .filter((id) => typeof (id) !== 'undefined')
            .subscribe((id) => this.loadById(id));
    }

    protected processLoadById(id: number): Promise<any> {
        return id
            ? this.photoDataProvider.getById(id)
            : Promise.reject(new Error('Invalid value of a id parameter.'));
    }

    loadById(id: number): Promise<any> {
        return this.processLocker
            .lock(() => this.processLoadById(id))
            .then((photo) => this.onLoadByIdSuccess(photo));
    }

    protected onLoadByIdSuccess(photo) {
        this.photo.setSavedPhotoAttributes(photo);
        this.title.setPageNameSegment('Edit Photo');
        return photo;
    }

    protected processSavePhoto(): Promise<any> {
        return this.photo.id
            ? this.photoDataProvider.updateById(this.photo.id, this.photo)
            : this.photoDataProvider.create(this.photo);
    }

    save(): Promise<any> {
        return this.processLocker
            .lock(() => this.processSavePhoto())
            .then((photo) => this.onSaveSuccess(photo));
    }

    protected onSaveSuccess(photo) {
        this.photo.setSavedPhotoAttributes(photo);
        this.notices.success('Photo was successfully saved.');
        this.navigator.navigateToPhotos();
        return photo;
    }

    protected processUploadPhoto(file: FileList): Promise<any> {
        return this.photo.id
            ? this.photoDataProvider.uploadById(this.photo.id, file)
            : this.photoDataProvider.upload(file);
    }

    upload(file): Promise<any> {
        return this.processLocker
            .lock(() => this.processUploadPhoto(file))
            .then((photo) => this.onUploadSuccess(photo));
    }

    protected onUploadSuccess(photo) {
        this.photo.setUploadedPhotoAttributes(photo);
        this.notices.success('File was successfully uploaded.');
        return photo;
    }

    protected processDeletePhoto(): Promise<any> {
        return this.photo.id
            ? this.photoDataProvider.deleteById(this.photo.id)
            : Promise.reject(new Error('Invalid value of a id parameter.'));
    }

    deletePhoto(): Promise<any> {
        if (this.environmentDetector.isBrowser() && confirm('Confirm photo deleting?')) {
            return this.processLocker
                .lock(() => this.processDeletePhoto())
                .then((response) => this.onDeletePhotoSuccess(response))
        } else {
            return Promise.reject(new Error('Photo deleting was canceled.'));
        }
    }

    protected onDeletePhotoSuccess(response) {
        this.notices.success('Photo was successfully deleted.');
        this.navigator.navigateToPhotos();
        return response;
    }

    isProcessing(): boolean {
        return this.processLocker.isLocked();
    }
}
