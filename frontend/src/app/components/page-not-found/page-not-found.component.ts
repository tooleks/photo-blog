import {Component, Inject} from '@angular/core';
import {Router} from '@angular/router';
import {NavigatorServiceProvider, NavigatorService} from '../../../shared/services';

@Component({
    selector: 'page-not-found',
    template: require('./page-not-found.component.html'),
})
export class PageNotFoundComponent {
    private navigator:NavigatorService;

    constructor(@Inject(Router) private router:Router,
                @Inject(NavigatorServiceProvider) navigatorProvider:NavigatorServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
    }

    ngOnInit() {
        this.navigateTo404Page();
    }

    private navigateTo404Page = () => {
        if (this.router.url !== '/404') {
            this.navigator.navigate(['/404']);
        }
    };
}
