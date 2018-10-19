const mongoose = require('mongoose');

const db = mongoose.connect(
  process.env.MONGO_DB_CONNECTION_STRING,
  { useNewUrlParser: true },
  (error) => {
    if (error) {
      console.log(error);
    }
  },
);

module.exports = {
  db,
};
