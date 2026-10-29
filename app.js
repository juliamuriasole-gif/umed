const express = require('express');
const bodyParser = require('body-parser');
const app = express();

// Middleware para parsear el cuerpo de las solicitudes POST
app.use(bodyParser.urlencoded({ extended: true }));

// Ruta para manejar solicitudes GET en la ruta principal "/"
app.get('/', (req, res) => {
    res.send('Bienvenido a mi aplicación web!');
});

// Ruta para manejar el login
app.post('/login', (req, res) => {
    const { username, password } = req.body;

    // Verificación básica de usuario y contraseña
    if (username === 'usuario' && password === 'contraseña') {
        res.send('Login exitoso');
    } else {
        res.send('Usuario o contraseña incorrectos');
    }
});

// Servidor escucha en el puerto 3000
app.listen(3000, () => {
    console.log('Servidor Node.js corriendo en http://localhost:3000');
});

