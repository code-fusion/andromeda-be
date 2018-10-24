const User = require('../models/user');
const { errorResponse, HTTP_CODES } = require('../helpers/responses');

const register = (req, res) => {
  const newUser = new User(req.body);
  newUser.setPassword(req.body.password);
  newUser.save((error) => {
    if (error) {
      res.status(HTTP_CODES.DUPLICATED_RESOURCE).json(errorResponse(error.message));
    } else {
      res.status(HTTP_CODES.SUCCESS).json(newUser.toAuthJSON());
    }
  });
};

const login = (req, res) => {
  User.findOne({ username: req.body.username }, (err, doc) => {
    if (err) {
      return res.status(HTTP_CODES.SERVER_ERROR).json(errorResponse(err.message));
    }
    if(!doc) {
      return res.status(HTTP_CODES.NOT_FOUND).json(errorResponse('User not found'));
    }
    const user = new User(doc);
    if(!user.validPassword(req.body.password)) {
      return res.status(HTTP_CODES.NOT_FOUND).json(errorResponse('Incorrect user credentials'));
    } 
    res.status(HTTP_CODES.SUCCESS).json(user.toAuthJSON());

  });
}

const me = (req, res) => {
  User.findOne({_id: req.userId}, (err, doc) => {
    if (err) {
      return res.status(HTTP_CODES.SERVER_ERROR).json(errorResponse(err.message));
    }
    if(!doc) {
      return res.status(HTTP_CODES.NOT_FOUND).json(errorResponse('User not found'));
    }
    return res.status(HTTP_CODES.SUCCESS).json({
      username: doc.username,
      email: doc.email
    })
  });
}

module.exports = {
  register,
  login,
  me,
};
