import {NgModule} from '@angular/core';
import {ServerModule} from '@angular/platform-server';
import {ServerTransferStateModule} from '../modules/transfer-state/server-transfer-state.module';
import {AppComponent} from './components';
import {AppModule} from './app.module';
import {TransferState} from '../modules/transfer-state/transfer-state';
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
