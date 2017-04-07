import {Injectable} from '@angular/core';
import {LockerService, LockerServiceProvider} from '../locker';

@Injectable()
export class LockProcessService {
    private locker:LockerService;

    constructor(lockerProvider:LockerServiceProvider) {
        this.locker = lockerProvider.getInstance();
    }

    process = (callback:any, args?:any):Promise<any> => {
        return this.startProcess(callback, args).then(this.endProcess).catch(this.handleProcessErrors);
    };

    isProcessing = ():boolean => {
        return this.locker.isLocked();
    };

    private startProcess = (callback:any, args?:any):Promise<any> => {
        return new Promise((resolve, reject) => {
            if (!this.locker.isLocked()) {
                this.locker.lock();
                resolve(callback(...args));
            } else {
                reject(new Error(LockProcessService.name));
            }
        });
    };

    private endProcess = (result:any):any => {
        this.locker.unlock();
        return result;
    };

    private handleProcessErrors = (error:any) => {
        if (error instanceof Error && error.message !== LockProcessService.name) {
            this.locker.unlock();
        }
        return Promise.reject(error);
    };
}
