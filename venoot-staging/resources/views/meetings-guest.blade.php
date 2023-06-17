<!DOCTYPE html>
<html lang="en">
  <head>
      <meta content="text/html; charset=UTF-8" name="Content-Type" />
      <meta content="width=device-width, initial-scale=1.0" name="viewport" />

      <!-- HTML Meta Tags -->
      <title>{{ $meetingName }} Guest Login</title>

      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

      <style type="text/css">
        .container-fluid {
          height: 100vh;
          background-image: url('https://venoot-pro.work/images/venootSplash.jpg');
          background-size: cover;
        }

        .row {
          height: 100%;
        }
      </style>
  </head>

  <body>
    <div class="container-fluid">
      <div class="row justify-content-start align-items-center">
        <div class="col-auto">
          <div class="card text-light bg-dark bg-gradient" style="width: 34rem; margin-left: 10rem;">
            <div class="card-body">
              <h3 class="card-title">{{ $meetingName }}</h3>
              <br><br>
              <label class="form-label">Ingrese su nombre para el webinar:</label>
              <input id="userName" type="text" class="form-control" placeholder="Bob">
              <br>
              <div class="form-check">
                <input id="rememberMe" class="form-check-input" type="checkbox">
                <label class="form-check-label">
                  Recordarme para futuros webinars
                </label>
              </div>
              <br>
              <button id="joinButton" data-toggle="button" type="button" class="btn btn-success mb-3 float-end" onclick="joinMeeting();">
                <span id="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;"></span>
                <span class="sr-only">Ingresar</span>
              </button>
            </div>
          </div>
        </div>
      </div>    
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
      $(function() {
        const userName = localStorage.getItem('venoot-webinars-username')
        if (userName) {
          $("#userName").val(userName)
          $('#userName').prop('disabled', true)
          $('#rememberMe').prop('disabled', true)
          $('#joinButton').prop('disabled', true)
          $("#loading").show();
          $("#ingresar").hide();
        }

        axios.post('/api/meetings/guest-link-join', { userName, shortuuid: '{{ $shortuuid }}' })
          .then(response => {
            window.open(response.data.joinLink, '_self')
          })
      })

      function joinMeeting() {
        $('#userName').prop('disabled', true)
        $('#rememberMe').prop('disabled', true)
        $('#joinButton').prop('disabled', true)
        $("#loading").show();
        $("#ingresar").hide();

        const userName = $("#userName").val().trim()
        if ($('#rememberMe').is(":checked"))
        {
          localStorage.setItem('venoot-webinars-username', userName)
        }        
        axios.post('/api/meetings/guest-link-join', { userName, shortuuid: '{{ $shortuuid }}' })
          .then(response => {
            window.open(response.data.joinLink, '_self')
          })
      }
    </script>
  </body>
</html>