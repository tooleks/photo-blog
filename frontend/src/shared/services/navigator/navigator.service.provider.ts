import {Injectable, Inject} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';
import {NavigatorService} from './navigator.service';

@Injectable()
export class NavigatorServiceProvider {
    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(Router) private router:Router) {
    }

    getInstance = ():NavigatorService => {
        return new NavigatorService(this.route, this.router);
    };
}
