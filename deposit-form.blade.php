<!-- Example in resources/views/user/deposit.blade.php -->
<form action="/stkpush" method="POST">
    @csrf
    <label for="amount">Amount:</label>
    <input type="text" id="amount" name="amount" required>

    <label for="phone">Phone Number:</label>
    <input type="text" id="phone" name="phone" required>

    <button type="submit">Pay Now</button>
</form>
