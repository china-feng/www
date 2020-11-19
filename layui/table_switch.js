<script type="text/html" id="is_enable">
{{#  if(d.is_enable==1){ }}
    <input type="checkbox" name="" lay-skin="switch" checked   lay-text="开启|关闭"  value= {{d.id}}  lay-filter="is_enable" >
{{#  } else { }}
    <input type="checkbox" name="" lay-skin="switch"  lay-text="开启|关闭"  value= {{d.id}} lay-filter="is_enable" >
{{# } }}
</script>

form.on('switch(is_enable)', function(data){
    // 得到开关的value值，实际是需要修改的ID值。
    var id = data.value;
    var is_enable = this.checked ? '1' : '0';
    var index;
    $.ajax({
      type: 'POST',
      url: './<?php echo $page_iden;?>.php',
      data: {"id" :id,"is_enable":is_enable , "op" : "edit_is_entable"},
      dataType:'JSON',
      beforeSend:function(){
       index = layer.msg('正在切换中，请稍候',{icon: 16,time:false,shade:0.8});     
        },
      error: function(data){
        console.log(data);            
          layer.msg('数据异常，操作失败！'); 
           },
      success: function(data){  
        if(data.code == "0"){ 
            setTimeout(function(){
                layer.close(index);
                layer.msg(data.msg);}, 1000);  
          }else{
              layer.msg(data.msg); 
          }
      },      
    });
  });
