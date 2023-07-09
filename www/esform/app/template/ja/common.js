window.onload = function() {
	if (document.forms.length > 0) {
		focusFirstElement(document.forms[0]);
	}
};
function focusFirstElement(form)
{
	var elems = form.elements;
	for (var i = 0, l = elems.length; i < l; ++i) {
		var el = elems[i];
		var name = el.tagName;
		if (name == 'INPUT' && el.value == '') {
			if (el.type == 'text' || el.type == 'password') {
				el.focus();
				return el;
			}
		} else if (name == 'SELECT' && el.value == '') {
			el.focus();
			return el;
		} else if (name == 'TEXTAREA') {
			el.focus();
			return el;
		}
	}
	return null;
}
function toggle(id)
{
	var el = document.getElementById(id);
	el.style.display = el.style.display == 'none' ? 'block' : 'none';
}
function paste(id, value)
{
	var el = document.getElementById(id);
	el.value = value;
	el.focus();
}
function paste_textarea(id, value)
{
	var el = document.getElementById(id);
	var val = el.value;
	val = val.replace(/\s+$/, '');
	val += '\n\n' + value + '\n';
	el.value = val;
	// IE
	el.scrollTop = 0;
	el.scrollTop = el.scrollHeight - el.clientHeight;
	el.focus();
}
