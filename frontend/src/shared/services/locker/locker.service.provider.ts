import {Injectable} from '@angular/core';
import {LockerService} from './locker.service';

@Injectable()
export class LockerServiceProvider {
    getInstance():LockerService {
        return new LockerService;
    }
}
