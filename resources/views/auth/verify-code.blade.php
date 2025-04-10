<form method="POST" action="{{ route('verify-code') }}">
    @csrf
    <label for="code">Ingrese el código de verificación:</label>
    <input type="text" id="code" name="code" required>
    <button type="submit">Verificar Código</button>
</form>