import {Component, Input, Inject, ElementRef} from '@angular/core';
import {CallbackHandlerService} from '../../services/callback-handler';

@Component({
    selector: 'file-select-input',
    template: require('./file-select-input.component.html'),
})
export class FileSelectInputComponent {
    @Input() disabled:boolean;
    @Input() onChangeCallback:any;

    constructor(@Inject(ElementRef) private element:ElementRef,
                @Inject(CallbackHandlerService) private callbackHandler:CallbackHandlerService) {
    }

    onChange() {
        if (this.isFile()) {
            this.callbackHandler.resolveCallback(this.onChangeCallback, [this.getFile()]);
        }
    }

    getFile = ():FileList => {
        return this.element.nativeElement.firstElementChild.files[0];
    };

    isFile = ():boolean => {
        return this.element.nativeElement.firstElementChild.files.length > 0;
    };
}
