// 活动编辑页面用的一些公共函数
var activity_form_control_list = {};
function filter_select_control(obj)
{
	var text = obj.value;
	var default_text = obj.getAttribute('default');
	var obj_id = obj.getAttribute('for');
	var form_control = document.getElementById(obj_id);
	if (activity_form_control_list[obj_id] && (text.length < 1 || default_text == text))
	{	// 清空了搜索条件
		form_control.options.length = activity_form_control_list[obj_id].length;
		for (var i in activity_form_control_list[obj_id])
		{
			form_control.options[i] = activity_form_control_list[obj_id][i];
		}
	}
	if (!activity_form_control_list[obj_id])
	{
		var l = form_control.options.length;
		activity_form_control_list[obj_id] = new Array();
		for (var i = 0; i < l; i++)
		{
			activity_form_control_list[obj_id].push(form_control.options[i]);
		}
	}
	if (activity_form_control_list[obj_id] && text.length > 0)
	{
		form_control.options.length = 0;
		var l = activity_form_control_list[obj_id].length;
		for (var i = 0; i < l; i++)
		{
			var option = activity_form_control_list[obj_id][i];
			var str = option.text;
			if (str.toLowerCase().indexOf(text.toLowerCase()) > -1)
			{
				form_control.options.length++;
				form_control.options[form_control.options.length - 1] = option;
			}
		}
	}
}


