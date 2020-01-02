var Chat = {
	socket:false,
	start:function() {
		Chat.socket = io.connect('http://' + cfg.host + ':8888');
		//
		
			var name = '[login]_' + (Math.round(Math.random() * 10000));
			var messages = $("#canal0");
			var message_txt = $("#msg_text")
			$('.chat .nick').text(name);
	 
			function msg(nick, message) {
				var m = '<div class="msg">' +
						'<span class="user"><b>' + safe(nick) + '</b>:</span> '
						+ safe(message) +
						'</div>';
				messages
						.append(m)
						.scrollTop(messages[0].scrollHeight);
			}
	 
			function msg_system(message) {
				var m = '<div class="msg system">' + safe(message) + '</div>';
				messages
						.append(m)
						.scrollTop(messages[0].scrollHeight);
			}
	 
			Chat.socket.on('connecting', function () {
				msg_system('Соединение...');
			});
	 
			Chat.socket.on('connect', function () {
				msg_system('Соединение установлено!');
			});
	 
			Chat.socket.on('message', function (data) {
				msg( data.name , data.message);
				message_txt.focus();
			});
	 
			$("#btn1c").click(function () {
				top.Chat.formSendMessage();
			});
	 
			function safe(str) {
				return str.replace(/&/g, '&amp;')
				   .replace(/</g, '&lt;')
				   .replace(/>/g, '&gt;');
			}
	},
	testKeyPress:function(e) {
		if(event.keyCode==10 || event.keyCode==13) {
			this.formSendMessage();
		}
	},
	formSendMessage:function() {
		var text = $("#msg_text").val();
		var name = User.login;
		if (text.length <= 0)
			return;
		$("#msg_text").val('')
		this.socket.emit("message", { message: text, name: name });
	}
};