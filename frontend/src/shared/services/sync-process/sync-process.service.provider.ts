import {Injectable, Inject} from '@angular/core';
import {LockerServiceProvider} from '../locker';
import {SyncProcessService} from './sync-process.service';

@Injectable()
export class SyncProcessServiceProvider {
    constructor(@Inject(LockerServiceProvider) private lockerProvider:LockerServiceProvider) {
    }

    getInstance = ():SyncProcessService => new SyncProcessService(this.lockerProvider);
}
