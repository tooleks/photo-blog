import {Component, OnInit} from '@angular/core'
import {Router, NavigationEnd} from '@angular/router';
import {MetaTagsService, GoogleAnalyticsService} from '../../../core';
import {TransferState} from '../../../sys';
import {ScreenDetectorService} from '../../../core';
import {AppService, TitleService, AuthProviderService, ScrollFreezerService} from '../../../shared';
import '../../../../assets/static/img/meta_image.jpg'

@Component({
    selector: 'app',
    templateUrl: 'app.component.html',
    styleUrls: ['app.component.css'],
})
export class AppComponent implements OnInit {
    appContentStyles:{overflow:string} = {overflow: ''};

    constructor(protected cache:TransferState,
                protected router:Router,
                protected app:AppService,
                protected title:TitleService,
                protected metaTags:MetaTagsService,
                protected authProvider:AuthProviderService,
                protected screenDetector:ScreenDetectorService,
                protected scrollFreezer:ScrollFreezerService,
                protected googleAnalytics:GoogleAnalyticsService) {
    }

    ngOnInit():void {
        this.initMeta();
        this.initRouterSubscribers();
        this.initScrollFreezerSubscribers();
        this.googleAnalytics.init();
        this.cache.set('state-transfer', true); // Just for testing purposes.
    }

    protected initMeta():void {
        this.metaTags
            .setUrl(this.app.getUrl())
            .setWebsiteName(this.app.getName())
            .setTitle(this.title.getPageNameSegment())
            .setDescription(this.app.getDescription())
            .setImage(this.app.getImage());
    }

    protected initRouterSubscribers():void {
        this.router.events
            .filter((event) => event instanceof NavigationEnd)
            .subscribe((event:NavigationEnd) => this.metaTags.setUrl(this.app.getUrl() + event.urlAfterRedirects));
    }

    protected initScrollFreezerSubscribers():void {
        this.scrollFreezer.freezed.subscribe(() => this.appContentStyles.overflow = 'hidden');
        this.scrollFreezer.unfreezed.subscribe(() => this.appContentStyles.overflow = '');
    }

    getLinkedData() {
        return {
            '@context': 'http://schema.org',
            '@type': 'WebSite',
            'name': this.app.getName(),
            'url': this.app.getUrl(),
        };
    }

    onShowSideBar(event):void {
        if (this.screenDetector.isSmallScreen()) {
            this.scrollFreezer.freeze()
        }
    }

    onHideSideBar(event):void {
        this.scrollFreezer.unfreeze();
    }

    onToggleSideBar(event):void {
        event.isVisible ? this.onShowSideBar(event) : this.onHideSideBar(event);
    }
}
