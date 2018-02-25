//下拉框优化
function DropDown(el) {
    this.dd = el;
    this.span = this.dd.children('span');
    this.li = this.dd.find('ul.dropdown li');
    this.val = ''; 
}

DropDown.prototype.initEvents = function() {
    var obj = this;
    obj.dd.on('click', function(event) {
        $(this).toggleClass('active').siblings().removeClass('active');
        event.stopPropagation();
    });
    obj.li.on('click', function(){
        var opt = $(this);
        obj.val = opt.html();
        if(obj.span.html() == obj.val) return;
        obj.span.html(obj.val);
        $(document).click(function() {
            $('.select').removeClass('active');
        });
    });
}
var my_select = new DropDown($('.select'));
my_select.initEvents();
