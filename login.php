<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Registrační Formulář</title>
</head>
<body>
    <h1>Registrační Formulář</h1>
    <form id="registration-form">
        <label for="username">Uživatelské jméno:</label>
        <input type="text" id="username" name="username" placeholder="Zadejte své uživatelské jméno">
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Zadejte svůj email">
        
        <label for="password">Heslo:</label>
        <input type="password" id="password" name="password" placeholder="Zadejte své heslo">
        
        <span class="error">Chyba: Vyplňte toto pole správně.</span>
        
        <button type="submit" class="submit-btn">Odeslat</button>
    </form>
</body>
</html>