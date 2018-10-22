require('dotenv').config();
require('./db');

const { errorResponse, HTTP_CODES } = require('./helpers/responses');

const bodyParser = require('body-parser');
const bearerToken = require('express-bearer-token');

const express = require('express');

//Load routes
const userRouter = require('./routes/user');
const associationsRouter = require('./routes/association');

const app = express();

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bearerToken());

//Add route to manage user
app.use('/user', userRouter);

// Add route to manage associations
app.use('/association', associationsRouter);

app.all('*', (req, res) => {
  res.status(HTTP_CODES.NOT_FOUND).json(errorResponse('Resource not found'));
});

app.listen(process.env.PORT || 3000, () => {
  console.log('App listening on port 8000!');
}); 
