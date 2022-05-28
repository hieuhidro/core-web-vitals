define([
    'jquery',
], function ($) {
    return function (reCaptcha) {
        return reCaptcha.extend({
            _loadApi: function () {
                this.isInitAPI = false;
                if (window.deferRecaptcha) {
                    var captchaContainer = document.getElementById(this.getReCaptchaId() + '-container');
                    if (captchaContainer) {
                        var superFunction = this._super;
                        var captchaForm = document.getElementById(this.getReCaptchaId() + '-container').closest('form');
                        if (captchaForm) {
                            captchaForm.addEventListener('element-intersection', function () {
                                if (!this.isInitAPI) {
                                    this.isInitAPI = true;
                                    superFunction.apply(this);
                                }
                            }.bind(this));
                            IntersectionElement(captchaForm);
                        }
                    } else {
                        // this._super(); Do nothing because have no form.
                    }
                } else {
                    this._super();
                }
                return this;
            }
        });
    }
});
