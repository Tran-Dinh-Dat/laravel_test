import './bootstrap';
import '../sass/app.scss'

$(document).ready(function(){

  $(document).on('click','#send_message',function (e){
      e.preventDefault();

      let message = $('#message').val();

      if(message == ''){
          alert('Please enter message')
          return false;
      }

      $.ajax({
          method:'post',
          url:'/messages',
          data:{message:message},
          success:function(res){
            console.log(res);
          }
      });

  });
});

window.Echo.private('chat')
  .listen('MessageSent',(e) => {
    console.log(e);
      $('#messages').append('<p><strong>'+e.message+'</p>');
      $('#message').val('');
  });

// https://webjourney.dev/laravel-pusher-real-time-chat-application-build-with-javascript-jquery