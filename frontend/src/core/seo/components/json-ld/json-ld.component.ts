import {Input, Component} from '@angular/core';

@Component({
    selector: 'json-ld',
    templateUrl: 'json-ld.component.html',
    styles: [require('./json-ld.component.css').toString()],
})
export class JsonLdComponent {
    @Input() data = {};

    getContent(): string {
        return `<script type="application/ld+json">${JSON.stringify(this.data)}</script>`;
    }
}
