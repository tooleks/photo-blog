import {Injectable, Inject} from '@angular/core';
import {LockerService, LockerServiceProvider} from '../locker';

@Injectable()
export class SyncProcessService {
    protected lockerService:LockerService;

    constructor(@Inject(LockerServiceProvider) protected lockerServiceProvider:LockerServiceProvider) {
        this.lockerService = this.lockerServiceProvider.getInstance();
    }

    process = (callback:any) => this.startProcess(callback).then(this.endProcess).catch(this.handleProcessErrors);

    isProcessing = ():boolean => this.lockerService.isLocked();

    protected startProcess = (callback:any) => new Promise((resolve, reject) => {
        if (!this.lockerService.isLocked()) {
            this.lockerService.lock();
            resolve(callback());
        } else {
            reject(new Error('process.locked'));
        }
    });

    protected endProcess = (result:any) => {
        this.lockerService.unlock();
        return result;
    };

    protected handleProcessErrors = (error:any) => {
        if (error.message !== 'process.locked') {
            this.lockerService.unlock();
        }
        throw error;
    };
}
