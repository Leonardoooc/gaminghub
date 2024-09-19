$(document).ready(function(){
  $('gamesearch').on('submit', function(e){
      e.preventDefault();
      $.ajax({
          url: "{{ route('gameSearch') }}",
          type: "POST",
          data: $(this).serialize(),
          success: function(response){
              console.log(response);
          },
          error: function(response){
              console.error(response);
          }
      });
  });
});