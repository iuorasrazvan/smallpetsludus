/**
 * Reset Password Popup
 * @constructor
 * @param login
 * @param wrap
 * @returns {boolean}
 */
function hgLoginPopupResetPassword(login, wrap){
    if(typeof login === 'undefined'){
        return false;
    }

    var _this = this;
    _this.wrap = wrap;
    _this.login = login;
    _this.passStrength = 0;

    _this.authorizing = false;


    _this.initPopup = function(){
        jQuery.when( _this.loadPopup() ).done(function() {
            setTimeout(function(){
                _this.container = jQuery("#hg_login_primary_form_popup");
                _this.formBody = jQuery(".hg-login-modal-body");
                _this.closeBtn = jQuery(".hg-login-modal-close");
                _this.submitBtn = jQuery("#hg-login-button-resetpass-submit");
                _this.pass1Input = jQuery("#hg-login-modal-user-pass1");
                _this.pass2Input = jQuery("#hg-login-modal-user-pass2");
                _this.acceptWeakPass = jQuery("#hg-login-resetpass-accept-weak-password").val() === "yes";
                _this.formBody.off("keypress");
                _this.formBody.on("keypress",_this.keypressForm);
                _this.closeBtn.off("click");
                _this.closeBtn.on("click",_this.closePopup);
                _this.pass1Input.off('keypress');
                _this.pass1Input.on('keypress',_this.checkPass1);
                _this.pass2Input.off('change');
                _this.pass2Input.on('change',_this.checkPass2);
                _this.submitBtn.off("click");
                _this.submitBtn.on("click",_this.submitForm);
            },0);
        });
    };


    _this.loadPopup = function(){
        var dfd = jQuery.Deferred();
        jQuery.ajax({
            url : hgResetPassPopupL10n.ajax_admin,
            method: 'post',
            dataType :'json',
            data : { action : 'hg_resetpass_popup_ajax' },
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

        if( _this.container.find("input.invalid").length ) return false;

        var pass1 = _this.pass1Input.val(),
            pass2 = _this.pass2Input.val(),
            valid = true;

        if( _this.pass1Input.val() !== _this.pass2Input.val() ){
            hg_login.showPopupInfo( '-error', hgResetPassPopupL10n.passwordsDoNotMatch );
            valid = false;
        }

        /**
         * check password against whitespaces
         */
        var invalidPass = /\s/g.test(pass );

        if( invalidPass ){
            _this.isInvalid( _this.passInput, hgResetPassPopupL10n.noWhiteSpaces );
            valid = false;
        }else if( _this.passStrength <= 2 && !_this.acceptWeakPass ){
            /**
             * Password weakness
             */
            hg_login.showPopupInfo('-error', hgResetPassPopupL10n.passTooWeak);
            valid = false;
        }else if( pass.length < hgResetPassPopupL10n.passwordMinLength ){
            /**
             * Check against minimum allowed length
             */
            _this.isInvalid( _this.passInput, hgResetPassPopupL10n.minSymbols );
            valid = false;
        }else if( pass.length > hgResetPassPopupL10n.passwordMaxLength ){
            /**
             * Check against maximum allowed length
             */
            _this.isInvalid( _this.passInput, hgResetPassPopupL10n.maxSymbols );
            valid = false;
        }

        if(!valid){
            return false;
        }

        _this.doReset(pass1,pass2);
        return false;
    };

    _this.doReset = function(pass1,pass2){
        var data = {
            action : 'hg_login_do_resettpassword',
            login: _this.login,
            pass1: pass1,
            pass2: pass2,
            nonce: hgResetPassPopupL10n.nonce
        };
        jQuery.ajax({
            url: hgResetPassPopupL10n.ajax_admin,
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

            }
        }).fail(function(error){
            console.log(error);
        });
    };

    _this.checkPass2 = function(){
        if( _this.pass1Input.val() !== _this.pass2Input.val() ){
            hg_login.showPopupInfo( '-error', hgResetPassPopupL10n.passwordsDoNotMatch );
        }
    };

    _this.checkPass1 = function(){
        _this.checkPasswordStrength( _this.pass1Input );
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

    _this.keypressForm = function(e){
        var code = e.keyCode || e.which;
        if( code == 13 ) {
            _this.submitBtn.click();
        }
    };

    _this.closePopup = function(){
        _this.container.remove();
        jQuery("body").removeClass("hg-login-popup-open");
        return false;
    };


    _this.init = function(){
        _this.initPopup();
    };

    _this.init();

}
