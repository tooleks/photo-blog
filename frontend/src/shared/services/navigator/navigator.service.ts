import {Injectable} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';

@Injectable()
export class NavigatorService {
    protected queryParams:any = {};

    constructor(protected route:ActivatedRoute,
                protected router:Router) {
        this.route.queryParams.subscribe((queryParams) => {
            Object.keys(queryParams).forEach((key) => {
                this.queryParams[key] = queryParams[key];
            });
        });
    }

    setQueryParam = (name:string, value:any):Promise<boolean> => {
        this.queryParams[name] = value;
        return this.navigate([], {queryParams: this.queryParams});
    };

    unsetQueryParam = (name:string):Promise<boolean> => {
        delete this.queryParams[name];
        return this.navigate([], {queryParams: this.queryParams});
    };

    navigate = (route:any = [], extras:any = {}):Promise<boolean> => {
        return this.router.navigate(route, extras);
    };
}
