import {Inject} from '@angular/core';
import {LockerService, LockerServiceProvider} from '../../shared/services/locker';
import {NavigatorService, NavigatorServiceProvider} from '../../shared/services/navigator';
import {PhotoModel} from '../models/photo-model';

export abstract class PhotosGrid {
    protected empty:boolean;
    protected lockerService:LockerService;
    protected navigatorService:NavigatorService;

    constructor(@Inject(LockerServiceProvider) protected lockerServiceProvider:LockerServiceProvider,
                @Inject(NavigatorServiceProvider) protected navigatorServiceProvider:NavigatorServiceProvider) {
        this.lockerService = this.lockerServiceProvider.getInstance();
        this.navigatorService = this.navigatorServiceProvider.getInstance();
    }

    isEmpty() {
        return !this.lockerService.isLocked() && this.empty === true;
    }

    isLoading() {
        return this.lockerService.isLocked();
    }

    showPhotoCallback(photo:PhotoModel) {
        this.navigatorService.setQueryParam('show', photo.id);
    }

    hidePhotoCallback() {
        this.navigatorService.unsetQueryParam('show');
    }

    navigateToEditPhoto(photo:PhotoModel) {
        this.navigatorService.navigate(['photo/edit', photo.id]);
    }
}
