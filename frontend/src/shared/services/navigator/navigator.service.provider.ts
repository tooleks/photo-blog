import {Injectable} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {NavigatorService} from './navigator.service';

@Injectable()
export class NavigatorServiceProvider {
    constructor(protected route:ActivatedRoute,
                protected router:Router) {
    }

    getInstance = ():NavigatorService => {
        return new NavigatorService(this.route, this.router);
    };
}
