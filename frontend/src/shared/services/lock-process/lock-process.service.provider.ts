import {Injectable, Inject} from '@angular/core';
import {LockerServiceProvider} from '../locker';
import {LockProcessService} from './lock-process.service';

@Injectable()
export class LockProcessServiceProvider {
    constructor(@Inject(LockerServiceProvider) private lockerProvider:LockerServiceProvider) {
    }

    getInstance = ():LockProcessService => {
        return new LockProcessService(this.lockerProvider);
    };
}
