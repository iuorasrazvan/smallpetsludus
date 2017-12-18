var hgLoginPopupInfoTimeout;

hg_login = {

    doLogout : function(){
        jQuery.ajax({
            url: hgLoginMainL10n.ajax_admin,
            method: 'post',
            dataType :'json',
            data : { action: 'hg_login_do_logout',nonce:hgLoginMainL10n.logoutNonce }
        }).done(function(response){
            if(response.success){
                hg_login.doRedirect( response.redirect_to );
            }else if( typeof response.errorMsg != "undefined" && response.errorMsg ){
                hg_login.showPopupInfo( '-error', response.errorMsg );
            }
        }).fail(function(error){
            console.log(error);
        });
    },

    doRedirect: function(redirect_to){
        redirect_to = typeof redirect_to !== 'undefined' ? redirect_to : '';

        if(redirect_to && redirect_to!=''){
            window.location.href = redirect_to;
        }else{
            location.reload();
        }
    },

    showPopupInfo : function( type, msg ){
        type = type !== 'undefined' ? type : '-notice';

        msg = msg !== 'undefined' ? msg : '';

        jQuery.when(hg_login.closePopupInfo()).done(function(){
            jQuery("body").append('<div id="hg_login_popup_info_temp" class="'+ type +'" ><p>'+ msg +'</p></div>');
            hgLoginPopupInfoTimeout = setTimeout(function(){
                hg_login.closePopupInfo();
            },5000);
            jQuery("#hg_login_popup_info_temp").on("click",function(){
                hg_login.closePopupInfo();
            });
        });
    },

    closePopupInfo : function(){
        var dfd = jQuery.Deferred();
        clearTimeout(hgLoginPopupInfoTimeout);
        if( jQuery("#hg_login_popup_info_temp").length ){
            jQuery("#hg_login_popup_info_temp").addClass("close--popup");
            var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
            jQuery("#hg_login_popup_info_temp").one( animationEnd,function(){
                jQuery(this).remove();
                dfd.resolve();
            } );
        }else{
            dfd.resolve();
        }

        return dfd.promise();
    },

    getLinkParams: function(link) {
        var query_string = {};

        var regexp = /\?([\s\S]*)/g;

        var search_query = link.match(regexp);

        if (typeof search_query === "object") {
            var query = search_query[0];

            var vars = query.split("&");

            for (var i = 0; i < vars.length; i++) {

                var pair = vars[i].split("=");
                pair[0] = pair[0].replace("?","");
                // If first entry with this name
                if (typeof query_string[pair[0]] === "undefined") {

                    query_string[pair[0]] = decodeURIComponent(pair[1]);
                    // If second entry with this name

                } else if (typeof query_string[pair[0]] === "string") {

                    var arr = [query_string[pair[0]], decodeURIComponent(pair[1])];

                    query_string[pair[0]] = arr;
                    // If third or later entry with this name

                } else {
                    query_string[pair[0]].push(decodeURIComponent(pair[1]));
                }
            }
        }

        return query_string;
    }

};

jQuery("document").ready(function(){
    var actionCookie = hgLoginGetCookie( 'hg_login_action' ),
        body = jQuery('body');
    if( typeof actionCookie !== 'undefined' ){
        hgLoginDeleteCookie('hg_login_action');

        switch(actionCookie){
            case "user_activated":

                hg_login.showPopupInfo( '-success', hgLoginMainL10n.userActivated );

                break;
            case "expiredkey":

                hg_login.showPopupInfo( '-error', hgLoginMainL10n.expiredKey );

                break;
            case "invalidkey":

                hg_login.showPopupInfo( '-error', hgLoginMainL10n.invalidKey );

                break;
            case "do_reset_pass":

                var login = hgLoginGetCookie( 'hg_login_user' );

                if(typeof login !== 'undefined'){

                    new hgLoginPopupResetPassword(login, body);
                }

                break;
            case 'email_activation_link_sent':

                hg_login.showPopupInfo( '-success', hgLoginMainL10n.activationLinkSent );

                break;
            case 'fb_no_email':

                hg_login.showPopupInfo( '-error', hgLoginMainL10n.fbNoEmail );

                break;
            case 'user_exists':

                hg_login.showPopupInfo( '-error', hgLoginMainL10n.userExists );

                break;
            case 'email_exists':

                hg_login.showPopupInfo( '-error', hgLoginMainL10n.emailExists );

                break;
            case 'login_failed':

                hg_login.showPopupInfo( '-error', hgLoginMainL10n.loginFailed );

                break;
            case 'latin_letters':

                hg_login.showPopupInfo( '-error', hgLoginMainL10n.latinLetters );

                break;
            case 'need_refresh':

                location.reload();

                break;
            case 'open_login_popup':

                new hgLoginPopupLogin(body);

                break;
            case 'open_forgotpass_popup':

                new hgLoginPopupForgotPass(body);

                break;
        }
    }

    /**
     * This is not a very good solution, but jQuery does not give us any chance to do such things in more optimized way,
     * so we shall wait until custom elements work on all browsers and start working with prototypes
     */
    body.on("click",".hg_login_open_signup_button",function(e){
       if(e.button == 0){
           new hgLoginPopupSignup( jQuery(this).parent() );
       }
   });

    body.on("click",".hg_login_open_login_button",function(e){
        if(e.button == 0){
            new hgLoginPopupLogin( jQuery(this).parent() );
        }
    });

    body.on("click",".hg-login-button-logout",function(){
        hg_login.doLogout();
        return false;
    });
});

/* Cookies */
function hgLoginGetCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function hgLoginSetCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }


    if(typeof value == "object"){
        value = JSON.stringify(value);
    }
    value = encodeURIComponent(value);
    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}

function hgLoginDeleteCookie(name) {
    var cookieVal = hgLoginGetCookie( name );
    hgLoginSetCookie(name, cookieVal, {
        expires: -1
    })
}
