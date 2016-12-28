import {Injectable} from '@angular/core';
import {LockerService} from './locker.service';

@Injectable()
export class LockerServiceProvider {
    getInstance = ():LockerService => new LockerService;
}
