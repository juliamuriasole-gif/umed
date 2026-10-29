const express = require('express');
const bodyParser = require('body-parser');
const session = require('express-session');
const bcrypt = require('bcrypt');

const app = express();

// Configuración para servir archivos estáticos (index.html y styles.css)
app.use(express.static('public'));

// Middleware para procesar los datos del formulario
app.use(bodyParser.urlencoded({ extended: true }));

// Configuración de sesiones
app.use(session({
    secret: process.env.SESSION_SECRET || 'change-me',
    resave: false,
    saveUninitialized: true
}));

// Usuarios de ejemplo (en lugar de una base de datos)
const usuarios = [
    {
        username: 'alumno1',
        password: '$2b$10$7XdF8QGFkDehfyfL.9QPOeQBh8wChKiKa8Xj6FE6.Rhb23C5oipOe' // Contraseña: 123456
    },
    {
        username: 'alumno2',
        password: '$2b$10$ePwH3GqsYNbwrz98hOt9Ru1B1R0EyxHMG.EQxTPPN.kj6CnSDwyu6' // Contraseña: password
    }
];

// Ruta para la página de inicio
app.get('/', (req, res) => {
    res.sendFile(__dirname + '/public/index.html');
});

// Ruta para procesar el inicio de sesión
app.post('/login', async (req, res) => {
    const { username, password } = req.body;

    // Buscar al usuario
    const usuario = usuarios.find(u => u.username === username);
    if (usuario) {
        // Verificar la contraseña
        const match = await bcrypt.compare(password, usuario.password);
        if (match) {
            req.session.username = username; // Guardar el nombre de usuario en la sesión
            res.send(`¡Hola, ${username}! Has iniciado sesión correctamente.`);
        } else {
            res.send('Contraseña incorrecta.');
        }
    } else {
        res.send('Usuario no encontrado.');
    }
});

// Ruta para cerrar sesión
app.get('/logout', (req, res) => {
    req.session.destroy();
    res.send('Has cerrado sesión.');
});

// Iniciar el servidor
app.listen(3000, '0.0.0.0', () => {
    console.log('Servidor corriendo en http://0.0.0.0:3000');
});

app.get('/', (req, res) => {
  res.send('Bienvenidos a la Cumbre de la Unión Mediterránea');
});

app.listen(3000, '0.0.0.0', () => {
  console.log('Servidor corriendo en http://0.0.0.0:3000/');
});

