


function ssClient(o){
    var self=this;
    self.user=o;
    self.url=window.location.pathname+'?'+Math.random();    
     
    window.document.onclick=function(){
    self.sendMess('activatePage');
    } 
    
    self.connect();
    
    
    
    
}

ssClient.prototype={
    user: 0,
    url: '' ,
    ws:  "ws://10.0.1.5:8081" ,
    conn: '',
    connect: function(){
         var self=this;
             self.conn = new WebSocket(self.ws);
     
             self.conn.onopen = function(e) {
               self.sendMess('connection')     
             };
             self.conn.onmessage = function(e) {
                 var mess=JSON.parse(e.data);
                  if(mess.type=='call'){
                      
                      window.open('../firms/kartafirm.php?EDITID='+mess.orgId,' Звонок!!! ' ,"width=600,height=450");
                      
                  }else{
                      console.log(mess);
                  }
              };
    }, 
    sendMess: function(d){       
        var self=this;
         self.conn.send( JSON.stringify({
                     type: d
                    , data:{
                         user:self.user
                       ,  url: self.url    
                       , type: 'js'        
                    }  
               }));
    }
}
