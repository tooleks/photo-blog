import {NgModule} from '@angular/core';
import {ServerModule} from '@angular/platform-server';
import {ServerTransferStateModule, TransferState} from '../modules';
import {AppComponent} from './components';
import {AppModule} from './app.module';
import {BrowserModule} from '@angular/platform-browser';

@NgModule({
    bootstrap: [AppComponent],
    imports: [
        BrowserModule.withServerTransition({
            appId: 'my-app-id'
        }),
        ServerModule,
        ServerTransferStateModule,
        AppModule
    ]
})
export class ServerAppModule {
    constructor(protected transferState:TransferState) {
    }

    // Gotcha
    ngOnBootstrap = () => {
        this.transferState.inject();
    }
}
