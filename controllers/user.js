const User = require('../models/user');
const { errorResponse, HTTP_CODES } = require('../helpers/responses');

const register = (req, res) => {
  const newUser = new User(req.body);
  newUser.setPassword(req.body.password);
  newUser.save((error) => {
    if (error) {
      res.status(HTTP_CODES.DUPLICATED_RESOURCE).json(errorResponse('The user cannot be created'));
    }
    res.status(HTTP_CODES.SUCCESS).json(newUser.toAuthJSON());
  });
};

module.exports = {
  register,
};
