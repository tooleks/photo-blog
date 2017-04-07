import {Pipe, PipeTransform} from '@angular/core';
import {DomSanitizer} from '@angular/platform-browser'
import {SafeHtml} from '@angular/platform-browser/public_api';

@Pipe({name: 'safeHtml'})
export class SafeHtmlPipe implements PipeTransform {
    constructor(private sanitized:DomSanitizer) {
    }

    transform(value:any):SafeHtml {
        return this.sanitized.bypassSecurityTrustHtml(value);
    }
}
