import {Injectable} from '@angular/core';
import {LockerServiceProvider} from '../locker';
import {SyncProcessService} from './sync-process.service';

@Injectable()
export class SyncProcessServiceProvider {
    getInstance() {
        return new SyncProcessService(new LockerServiceProvider);
    }
}
