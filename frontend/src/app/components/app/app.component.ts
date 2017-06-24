import {Component, OnInit, OnDestroy} from '@angular/core'
import {Router, NavigationEnd} from '@angular/router';
import 'rxjs/add/operator/filter';
import {MetaTagsService, GoogleAnalyticsService} from '../../../core';
import {TransferState} from '../../../sys';
import {ScreenDetectorService} from '../../../core';
import {AppService, TitleService, AuthProviderService, ScrollFreezerService} from '../../../shared';
import './meta';
import './favicon';

@Component({
    selector: 'app',
    templateUrl: 'app.component.html',
    styles: [require('./app.component.css').toString()],
})
export class AppComponent implements OnInit, OnDestroy {
    appContentStyles: { overflow: string } = {overflow: ''};

    protected metaTagsUrlSubscriber: any = null;
    protected scrollFreezerFreezedSubscriber: any = null;
    protected scrollFreezerUnfreezedSubscriber: any = null;

    constructor(protected cache: TransferState,
                protected router: Router,
                protected app: AppService,
                protected title: TitleService,
                protected metaTags: MetaTagsService,
                protected authProvider: AuthProviderService,
                protected screenDetector: ScreenDetectorService,
                protected scrollFreezer: ScrollFreezerService,
                protected googleAnalytics: GoogleAnalyticsService) {
    }

    ngOnInit(): void {
        this.initMeta();
        this.initRouterSubscribers();
        this.initScrollFreezerSubscribers();
        this.googleAnalytics.init();
        this.cache.set('state-transfer', true); // Just for testing purposes.
    }

    ngOnDestroy(): void {
        if (this.metaTagsUrlSubscriber !== null) {
            this.metaTagsUrlSubscriber.unsubscribe();
            this.metaTagsUrlSubscriber = null;
        }

        if (this.scrollFreezerFreezedSubscriber !== null) {
            this.scrollFreezerFreezedSubscriber.unsubscribe();
            this.scrollFreezerFreezedSubscriber = null;
        }

        if (this.scrollFreezerUnfreezedSubscriber !== null) {
            this.scrollFreezerUnfreezedSubscriber.unsubscribe();
            this.scrollFreezerUnfreezedSubscriber = null;
        }
    }

    protected initMeta(): void {
        this.metaTags
            .setUrl(this.app.getUrl())
            .setWebsiteName(this.app.getName())
            .setTitle(this.title.getPageNameSegment())
            .setDescription(this.app.getDescription())
            .setImage(this.app.getImage());
    }

    protected initRouterSubscribers(): void {
        this.metaTagsUrlSubscriber = this.router.events
            .filter((event) => event instanceof NavigationEnd)
            .subscribe((event: NavigationEnd) => this.metaTags.setUrl(this.app.getUrl() + event.urlAfterRedirects));
    }

    protected initScrollFreezerSubscribers(): void {
        this.scrollFreezerFreezedSubscriber = this.scrollFreezer.freezed.subscribe(() => this.appContentStyles.overflow = 'hidden');
        this.scrollFreezerUnfreezedSubscriber = this.scrollFreezer.unfreezed.subscribe(() => this.appContentStyles.overflow = '');
    }

    getLinkedData() {
        return {
            '@context': 'http://schema.org',
            '@type': 'WebSite',
            'name': this.app.getName(),
            'url': this.app.getUrl(),
        };
    }

    onShowSideBar(event): void {
        if (this.screenDetector.isSmallScreen()) {
            this.scrollFreezer.freeze()
        }
    }

    onHideSideBar(event): void {
        this.scrollFreezer.unfreeze();
    }

    onToggleSideBar(event): void {
        event.isVisible ? this.onShowSideBar(event) : this.onHideSideBar(event);
    }
}
