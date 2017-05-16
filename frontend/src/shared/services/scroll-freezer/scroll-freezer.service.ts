import {Injectable, EventEmitter} from '@angular/core';
import {EnvironmentDetectorService} from '../../../core';

@Injectable()
export class ScrollFreezerService {
    freezed: EventEmitter<boolean> = new EventEmitter<boolean>();
    unfreezed: EventEmitter<boolean> = new EventEmitter<boolean>();

    constructor(protected environmentDetector: EnvironmentDetectorService) {
    }

    freeze(): void {
        if (this.environmentDetector.isBrowser()) {
            const top = window.scrollY;
            window.onscroll = () => window.scroll(0, top);
            this.freezed.emit(true);
        }
    }

    unfreeze(): void {
        if (this.environmentDetector.isBrowser()) {
            window.onscroll = null;
            this.unfreezed.emit(true);
        }
    }
}
