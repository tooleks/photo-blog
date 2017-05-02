import {Component} from '@angular/core';
import {LinkedDataService} from '../../services/linked-data';

@Component({
    selector: 'json-ld',
    template: `<div [style.display]="'none'" [innerHtml]="getInnerHtml() | safeHtml"></div>`,
})
export class JsonLdComponent {
    constructor(protected linkedData:LinkedDataService) {
    }

    getInnerHtml():string {
        return this.linkedData
            .getItems()
            .map((item:any) => `<script type="application/ld+json">${JSON.stringify(item)}</script>`)
            .join('');
    }
}
