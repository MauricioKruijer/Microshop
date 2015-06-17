<h1>Register User</h1>
<form action="/user/create?redirect=billing" method="post">
    <p>
        <label for="first_name">First name: <input id="first_name" name="first_name" type="text"/></label>
    </p>
    <p><label for="last_name">Last name: <input id="last_name" name="last_name" type="text"/></label></p>
    <p><label for="email">Email: <input id="email" name="email" type="text"/></label></p>
    <p><label for="password1">Repeat password: <input id="password1" type="password" name="password"/></label></p>
    <p><label for="password2">Repeat password: <input id="password2" type="password" name="password2"/></label></p>
    <p><input type="submit" value="register"/></p>
</form>