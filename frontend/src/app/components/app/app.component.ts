import {Component, Inject, ViewChild} from '@angular/core';
import {
    EnvService,
    TitleService,
    AuthProviderService,
    ScrollFreezerService
} from '../../../shared/services';

@Component({
    selector: 'app',
    templateUrl: 'app.component.html',
    styleUrls: ['app.component.css'],
})
export class AppComponent {
    @ViewChild('sideBarComponent') sideBarComponent:any;
    private appContentStyles:{overflow:string} = {overflow: ''};

    constructor(@Inject(EnvService) private env:EnvService,
                @Inject(TitleService) private title:TitleService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService,
                @Inject(ScrollFreezerService) private scrollFreezer:ScrollFreezerService) {
    }

    ngOnInit() {
        this.title.setTitle();
    }

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
