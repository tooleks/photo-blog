import {Component, Inject, ViewChild} from '@angular/core';
import {
    EnvService,
    TitleService,
    AuthProviderService,
    ApiService,
    ScrollFreezerService
} from '../../../shared/services';

import '../../../../assets/app/css/overrides.css';

@Component({
    selector: 'app',
    template: require('./app.component.html'),
    styles: [require('./app.component.css').toString()],
})
export class AppComponent {
    @ViewChild('sideBarComponent') sideBarComponent:any;
    private sideBarComponentTags:Array<any> = [];
    private appContentStyles:{overflow:string} = {overflow: ''};

    constructor(@Inject(EnvService) private env:EnvService,
                @Inject(TitleService) private title:TitleService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService,
                @Inject(ApiService) private apiService:ApiService,
                @Inject(ScrollFreezerService) private scrollFreezer:ScrollFreezerService) {
    }

    ngOnInit() {
        this.title.setTitle();
        this.loadSideBarComponentTags();
    }

    private loadSideBarComponentTags = () => {
        this.apiService.get('/tags', {params: {take: 7, skip: 0}}).toPromise().then((tags:Array<any>) => {
            this.sideBarComponentTags = tags;
        });
    };

    private onSwipeLeft = (event:any) => {
        // Prevent firing event on 'gallery' component swipeleft event, as it has an own handler.
        if (event.target.className.search('gl-') !== -1) {
            return;
        }

        this.sideBarComponent.hide();
    };

    private onSwipeRight = (event:any) => {
        // Prevent firing event on 'gallery' component swipeleft event, as it has an own handler.
        if (event.target.className.search('gl-') !== -1) {
            return;
        }

        this.sideBarComponent.show();
    };

    private onShowSideBar = (event:any) => {
        if (event.isSmallDevice) {
            this.scrollFreezer.freezeBackgroundScroll();
            this.appContentStyles.overflow = 'hidden';
        }
    };

    private onHideSideBar = (event:any) => {
        this.scrollFreezer.unfreezeBackgroundScroll();
        this.appContentStyles.overflow = '';
    };

    private onToggleSideBar = (event:any) => {
        event.isVisible ? this.onShowSideBar(event) : this.onHideSideBar(event);
    };

    private getCurrentYear = () => {
        return (new Date).getFullYear();
    };
}
