(function(root, $) {
	'use strict';

	var CopyUI = function() {};
	root.CopyUI = CopyUI;
	// 模板
	CopyUI.Tpl = {
	  	'box': '<div class="CopyUIBox">' +
	  	            '<div class="copyBtn">复制</div>' +
	  	       '</div>'
	};
    // 默认配置
    CopyUI.Defaults = {
        value: '', //要复制的内容
        $parent: '', // 父容器
        swfPath: '/import/ZeroClipboard.swf',
        beforeCopy: function() {}, // 复制前的操作
        afterCopy: function() {} // 复制成功后的操作
    };
    // 检测依赖的第三方库是否引入
    CopyUI.check = function() {
        var bool = true;
        if(!window.jQuery) {
            throw '请引入jQuery插件';
            bool = false;
        }
        if(!window.jQuery.fn.zclip) {
            throw '请引入jQuery.zclip.js插件';
            bool = false;
        }
        return bool;
    };
	// 每个实例标记
	CopyUI.prototype._mark = '';
	// 当前 $Dom
	CopyUI.prototype.$Dom = '';
	// 设置复制的值
	CopyUI.prototype.setValue = function(value) {
		this.Defaults.value = value;
		return this;
	};
	// 设置实例标记
	CopyUI.prototype._setMark = function(mark) {
		var marks = mark || "CopyUI" + Math.floor(Math.random()*1000000);
		this._mark = marks;
		return this;
	};
	// 获取实例标记
	CopyUI.prototype._getMark = function() {
		return this._mark;
	};
	// 事件
	CopyUI.prototype._event = function() {
		var that = this;
        that.$Dom.find('.copyBtn').zclip({
            path: that.Defaults.swfPath,
            copy: function(){
                return that.getValue();
            },
            beforeCopy:function(){/* 复制前的操作 */
                that.Defaults.beforeCopy();
            },
            afterCopy:function(){/* 复制成功后的操作 */
                that.Defaults.afterCopy();
            }
       });
	   return that;
	};
	// 初始化组件
	CopyUI.prototype.init = function(config) {
        CopyUI.check();
		this.Defaults = $.extend({},CopyUI.Defaults, config || {});
		this._setMark().render()._event()
		return this;
	};
    // 视图渲染
    CopyUI.prototype.render = function(config) {
        CopyUI.check();
    	var marks = this._getMark();
		if(!this.$Dom) {
		    this.$Dom = $(CopyUI.Tpl.box);
		    this.$Dom.addClass(marks);
		}
		if(this.Defaults.$parent) {
			if(!this.Defaults.$parent.find('.CopyUIBox').length) {
				this.Defaults.$parent.append(this.$Dom);
			}
		} else {
			if(!$('body').find('.' + marks).length) {
				$('body').append(this.$Dom);
			}
		}
		return this;
    };
    // 获取要复制的值
    CopyUI.prototype.getValue = function() {
    	return this.Defaults.value;
    };
    // 复制成功后调用方法
    CopyUI.prototype.after = function(callback) {
    	this.Defaults.afterCopy = callback || function() {};
    	return this;
    };
    // 复制前调用方法
    CopyUI.prototype.before = function(callback) {
    	this.Defaults.beforeCopy = callback || function() {};
        return this;
    };
    
    if(typeof define === "function" && define.amd) {
        define(function () {
            return CopyUI;
        });
    }
})(this, $);