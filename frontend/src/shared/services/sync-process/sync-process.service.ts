import {Injectable, Inject} from '@angular/core';
import {LockerService, LockerServiceProvider} from '../locker';

@Injectable()
export class SyncProcessService {
    protected lockerService:LockerService;

    constructor(@Inject(LockerServiceProvider) protected lockerServiceProvider:LockerServiceProvider) {
        this.lockerService = this.lockerServiceProvider.getInstance();
    }

    startProcess() {
        return new Promise((resolve, reject) => {
            !this.lockerService.isLocked() ? resolve(this.lockerService.lock()) : reject(new Error('Process is locked.'));
        }).catch((error:any) => {
            this.lockerService.unlock();
            throw error;
        });
    }

    endProcess() {
        this.lockerService.unlock();
    }

    isProcessing() {
        return this.lockerService.isLocked();
    }
}
