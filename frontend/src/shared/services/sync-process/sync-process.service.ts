import {Injectable, Inject} from '@angular/core';
import {LockerService, LockerServiceProvider} from '../locker';

@Injectable()
export class SyncProcessService {
    private locker:LockerService;

    constructor(@Inject(LockerServiceProvider) lockerProvider:LockerServiceProvider) {
        this.locker = lockerProvider.getInstance();
    }

    process = (callback:any) => this.startProcess(callback).then(this.endProcess).catch(this.handleProcessErrors);

    isProcessing = ():boolean => this.locker.isLocked();

    private startProcess = (callback:any):Promise<any> => new Promise((resolve, reject) => {
        if (!this.locker.isLocked()) {
            this.locker.lock();
            resolve(callback());
        } else {
            reject(new Error(SyncProcessService.name));
        }
    });

    private endProcess = (result:any) => {
        this.locker.unlock();
        return result;
    };

    private handleProcessErrors = (error:any) => {
        if (error instanceof Error && error.message !== SyncProcessService.name) {
            this.locker.unlock();
        }

        return Promise.reject(error);
    };
}
