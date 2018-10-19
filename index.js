require('dotenv').config();
require('./db');
const userController = require('./controllers/user');

const bodyParser = require('body-parser');

const express = require('express');

const app = express();

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Hello world
app.get('/', (req, res) => {
  res.send('Hello World!');
});

// Another example
app.post('/register', userController.register);

app.listen(process.env.PORT || 3000, () => {
  console.log('App listening on port 8000!');
});
