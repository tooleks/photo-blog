import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router';
import {MetaTagsService} from '../../../core'
import {TitleService, NavigatorServiceProvider, NavigatorService} from '../../../shared';

@Component({
    selector: 'page-not-found',
    templateUrl: 'page-not-found.component.html',
})
export class PageNotFoundComponent implements OnInit {
    protected navigator: NavigatorService;

    constructor(protected title: TitleService,
                protected metaTags: MetaTagsService,
                protected router: Router,
                navigatorProvider: NavigatorServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
    }

    ngOnInit(): void {
        this.title.setPageNameSegment('Page Not Found');
        this.metaTags.setTitle(this.title.getPageNameSegment());
        this.navigateTo404Page();
    }

    navigateTo404Page(): void {
        if (this.router.url !== '/404') {
            this.navigator.navigate(['/404']);
        }
    }
}
