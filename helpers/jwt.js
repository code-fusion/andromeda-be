const jwt = require('jsonwebtoken');
const { HTTP_CODES, errorResponse } = require('./responses');

const verifyToken = (req, res, next) => {
  var token = req.token;
  if (!token)
    return res.status(HTTP_CODES.FORBIDDEN).send({ auth: false, message: 'No token provided.' });
  jwt.verify(token, process.env.JWT_SECRET, function (err, decoded) {
    if (err)
      return res.status(HTTP_CODES.SERVER_ERROR).send({ auth: false, message: 'Failed to authenticate token.' });
    // if everything good, save to request for use in other routes
    req.userId = decoded.id;
    next();
  });
};

const verifyAutorizationPresent = (req, res, next) => {
  if (!req.headers.authorization) {
    return res.status(HTTP_CODES.FORBIDDEN).json(errorResponse('No credentials sent'));
  }
  next();
}

module.exports = {
  verifyToken,
  verifyAutorizationPresent
};
