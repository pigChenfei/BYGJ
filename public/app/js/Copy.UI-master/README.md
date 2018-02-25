# 复制 Copy 组件 使用说明


## 引用方式

```
<div id="box"></div>
<script src="import/jquery.js"></script>
<script src="import/jquery.zclip.js"></script>
<script src="copy.UI.js"></script>
<script>
    var copyUI = new CopyUI();
    copyUI.init({value:'aa.bb',$parent: $('#box')})
    .before(function() {console.log('开始复制');})
    .after(function() {console.log('复制成功了')})
    .setValue('测试');
</script>
```

## API 

**init 方法 @param {Object}**

初始化时需要调用该方法。    
参数说明：    
object.value    :  要复制的内容    
object.$parent  :  父容器,jquery对象    
object.swfPath    :  flash ZeroClipboard.swf 的引入路径
object.beforeCopy :  开始复制回调函数    
object.afterCopy  :  复制成功后的回调函数 

例子：
```
var copyUI = new CopyUI();
copyUI.init({value:'aa.bb',$parent: $('#box')});
```

-------------------    
**before 方法 @parma {Function}**    

开始复制调用该方法。    

例子：    
```
var beforeCallback = function() {
    console.log('开始复制');
};
copyUI.before(beforeCallback);
```

-------------------     
**after 方法 @param {Function}**   
 
复制成功后调用该方法。

例子：
```
var afterCallback = function() {
    console.log('复制成功');
};
copyUI.after(afterCallback);
```

-------------------   
**setValue 方法 @param {String}**    

设置复制的内容    

例子：    
```
copyUI.setValue('测试');
```

------------------- 
**getValue 方法 @return {String}**    
 返回要复制的内容
 
 例子：
 ```
 copyUI.setValue('测试');
 var copyValue = copyUI.getValue();
 ```



    