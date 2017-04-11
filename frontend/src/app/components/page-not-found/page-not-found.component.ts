import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {
    TitleService,
    MetaTagsService,
    NavigatorServiceProvider,
    NavigatorService
} from '../../../lib';

@Component({
    selector: 'page-not-found',
    templateUrl: 'page-not-found.component.html',
})
export class PageNotFoundComponent implements OnInit {
    protected navigator:NavigatorService;

    constructor(protected title:TitleService,
                protected metaTags:MetaTagsService,
                protected router:Router,
                navigatorProvider:NavigatorServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
    }

    ngOnInit():void {
        this.initTitle();
        this.initMeta();
        this.navigateTo404Page();
    }

    protected initTitle = ():void => {
        this.title.setTitle('Page Not Found');
    };

    protected initMeta = ():void => {
        this.metaTags.setTitle(this.title.getPageName());
    };

    protected navigateTo404Page = () => {
        if (this.router.url !== '/404') {
            this.navigator.navigate(['/404']);
        }
    };
}
