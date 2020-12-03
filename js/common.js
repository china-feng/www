#原生js

#通过 getElementsByClassName 获取的对象是一个数组，如果相对数组内的每一个对象绑定事件，可用for 循环
objs[i].onclick = function(){
            alert(this.getAttribute('data-status'));
        }


#设置属性 .setAttribute("属性","值")
#获取属性 .getAttribute("属性")

#document.myNews.op.value = "save";//设置表单的值
#document.myNews.submit(); //提交表单

#img:hover{ //鼠标放置图片上会有放大的效果，鼠标移出自动回复原状
        transform: scale(8,8);
    }

#eval()  //执行字符串代码
#将时间戳转换成日期
function format(t)
{
//shijianchuo是整数，否则要parseInt转换
t = parseInt(t);
var time = new Date(t);
var y = time.getFullYear();
var m = time.getMonth()+1;
var d = time.getDate();
var h = time.getHours();
var mm = time.getMinutes();
var s = time.getSeconds();
return y+'-'+add0(m)+'-'+add0(d)+' '+add0(h)+':'+add0(mm)+':'+add0(s);
}
function add0(m){return m<10?'0'+m:m };
