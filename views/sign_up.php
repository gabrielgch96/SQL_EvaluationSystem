<!DOCTYPE html>
<html>

<head>
    <title>Sign-Up</title>
</head>

<body>
    <h3>Sign Up</h3>
    <form id="signupForm" method="POST" action="../controllers/signup">
        <label>E-mail</label>
        <input type="email" name="email" required />
        <label>password</label>
        <input type="password" name="pwd" required />
        <label>First Name</label>
        <input type="text" name="first_name" required />
        <label>Last Name</label>
        <input type="text" name="name" required />
        <label>Trainer?</label>
        <input type="checkbox" name="is_trainer" />
        <input type="submit" value="Sign-up" />
    </form>
</body>

</html>