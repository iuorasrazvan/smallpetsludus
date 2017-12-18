/**
 * Signup Popup
 * @constructor
 */
function hgLoginPopupSignup( wrap ){
    var _this = this;
    _this.wrap = wrap;


    _this.authorizing = false;


    _this.initPopup = function(){
        jQuery.when( _this.loadPopup() ).done(function() {
            setTimeout(function(){
                _this.container = jQuery("#hg_login_primary_form_popup");
                _this.formBody = jQuery(".hg-login-modal-body");
                _this.closeBtn = jQuery(".hg-login-modal-close");
                _this.submitBtn = jQuery("#hg-login-button-signup-submit");
                _this.loginInput = jQuery("#hg-login-modal-user-login");
                _this.emailInput = jQuery("#hg-login-modal-user-email");
                _this.passInput = jQuery("#hg-login-modal-user-pass");
                _this.passStrength = 0;
                _this.termsInput = jQuery("#hg-login-modal-terms");
                _this.newsletterInput = jQuery("#hg-login-modal-newsletter");
                _this.recaptcha = _this.container.find(".hg-login-modal-recaptcha");
                _this.googleBtn = _this.container.find('.hg-login-google-button');
                _this.googleEnabled = _this.googleBtn.length;
                _this.enableRecaptcha = _this.recaptcha.length;
                _this.acceptWeakPass = jQuery("#hg-login-signup-accept-weak-password").val() === "yes";
                _this.closeBtn.off("click");
                _this.closeBtn.on("click",_this.closePopup);
                _this.formBody.off("keypress");
                _this.formBody.on("keypress",_this.keypressForm);
                _this.submitBtn.off("click");
                _this.submitBtn.on("click",_this.submitForm);
                _this.container.find("input").on("keyup",_this.checkInvalidKeyPress);
                _this.container.find("input").on("blur",_this.checkInvalidBlur);
                _this.container.find("input[type=checkbox]").on("change",_this.checkRequiredCheckbox);
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
                            client_id: hgSignupPopupL10n.googleClientID,
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
                    url: hgSignupPopupL10n.ajax_admin,
                    method: 'post',
                    dataType: 'json',
                    data: {action: 'hg_g_signin_ajax', gData: gData,nonceName:"hg_signup", nonce: hgSignupPopupL10n.nonce},
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
            url : hgSignupPopupL10n.ajax_admin,
            method: 'post',
            dataType :'json',
            data : { action : 'hg_signup_popup_ajax', nonce: hgSignupPopupL10n.nonce },
            beforeSend : function(){
                var container = jQuery("#hg_login_primary_form_popup");
                if( container.length){
                    _this.wrap = container.parent();
                    container.remove();
                }
                jQuery("body").addClass("hg-login-popup-open");

                _this.wrap.append(
                    '<div id="hg_login_primary_form_popup">' +
                    '<div class="hg-login-modal hg-login-modal-overlay hg-login-modal-signup">' +
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

            _this.wrap.find('.hg-login-modal-container').html(response.return);

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

        var valid = true,
            pass = _this.passInput.val(),
            login = _this.loginInput.val(),
            email = _this.emailInput.val(),
            newsletter = false;

        _this.checkValidLogin(login);

        if( _this.newsletterInput.length ){
            newsletter = _this.newsletterInput.is(":checked");
        }

        if( pass == "" ){
            _this.isInvalid( _this.passInput, hgSignupPopupL10n.requiredField );
            valid = false;
        }

        if( !_this.checkValidLoginFormat( login ) ){
            _this.isInvalid(_this.loginInput, hgSignupPopupL10n.onlyLatinAndNumbers );
            valid = false;
        }


        if( !_this.checkValidEmail( email ) ){
            _this.isInvalid( _this.emailInput, hgSignupPopupL10n.invalidEmail );
            valid = false;
        }

        if( _this.termsInput.length ){
            if( !_this.termsInput.is(":checked") ){
                _this.isInvalid( _this.termsInput, hgSignupPopupL10n.requiredField );
                valid = false;
            }
        }

        if( _this.container.find("input.invalid").length ) valid = false;

        /**
         * check password against whitespaces
         */
        var invalidPass = /\s/g.test(pass );

        if( invalidPass ){
            _this.isInvalid( _this.passInput, hgSignupPopupL10n.noWhiteSpaces );
            valid = false;
        }else if( _this.passStrength <= 2 && !_this.acceptWeakPass ){
            /**
             * Password weakness
             */
            hg_login.showPopupInfo('-error', hgSignupPopupL10n.passTooWeak);
            valid = false;
        }else if( pass.length < hgSignupPopupL10n.passwordMinLength ){
            /**
             * Check against minimum allowed length
             */
            _this.isInvalid( _this.passInput, hgSignupPopupL10n.minSymbols );
            valid = false;
        }else if( pass.length > hgSignupPopupL10n.passwordMaxLength ){
            /**
             * Check against maximum allowed length
             */
            _this.isInvalid( _this.passInput, hgSignupPopupL10n.maxSymbols );
            valid = false;
        }

        if(_this.enableRecaptcha){
            var recaptcha_response = grecaptcha.getResponse(_this.recpatchaWidgetID);
            if(recaptcha_response == ''){
                hg_login.showPopupInfo( '-error', hgSignupPopupL10n.recaptchaErrorMsg );
                valid = false;
            }
        }

        if(!valid){
            return false;
        }

        _this.doSignup(login,email,pass,newsletter);
        return false;
    };


    _this.doSignup = function( login,email,pass,newsletter){
        var data = {
            action : 'hg_login_do_signup',
            user_email: email,
            user_login: login,
            user_pass: pass,
            user_newsletter : newsletter,
            nonce: hgSignupPopupL10n.nonce
        };

        if( _this.enableRecaptcha ){
            data.recaptchaResponse = grecaptcha.getResponse(_this.recpatchaWidgetID);
        }

        jQuery.ajax({
            url: hgSignupPopupL10n.ajax_admin,
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
                _this.submitBtn.find(".button--content").text(_this.submitBtnOriginal);
                _this.authorizing = false;
                if(response.verify_required){
                    hg_login.showPopupInfo( '-success', response.verify_msg );
                    _this.closePopup();
                }else{
                    hg_login.doRedirect( response.redirect_to );
                }

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


    _this.checkInvalidKeyPress = function(){
        if(jQuery(this).hasClass("invalid") && jQuery(this).val() != ""){
            _this.isValid( jQuery(this) );
            jQuery(this).removeClass("invalid");
        }

        var valid = true,
            validMsg = hgSignupPopupL10n.requiredField;
        if( jQuery(this)[0].hasAttribute("data-type") ){
            switch( jQuery(this).data('type') ){
                case 'password':
                    _this.checkPasswordStrength( jQuery(this) );
                    break;
                default :
                    valid = true;
                    break;
            }
        }

    };

    _this.checkPasswordStrength = function( el ){
        var pass1 = el.val(),strength;

        el.removeClass( 'pass-bad pass-good pass-strong pass-mismatch pass-short' );

        strength = wp.passwordStrength.meter( pass1, wp.passwordStrength.userInputBlacklist(), pass1 );

        switch ( strength ) {
            case 2:
                _this.isInvalid(el, pwsL10n.bad, 'pass-bad');
                break;
            case 3:
                _this.isInvalid(el, pwsL10n.good, 'pass-good');
                break;
            case 4:
                _this.isInvalid(el, pwsL10n.strong, 'pass-strong');
                break;
            case 5:
                _this.isInvalid(el, pwsL10n.mismatch, 'pass-mismatch');
                break;
            default:
                _this.isInvalid(el, pwsL10n.short, 'pass-short');
        }

        _this.passStrength = strength;

    };

    _this.checkInvalidBlur = function( event ){
        if(jQuery(this).hasClass("invalid") && jQuery(this).val() != ""){
            _this.isValid(jQuery(this));
            jQuery(this).removeClass("invalid");
        }

        var valid = true,
            validMsg = hgSignupPopupL10n.requiredField,
            val = jQuery(this).val();
        if( jQuery(this)[0].hasAttribute("data-type") ){
            switch( jQuery(this).data('type') ){
                case 'email':
                    valid = _this.checkValidEmail( val );
                    validMsg = hgSignupPopupL10n.invalidEmail;
                    _this.checkUniqueEmail( val );

                    break;
                case 'login':
                    valid = true;
                    _this.checkValidLogin( val );
                    _this.checkValidLoginFormatBlur( val );
                    break;
                default :
                    valid = true;
                    break;
            }
        }else if( typeof event.target.checkValidity == 'function' ){
            valid = event.target.checkValidity();
        }else if( jQuery(this).is(":required") ){
            valid = jQuery(this).val() == "";
        }else{
            valid = true;
        }

        if( !valid ){
            _this.isInvalid( jQuery(this), validMsg );
        }
    };

    _this.isValid = function( el ){
        el.removeClass('invalid');
        if(el.parent().find("sup").length){
            el.parent().find("sup").remove();
        }
    };

    _this.isInvalid = function( el, message, html_class ){

        html_class = typeof html_class !== 'undefined' ? html_class : 'invalid';

        el.addClass(html_class);

        message = typeof message !== 'undefined' ? message : '';



        if( message == '' ) return false;

        if( el.parent().find("sup").length ){
            el.parent().find("sup").remove();
        }

        el.siblings("label").append("<sup class='" + html_class + "'> &#42; "+message+"</sup>");
    };

    _this.checkValidLoginFormatBlur = function(login){
        if( !_this.checkValidLoginFormat(login) ){
            _this.isInvalid(_this.loginInput, hgSignupPopupL10n.onlyLatinAndNumbers );
        }
    };

    _this.checkValidLoginFormat = function (login) {
        var r = /^\w+$/g.test(login);

        return r;

    };

    _this.checkValidLogin = function( login ){
        if( typeof login != 'string' || login == '' ){
            _this.isInvalid(_this.loginInput,hgSignupPopupL10n.requiredField);
            return false;
        }

        jQuery.ajax({
            url: hgSignupPopupL10n.ajax_admin,
            method: 'post',
            dataType :'json',
            data : { action: 'hg_login_check_unqiue_login',login:login,nonce:hgSignupPopupL10n.nonce },
            beforeSend: function(){

            }
        }).done(function(response){
            if(response.error){
                _this.isInvalid(_this.loginInput, hgSignupPopupL10n.usernameInUse );
                return false;
            }
        }).fail(function(error){

        });
        return false;
    };

    _this.checkValidEmail = function( val ){
        if( val == "" ){
            return false;
        }

        var atpos = val.indexOf("@"),
            dotpos = val.lastIndexOf(".");

        return !(atpos<1 || dotpos<atpos+2 || dotpos+2>=val.length);
    };

    _this.checkUniqueEmail = function( email ){
        if( typeof email != 'string' || email == '' ){
            _this.isInvalid(_this.emailInput,hgSignupPopupL10n.requiredField);
            return false;
        }

        jQuery.ajax({
            url: hgSignupPopupL10n.ajax_admin,
            method: 'post',
            dataType :'json',
            data : { action: 'hg_login_check_unqiue_email',email:email,nonce:hgSignupPopupL10n.nonce },
            beforeSend: function(){

            }
        }).done(function(response){
            if(response.error){
                _this.isInvalid(_this.emailInput,hgSignupPopupL10n.emailInUse);
                return false;
            }
        });
        return false;
    };

    _this.checkRequiredCheckbox = function(){
        if( jQuery(this).is(":checked") ){
            _this.isValid(jQuery(this));
        }else if(jQuery(this).is(":required")){
            _this.isInvalid(jQuery(this),hgSignupPopupL10n.requiredField);
        }
    };

    _this.showInlineNotice = function( msg ){

        if( typeof msg == 'undefined' || msg == '' ){
            return false;
        }

        this.container.find(".hg-login-modal-body").html("<p class='hg_login_signup_form_inline_notice'>" + msg + "</p>");
        return true;
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


    _this.init = function(){
        _this.initPopup();
    };


    _this.init();
}