import {Injectable} from '@angular/core';
import {LockerServiceProvider} from '../locker';
import {ProcessLockerService} from './process-locker.service';

@Injectable()
export class ProcessLockerServiceProvider {
    constructor(protected lockerProvider:LockerServiceProvider) {
    }

    getInstance():ProcessLockerService {
        return new ProcessLockerService(this.lockerProvider.getInstance());
    }
}
