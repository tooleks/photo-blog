import {Component, OnInit, ViewChild} from '@angular/core'
import {Router, NavigationEnd} from '@angular/router';
import {
    AppService,
    MetaTagsService,
    TitleService,
    AuthProviderService,
    ScrollFreezerService
} from '../../../lib';
import '../../../../assets/static/img/meta_image.jpg'

@Component({
    selector: 'app',
    templateUrl: 'app.component.html',
    styleUrls: ['app.component.css']
})
export class AppComponent implements OnInit {
    @ViewChild('sideBarComponent') sideBarComponent:any;
    protected appContentStyles:{overflow:string} = {overflow: ''};

    constructor(protected router:Router,
                protected app:AppService,
                protected title:TitleService,
                protected metaTags:MetaTagsService,
                protected authProvider:AuthProviderService,
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
        this.metaTags.setUrl(this.app.getUrl());
        this.metaTags.setWebsiteName(this.app.getName());
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

    protected toggleSideBar = ():void => {
        this.sideBarComponent.toggle();
    };

    protected onShowSideBar = (event:any):void => {
        if (event.isSmallDevice) {
            this.scrollFreezer.freeze();
        }
    };

    protected onHideSideBar = (event:any):void => {
        this.scrollFreezer.unfreeze();
    };

    protected onToggleSideBar = (event:any):void => {
        event.isVisible ? this.onShowSideBar(event) : this.onHideSideBar(event);
    };
}
