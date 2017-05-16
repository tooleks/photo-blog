import {Pipe, PipeTransform} from '@angular/core';
import {DomSanitizer, SafeHtml} from '@angular/platform-browser'

@Pipe({name: 'safeHtml'})
export class SafeHtmlPipe implements PipeTransform {
    constructor(protected sanitized: DomSanitizer) {
    }

    transform(value): SafeHtml {
        return this.sanitized.bypassSecurityTrustHtml(value);
    }
}
