import {Component, Inject, HostListener, trigger, state, style, transition, animate} from '@angular/core';
import {AuthProviderService} from '../../../../shared/services/auth';

@Component({
    selector: 'sidebar',
    template: require('./sidebar.component.html'),
    styles: [require('./sidebar.component.css').toString()],
    animations: [
        trigger('slideInOut', [
            state('in', style({
                transform: 'translate3d(0, 0, 0)'
            })),
            state('out', style({
                transform: 'translate3d(-230px, 0, 0)'
            })),
            transition('in => out', animate('300ms ease-in-out')),
            transition('out => in', animate('300ms ease-in-out'))
        ]),
        trigger('appearInOut', [
            state('in', style({
                display: 'block',
                opacity: '0.85'
            })),
            state('out', style({
                display: 'none',
                opacity: '0'
            })),
            transition('in => out', animate('300ms ease-in-out')),
            transition('out => in', animate('300ms ease-in-out'))
        ]),
    ],
})
export class SideBarComponent {
    private windowInnerWidth:number;
    private sideBarState:string;

    constructor(@Inject(AuthProviderService) private authProvider:AuthProviderService) {
        this.windowInnerWidth = window.innerWidth;
        this.windowInnerWidth > 767 ? this.showSideBar() : this.hideSideBar();
    }

    @HostListener('window:resize', ['$event'])
    onWindowResize(event:any) {
        this.windowInnerWidth = window.innerWidth;
        if (this.windowInnerWidth > 767) {
            this.showSideBar();
        }
    }

    toggleSideBar = () => {
        if (this.windowInnerWidth <= 767) {
            this.isVisibleSidebar() ? this.hideSideBar() : this.showSideBar();
        }
    };

    showSideBar = () => {
        this.sideBarState = 'in';
    };

    hideSideBar = () => {
        this.sideBarState = 'out';
    };

    isVisibleSidebar = () => {
        return this.sideBarState === 'in';
    };
}
