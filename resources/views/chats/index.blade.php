@extends('layouts.app')
<style>
#message-all {
  min-height: 100px;
  background: #ddd;

}
</style>
@section('content')
{{-- <script src="https://unpkg.com/flowbite@1.5.1/dist/flowbite.js"></script> --}}
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
<script src="https://js.pusher.com/4.1/pusher.min.js"></script>
@vite(['resources/js/app.js']) --}}
  <div class="container">
    <div class="app">
      <div class="row">
          <div class="col-sm-6 offset-sm-3">
              <div class="box box-primary direct-chat direct-chat-primary">
  
                  <div class="box-body">
                      <div class="direct-chat-messages" id="messages"></div>
                  </div>
  
                  <div class="box-footer">
                      <form action="#" method="post" id="message_form">
                          <div class="input-group">
                              <input type="text" name="message" id="message" placeholder="Type Message ..." class="form-control">
                              <span class="input-group-btn">
                                  <button type="submit" id="send_message"  class="btn btn-primary btn-flat">Send</button>
                            </span>
                          </div>
                      </form>
                  </div>
  
              </div>
          </div>
  
      </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src ="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
  </script>
@endsection