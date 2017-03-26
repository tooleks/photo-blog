import {Component, Inject} from '@angular/core';
import {ActivatedRoute} from '@angular/router';
import {
    TitleService,
    ScrollerService,
    AuthProviderService,
    NavigatorServiceProvider,
    NavigatorService,
    LockProcessServiceProvider,
    LockProcessService,
} from '../../../shared/services';
import {Photo} from '../../../shared/models';
import {NoticesService} from '../../../common/notices';
import {PhotoDataProviderService, PhotoMapper} from '../../services'

@Component({
    selector: 'photo-form',
    templateUrl: './photo-form.component.html',
    styles: [String(require('./photo-form.component.css'))],
})
export class PhotoFormComponent {
    private photo:any;
    private navigator:NavigatorService;
    private lockProcess:LockProcessService;

    constructor(@Inject(ActivatedRoute) private route:ActivatedRoute,
                @Inject(TitleService) private title:TitleService,
                @Inject(ScrollerService) private scroller:ScrollerService,
                @Inject(AuthProviderService) private authProvider:AuthProviderService,
                @Inject(PhotoDataProviderService) private photoDataProvider:PhotoDataProviderService,
                @Inject(NoticesService) private notices:NoticesService,
                @Inject(NavigatorServiceProvider) navigatorServiceProvider:NavigatorServiceProvider,
                @Inject(LockProcessServiceProvider) lockProcessServiceProvider:LockProcessServiceProvider) {
        this.navigator = navigatorServiceProvider.getInstance();
        this.lockProcess = lockProcessServiceProvider.getInstance();
    }

    ngOnInit() {
        this.scroller.scrollToTop();

        this.title.setTitle('Add Photo');

        this.photo = new Photo;

        this.route.params.map((params) => params['id']).subscribe((id:number) => {
            if (!id) return;
            this.photoDataProvider.getById(id).then((photo:any) => {
                this.title.setTitle('Edit Photo');
                this.photo = PhotoMapper.mapToPhoto(photo);
                return photo;
            });
        });
    }

    private processSavePhoto = () => {
        let saver = this.photo.id
            ? this.photoDataProvider.updateById(this.photo.id, this.photo)
            : this.photoDataProvider.create(this.photo);

        return saver.then((photo:any) => this.photo = PhotoMapper.mapToPhoto(photo));
    };

    save = () => {
        return this.lockProcess.process(this.processSavePhoto).then((result:any) => {
            this.notices.success('Photo was successfully saved.');
            this.navigator.navigate(['/photos']);
            return result;
        });
    };

    private processUploadPhoto = (file:FileList) => {
        let uploader = this.photo.id
            ? this.photoDataProvider.uploadById(this.photo.id, file)
            : this.photoDataProvider.upload(file);

        return uploader.then((photo:any) => this.photo = PhotoMapper.mapToPhoto(photo));
    };

    upload = (file:FileList) => {
        return this.lockProcess.process(this.processUploadPhoto, [file]).then((result:any) => {
            this.notices.success('File was successfully uploaded.');
            return result;
        });
    };

    private processDeletePhoto = () => {
        let deleter:Promise<any> = this.photo.id
            ? this.photoDataProvider.deleteById(this.photo.id)
            : Promise.reject(new Error('You can\'n delete unsaved photo.'));

        return deleter;
    };

    deletePhoto = () => {
        return this.lockProcess.process(this.processDeletePhoto).then((result:any) => {
            this.notices.success('Photo was successfully deleted.');
            this.navigator.navigate(['/photos']);
            return result;
        });
    };

    isLoading = ():boolean => {
        return this.lockProcess.isProcessing();
    };
}
