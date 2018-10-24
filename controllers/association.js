const Association = require('../models/association');
const { errorResponse, HTTP_CODES } = require('../helpers/responses');

const list = (req, res) => {
  Association.find({}, (err, docs) => {
    if(err){
      res.status(HTTP_CODES.SERVER_ERROR).json(errorResponse(error.message));
    } else {
      res.status(HTTP_CODES.SUCCESS).json({
        associations: docs,
      });
    }
  })
};

const add = (req, res) => {
  const newAssociation = new Association(req.body);
  newAssociation.user_id = req.userId;
  newAssociation.save((error) => {
    if (error) {
      res.status(HTTP_CODES.SERVER_ERROR).json(errorResponse(error.message));
    } else {
      res.status(HTTP_CODES.SUCCESS).json(newAssociation);
    }
  });
}

module.exports = {
  list,
  add,
};
