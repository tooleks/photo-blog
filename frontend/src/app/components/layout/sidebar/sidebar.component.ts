import {Component, HostListener, Output, EventEmitter, OnInit} from '@angular/core';
import {trigger, state, style, transition, animate} from '@angular/animations';
import {ScreenDetectorService} from '../../../../core';
import {ApiService, AuthProviderService, AppService} from '../../../../shared';

@Component({
    selector: 'sidebar',
    templateUrl: 'sidebar.component.html',
    styles: [require('./sidebar.component.css').toString()],
    animations: [
        trigger('slideInOut', [
            state('in', style({
                'transform': 'translate3d(0, 0, 0)',
                'box-shadow': '1px 0 3px rgba(0, 0, 0, 0.25)',
            })),
            state('out', style({
                'transform': 'translate3d(-220px, 0, 0)',
                'box-shadow': 'none',
            })),
            transition('in => out', animate('200ms ease-in-out')),
            transition('out => in', animate('200ms ease-in-out'))
        ]),
        trigger('appearInOut', [
            state('in', style({
                'display': 'block',
                'opacity': '0.85',
            })),
            state('out', style({
                'display': 'none',
                'opacity': '0',
            })),
            transition('in => out', animate('200ms ease-in-out')),
            transition('out => in', animate('200ms ease-in-out'))
        ]),
    ],
})
export class SideBarComponent implements OnInit {
    @Output() onToggle: EventEmitter<any> = new EventEmitter<any>();
    @Output() onShow: EventEmitter<any> = new EventEmitter<any>();
    @Output() onHide: EventEmitter<any> = new EventEmitter<any>();
    animationState: string;
    tags: Array<any> = [];

    constructor(protected app: AppService,
                protected api: ApiService,
                public authProvider: AuthProviderService,
                protected screenDetector: ScreenDetectorService) {
        this.reset();
        this.loadTags();
    }

    ngOnInit(): void {
        this.reset();
    }

    @HostListener('window:resize', ['$event'])
    onWindowResize(event) {
        this.reset();
    }

    reset(): void {
        this.screenDetector.isLargeScreen() ? this.show() : this.hide();
    }

    show(): void {
        this.animationState = 'in';
        this.emitChange('onShow');
    }

    hide(): void {
        if (this.screenDetector.isSmallScreen()) {
            this.animationState = 'out';
        }
        this.emitChange('onHide');
    }

    toggle(): void {
        this.isVisible() ? this.hide() : this.show();
        this.emitChange('onToggle');
    }

    isVisible(): boolean {
        return this.animationState === 'in';
    }

    emitChange(event: string): void {
        this[event].emit({isVisible: this.isVisible()});
    }

    protected loadTags(): void {
        this.api.get('/tags', {params: {page: 1, per_page: 7}}).then((response) => this.tags = response.data);
    }
}
