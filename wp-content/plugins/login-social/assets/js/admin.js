jQuery(document).ready(function(){
    jQuery(".close_free_banner").on("click",function(){
        jQuery(".free_version_banner").css("display","none");
        hgLoginSetCookie( 'HgLoginBannerShow', 'no', {expires:86400} );
    });

    var acc_menu_settings = jQuery( '.hg_login_acc_menu_settings' );

    if( acc_menu_settings.length ){
        var sortableOptions = {
            handle: ".hg_login_drag"
        };

        acc_menu_settings.sortable(sortableOptions);

        acc_menu_settings.on("click",".hg_login_del_acc_item",function(){
            jQuery(this).parent().remove();
        });

        jQuery('.hg_login_add_acc_menu').on("click",function(){
            jQuery.ajax({
                url : hgLoginAdminL10n.ajax_admin,
                method: 'post',
                dataType :'json',
                data : { action : 'hg_login_get_acc_menu_item', nonce: hgLoginAdminL10n.nonce },
                beforeSend : function(){}
            }).done(function(response){
                if( response.success ){
                    jQuery( '.hg_login_acc_menu_settings' ).prepend( response.html );
                    if( typeof window.wpdevSetttings !== 'undefined' ){
                        window.wpdevSetttings.masonry.masonry();
                    }
                }
            }).fail(function(error){
                console.log(error);
            });

            return false;
        });
    }
});

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

function hgLoginGetCookie (name) {
    var cookie, allcookie = document.cookie.split(';');
    for (var i = 0; i < allcookie.length; i++) {
        cookie = allcookie[i].split('=');
        cookie[0] = cookie[0].replace(/ +/g,'');
        if (cookie[0] == name) {
            return decodeURIComponent(cookie[1]);
        }
    }
    return false;
}
