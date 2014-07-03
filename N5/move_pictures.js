var bIE=0;bOp=0;bFF=0;
var verBr=navigator.userAgent;
if (verBr.indexOf("Opera")!=-1)
    bOp=1;
else  {
    if (verBr.indexOf("MSIE")!=-1)
	    bIE=1;
	else {
        bFF=1;
    }
}
var pict_num=Number(prompt("Сколько картинок будем двигать? (не более 20)", "8"));
if (pict_num>20) {pict_num = 20;}
max_x=800;
max_y=500;
for (i=1;i<=pict_num;i++) {
   x=Math.floor(Math.random() * max_x) + 1;
   y=Math.floor(Math.random() * max_y) + 1;
   str = "<div id=\"div_pict"+i+"\" class=\"div_pict\" style=\'left:"+x+"px; top:"+y+"px;\'>";
   document.writeln(str);
   str = "<img src=\"images/"+i+".jpg\">"
   document.writeln(str);
   document.writeln("</div>");  
   
   str="div_pict"+i
   obj_move1 = new move_div(str);
}
function move_div(id_div_pict){
    this.id_div_pict=id_div_pict;
    this.obj_pict=document.getElementById(this.id_div_pict);
    this.obj_div_pict=document.getElementById(this.id_div_pict);
    this.delta_x=0;
    this.delta_y=0;  
	
    this.setup_mouse = function(){
        var self=this;
        this.obj_div_pict.onmousedown=function(obj){ self.save_delta_koor(self,obj) };
        if (bOp || bFF) {
            this.obj_div_pict.addEventListener("onmousedown",self.save_delta_koor,false);
        }
        document.onmouseup=self.clear_delta_koor;
    }
	
    this.save_delta_koor = function(obj_move,obj_evt){       
        if (obj_evt) {
        x=obj_evt.pageX;
        y=obj_evt.pageY;
        }
        else {
            x=window.event.clientX;
            y=window.event.clientY;          
        }           
        x_pict=obj_move.obj_pict.offsetLeft;
        y_pict=obj_move.obj_pict.offsetTop;      
        obj_move.delta_x=x_pict-x;
        obj_move.delta_y=y_pict-y;
              
        document.onmousemove=function(obj){ 
	        obj_move.motor_pict(obj_move,obj) 
	    };
	
        if (bOp || bFF)
            document.addEventListener("onmousemove",obj_move.motor_pict,false);
    }
    this.clear_delta_koor = function(){
       document.onmousemove=null;       
    }
     
    this.motor_pict = function(obj_move,obj_event){       
        if (obj_event) {
          x=obj_event.pageX;
          y=obj_event.pageY;
        }
        else {
            x=window.event.clientX;
            y=window.event.clientY;           
        }
        new_x=obj_move.delta_x+x;
        new_y=obj_move.delta_y+y;
        obj_move.obj_pict.style.top=new_y+"px";
        obj_move.obj_pict.style.left=new_x+"px";
       
    }   
    this.setup_mouse();
}