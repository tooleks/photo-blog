import {Injectable, Inject} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';

@Injectable()
export class NavigatorService {
    private queryParams:any = {};

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(Router) private router:Router) {
        this.route.queryParams.subscribe((queryParams) => {
            Object.keys(queryParams).forEach((key) => {
                this.queryParams[key] = queryParams[key];
            });
        });
    }

    setQueryParam(name:string, value:any) {
        this.queryParams[name] = value;
        this.navigate();
    }

    unsetQueryParam(name:string) {
        delete this.queryParams[name];
        this.navigate();
    }

    navigate(route:any = []) {
        this.router.navigate(route, {
            queryParams: this.queryParams,
        });
    }
}
