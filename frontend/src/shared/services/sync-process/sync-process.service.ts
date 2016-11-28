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
            if (!this.lockerService.isLocked()) {
                resolve(this.lockerService.lock());
            } else {
                reject({message: 'Locked process error.'});
            }
        });
    }

    endProcess() {
        this.lockerService.unlock();
    }

    isProcessing() {
        return this.lockerService.isLocked();
    }
}
