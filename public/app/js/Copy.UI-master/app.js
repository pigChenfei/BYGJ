// http://my.oschina.net/u/1866405/blog/301368
// 启动命名 supervisor --debug server  调试 node-inspector
// http://ourjs.com/detail/529ca5950cb6498814000005 学习教程
// https://cnodejs.org/topic/4f16442ccae1f4aa2700104d  -1
// http://www.cnblogs.com/imlucky/archive/2012/10/29/2744302.html -2  POST 实现 formidable--上传插件模块
// 调试工具 npm install -g node-inspector  首先启动服务，然后在新dos界面启动 node-inspector .哪里需要debugger,就添加debugger.
// chome浏览器下 访问 http://127.0.0.1:8080/debug?port=5858 ，port 是调试端口
// 监听文件变化，重启服务  npm install supervisor -g  || supervisor index
// 启动监听加文件变动启动服务命令 supervisor --debug server  
var path = require('path');
var fs = require("fs");
var express = require('express');
var formidable = require("formidable"); // 图片上传
var app = express();
var router = express.Router();

// 事件
var util = require('util');
var events = require('events');


var http = require('http').Server(app);


// 添加属性  Properties
app.locals.title = 'wangyangapp';
app.locals.email = '534591395@qq.com';

// 设置静态资源路径
app.use(express.static(path.join(__dirname, 'assets')));


// 设置模板
app.engine('html', require('ejs').renderFile);
app.set('views', './assets');
app.set("view engine", "html");


app.get('/', function(req, res) {
	res.render('index', { });
});



app.post('/number', function(request,response) {
	var data = {
		resultCode: 0,
		resultDes: '测试',
		data: '12920786'
	};
	response.writeHead(200,{"access-control-allow-origin":"*","Content-Type":"text/json"});
	response.write(JSON.stringify(data));
	response.end();
});

http.listen(18081, function() {
	console.log('start...');
	console.log('listening on *:18081');
});