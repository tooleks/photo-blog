import {
    Component,
    Inject,
    HostListener,
    Input,
    Output,
    EventEmitter,
    trigger,
    state,
    style,
    transition,
    animate
} from '@angular/core';
import {ScreenDetectorService, AuthProviderService, EnvService} from '../../../../shared/services';

@Component({
    selector: 'sidebar',
    template: require('./sidebar.component.html'),
    styles: [require('./sidebar.component.css').toString()],
    animations: [
        trigger('slideInOut', [
            state('in', style({
                'transform': 'translate3d(0, 0, 0)',
                'box-shadow': '1px 0 3px rgba(0, 0, 0, 0.25)'
            })),
            state('out', style({
                'transform': 'translate3d(-220px, 0, 0)',
                'box-shadow': 'none'
            })),
            transition('in => out', animate('200ms ease-in-out')),
            transition('out => in', animate('200ms ease-in-out'))
        ]),
        trigger('appearInOut', [
            state('in', style({
                'display': 'block',
                'opacity': '0.85'
            })),
            state('out', style({
                'display': 'none',
                'opacity': '0'
            })),
            transition('in => out', animate('200ms ease-in-out')),
            transition('out => in', animate('200ms ease-in-out'))
        ]),
    ],
})
export class SideBarComponent {
    @Input() tags:Array<any> = [];
    @Output() onToggle:EventEmitter<any> = new EventEmitter<any>();
    @Output() onShow:EventEmitter<any> = new EventEmitter<any>();
    @Output() onHide:EventEmitter<any> = new EventEmitter<any>();

    private animationState:string;

    constructor(@Inject(ScreenDetectorService) private screenDetector:ScreenDetectorService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService,
                @Inject(EnvService) private env:EnvService) {
        this.init();
    }

    @HostListener('window:resize', ['$event'])
    onWindowResize(event:any) {
        this.init();
    }

    init = () => {
        this.screenDetector.isLargeScreen() ? this.show() : this.hide();
    };

    show = () => {
        this.animationState = 'in';
        this.emitChange('onShow');
    };

    hide = () => {
        if (this.screenDetector.isSmallScreen()) {
            this.animationState = 'out';
        }
        this.emitChange('onHide');
    };

    toggle = () => {
        this.isVisible() ? this.hide() : this.show();
        this.emitChange('onToggle');
    };

    isVisible = ():boolean => {
        return this.animationState === 'in';
    };

    emitChange = (event:string) => {
        this[event].emit({
            isVisible: this.isVisible(),
            isSmallDevice: this.screenDetector.isSmallScreen(),
            isLargeDevice: this.screenDetector.isLargeScreen(),
        });
    };
}
