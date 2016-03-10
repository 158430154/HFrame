RongIMClient.init("pwe86ga5el946");

var token = "rsllXGjt+OAP9/upBCQnYjtoMkqSUIUusDzNRdOhn3XoHX2TAuy8osutnfHARJNA6SE7STkjlo+xku4RV4o8Cw==";

RongIMClient.setConnectionStatusListener({
    onChanged: function (status) {
        switch (status) {
            case RongIMLib.ConnectionStatus.CONNECTED:
                console.log('链接成功');
                break;
            case RongIMLib.ConnectionStatus.CONNECTING:
                console.log('正在链接');
                break;
            case RongIMLib.ConnectionStatus.DISCONNECTED:
                console.log('断开连接');
                break;
            case RongIMLib.ConnectionStatus.KICKED_OFFLINE_BY_OTHER_CLIENT:
                console.log('其他设备登陆');
                break;
            case RongIMLib.ConnectionStatus.NETWORK_UNAVAILABLE:
                console.log('网络不可用');
                break;
        }
    }
});

//消息监听器
RongIMClient.setOnReceiveMessageListener({
    onReceived: function (message) {
        if(message.messageType == RongIMClient.MessageType.TextMessage){
            console.log(message.content.content);
        }
    }
});

RongIMClient.connect(token, {
    onSuccess: function (userId) {
        console.log("登录成功,用户IMID：" + userId);
        //加入聊天室
        RongIMClient.getInstance().joinChatRoom("1", 0, {
            onSuccess: function () {
                console.log("聊天室加入成功");
            },
            onError: function (error) {
                console.log("聊天室加入失败,错误码：" + error);
            }
        });
    },
    onTokenIncorrect: function () {
        console.log('token无效');
    },
    onError: function (errorCode) {
        var info = '';
        switch (errorCode) {
            case RongIMLib.ErrorCode.TIMEOUT:
                info = '超时';
                break;
            case RongIMLib.ErrorCode.UNKNOWN_ERROR:
                info = '未知错误';
                break;
            case RongIMLib.ErrorCode.UNACCEPTABLE_PaROTOCOL_VERSION:
                info = '不可接受的协议版本';
                break;
            case RongIMLib.ErrorCode.IDENTIFIER_REJECTED:
                info = 'appkey不正确';
                break;
            case RongIMLib.ErrorCode.SERVER_UNAVAILABLE:
                info = '服务器不可用';
                break;
        }
        console.log(errorCode);
    }
});