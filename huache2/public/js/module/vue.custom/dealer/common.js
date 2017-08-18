
//hasClass() 
/*Object.prototype.hasClass = function(cName){ 
 return !!this.className.match( new RegExp( "(\\s|^)" + cName + "(\\s|$)") ); 
} */
/*//addClass() 
Object.prototype.addClass = function(cName){ 
 if( !this.hasClass( cName ) ){ 
  this.className += " " + cName; 
 } 
 return this; 
} 
//removeClass() 
Object.prototype.removeClass = function(cName){ 
 if( this.hasClass( cName ) ){ 
  this.className = this.className.replace( new RegExp( "(\\s|^)" + cName + "(\\s|$)" )," " ); 
 } 
 return this; 
}

Object.prototype.hasClass = function(cName){ 
 return this.classList.contains(cName) 
} 
Object.prototype.addClass = function(cName){ 
 if( !this.hasClass( cName ) ){ 
  this.classList.add(cName); 
 } 
 return this; 
} 
Object.prototype.removeClass = function(cName){ 
 if( this.hasClass( cName ) ){ 
  this.classList.remove(cName); 
 } 
 return this; 
} 
*/
/*Object.prototype.hide = function(){ 
   this.style.display="none"; 
   return this; 
} */

//hide() 
/*Object.prototype.hide = function(){ 
 this.style.display="none"; 
 return this; 
} 
//show() 
Object.prototype.show = function(){ 
 this.style.display="block"; 
 return this; 
}*/
/*
//slideDown() 
Object.prototype.slideDown = function(){ 
 this.style.display = 'block'; 
 if(this.clientHeight<this.scrollHeight){ 
  this.style.height=10+this.clientHeight+"px"; 
  var _this = this; 
  setTimeout(function(){_this.slideDown()},10) 
 }else{ 
  this.style.height=this.scrollHeight+"px"; 
 } 
} 
//slideUp() 
Object.prototype.slideUp = function(){ 
 if(this.clientHeight>0){ 
  this.style.height=this.clientHeight-10+"px"; 
  var _this = this; 
  setTimeout(function(){_this.slideUp()},10) 
 }else{ 
  this.style.height=0; 
  this.style.display = 'none'; 
 } 
}

//attr() 
Object.prototype.attr = function(){ 
 if(arguments.length==1){ 
  return eval("this."+arguments[0]); 
 }else if(arguments.length==2){ 
  eval("this."+arguments[0]+"="+arguments[1]); 
  return this; 
 } 
} 
//val() 
Object.prototype.val = function(){ 
 if(arguments.length==0){ 
  return this.value; 
 }else if(arguments.length==1){ 
  this.value = arguments[0]; 
  return this; 
 } 
} 
//html() 
Object.prototype.html = function(){ 
 if(arguments.length==0){ 
  return this.innerHTML; 
 }else if(arguments.length==1){ 
  this.innerHTML = arguments[0]; 
  return this; 
 } 
} 
//text()需要在html()结果基础上排除标签，会很长，省略
//css() 
Object.prototype.css = function(){ 
 if(arguments.length==1){ 
  return eval("this.style."+arguments[0]); 
 }else if(arguments.length==2){ 
  eval("this.style."+arguments[0]+"='"+arguments[1]+"'"); 
  return this; 
 } 
 //append() 
Object.prototype.append = function(newElem){ 
 this.innerHTML += newElem; 
 return this; 
} 
//prepend() 
Object.prototype.prepend = function(newElem){ 
 this.innerHTML = arguments[0] + this.innerHTML; 
 return this; 
} 
//after() 
Object.prototype.after = function(newElem){ 
 this.outerHTML += arguments[0]; 
 return this; 
} 
//before() 
Object.prototype.before = function(newElem){ 
 this.outerHTML = arguments[0] + this.outerHTML; 
 return this; 
}
//empty() 
Object.prototype.empty = function(){ 
 this.innerHTML = ""; 
 return this; 
} 
//replaceWith() 
Object.prototype.replaceWith = function(newElem){ 
 this.outerHTML = arguments[0]; 
 return this; 
} 
//remove() js自带，省略。
//hasClass() 
Object.prototype.hasClass = function(cName){ 
 return !!this.className.match( new RegExp( "(\\s|^)" + cName + "(\\s|$)") ); 
} 
//addClass() 
Object.prototype.addClass = function(cName){ 
 if( !this.hasClass( cName ) ){ 
  this.className += " " + cName; 
 } 
 return this; 
} 
//removeClass() 
Object.prototype.removeClass = function(cName){ 
 if( this.hasClass( cName ) ){ 
  this.className = this.className.replace( new RegExp( "(\\s|^)" + cName + "(\\s|$)" )," " ); 
 } 
 return this; 
}

Object.prototype.hasClass = function(cName){ 
 return this.classList.contains(cName) 
} 
Object.prototype.addClass = function(cName){ 
 if( !this.hasClass( cName ) ){ 
  this.classList.add(cName); 
 } 
 return this; 
} 
Object.prototype.removeClass = function(cName){ 
 if( this.hasClass( cName ) ){ 
  this.classList.remove(cName); 
 } 
 return this; 
}*/

/*//id或class选择器$("elem") 
function $(strExpr){ 
 var idExpr = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/; 
 var classExpr = /^(?:\s*(<[\w\W]+>)[^>]*|.([\w-]*))$/; 
 if(idExpr.test(strExpr)){ 
  var idMatch = idExpr.exec(strExpr); 
  return document.getElementById(idMatch[2]); 
 }else if(classExpr.test(strExpr)){ 
  var classMatch = classExpr.exec(strExpr); 
  var allElement = document.getElementsByTagName("*"); 
  var ClassMatch = []; 
  for(var i=0,l=allElement.length; i<l; i++){ 
   if(allElement[i].className.match( new RegExp( "(\\s|^)" + classMatch[2] + "(\\s|$)") )){ 
    ClassMatch.push(allElement[i]); 
   } 
  } 
  return ClassMatch; 
 } 
}*/