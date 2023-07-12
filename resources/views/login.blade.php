<!-- resources/views/login.blade.php -->

<form method="POST" action="{{ route('checkLogin') }}">
    @csrf
    <input type="text" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Login</button>
</form>
