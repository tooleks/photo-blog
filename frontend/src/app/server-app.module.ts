import {NgModule} from '@angular/core';
import {ServerModule} from '@angular/platform-server';
import {ServerTransferStateModule, TransferState} from '../sys';
import {AppComponent} from './components';
import {AppModule} from './app.module';
import {BrowserModule} from '@angular/platform-browser';

@NgModule({
    bootstrap: [AppComponent],
    imports: [
        BrowserModule.withServerTransition({
            appId: process.env.APP_ID
        }),
        ServerModule,
        ServerTransferStateModule,
        AppModule
    ]
})
export class ServerAppModule {
    constructor(protected transferState: TransferState) {
    }

    ngOnBootstrap = () => {
        this.transferState.inject();
    }
}
