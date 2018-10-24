const mongoose = require('mongoose');

const AssociationModel = new mongoose.Schema({
  name: {
    type: String,
    required: [true, "can't be blank"],
    index: true,
  },
  flag: { type: String },
  user_id: { type: mongoose.Schema.Types.ObjectId}
});

module.exports = mongoose.model("Association",AssociationModel);
