<div class="input-group mb-3" onload='setactivePage("login")'>
  <input  name="username" id="usrname" type="text" class="form-control usrname" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
</div>

<div class="input-group mb-3">
  <input name="password" id="passwd" type="password" class="form-control passwd" placeholder="Password" aria-label="Password" aria-describedby="basic-addon2">
  <button type="button" class="btn btn-warning" onclick="login()">Login</button>
</div>

<p id="msg"></p>

<script>
$(".usrname").on('keyup', function (e) {
    if (e.key === 'Enter' || e.keyCode === 13) {
        login();
    }
});

$(".passwd").on('keyup', function (e) {
    if (e.key === 'Enter' || e.keyCode === 13) {
        login();
    }
});
</script>

<script src="js/usersystem.js"></script>