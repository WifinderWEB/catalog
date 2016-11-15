var TranslateToken = {
    token: null,
    SetToken: function(token) {
        this.token = token;
    },
    GetToken: function() {
        return this.token;
    }
};

var TranslateCallback = {
    fields : {
        title : null,
        alias : null
    },
    Main : function(response){
        response = response.toLowerCase().replace(/\s/g, '-').replace(/<\/?[^>]+(>|$)/g, "");
        response = response.replace(/\,/g, "");
        response = response.replace(/\//g, "-");
        response = response.replace(/\./g, "-");
        
        if (TranslateCallback.fields.title.length > 0)
            TranslateCallback.fields.alias.val(response);
        else
            TranslateCallback.fields.alias.val(null);
    }
};

var TranslateModel = function() {
    var self = this;
    self.lang = {
        from: 'ru',
        to: 'en'
    };
    self.id = {
        title: null,
        alias: null,
        button : null
    };
    self.object = {
        title: null,
        alias: null,
        button : null
    };
    self.callback = null;
    self.Init = function(opt) {
        if(opt.fromFieldId)
            self.id.title = opt.fromFieldId;
        if(opt.toFieldId)
            self.id.alias = opt.toFieldId;
        if(opt.buttonId)
            self.id.button = opt.buttonId;
        if(opt.fromLang)
            self.lang.from = opt.fromLang; 
        if(opt.toLang)
            self.lang.to = opt.toLang;
        if(opt.callback)
            self.Set.Callback(opt.callback);
        else
            self.Set.Callback('TranslateCallback.Main');
    };
    self.Translate = {
        OnChange: function() {
            self.Get.Object.Title().bind('textchange', function() {
                self.Translate.OnRequest();
            });
        },
        OnClick : function(){
            self.Get.Object.Button().click(function(){
                self.Translate.OnRequest();
            });
        },
        OnRequest: function() {
            var title = self.Get.Value.Title();
            if (title.length > 0) {
                TranslateCallback.fields.title = self.Get.Object.Title();
                TranslateCallback.fields.alias = self.Get.Object.Alias();
                
                var s = document.createElement("script");
                s.src = "http://api.microsofttranslator.com/V2/Ajax.svc/Translate" +
                        "?appId=Bearer " + encodeURIComponent(TranslateToken.GetToken()) +
                        "&from=" + encodeURIComponent(self.lang.from) +
                        "&to=" + encodeURIComponent(self.lang.to) +
                        "&text=" + encodeURIComponent(title) +
                        "&oncomplete=TranslateCallback.Main";
                document.body.appendChild(s);
            }
            else {
                self.Set.Value.Alias(null);
            }
        }
    };
    self.Get = {
        Object : {
            Title: function() {
                if(!self.object.title)
                    self.object.title = $('#' + self.id.title);
                return self.object.title;
            },
            Alias: function() {
                if(!self.object.alias)
                    self.object.alias = $('#' + self.id.alias);
                return self.object.alias;
            },
            Button : function(){
                if(!self.object.button)
                    self.object.button = $('#' + self.id.button);
                return self.object.button;
            }
        },
        Value : {
            Title: function() {
                return self.Get.Object.Title().val();
            },
            Alias: function() {
                return self.Get.Object.Alias().val();
            }
        },
        Callback  : function(){
            return self.callback;
        }
    };
    self.Set = {
        Value : {
            Title: function(title) {
                self.Get.Object.Title().val(title);
            },
            Alias: function(alias) {
                self.Get.Object.Alias().val(alias);
            }
        },
        Callback : function(callback){
            self.callback = callback;
        }
    };
};


