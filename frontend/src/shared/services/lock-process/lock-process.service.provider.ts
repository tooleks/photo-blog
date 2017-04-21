import {Injectable} from '@angular/core';
import {LockerServiceProvider} from '../locker';
import {LockProcessService} from './lock-process.service';

@Injectable()
export class LockProcessServiceProvider {
    constructor(protected lockerProvider:LockerServiceProvider) {
    }

    getInstance = ():LockProcessService => {
        return new LockProcessService(this.lockerProvider.getInstance());
    };
}
