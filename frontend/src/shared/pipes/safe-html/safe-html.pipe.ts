import {Pipe, Inject, PipeTransform} from '@angular/core';
import {DomSanitizer} from '@angular/platform-browser'

@Pipe({name: 'safeHtml'})
export class SafeHtmlPipe implements PipeTransform {
    constructor(@Inject(DomSanitizer) private sanitized:DomSanitizer) {
    }

    transform(value:any):any {
        return this.sanitized.bypassSecurityTrustHtml(value);
    }
}
