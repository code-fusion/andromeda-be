const mongoose = require('mongoose');

const AssociationModel = new mongoose.Schema({
  name: { type: String },
  flag: { type: String },
  user_id: { type: mongoose.Schema.Types.ObjectId}
});

module.exports = mongoose.model("Association",AssociationModel);
