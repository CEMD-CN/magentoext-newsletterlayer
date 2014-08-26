newsletterLayer = Class.create();

newsletterLayer.prototype = {
    initialize: function(params) {
        this.nlForm = $(params.formId);
        this.actions = $(params.actionsId);
        this.url = params.url;
        this.indicator = params.indicator;
        this.button = $(params.actionsId).innerHTML;
        this.canSetCookie = params.canSetCookie;
        this.cookieLifetime = params.cookieLifetime;
        
        this.setStyle($(params.contentWrapperId));
        var form = new VarienForm(this.nlForm, true);
    },

    setStyle: function(blockId){
        if(blockId.getAttribute('style').indexOf('width:') == -1) {
            blockId.style.width = blockId.clientWidth + 'px';
            blockId.style.display = 'block';
        }
    },

    validate: function(inputVal){
        if(Validation.get('validate-email').test(inputVal) == false || inputVal == '') {
            return;
        } else {
            this.subscribe();
        }
    },

    subscribe: function(field) {
        var actions = this.actions;
        var button = this.button;

        this.nlForm.action = this.url;
        this.nlForm.request({
            method: 'post',
            onLoading: this.showLoader(),
            onComplete: function(transport) {
                if(transport.status == 200)     {
                    var data = transport.responseText.evalJSON();
                    actions.update(button+'<ul class="messages"><li class="'+data.class+'"><ul><li><span>'+data.message+'</span></li></ul></li></ul>');
                    this.button.disabled = false;
                }
            }
        });
    },

    showLoader: function() {
        this.actions.down('span').update('<span><img src="'+this.indicator+'" alt="" /></span>');
        this.actions.down('button').disable();
    },

    closeLayer: function(layer) {
        new Effect.Fade(layer, { duration: 0.3 });
        this.setCookie();
    },

    setCookie: function() {
        if(this.canSetCookie) {
            var cookieLifetime = new Date(new Date().getTime() + (this.cookieLifetime*24*60*60*1000));
            Mage.Cookies.set('newsletterLayer', '1', cookieLifetime);
        }
    }
}