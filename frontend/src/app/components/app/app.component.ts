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
    private appContentStyles:{overflow:string} = {overflow: ''};

    constructor(private app:AppService,
                private title:TitleService,
                private metaTags:MetaTagsService,
                private authProvider:AuthProviderService,
                private scrollFreezer:ScrollFreezerService) {
    }

    ngOnInit():void {
        this.initTitle();
        this.initMeta();
    }

    private initTitle = ():void => {
        this.title.setTitle();
    };

    private initMeta = ():void => {
        this.metaTags.setUrl(this.app.getUrl());
        this.metaTags.setTitle(this.title.getPageName());
        this.metaTags.setDescription(this.title.getTitle());
        this.metaTags.setImage(this.app.getUrl() + '/assets/static/meta_image.png');
    };

    private toggleSideBar = ():void => {
        this.sideBarComponent.toggle();
    };

    private onShowSideBar = (event:any):void => {
        if (event.isSmallDevice) {
            this.scrollFreezer.freezeBackgroundScroll();
            this.appContentStyles.overflow = 'hidden';
        }
    };

    private onHideSideBar = (event:any):void => {
        this.scrollFreezer.unfreezeBackgroundScroll();
        this.appContentStyles.overflow = '';
    };

    private onToggleSideBar = (event:any):void => {
        event.isVisible ? this.onShowSideBar(event) : this.onHideSideBar(event);
    };

    private getCurrentYear = ():number => {
        return (new Date).getFullYear();
    };

    private getAppName = ():string => {
        return this.app.getName();
    };
}
