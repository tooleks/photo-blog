import {Injectable} from '@angular/core';

@Injectable()
export class LockerService {
    protected locked:boolean = false;

    lock = () => this.locked = true;

    unlock = () => this.locked = false;

    isLocked = () => this.locked === true;
}
