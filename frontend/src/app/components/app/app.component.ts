import {Component, OnInit} from '@angular/core'
import {Router, NavigationEnd} from '@angular/router';
import {MetaTagsService} from '../../../core';
import {
    AppService,
    TitleService,
    AuthProviderService,
    ScrollFreezerService,
    ScreenDetectorService,
} from '../../../shared';
import '../../../../assets/static/img/meta_image.jpg'

@Component({
    selector: 'app',
    templateUrl: 'app.component.html',
    styleUrls: ['app.component.css']
})
export class AppComponent implements OnInit {
    protected appContentStyles:{overflow:string} = {overflow: ''};

    constructor(protected router:Router,
                protected app:AppService,
                protected title:TitleService,
                protected metaTags:MetaTagsService,
                protected authProvider:AuthProviderService,
                protected screenDetector:ScreenDetectorService,
                protected scrollFreezer:ScrollFreezerService) {
    }

    ngOnInit():void {
        this.initTitle();
        this.initMeta();
        this.initRouterSubscribers();
        this.initScrollFreezerSubscribers();
    }

    protected initTitle = ():void => {
        this.title.setTitle();
    };

    protected initMeta = ():void => {
        this.metaTags.setWebsiteName(this.app.getName());
        this.metaTags.setUrl(this.app.getUrl());
        this.metaTags.setTitle(this.title.getPageName());
        this.metaTags.setDescription(this.app.getDescription());
        this.metaTags.setImage(this.app.getUrl() + '/assets/static/meta_image.jpg');
    };

    protected initRouterSubscribers = ():void => {
        this.router.events
            .filter((event:any) => event instanceof NavigationEnd)
            .subscribe((event:NavigationEnd) => this.metaTags.setUrl(this.app.getUrl() + event.url));
    };

    protected initScrollFreezerSubscribers = ():void => {
        this.scrollFreezer.freezed.subscribe(() => this.appContentStyles.overflow = 'hidden');
        this.scrollFreezer.unfreezed.subscribe(() => this.appContentStyles.overflow = '');
    };

    protected onShowSideBar = (event:any):void => {
        this.screenDetector.isSmallScreen() && this.scrollFreezer.freeze()
    };

    protected onHideSideBar = (event:any):void => {
        this.scrollFreezer.unfreeze();
    };

    protected onToggleSideBar = (event:any):void => {
        event.isVisible ? this.onShowSideBar(event) : this.onHideSideBar(event);
    };
}
