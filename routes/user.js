const user = require('express').Router();
const userController = require('./../controllers/user');
const {verifyToken} = require('./../helpers/jwt');

//Register new user
user.post('/register', userController.register);

//Login user
user.post('/login', userController.login);

// Get user info
user.post('/me', verifyToken ,userController.me);

module.exports = user;