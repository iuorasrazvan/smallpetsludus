/**
 * Forgot Password Popup
 * @constructor
 */
function hgLoginPopupForgotPass(wrap){
    var _this = this;
    _this.wrap = wrap;
    _this.authorizing = false;


    _this.initPopup = function(){
        jQuery.when( _this.loadPopup() ).done(function() {
            setTimeout(function(){
                _this.container = jQuery("#hg_login_primary_form_popup");
                _this.formBody = jQuery(".hg-login-modal-body");
                _this.closeBtn = jQuery(".hg-login-modal-close");
                _this.submitBtn = jQuery("#hg-login-button-forgotpass-submit");
                _this.loginInput = jQuery("#hg-login-modal-user-login");
                _this.recaptcha = _this.container.find(".hg-login-modal-recaptcha");
                _this.enableRecaptcha = _this.recaptcha.length;
                _this.formBody.off("keypress");
                _this.formBody.on("keypress",_this.keypressForm);
                _this.closeBtn.off("click");
                _this.closeBtn.on("click",_this.closePopup);
                _this.submitBtn.off("click");
                _this.submitBtn.on("click",_this.submitForm);
                if(_this.enableRecaptcha){
                    _this.recpatchaWidgetID = grecaptcha.render(_this.recaptcha[0],
                        {
                            "sitekey": _this.recaptcha.data('sitekey'),
                            "theme": _this.recaptcha.data('theme')
                        }
                    );
                }
            },0);
        });
    };


    _this.loadPopup = function(){
        var dfd = jQuery.Deferred();
        jQuery.ajax({
            url : hgLoginPopupL10n.ajax_admin,
            method: 'post',
            dataType :'json',
            data : { action : 'hg_forgotpass_popup_ajax' },
            beforeSend : function(){
                var container = jQuery("#hg_login_primary_form_popup");
                if( container.length){
                    _this.wrap = container.parent();
                    container.remove();
                }
                jQuery("body").addClass("hg-login-popup-open");

                _this.wrap.append(
                    '<div id="hg_login_primary_form_popup">' +
                    '<div class="hg-login-modal hg-login-modal-overlay hg-login-modal-forgotpass">' +
                    '<div class="hg-login-modal-fit center-center hg-login-layout">'+
                    '<div class="hg-login-modal-container">' +
                    '<div class="hg-login-popup-spinner-flex">' +
                    '<div class="hg-login-popup-spinner">' +
                    '<div class="hg-login-popup-spin hg-spinner-1"></div>' +
                    '<div class="hg-login-popup-spin hg-spinner-2"></div>' +
                    '<div class="hg-login-popup-spin hg-spinner-3"></div>' +
                    '<div class="hg-login-popup-spin hg-spinner-4"></div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' );

            }
        }).done(function(response){
            _this.wrap.find('.hg-login-modal-container').html( response.return );

            setTimeout(function(){
                dfd.resolve();
            },0);
        }).fail(function(error){
            console.log(error);
        });
        return dfd.promise();
    };

    _this.submitForm = function(){
        if(_this.authorizing) return false;

        if( _this.container.find("input.invalid").length ) return false;

        var login = _this.loginInput.val(),
            valid = true;
        if( login == "" ){
            hg_login.showPopupInfo('-error',hgForgotPassPopupL10n.fillLogin);
            valid = false;
        }

        if(_this.enableRecaptcha){
            var recaptcha_response =grecaptcha.getResponse(_this.recpatchaWidgetID);
            if(recaptcha_response == ''){
                hg_login.showPopupInfo( '-error', hgLoginPopupL10n.recaptchaErrorMsg );
                valid = false;
            }
        }

        if(!valid){
            return false;
        }

        _this.doSend(login);
        return false;
    };

    _this.doSend = function(login){
        var data = {
            action : 'hg_login_do_send_forgotpassword_email',
            login: login,
            nonce: hgForgotPassPopupL10n.nonce
        };

        if( _this.enableRecaptcha ){
            data.recaptchaResponse = grecaptcha.getResponse(_this.recpatchaWidgetID);
        }

        jQuery.ajax({
            url: hgForgotPassPopupL10n.ajax_admin,
            method: 'post',
            dataType :'json',
            data : data,
            beforeSend : function(){
                _this.submitBtnOriginal = _this.submitBtn.find(".button--content").text();
                _this.submitBtn.find(".button--content").text("Authorizing");
                _this.authorizing = true;
            }
        }).done(function(response){
            if(typeof response.successMsg !== 'undefined' && response.successMsg){
                _this.submitBtn.find(".button--content").text(_this.submitBtnOriginal);
                _this.authorizing = false;
                _this.closePopup();
                hg_login.showPopupInfo( '-success', response.successMsg );
            }else{
                _this.submitBtn.find(".button--content").text(_this.submitBtnOriginal);
                _this.authorizing = false;

                if( typeof response.errorMsg != "undefined" && response.errorMsg ){
                    hg_login.showPopupInfo( '-error', response.errorMsg );
                }

                if( _this.enableRecaptcha ){
                    grecaptcha.reset(_this.recpatchaWidgetID);
                }

            }
        }).fail(function(error){
            console.log(error);
        });
    };

    _this.closePopup = function(){
        _this.container.remove();
        jQuery("body").removeClass("hg-login-popup-open");
        return false;
    };

    _this.keypressForm = function(e){
        var code = e.keyCode || e.which;
        if( code == 13 ) {
            _this.submitBtn.click();
        }
    };

    _this.init = function(){
        _this.initPopup();
    };

    _this.init();
}
