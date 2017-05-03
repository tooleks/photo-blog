import {Input, Component} from '@angular/core';

@Component({
    selector: 'json-ld',
    template: `<div [style.display]="'none'" [innerHtml]="getContent() | safeHtml"></div>`,
})
export class JsonLdComponent {
    @Input() data:any = {};

    getContent():string {
        return `<script type="application/ld+json">${JSON.stringify(this.data)}</script>`;
    }
}
