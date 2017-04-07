import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {
    TitleService,
    MetaTagsService,
    NavigatorServiceProvider,
    NavigatorService
} from '../../../shared/services';

@Component({
    selector: 'page-not-found',
    templateUrl: 'page-not-found.component.html',
})
export class PageNotFoundComponent implements OnInit {
    private navigator:NavigatorService;

    constructor(private title:TitleService,
                private metaTags:MetaTagsService,
                private router:Router,
                navigatorProvider:NavigatorServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
    }

    ngOnInit():void {
        this.initTitle();
        this.initMeta();
        this.navigateTo404Page();
    }

    private initTitle = ():void => {
        this.title.setTitle('Page not found');
    };

    private initMeta = ():void => {
        this.metaTags.setTitle(this.title.getPageName());
    };

    private navigateTo404Page = () => {
        if (this.router.url !== '/404') {
            this.navigator.navigate(['/404']);
        }
    };
}
