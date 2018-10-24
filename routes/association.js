const associations = require('express').Router();
const associationController = require('./../controllers/association');
const {verifyToken} = require('./../helpers/jwt');

associations.all('*', verifyToken);

//Get all associations by user
associations.get('/user', associationController.list);

//Create new association
associations.put('/user/add', associationController.add);

module.exports = associations;