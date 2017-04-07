import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {NavigatorServiceProvider, NavigatorService} from '../../../shared/services';

@Component({
    selector: 'page-not-found',
    templateUrl: 'page-not-found.component.html',
})
export class PageNotFoundComponent implements OnInit {
    private navigator:NavigatorService;

    constructor(private router:Router, navigatorProvider:NavigatorServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
    }

    ngOnInit():void {
        this.navigateTo404Page();
    }

    private navigateTo404Page = () => {
        if (this.router.url !== '/404') {
            this.navigator.navigate(['/404']);
        }
    };
}
