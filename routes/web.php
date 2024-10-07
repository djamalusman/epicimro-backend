<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\LaporanTahunanController;
use App\Http\Controllers\InsightContoller;
use App\Http\Controllers\Pages\BerandaController;
use App\Http\Controllers\Pages\JobVacancyController;
use App\Http\Controllers\Pages\NewsUpdateController;
use App\Http\Controllers\Pages\ContactUsController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\FooterController;
use App\Http\Controllers\Pages\IfgCorporateUniversityController;
use App\Http\Controllers\Pages\IfgProgressController;
use App\Http\Controllers\Pages\KarirController;
use App\Http\Controllers\Pages\KeterbukaanInformasiPublikController;
use App\Http\Controllers\Pages\PedomanTataKelolaPerusahaanController;
use App\Http\Controllers\Pages\PengandaanTenderUmumController;
use App\Http\Controllers\Pages\ProdukDanLayananController;
use App\Http\Controllers\Pages\TentangKamiController;
use App\Http\Controllers\Pages\WhistleblowingController;
use App\Http\Controllers\Pages\TrainingCourseController;
use App\Http\Controllers\Pages\GalleryController;
use App\Http\Controllers\Pages\PosterController;
use App\Http\Controllers\Pages\TestimonialsController;
use App\Http\Controllers\Pages\YotubeNewsController;
use App\Http\Controllers\Pages\YoutubeController;
use App\Http\Controllers\Pages\SocialMediaController;
use App\Http\Controllers\Pages\HomeController;
use App\Http\Controllers\Pages\BannerController;
use App\Http\Controllers\Pages\SertifikatController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



    //*beranda

    Route::get('/index', [BerandaController::class, 'index'])
        ->name('index');

    Route::post('/beranda-store-first-slider', [BerandaController::class, 'storeFirstSlider'])
        ->name('beranda-store-first-slider');

    Route::put('/beranda-detail-item-update', [BerandaController::class, 'updateSlider'])
        ->name('beranda-detail-item-update');

    Route::get('/beranda-detail-item-edit/{id}', [BerandaController::class, 'editListItem'])
        ->name('beranda-detail-item-edit');

    Route::get('/beranda-detail-item-delete/{id}', [BerandaController::class, 'deleteListItem'])
        ->name('beranda-detail-item-delete');

    Route::post('/beranda-store-youtube', [BerandaController::class, 'storeYoutube'])
        ->name('beranda-store-youtube');

        // Route::get('/contact-us/{id}', [ContactUsController::class, 'index'])
        //     ->name('contact-us');

        // Route::post('/store-contact-us-list', [ContactUsController::class, 'storeHubungiKamiList'])
        //     ->name('store-contact-us-list');



    // test
        Route::get('/storeImage', [BerandaController::class, 'fileStore'])
            ->name('storeImage');

        Route::get('/storeIg', [HelperController::class, 'storeIg'])
            ->name('storeIg');

        Route::get('/refreshTokenIg', [HelperController::class, 'refreshTokenIg'])
            ->name('refreshTokenIg');

        Route::get('/phpinfo', [HelperController::class, 'phpinfo'])
            ->name('phpinfo');

        Route::get('/insertApiGallery', [HelperController::class, 'insertApiGallery'])
            ->name('insertApiGallery');

        Route::get('/insertApiNews', [HelperController::class, 'insertApiNews'])
            ->name('insertApiNews');

        Route::get('/newsDetail', [HelperController::class, 'newsDetail'])
            ->name('newsDetail');


            Route::middleware('guest')->group(function () {
                Route::get('/', [AuthController::class, 'index'])
                    ->name('login');

                Route::post('/login', [AuthController::class, 'store'])
                    ->name('login-action');
            });

            Route::middleware('auth')->group(function () {
                Route::post('logout', [AuthController::class, 'destroy'])
                    ->name('logout');

            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            Route::post('/ubah-password', [BerandaController::class, 'updatePassword'])
                ->name('ubah-password');

    //end


     //Tentang Kami
            Route::get('/company-overview/{id}', [TentangKamiController::class, 'sekilasPerusahaan'])
                ->name('company-overview');

            Route::post('/store-company-overview', [TentangKamiController::class, 'storeSekilasPerusahaan'])
                ->name('store-company-overview');

            Route::get('/vision-mission/{id}', [TentangKamiController::class, 'visiMisi'])
                ->name('vision-mission');

            Route::post('/store-visi-misi', [TentangKamiController::class, 'storeVisiMisi'])
                ->name('store-visi-misi');

            Route::get('/edit-visi-misi/{id}', [TentangKamiController::class, 'editVisiMisi'])
                ->name('edit-visi-misi');

            Route::post('/update-title-visi-misi', [TentangKamiController::class, 'updateTitleVisiMisi'])
                ->name('update-title-visi-misi');

            Route::post('/update-visi-misi', [TentangKamiController::class, 'updateVisiMisi'])
                ->name('update-visi-misi');

            Route::put('/update-visi-misi-detail', [TentangKamiController::class, 'updateVisiMisiDetail'])
                ->name('update-visi-misi-detail');

            Route::get('/edit-list-detail-tk/{id}', [TentangKamiController::class, 'editListItemDetail'])
                ->name('edit-list-detail-tk');

            Route::get('/edit-profile-manajemen/{id}', [TentangKamiController::class, 'editProfileManajemen'])
                ->name('edit-profile-manajemen');

            Route::get('/profile-manajemen-edit-list/{id}', [TentangKamiController::class, 'modalProfileManajemen'])
                ->name('profile-manajemen-edit-list');

            Route::put('/update-profile-manajemen', [TentangKamiController::class, 'updateProfileManajemen'])
                ->name('update-profile-manajemen');

            Route::get('/history/{id}', [TentangKamiController::class, 'sejarahKami'])
                ->name('history');

            Route::post('/store-history', [TentangKamiController::class, 'storeSejarahKami'])
                ->name('store-history');

            Route::put('/update-history', [TentangKamiController::class, 'updateSejarahKamiDetail'])
                ->name('update-history');

            Route::get('/management-profile/{id}', [TentangKamiController::class, 'profileManajemen'])
                ->name('management-profile');

            Route::post('/store-profil-manajemen', [TentangKamiController::class, 'storeProfilManajemen'])
                ->name('store-profil-manajemen');

            Route::get('/subsidiaries/{id}', [TentangKamiController::class, 'anakPerusahaan'])
                ->name('subsidiaries');

            Route::post('/store-anak-perusahaan', [TentangKamiController::class, 'storeAnakPerusahaan'])
                ->name('store-anak-perusahaan');

            Route::get('/award-and-certificate/{id}', [TentangKamiController::class, 'penghargaanSertifikat'])
                ->name('award-and-certificate');

            Route::post('/store-penghargaan-dan-sertifikat', [TentangKamiController::class, 'storePenghargaanSertifikat'])
                ->name('store-penghargaan-dan-sertifikat');

            Route::get('/edit-penghargaan-dan-sertifikat/{id}', [TentangKamiController::class, 'editPernghargaanSertifikat'])
                ->name('edit-penghargaan-dan-sertifikat');

            Route::put('/update-penghargaan-dan-sertifikat', [TentangKamiController::class, 'updatePernghargaanSertifikat'])
                ->name('update-penghargaan-dan-sertifikat');

            Route::put('/beranda-update-anggota-holding', [BerandaController::class, 'updateAnggotaHolding'])
                ->name('beranda-update-anggota-holding');

    // end tentang kami

    //helper
            Route::get('/pages-list-detail-delete/{id}', [HelperController::class, 'deleteListItemDetail'])
                ->name('pages-list-detail-delete');

            Route::get('/edit-list-detail/{id}', [HelperController::class, 'editListItemDetail'])
                ->name('edit-list-detail');

            Route::post('/store-page-header', [HelperController::class, 'storePageHeader'])
                ->name('store-page-header');

            Route::post('/store-side-list', [HelperController::class, 'storeSideList'])
                ->name('store-side-list');

            Route::post('/update-side-list', [HelperController::class, 'updateOnlySideList'])
                ->name('update-side-list');

            Route::post('/store-anggota-holding', [HelperController::class, 'storeAnggotaHolding'])
                ->name('store-anggota-holding');

            Route::get('/delete-side-list/{id}', [HelperController::class, 'deleteSideList'])
                ->name('delete-side-list');

            Route::get('/anggota-holding-delete/{id}', [HelperController::class, 'deleteAnggotaHolding'])
                ->name('anggota-holding-delete');

            Route::get('/edit-list-anggot-holding/{id}', [BerandaController::class, 'editListAnggotaHolding'])
                ->name('edit-list-anggot-holding');
    // end helper

    // training course

            Route::get('traningcourse/{id}', [TrainingCourseController::class, 'traningcourse'])
                ->name('traningcourse');

            Route::get('/get-datacourse-filters', [TrainingCourseController::class, 'getFilters'])
                ->name('get-datacourse-filters');

            Route::get('/get-data-course', [TrainingCourseController::class, 'getDataCourses'])
                ->name('get-data-course');

            Route::get('/get-view-store-traningcourse/{id}', [TrainingCourseController::class, 'ViewsStoretraningcourse'])
                ->name('get-view-store-traningcourse');

            Route::post('/store-course-endpoint', [TrainingCourseController::class, 'storeCourseEndpoint'])
            ->name('store-course-endpoint');

            Route::get('/edit-traningcourse/{id}', [TrainingCourseController::class, 'editTraningCourse'])
                ->name('edit-traningcourse');

            Route::post('/update-course-endpoint', [TrainingCourseController::class, 'updateCourseEndpoint'])
            ->name('update-course-endpoint');

            Route::get('/remove-persyaratan-endpoint/{id}', [TrainingCourseController::class, 'removePersyaratanEndpoint'])
                ->name('remove-persyaratan-endpoint');

            Route::get('/remove-materitraining-endpoint/{id}', [TrainingCourseController::class, 'removeMateriTrainingEndpoint'])
                ->name('remove-materitraining-endpoint');

            Route::get('/remove-fasilitas-endpoint/{id}', [TrainingCourseController::class, 'removeFasilitasEndpoint'])
                ->name('remove-fasilitas-endpoint');

            Route::get('/remove-photo-endpoint/{id}', [TrainingCourseController::class, 'removePhotoEndpoint'])
                ->name('remove-photo-endpoint');

            Route::get('/remove-trainingcourse/{id}', [TrainingCourseController::class, 'removePTrainingCourse'])
                ->name('remove-trainingcourse');

            Route::get('/stop-data-course/{id}', [TrainingCourseController::class, 'stopTrainingCourse'])
                ->name('stop-data-course');

            Route::get('/copy-training-course-list/{id}', [TrainingCourseController::class, 'copyTrainingCourseList'])
                ->name('copy-training-course-list');

            Route::put('/update-copy-training-course', [TrainingCourseController::class, 'updateCopyTrainingCourseList'])
                ->name('update-copy-training-course');

            Route::get('/delete-data-course/{id}', [TrainingCourseController::class, 'removePTrainingCourse'])
                ->name('delete-data-course');




            Route::get('/edit-traningcourse-detail/{id}', [TrainingCourseController::class, 'editTraningCourseDetail'])
                ->name('edit-traningcourse-detail');

            // Route::put('/update-traning-course-detail', [TrainingCourseController::class, 'updateTraningCourseDetail'])
            //     ->name('update-traning-course-detail');

    //end training

    // job vacancy

            Route::get('/jobvacancy/{id}', [JobVacancyController::class, 'jobVacancy'])
                ->name('jobvacancy');

            Route::get('/get-data-job', [JobVacancyController::class, 'getDataJobFilter'])
                ->name('get-data-job');

            Route::get('/get-filters-job', [JobVacancyController::class, 'getDropdownJob'])
                ->name('get-filters-job');

            Route::get('/get-view-store-jobvacancy/{id}', [JobVacancyController::class, 'getViewStoreJobvacancy'])
                ->name('get-view-store-jobvacancy');

            Route::post('/store-jobvacancy', [JobVacancyController::class, 'storeJobVacancy'])
                ->name('store-jobvacancy');


            Route::get('/edit-jobvacancy/{id}', [JobVacancyController::class, 'editJobVacancy'])
                ->name('edit-jobvacancy');

            Route::post('/update-job-vacancy', [JobVacancyController::class, 'updateJobVacancy'])
                ->name('update-job-vacancy');

            Route::get('/stop-data-job/{id}', [JobVacancyController::class, 'stopJobvacancy'])
                ->name('stop-data-job');

            Route::get('/delete-master-job-cavancy/{id}', [JobVacancyController::class, 'deleteJobVacancyMaster'])
                ->name('delete-master-job-cavancy');

            Route::get('/delete-master-job-cavancy-detail/{id}', [JobVacancyController::class, 'deleteJobVacancyDetail'])
                ->name('delete-master-job-cavancy-detail');

    // end job

    // news & update

            Route::get('/newsupdate/{id}', [NewsUpdateController::class, 'newsUpdate'])
                ->name('newsupdate');

            Route::get('/get-data-news', [NewsUpdateController::class, 'getDataNewsFilter'])
                ->name('get-data-news');

            Route::get('/get-filters-news', [NewsUpdateController::class, 'getDropdownNews'])
                ->name('get-filters-news');


            Route::get('/get-view-store-news/{id}', [NewsUpdateController::class, 'getViewStoreNews'])
                ->name('get-view-store-news');

            Route::post('/store-news-update', [NewsUpdateController::class, 'storeNewsUpdate'])
                ->name('store-news-update');

            Route::get('/edit-newsupdate/{id}', [NewsUpdateController::class, 'editNewsUpdate'])
                ->name('edit-newsupdate');

            Route::post('/update-news-update', [NewsUpdateController::class, 'updateNewsUpdate'])
                ->name('update-news-update');

            Route::get('/delete-news/{id}', [NewsUpdateController::class, 'deleteNews'])
                ->name('delete-news');



            Route::get('/edit-NewsUpdate-detail/{id}', [NewsUpdateController::class, 'editNewsUpdateDetail'])
                ->name('edit-NewsUpdate-detail');

            Route::put('/update-news-update-detail', [NewsUpdateController::class, 'updateNewsUpdateDetail'])
                ->name('update-news-update-detail');


    //end news

    // cooperation

            Route::get('/get-data-cooperation', [JobVacancyController::class, 'getDataCooperationFilter'])
                ->name('get-data-cooperation');

            Route::get('/get-filters-cooperation', [JobVacancyController::class, 'getDropdownCooperation'])
                ->name('get-filters-cooperation');

            Route::get('/cooperation/{id}', [JobVacancyController::class, 'cooperation'])
                ->name('cooperation');

            Route::get('/get-view-store-cooperation/{id}', [JobVacancyController::class, 'getViewStoreCooperation'])
                ->name('get-view-store-cooperation');

            Route::get('/get-view-edit-cooperation/{id}', [JobVacancyController::class, 'editCooperation'])
                ->name('get-view-edit-cooperation');

            Route::post('/store-cooperation', [JobVacancyController::class, 'storeCooperation'])
                ->name('store-cooperation');

            Route::post('/update-cooperation', [JobVacancyController::class, 'updateCooperation'])
                ->name('update-cooperation');

            Route::get('remove-cooperation/{id}', [JobVacancyController::class, 'removeCooperation'])
                    ->name('remove-cooperation');

    // end cooperation

    // home

    // Route::get('home/{id}', [HomeController::class, 'index'])->name('home');

    // Route::post('/store-home', [HomeController::class, 'storeHome'])
    // ->name('store-home');

    // end home

    // Galery

        Route::get('gallery/{id}', [GalleryController::class, 'index'])->name('gallery');

        Route::get('/get-data-gallery', [GalleryController::class, 'getDataGallery'])
            ->name('get-data-gallery');

        Route::get('/get-filters-gallery', [GalleryController::class, 'getDropdownGallery'])
            ->name('get-filters-gallery');

        Route::get('/view-image-gallery/{id}', [GalleryController::class, 'viewInmage'])
            ->name('view-image-gallery');


        Route::get('/get-view-store-gallery/{id}', [GalleryController::class, 'getViewStoreGallery'])
            ->name('get-view-store-gallery');

            Route::post('/gallery-store', [GalleryController::class, 'storeGallery'])
            ->name('gallery-store');


        Route::get('/edit-gallery/{id}/{id_category}', [GalleryController::class, 'galleryEdit'])
                ->name('edit-gallery');

        Route::post('/update-gallery', [GalleryController::class, 'galleryUpdate'])
                ->name('update-gallery');

        Route::get('/remove-photo-gallery/{id}', [GalleryController::class, 'removePhotoGalerry'])
                ->name('remove-photo-gallery');

        Route::get('/delete-data-gallery/{id}', [GalleryController::class, 'deleteDataGalerry'])
                ->name('delete-data-gallery');
    // end Galery

    // Testimoni

        Route::get('testimonials/{id}', [TestimonialsController::class, 'index'])->name('testimonials');

        Route::get('/get-data-testimonials', [TestimonialsController::class, 'getDataTestimoniFilter'])
            ->name('get-data-testimonials');

        Route::get('/get-filters-testimonials', [TestimonialsController::class, 'getDropdownTestimoni'])
            ->name('get-filters-testimonials');

        Route::get('/get-view-store-testimonials/{id}', [TestimonialsController::class, 'getViewStoreTestimonial'])
            ->name('get-view-store-testimonials');

        Route::post('/store-testimonials', [TestimonialsController::class, 'storeTestimoniUpdate'])
            ->name('store-testimonials');

        Route::get('/edit-testimonials/{id}', [TestimonialsController::class, 'getVieweditTestimoni'])
            ->name('edit-testimonials');

        Route::post('/update-testimonials', [TestimonialsController::class, 'updateTestimoni'])
                ->name('update-testimonials');

        Route::get('/remove-embed-video/{id}', [TestimonialsController::class, 'removeEmbedVideo'])
                ->name('remove-embed-video');

        Route::get('/delete-data-testimoni/{id}', [TestimonialsController::class, 'deleteDataTestimoni'])
                ->name('delete-data-testimoni');

       Route::get('/view-video-popup/{id}', [TestimonialsController::class, 'viewPopUpVid'])
                ->name('view-video-popup');
    // end Testimoni

     // Poster

        Route::get('poster/{id}', [PosterController::class, 'index'])->name('poster');

        Route::get('/get-data-poster', [PosterController::class, 'getDataPoster'])
            ->name('get-data-poster');

        Route::get('/get-filters-poster', [PosterController::class, 'getDropdownPoster'])
            ->name('get-filters-poster');

        Route::get('/view-image-poster/{id}', [PosterController::class, 'viewInmage'])
            ->name('view-image-poster');


        Route::get('/get-view-store-poster/{id}', [PosterController::class, 'getViewStorePoster'])
            ->name('get-view-store-poster');

            Route::post('/poster-store', [PosterController::class, 'storePoster'])
            ->name('poster-store');


        Route::get('/edit-poster/{id}/{id_category}', [PosterController::class, 'posterEdit'])
                ->name('edit-poster');

        Route::post('/update-poster', [PosterController::class, 'posterUpdate'])
                ->name('update-poster');

        Route::get('/remove-photo-poster/{id}', [PosterController::class, 'removePhotoPoster'])
                ->name('remove-photo-poster');

        Route::get('/delete-data-poster/{id}', [PosterController::class, 'deleteDataPoster'])
                ->name('delete-data-poster');
    // end Poster

     // Yotube News

        Route::get('video/{id}', [YotubeNewsController::class, 'index'])->name('video');

        Route::get('/get-data-video', [YotubeNewsController::class, 'getDataVideo'])
            ->name('get-data-video');

        Route::get('/get-filters-video', [YotubeNewsController::class, 'getDropdownVideo'])
            ->name('get-filters-video');

        Route::get('/get-view-store-video/{id}', [YotubeNewsController::class, 'getViewStoreVideo'])
            ->name('get-view-store-video');

        Route::post('/store-video', [YotubeNewsController::class, 'storeVideo'])
            ->name('store-video');

        Route::get('/edit-video/{id}', [YotubeNewsController::class, 'getVieweditVideo'])
            ->name('edit-video');

        Route::post('/update-video', [YotubeNewsController::class, 'updateVideo'])
                ->name('update-video');

        Route::get('/remove-embed-video/{id}', [YotubeNewsController::class, 'removeEmbedVideo'])
                ->name('remove-embed-video');

        Route::get('/delete-data-video/{id}', [YotubeNewsController::class, 'deleteDataVideo'])
                ->name('delete-data-video');

        Route::get('/view-video-popup/{id}', [YotubeNewsController::class, 'viewPopUpVid'])
                ->name('view-video-popup');
    // end Yotube News

     // socail media

        Route::get('social-media/{id}', [SocialMediaController::class, 'index'])->name('social-media');

        Route::get('/get-data-social-media', [SocialMediaController::class, 'getDataSm'])
        ->name('get-data-social-media');

        Route::get('/get-filters-social-media', [SocialMediaController::class, 'getDropdownSm'])
        ->name('get-filters-social-media');

        Route::get('/get-view-store-social-media/{id}', [SocialMediaController::class, 'getViewStoreSm'])
                ->name('get-view-store-social-media');

        Route::post('/store-social-media', [SocialMediaController::class, 'storeSm'])
            ->name('store-social-media');

        Route::get('/edit-social-media/{id}', [SocialMediaController::class, 'getVieweditSm'])
            ->name('edit-social-media');

        Route::post('/update-social-media', [SocialMediaController::class, 'updateSm'])
                ->name('update-social-media');


       Route::get('/delete-data-sm/{id}', [SocialMediaController::class, 'deleteDataSm'])
                ->name('delete-data-sm');

    // end social media


    // home

        Route::get('home/{id}', [BannerController::class, 'index'])->name('home');

        Route::get('/get-data-banner', [BannerController::class, 'getDataBanner'])
            ->name('get-data-banner');

        Route::get('/get-filters-banner', [BannerController::class, 'getDropdownBanner'])
            ->name('get-filters-banner');

        Route::get('/view-image-banner/{id}', [BannerController::class, 'viewInmageBanner'])
            ->name('view-image-banner');


        Route::get('/get-view-store-banner/{id}', [BannerController::class, 'getViewStoreBanner'])
            ->name('get-view-store-banner');

            Route::post('/banner-store', [BannerController::class, 'storeBanner'])
            ->name('banner-store');


        Route::get('edit-banner/{id}/{id_category}', [BannerController::class, 'bannerEdit'])
                ->name('edit-banner');

        Route::post('update-banner', [BannerController::class, 'bannerUpdate'])
                ->name('update-banner');

        Route::get('remove-photo-banner/{id}', [BannerController::class, 'removePhotoBanner'])
                ->name('remove-photo-banner');

        Route::get('delete-data-banner/{id}', [BannerController::class, 'deleteDataBanner'])
                ->name('delete-data-banner');
    // end home


    // master

        Route::get('master/{id}', [HomeController::class, 'index'])->name('master');

        Route::post('/store-home', [HomeController::class, 'storeHome'])
        ->name('store-home');

    // end master

    // sertifikat

            Route::get('/sertifikat/{id}', [SertifikatController::class, 'index'])
                ->name('sertifikat');

            Route::get('/get-data-sertifikat', [SertifikatController::class, 'getDataSertifikat'])
                ->name('get-data-sertifikat');

            Route::get('/get-filters-sertifikat', [SertifikatController::class, 'getDropdown'])
                ->name('get-filters-sertifikat');


            Route::get('/get-view-store-sertifikat/{id}', [SertifikatController::class, 'getViewStoreSertifikat'])
                ->name('get-view-store-sertifikat');

            Route::get('/get-view-excel-sertifikat/{id}', [SertifikatController::class, 'getViewExcelSertifikat'])
                ->name('get-view-excel-sertifikat');

            Route::post('/store-sertifikat', [SertifikatController::class, 'storeSertifikat'])
                ->name('store-sertifikat');

            Route::post('/store-sertifikat-excel', [SertifikatController::class, 'import'])
                ->name('store-sertifikat-excel');

            Route::get('/edit-sertifikat/{id}', [SertifikatController::class, 'editSertifikat'])
                ->name('edit-sertifikat');

            Route::post('/update-sertifikat', [SertifikatController::class, 'updateSertifikat'])
                ->name('update-sertifikat');

            Route::get('/delete-sertifikat/{id}', [SertifikatController::class, 'deleteSertifikat'])
                ->name('delete-sertifikat');

            Route::get('/download-excel', function () {
                    $filePath = storage_path('app\public\yourfile.xlsx'); // Path ke file Excel
                    return response()->download($filePath);
                })->name('download-excel');

            Route::get('/export-sertifikat', [SertifikatController::class, 'export'])
                ->name('export-sertifikat');

            // Route::put('/update-news-update-detail', [NewsUpdateController::class, 'updateNewsUpdateDetail'])
            //     ->name('update-news-update-detail');


    //end sertifikat

    // registrasi

        Route::get('masterregistrasion/{id}', [HomeController::class, 'registrasiindex'])->name('masterregistrasion');

        Route::post('/store-home-registrasion', [HomeController::class, 'storeHomeregistrasion'])
        ->name('store-home-registrasion');

    // end registrasi

});
