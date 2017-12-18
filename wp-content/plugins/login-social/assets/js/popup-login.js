/**
 * Login Popup
 * @constructor
 */
function hgLoginPopupLogin( wrap ){
    var _this = this;
    _this.wrap = wrap;

    _this.authorizing = false;


    _this.initPopup = function(){
        jQuery.when( _this.loadPopup() ).done(function() {
            setTimeout(function(){
                _this.container = jQuery("#hg_login_primary_form_popup");
                _this.formBody = jQuery(".hg-login-modal-body");
                _this.closeBtn = jQuery(".hg-login-modal-close");
                _this.submitBtn = jQuery("#hg-login-button-login-submit");
                _this.forgotPassBtn = jQuery(".modal-auth--forgot-password");
                _this.loginInput = jQuery("#hg-login-modal-user-login");
                _this.passInput = jQuery("#hg-login-modal-user-pass");
                _this.rememberInput = jQuery("#hg-login-modal-rememberme");
                _this.recaptcha = _this.container.find(".hg-login-modal-recaptcha");
                _this.googleBtn = _this.container.find('.hg-login-google-button');
                _this.googleEnabled = _this.googleBtn.length;
                _this.enableRecaptcha = _this.recaptcha.length;
                _this.closeBtn.off("click");
                _this.closeBtn.on("click",_this.closePopup);
                _this.formBody.off("keypress");
                _this.formBody.on("keypress",_this.keypressForm);
                _this.submitBtn.off("click");
                _this.submitBtn.on("click",_this.submitForm);
                _this.forgotPassBtn.off("click");
                _this.forgotPassBtn.on("click",_this.openForgotPassModal);
                _this.container.find("input").on("keypress",_this.checkInvalidKeyPress);
                _this.container.find("input").on("blur",_this.checkInvalidBlur);
                if(_this.enableRecaptcha){
                    _this.recpatchaWidgetID = grecaptcha.render(_this.recaptcha[0],
                        {
                            "sitekey": _this.recaptcha.data('sitekey'),
                            "theme": _this.recaptcha.data('theme')
                        }
                    );
                }
                if(_this.googleEnabled){
                    gapi.load('auth2', function(){
                        // Retrieve the singleton for the GoogleAuth library and set up the client.
                        _this.auth2 = gapi.auth2.init({
                            client_id: hgLoginPopupL10n.googleClientID,
                            cookiepolicy: 'single_host_origin',
                            scope: 'profile email'
                        });
                        _this.attachGoogleSignin(_this.googleBtn[0]);
                    });
                }
            },0);
        });
    };
    _this.attachGoogleSignin = function(element){
        _this.auth2.attachClickHandler(element, {},
            function(googleUser) {
                var gData = {
                    "name": googleUser.getBasicProfile().getGivenName(),
                    "lastName": googleUser.getBasicProfile().getFamilyName(),
                    "email": googleUser.getBasicProfile().getEmail(),
                    "displayName": googleUser.getBasicProfile().getName()
                };

                jQuery.ajax({
                    url: hgLoginPopupL10n.ajax_admin,
                    method: 'post',
                    dataType: 'json',
                    data: {action: 'hg_g_signin_ajax', gData: gData,nonceName:"hg_login", nonce: hgLoginPopupL10n.nonce},
                    beforeSend: function () {
                        var container = jQuery("#hg_login_primary_form_popup");
                        if (container.length) {
                            _this.wrap = container.parent();
                            container.remove();
                        }
                        jQuery("body").addClass("hg-login-popup-open");

                        _this.wrap.append(
                            '<div id="hg_login_primary_form_popup">' +
                            '<div id="hg-login-modal-reset-pass" class="hg-login-modal hg-login-modal-overlay hg-login-modal-reset-pass">' +
                            '<div class="hg-login-modal-fit center-center hg-login-layout">' +
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
                            '</div>');
                    }
                }).done(function (response) {
                    window.location.replace(response.redirect);

                }).fail(function (error) {
                    window.location.replace(error.redirect);
                });
            }, function(error) {
                /*console.log(JSON.stringify(error, undefined, 2));*/
            });
    };

    _this.loadPopup = function(){
        var dfd = jQuery.Deferred();
        jQuery.ajax({
            url : hgLoginPopupL10n.ajax_admin,
            method: 'post',
            dataType :'json',
            data : { action : 'hg_login_popup_ajax', nonce: hgLoginPopupL10n.loadPopupNonce },
            beforeSend : function(){
                var container = jQuery("#hg_login_primary_form_popup");
                if( container.length){
                    _this.wrap = container.parent();
                    container.remove();
                }
                jQuery("body").addClass("hg-login-popup-open");

                _this.wrap.append(
                    '<div id="hg_login_primary_form_popup">' +
                        '<div class="hg-login-modal hg-login-modal-overlay hg-login-modal-signin">' +
                            '<div class="hg-login-modal-fit center-center hg-login-layout">' +
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
            if( jQuery("#hg_login_primary_form_popup").length ){
                jQuery("#hg_login_primary_form_popup").remove();
            }


            console.log(error);
        });
        return dfd.promise();
    };

    _this.submitForm = function(){
        if(_this.authorizing) return false;

        if( _this.container.find("input.invalid").length ) return false;

        var pass = _this.passInput.val(),
            login = _this.loginInput.val(),
            remember = _this.rememberInput.is(":checked"),
            valid = true;

        if( login == "" ){
            _this.invalidInput( _this.loginInput );
            valid = false;
        }

        if( pass == "" ){
            _this.invalidInput( _this.passInput );
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

        _this.doLogin(login, pass, remember);
        return false;
    };


    _this.doLogin = function( login, pass, remember ){
        var data = {
            action : 'hg_login_do_login',
            pass: pass,
            login: login,
            remember: remember,
            nonce: hgLoginPopupL10n.nonce
        };

        if( _this.enableRecaptcha ){
            data.recaptchaResponse = grecaptcha.getResponse(_this.recpatchaWidgetID);
        }

        jQuery.ajax({
            url: hgLoginPopupL10n.ajax_admin,
            method: 'post',
            dataType :'json',
            data : data,
            beforeSend : function(){
                _this.submitBtnOriginal = _this.submitBtn.find(".button--content").text();
                _this.submitBtn.find(".button--content").text("Authorizing");
                _this.authorizing = true;
            }
        }).done(function(response){
            if(response.success){
                _this.submitBtn.find(".button--content").text('Logging in');
                _this.authorizing = false;
                hg_login.doRedirect( response.redirect_to );
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

    _this.invalidInput = function(el){
        if(el.length){
            el.addClass('invalid');
        }
    };

    _this.checkInvalidKeyPress = function(){
        if(jQuery(this).hasClass("invalid") && jQuery(this).val() != ""){
            _this.isValid( jQuery(this) );
            jQuery(this).removeClass("invalid");
        }
    };



    _this.checkInvalidBlur = function(){
        if(jQuery(this).hasClass("invalid") && jQuery(this).val() != ""){
            _this.isValid(jQuery(this));
            jQuery(this).removeClass("invalid");
        }

        if(jQuery(this).is(":required") && jQuery(this).val() == ""){
            _this.isInvalid( jQuery(this), hgLoginPopupL10n.requiredField );
        }
    };

    _this.isValid = function( el ){
        el.removeClass('invalid');
        if(el.parent().find("sup").length){
            el.parent().find("sup").remove();
        }
    };

    _this.isInvalid = function(el, message){
        el.addClass("invalid");

        message = typeof message !== 'undefined' ? message : '';

        if( message == '' ) return false;

        if( !el.parent().find("sup").length ){
            el.siblings("label").append("<sup> * "+message+"</sup>");
        }
    };

    _this.focusLogin = function(){
        if (_this.loginInput.length) _this.loginInput.focus();
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

    _this.openForgotPassModal = function(){
        new hgLoginPopupForgotPass(_this.wrap);
    };


    _this.init = function(){
        _this.initPopup();
    };


    _this.init();
}