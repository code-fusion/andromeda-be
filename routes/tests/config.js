const supertest = require('supertest');
const api = supertest('http://localhost:8000');

module.exports = {
  api
}
