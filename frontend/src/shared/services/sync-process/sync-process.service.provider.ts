import {Injectable} from '@angular/core';
import {LockerServiceProvider} from '../locker';
import {SyncProcessService} from './sync-process.service';

@Injectable()
export class SyncProcessServiceProvider {
    getInstance = ():SyncProcessService => {
        return new SyncProcessService(new LockerServiceProvider);
    };
}
