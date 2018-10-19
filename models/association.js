const { Schema, model } = require('mongoose');

const AssociationModel = new Schema({
  name: { type: String },
});

module.exports = model(AssociationModel);
