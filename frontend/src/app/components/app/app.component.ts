import {Component, Inject, ViewChild} from '@angular/core';
import {EnvService, TitleService, AuthProviderService, ScrollFreezerService} from '../../../shared/services';

import '../../../../public/app/css/styles.css';

@Component({
    selector: 'app',
    template: require('./app.component.html'),
    styles: [require('./app.component.css').toString()],
})
export class AppComponent {
    @ViewChild('sideBarComponent') sideBarComponent:any;
    private pageWrapperStyles:{overflow:string} = {overflow: ''};

    constructor(@Inject(EnvService) private env:EnvService,
                @Inject(TitleService) private title:TitleService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService,
                @Inject(ScrollFreezerService) private scrollFreezer:ScrollFreezerService) {
    }

    ngOnInit() {
        this.title.setTitle();
    }

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
            this.pageWrapperStyles.overflow = 'hidden';
        }
    };

    private onHideSideBar = (event:any) => {
        this.scrollFreezer.unfreezeBackgroundScroll();
        this.pageWrapperStyles.overflow = '';
    };

    private onToggleSideBar = (event:any) => {
        event.isVisible ? this.onShowSideBar(event) : this.onHideSideBar(event);
    };

    private getCurrentYear = () => {
        return (new Date).getFullYear();
    };
}
