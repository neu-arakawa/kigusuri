Event.observe(window, 'load', function() {
	var elems = $A($('inputform').elements);
	elems.each(function(el) {
		if ('rows' in el) {
			el.onfocus = resizeTextarea;
		}
	});
});
function resizeTextarea()
{
	var min_rows = this.rows;
	var max_rows = min_rows * 3;
	this.onfocus = this.onkeyup = this.onmouseup = function() {
		var match = this.value.match(/\r\n?|\n/g);
		var lines = match == null ? 1 : match.length + 2;
		this.rows = Math.max(min_rows, Math.min(lines, max_rows));
	};
}
var Validator = Class.create();
Validator.register = function(id, list) {
	var form = $(id);
	if (form == null) return false;
	Form.focusFirstElement(form);
	form.onreset = function() {
		Element.scrollTo(this);
	};
	form.onsubmit = function() {
		var focused = false;
		list.each(function(def) {
			var el = $(def.id);
			if (el == null) return;
			if (!el.validate() && !focused) {
				focused = true;
				el.focus();
				Element.scrollTo(el.previousSibling);
			}
		});
		return !focused;
	};
	list.each(function(def) {
		var el = $(def.id);
		if (el == null) return;
		var class_name = def.type + '_Define';
		if (class_name in window) {
			var define = new window[class_name]();
			Object.extend(define, def);
		} else {
			var define = new Object();
			def = {};
		}
		define.el = el;
		el.def = def;
		el.define = define;
		el.validate = function() {
			Element.cleanWhitespace(this.parentNode);
			var err_el = this.previousSibling;
			var has_err = err_el && err_el.className == 'error';
			for (var mem in def) {
				var name = mem + '_check';
				if (typeof define[name] == 'function') {
					var result = define[name]();
					if (result === null) break;
					if (result === true) continue;
					var msg = define[mem + '_error'] + '。';
					msg = msg.replace('{form}', define.name);
					if (has_err) {
						err_el.innerHTML = msg;
					} else {
						err_el = document.createElement('em');
						err_el.className = 'error';
						err_el.innerHTML = msg;
						this.parentNode.insertBefore(err_el, this);
					}
					this.style.backgroundColor = '#FFE7D7';
					return false;
				}
			}
			if (has_err) {
				Element.remove(err_el);
				this.style.backgroundColor = '';
			}
			return true;
		};
		if (el.tagName == 'SELECT' || el.tagName == 'SPAN') {
			el.onchange = el.validate;
		} else {
			el.onblur = el.validate;
		}
	});
	return true;
};
function Define()
{
	this.required = false;
	this.required_error = '{form}を入力して下さい';
	this.required_check = function() {
		if (this.required) {
			return $F(this.el) != '';
		} else if ($F(this.el) == '') {
			return null;
		}
		return true;
	};
	this.min = 0;
	this.min_error = '';
	this.min_check = function() {
		var bool = $F(this.el).length >= this.min;
		if (!bool && this.min_error == '') {
			this.min_error = '{form}は' + this.min + '文字以上にして下さい';
		}
		return bool;
	};
	this.max = 2000;
	this.max_error = '';
	this.max_check = function() {
		var bool = $F(this.el).length <= this.max;
		if (!bool && this.max_error == '') {
			this.max_error = '{form}は' + this.max + '文字以内にして下さい';
		}
		return bool;
	};
}
function T_Define()
{
	this.regexp_list = {
		mailaddress:   /^([a-z\d_]|\-|\.|\+)+@(([a-z\d_]|\-)+\.)+[a-z]{2,6}$/i,
		mailaddress_r: /^([a-z\d_]|\-|\.|\+)+@(([a-z\d_]|\-)+\.)+[a-z]{2,6}$/i,
		url: /^(https?|ftp):\/\/.+/,
		alphabet: /^[a-z]+$/i,
		number: /^\d+$/,
		alphanum: /^[a-z\d]+$/i,
		integer: /^[1-9]\d*$/i,
		zipcode: /^\d{3}-\d{4}$/,
		zipcode_d: /^\d{7}$/,
		telnum: /^0[1-9]\d{0,3}-\d{1,4}-\d{4}$/,
		telnum_d: /^0[1-9]\d{8}$/,
		mobilenum: /^0[7-9]0-\d{4}-\d{4}$/,
		mobilenum_d: /^0[7-9]0\d{8}$/,
		katakana: /^[ア-ン　 ]+$/,
		hiragana: /^[あ-ん　 ]+$/
	};
	this.regexp = null;
	this.regexp_error = '{form}が正しくありません';
	this.regexp_check = function() {
		if (this.regexp in this.regexp_list) {
			return this.regexp_list[this.regexp].test($F(this.el));
		}
		return true;
	};
	this.repeat = false;
	this.repeat_error = '{form}が正しくありません';
	this.repeat_check = function() {
		var elems = $A(this.el.form.elements);
		var n = elems.indexOf(this.el);
		if (n > 0) {
			return elems[n - 1].value == this.el.value;
		}
		return false;
	};
}
function S_Define()
{
	this.required_error = '{form}を選択して下さい';
}
function R_Define()
{
	this.required_error = '{form}を選択して下さい';
	this.required_check = function() {
		if (this.required) {
			var nodes = this.el.childNodes;
			for (var name in nodes) {
				var node = nodes[name];
				if (node && node.tagName == 'LABEL' && node.firstChild.checked) {
					return true;
				}
			}
			return false;
		}
		return true;
	};
}
function C_Define()
{
	this.required_error = '{form}を選択して下さい';
	this.required_check = function() {
		if (this.required) {
			var nodes = this.el.childNodes;
			for (var name in nodes) {
				var node = nodes[name];
				if (node && node.tagName == 'LABEL' && node.firstChild.checked) {
					return true;
				}
			}
			return false;
		}
		return true;
	};
}
function F_Define()
{
	this.required_error = '{form}を選択して下さい';
}
T_Define.prototype = new Define();
R_Define.prototype = new Define();
S_Define.prototype = new Define();
C_Define.prototype = new Define();
F_Define.prototype = new Define();
