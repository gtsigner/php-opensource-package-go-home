/**
 * Created by zhaojunlike on 2016/11/9.
 */

window.AppSetting = {
    app_debug: false,
    app_host: 'package.oeynet.com',
    app_theme: 'v1',
};

seajs.config({
    base: "/",
    alias: {
        'jquery': 'libs/jquery/jquery-2.1.4.min.js',
        'jqueryCheckALL': 'libs/jquery/jquery-checkall.js',
        'angular': 'libs/angular/angular.min.js',
        'layer': 'libs/layer/layer.js',
        'layerCss': 'libs/layer/skin/layer.css',
        'bootstrap': 'libs/bootstrap/js/bootstrap.min.js',
        'toaster': 'libs/jquery/jquery.toaster.js',
        'go': 'libs/go.min.js',
        'webuploader': 'libs/webuploader/webuploader.js',
        'vue': 'libs/vue-2.0/vue.min.js',
        'swiperJQ': 'libs/swiper-3.4.1/js/swiper.jquery.min.js',
        'fullPageJs': 'libs/jquery-fullpage/jquery.fullpage.js',
        'fullPageCss': 'libs/jquery-fullpage/jquery.fullpage.css',
        'swiper': 'libs/swiper-3.4.1/js/swiper.js',
        'swiperCss': 'libs/swiper-3.4.1/css/swiper.min.css',
        'amazeui': 'admin/v1/js/amazeui.min.js',
        'wangEditorCss': 'libs/wangEditor/css/wangEditor.min.css',
        'wangEditorJs': 'libs/wangEditor/js/wangEditor.js',
    },
    preload: ['jquery'],
    debug: true,
    charset: 'utf-8'
});
