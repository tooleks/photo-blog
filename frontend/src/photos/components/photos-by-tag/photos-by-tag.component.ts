import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {TitleService} from '../../../shared/services/title';
import {LockProcessService, LockProcessServiceProvider} from '../../../shared/services/lock-process';
import {NavigatorService, NavigatorServiceProvider} from '../../../shared/services/navigator';
import {PagerService, PagerServiceProvider} from '../../../shared/services/pager';
import {AuthProviderService} from '../../../shared/services/auth';
import {PhotoDataProviderService} from '../../services/photo-data-provider.service';
import {Photo} from '../../../shared/models';

@Component({
    selector: 'photos',
    template: require('./photos-by-tag.component.html'),
})
export class PhotosByTagComponent {
    protected loaded:boolean;
    protected queryParams:Object = {};
    protected pager:PagerService;
    protected lockProcess:LockProcessService;
    protected navigator:NavigatorService;

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(TitleService) private title:TitleService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService,
                @Inject(PhotoDataProviderService) private photoDataProvider:PhotoDataProviderService,
                @Inject(NavigatorServiceProvider) navigatorProvider:NavigatorServiceProvider,
                @Inject(PagerServiceProvider) pagerProvider:PagerServiceProvider,
                @Inject(LockProcessServiceProvider) lockProcessProvider:LockProcessServiceProvider) {
        this.navigator = navigatorProvider.getInstance();
        this.pager = pagerProvider.getInstance();
        this.lockProcess = lockProcessProvider.getInstance();
    }

    ngOnInit() {
        this.route.queryParams
            .map((queryParams) => queryParams['page'])
            .subscribe((page:number) => this.pager.setPage(page));

        this.route.queryParams
            .map((queryParams) => queryParams['show'])
            .subscribe((show:number) => this.queryParams['show'] = show);

        this.route.params
            .map((params) => params['tag'])
            .subscribe((tag:string) => {
                if (this.queryParams['tag'] === tag) {
                    return;
                }

                this.queryParams['tag'] = tag;

                this.title.setTitle(['Photos', '#' + tag]);

                this.pager.reset();

                this.loadPhotos(this.pager.calculateLimitForPage(this.pager.getPage()),
                    this.pager.getOffset(), this.queryParams['tag']);
            });
    }

    private processLoadPhotos = (take:number, skip:number, tag:string):Promise<Array<Photo>> => {
        return this.photoDataProvider
            .getByTag(take, skip, tag)
            .then((photos:Array<Photo>) => this.pager.appendItems(photos));
    };

    private loadPhotos = (take:number, skip:number, tag:string):Promise<Array<Photo>> => {
        return this.lockProcess
            .process(this.processLoadPhotos, [take, skip, tag])
            .then((result:any) => {
                this.navigator.setQueryParam('page', this.pager.getPage());
                return result;
            });
    };

    getLoadedPhotos = () => {
        return this.pager.getItems();
    };

    loadMorePhotos = () => {
        return this.loadPhotos(this.pager.getLimit(), this.pager.getOffset(), this.queryParams['tag']);
    };

    isEmpty = ():boolean => {
        return !this.pager.getItems().length && !this.lockProcess.isProcessing() && !this.loaded;
    };

    isLoading = ():boolean => {
        return this.lockProcess.isProcessing();
    };

    onShowPhoto = (photo:Photo):void => {
        this.navigator.setQueryParam('show', photo.id);
    };

    onHidePhoto = (photo:Photo):void => {
        this.navigator.unsetQueryParam('show');
    };

    onEditPhoto = (photo:Photo):void => {
        this.navigator.navigate(['photo/edit', photo.id]);
    };
}
