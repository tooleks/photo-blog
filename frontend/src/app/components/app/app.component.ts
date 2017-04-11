import {Component, OnInit, ViewChild} from '@angular/core'
import {
    AppService,
    MetaTagsService,
    TitleService,
    AuthProviderService,
    ScrollFreezerService
} from '../../../shared/services';
import '../../../../assets/static/img/meta_image.png'

@Component({
    selector: 'app',
    templateUrl: 'app.component.html',
    styleUrls: ['app.component.css']
})
export class AppComponent implements OnInit {
    @ViewChild('sideBarComponent') sideBarComponent:any;
    protected appContentStyles:{overflow:string} = {overflow: ''};

    constructor(protected app:AppService,
                protected title:TitleService,
                protected metaTags:MetaTagsService,
                protected authProvider:AuthProviderService,
                protected scrollFreezer:ScrollFreezerService) {
    }

    ngOnInit():void {
        this.initTitle();
        this.initMeta();
    }

    protected initTitle = ():void => {
        this.title.setTitle();
    };

    protected initMeta = ():void => {
        this.metaTags.setUrl(this.app.getUrl());
        this.metaTags.setWebsiteName(this.app.getName());
        this.metaTags.setTitle(this.title.getPageName());
        this.metaTags.setDescription(this.app.getDescription());
        this.metaTags.setImage(this.app.getUrl() + '/assets/static/meta_image.png');
    };

    protected toggleSideBar = ():void => {
        this.sideBarComponent.toggle();
    };

    protected onShowSideBar = (event:any):void => {
        if (event.isSmallDevice) {
            this.scrollFreezer.freezeBackgroundScroll();
            this.appContentStyles.overflow = 'hidden';
        }
    };

    protected onHideSideBar = (event:any):void => {
        this.scrollFreezer.unfreezeBackgroundScroll();
        this.appContentStyles.overflow = '';
    };

    protected onToggleSideBar = (event:any):void => {
        event.isVisible ? this.onShowSideBar(event) : this.onHideSideBar(event);
    };
}
