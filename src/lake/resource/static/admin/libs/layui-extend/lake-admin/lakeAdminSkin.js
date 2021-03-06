/*!
 * lakeAdminSkin.js v1.0.2
 * https://github.com/deatil/lake-admin
 * 
 * Apache License 2.0 © Deatil
 */
!(function(a){
    layui.define(['jquery', 'jqueryCookie'], function (exports) {
        var jquery = layui.$;
        
        exports('lakeAdminSkin', a(jquery));
    });
})(function($) {
    
    // 主题
    var lakeAdminSkin = {
        /* 设置主题 */
        changeTheme: function (theme) {
            try {
                this.removeTheme(self);
                (theme && self.layui) && self.layui.link(this.getThemeDir() + theme + this.getCssSuffix(), theme);
                var ifs = self.window.frames;
                for (var i = 0; i < ifs.length; i++) {
                    try {  // 可能会跨域
                        var tif = ifs[i];
                        this.removeTheme(tif);
                        if (theme && tif.layui) {
                            tif.layui.link(this.getThemeDir() + theme + this.getCssSuffix(), theme);
                        }
                        // iframe下还有iframe的情况
                        var sifs = tif.frames;
                        for (var j = 0; j < sifs.length; j++) {
                            try {  // 可能会跨域
                                var stif = sifs[j];
                                this.removeTheme(stif);
                                if (theme && stif.layui) {
                                    stif.layui.link(this.getThemeDir() + theme + this.getCssSuffix(), theme);
                                }
                            } catch (e) {
                            }

                        }
                    } catch (e) {
                    }
                }
            } catch (e) {
            }
        },
        
        /* 移除主题 */
        removeTheme: function (w) {
            if (!w) {
                w = window;
            }
            if (w.layui) {
                var themeId = 'layuicss-theme';
                w.layui.jquery('link[id^="' + themeId + '"]').remove();
            }
        },
        
        /* 获取主题文件后缀 */
        getCssSuffix: function () {
            var cssSuffix = '.css';
            if (layui.cache.version != undefined) {
                cssSuffix += '?v=';
                if (layui.cache.version == true) {
                    cssSuffix += new Date().getTime();
                } else {
                    cssSuffix += layui.cache.version;
                }
            }
            return cssSuffix;
        },
        
        /* 获取主题目录 */
        getThemeDir: function () {
            return layui.cache.base + 'lake-admin/theme/';
        },
        
        // 皮肤按钮
        listenSkinBtn: function() {
            var arr = $.cookie('lake-admin-skin');
            var skin = (arr != null) ? arr : "theme-black";
            
            if (skin) {
                $(".lake-admin-skin dd")
                    .removeClass("lake-admin-skin-active");
                $(".lake-admin-skin dd[data-skin="+skin+"]")
                    .addClass("lake-admin-skin-active");
            }
            
            return skin;
        },
        
        // 皮肤
        listenSkin: function() {
            var skin = this.listenSkinBtn();
            
            if (skin) {
                this.changeTheme(skin);
            }
        },
        
        listen: function() {
            var thiz = this;
            this.listenSkin();
            
            // 监听顶部右侧皮肤
            $(document).on('click', '.lake-admin-skin dd a', function (elem) {
                // 修改skin
                if ($(this).parent('dd').attr('data-skin')) {
                    var skin = $(this).parent('dd').attr('data-skin');
                    $.cookie("lake-admin-skin", "", {expires: -1});
                    $.cookie('lake-admin-skin', skin, {
                        expires: 10,
                        path: '/'
                    });
                    
                    thiz.listenSkin();
                }
            });
        }
    };
    
    return lakeAdminSkin;
});
