import {Injectable} from '@angular/core';
import {ActivatedRoute, Router} from '@angular/router';

@Injectable()
export class NavigatorService {
    protected queryParams = {};

    constructor(protected route: ActivatedRoute, protected router: Router) {
        this.route.queryParams.subscribe((queryParams) => {
            Object.keys(queryParams).forEach((key) => {
                this.queryParams[key] = queryParams[key];
            });
        });
    }

    setQueryParam(name: string, value): Promise<boolean> {
        this.queryParams[name] = value;
        return this.navigate([], {queryParams: this.queryParams});
    }

    unsetQueryParam(name: string): Promise<boolean> {
        delete this.queryParams[name];
        return this.navigate([], {queryParams: this.queryParams});
    }

    navigate(route = [], extras = {}): Promise<boolean> {
        return this.router.navigate(route, extras);
    }

    navigateToHome(extras = {}): Promise<boolean> {
        return this.navigate(['/'], extras);
    }

    navigateToSignIn(extras = {}): Promise<boolean> {
        return this.navigate(['/signin'], extras);
    }

    navigateToPhotos(extras = {}): Promise<boolean> {
        return this.navigate(['/photos'], extras);
    }
}
