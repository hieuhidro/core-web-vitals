define([
    'jquery',
    'ko'
], function ($, ko) {
    return function (reCaptcha) {
        return reCaptcha.extend({
            _loadApi: function(){
                this.isInitAPI = false;
                if(window.deferRecaptcha) {
                    var captchaContainer = document.getElementById(this.reCaptchaId + '-container');
                    if(captchaContainer) {
                        var superFunction = this._super;
                        var captchaForm = document.getElementById(this.reCaptchaId + '-container').closest('form');
                        var initAPI = function () {
                            if (!this.isInitAPI) {
                                console.log(this.reCaptchaId);
                                this.isInitAPI = true;
                                superFunction.apply(this);
                            }
                        };
                        if (captchaForm) {
                            captchaForm.addEventListener('element-intersection', initAPI.bind(this));
                            IntersectionElement(captchaForm);
                        }
                    }else{
                        // this._super(); Do nothing because have no form.
                    }
                }else{
                    this._super();
                }
                return this;
            }
        });
    }
});
