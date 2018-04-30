;(function(window,document){'use strict';window.StorageManager=(function(){function StoragePolyFill(type){function createCookie(name,value,days){var date,expires='';if(days){date=new Date();date.setTime(date.getTime()+(days*24*60*60*1000));expires='; expires='+date.toGMTString();}
value=encodeURIComponent(value);document.cookie=name+'='+value+expires+'; path=/';}
function readCookie(name){var nameEq=name+'=',cookies=document.cookie.split(';'),cookie,len=cookies.length,i=0;for(;i<len;i++){cookie=cookies[i];while(cookie.charAt(0)==' '){cookie=cookie.substring(1,cookie.length);}
if(cookie.indexOf(nameEq)==0){return decodeURIComponent(cookie.substring(nameEq.length,cookie.length));}}
return null;}
function setData(data){data=JSON.stringify(data);if(type=='session'){createCookie('sessionStorage',data,0);}else{createCookie('localStorage',data,365);}}
function clearData(){if(type=='session'){createCookie('sessionStorage','',0);}else{createCookie('localStorage','',365);}}
function getData(){var data=(type=='session')?readCookie('sessionStorage'):readCookie('localStorage');return data?JSON.parse(data):{};}
var data=getData();return{length:0,clear:function(){var me=this,p;for(p in data){if(!data.hasOwnProperty(p)){continue;}
delete data[p];}
me.length=0;clearData();},getItem:function(key){return typeof data[key]==='undefined'?null:data[key];},key:function(index){var i=0,p;for(p in data){if(!data.hasOwnProperty(p)){continue;}
if(i===index){return p;}
i++;}
return null;},removeItem:function(key){var me=this;if(data.hasOwnProperty(key)){me.length--;}
delete data[key];setData(data);},setItem:function(key,value){var me=this;if(!data.hasOwnProperty(key)){me.length++;}
data[key]=value+'';setData(data);}};}
function hasCookiesSupport(){if('cookie'in document&&(document.cookie.length>0)){return true;}
document.cookie='testcookie=1;';var writeTest=(document.cookie.indexOf('testcookie')!==-1);document.cookie='testcookie=1'+';expires=Sat, 01-Jan-2000 00:00:00 GMT';return writeTest;}
function hasLocalStorageSupport(){try{return(typeof window.localStorage!=='undefined');}catch(err){return false;}}
function hasSessionStorageSupport(){try{return(typeof window.sessionStorage!=='undefined');}catch(err){return false;}}
var localStorageSupport=hasLocalStorageSupport(),sessionStorageSupport=hasSessionStorageSupport(),storage={local:localStorageSupport?window.localStorage:new StoragePolyFill('local'),session:sessionStorageSupport?window.sessionStorage:new StoragePolyFill('session')},p;for(p in storage){if(!storage.hasOwnProperty(p)){continue;}
try{storage[p].setItem('storage','');storage[p].removeItem('storage');}catch(err){storage[p]=new StoragePolyFill(p);}}
return{getStorage:function(type){return storage[type];},getSessionStorage:function(){return this.getStorage('session');},getLocalStorage:function(){return this.getStorage('local');},clear:function(type){this.getStorage(type).clear();},getItem:function(type,key){return this.getStorage(type).getItem(key);},key:function(type,i){return this.getStorage(type).key(i);},removeItem:function(type,key){this.getStorage(type).removeItem(key);},setItem:function(type,key,value){this.getStorage(type).setItem(key,value);},hasCookiesSupport:hasCookiesSupport(),hasLocalStorageSupport:hasLocalStorageSupport(),hasSessionStorageSupport:hasSessionStorageSupport()};})();})(window,document);