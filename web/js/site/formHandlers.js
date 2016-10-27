$(document).ready(function() {
	var feedbackForm = $('form#feedback'),
		formSuccess = $('#form-success'),
		formFailed = $('#form-fail'),
		fieldPhone = feedbackForm.find('#phone'),
		fieldName = feedbackForm.find('#name');
	
	feedbackForm.submit(function(event) {
		event.preventDefault();
		
		feedbackForm.find('input').each(function(index, item) {
			if (!item.checkValidity()) {
				$(item).prop("invalid", true);
				return false;
			}
		});
		
		$.ajax({
			method: 'POST',
			async: true,
			url: feedbackForm.prop('action'),
			data: feedbackForm.serialize(),
			beforeSend: function () {
				formSuccess.css('display', 'none');
				formFailed.css('display', 'none');
			}
		})
			.done(function(result) {
				if (result.success)
				{
					feedbackForm.flush();
					formSuccess.css("display", "block");
				}
				else
					formFailed.css("display", "block");
			})
			.fail(function() {
				formFailed.css("display", "block");
			});
		return false;
	});
	
	$.prototype.flush = function() {
		this.find("input").each(function(index, item) {
			switch (item.type) {
				case "submit":
					break;
				case "checkbox":
					item.checked = false;
					break;
				case "tel":
					numbers.splice(0, numbers.length);
					item.value = "";
					break;
				default:
					item.value = "";
					break;
			}
		});
		this.find('textarea').val("");
	};
	
	// $.prototype.serialize = function() {
	// 	var data = {};
	// 	this.find("input").each(function(index, item) {
	// 		if (item.type == "checkbox")
	// 			data[item.id] = item.checked;
	// 		else
	// 			data[item.id] = item.value;
	// 	});
	// 	data["message"] = this.find("textarea").val();
	// 	return JSON.stringify({data: data});
	// };
	
	/* Обработка поля phone */
	var numbers = [];
	
	fieldPhone.keydown(function(event) {
		if (event.key == "Tab" || event.keyCode == 9)
			return true;
		if (event.key == "Backspace" || event.keyCode == 8)
			numbers.splice(numbers.length-1, 1);
		var key = event.key || String.fromCharCode(event.keyCode || event.charCode);
		if (numbers.length < 10 && /^[0-9]$/i.test(key)) {
			numbers.push(key);
		} else if (numbers.length >= 10) {
			return false;
		}
	});
	
	fieldPhone.focusin(function() {
		if (fieldPhone.val().length == 0)
			fieldPhone.val("+7 (");
	});
	
	fieldPhone.focusout(function() {
		if (fieldPhone.val() == "+7 (")
			fieldPhone.val("");
	});
	
	var parsePhone = function(event) {
		var isBackspace = event.key == "Backspace" || event.keyCode == 8,
			autocompleteEvent = event.key == "Enter" || event.keyCode == 13 || event.type == "input";
		
		if (!isBackspace && autocompleteEvent) {
			var groups = /\+[7] \((\d{3})\) (\d{3})\-(\d{2})\-(\d{2})/.exec(fieldPhone.val());
			if (groups != null) {
				numbers.splice(0, numbers.length);
				for (var i = 1; i < groups.length; i++) {
					var group = groups[i].split("");
					for (var j = 0; j < group.length; j++)
						numbers.push(group[j]);
				}
			}
		}
		fieldPhone.val(format());
	};
	
	fieldPhone.keyup(parsePhone);
	fieldPhone.addEventListener("input", function(e) {parsePhone(e);});
	fieldPhone.addEventListener("touchend", function(e) {parsePhone(e);});
	
	fieldName.keydown(function(event) {
		if (event.key == "Tab" || event.keyCode == 9)
			return true;
		var key = event.key || String.fromCharCode(event.keyCode || event.charCode);
		if (/^[0-9]$/i.test(key))
			return false;
	});
	
	function format() {
		var length = numbers.length;
		var string = "";
		
		if (length >= 0)
			string = "+7 (" + numbers.slice(0, 3).join("");
		if (length >= 3)
			string = string + ") " + numbers.slice(3, 6).join("");
		if (length >= 6)
			string = string + "-" + numbers.slice(6, 8).join("");
		if (length >= 8)
			string = string + "-" + numbers.slice(8, 10).join("");
		
		return string;
	}
});
