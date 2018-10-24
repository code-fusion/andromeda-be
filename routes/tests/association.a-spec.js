const expect = require('chai').expect;
const {api} = require('./config');

const { incorrectLoginContext } = require('./test-helpers');

module.exports = describe('Association', () => {

  let auth;

  before(done => {
    api.post('/user/login')
      .send({
        username: 'testuser',
        password: '12345678',
      })
      .end(function (err, res) {
        auth = res.body;
        done(err);
      });

  });

  describe('Get All By User', () => {

    context('With correct login', done => {
      it('Should return 200 status response', done => {
        api.get('/association/user')
          .set('Authorization', 'Bearer ' + auth.token)
          .expect(200)
          .end((err, res) => {
            done(err);
          });
      });

      it('Should return a Associations array', done => {
        api.get('/association/user')
          .set('Authorization', 'Bearer ' + auth.token)
          .expect(200)
          .end((err, res) => {
            expect(res.body).to.have.property('associations');
            expect(res.body.associations).to.be.an('array');
            done(err);
          });
      });

      it('Should return at leats 1 association with keys and values', done => {
        api.get('/association/user')
          .set('Authorization', 'Bearer ' + auth.token)
          .expect(200)
          .end((err, res) => {
            const associations = res.body.associations;
            expect(associations.length >= 1).to.be.true;
            expect(associations[0]).to.have.property('name');
            expect(associations[0].name).to.not.be.empty;
            expect(associations[0]).to.have.property('flag');
            done(err);
          });
      });
    });

    incorrectLoginContext('get', '/association/user', null);

  });

  describe('Add New association', () => {

    context('With correct login', done => {
      it('Should return the newly created object', done => {
        api.put('/association/user/add')
          .set('Authorization', 'Bearer ' + auth.token)
          .set('Content-Type', 'application/json')
          .send({
            "name": "Test association",
            "flag": ""
          })
          .expect(200)
          .end((err, res) => {
            expect(res.body).to.have.property('name');
            expect(res.body.name).to.be.equal('Test association');
            expect(res.body).to.have.property('flag');
            expect(res.body.flag).to.be.equal('');
            done(err);
          });
      });

      it('Should returned error message on name empty or no name', done => {
        api.put('/association/user/add')
          .set('Authorization', 'Bearer ' + auth.token)
          .set('Content-Type', 'application/json')
          .send({
            "flag": ""
          })
          .expect(500)
          .end((err, res) => {
            expect(res.body).to.have.property('error');
            expect(res.body.error).to.be.true;
            expect(res.body).to.have.property('errorMessage');
            done(err);
          });
      });

    });

    incorrectLoginContext('put', '/association/user/add', null);

  });

});