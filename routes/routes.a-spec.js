/* eslint-disable */

const should = require('chai').should();
const expect = require('chai').expect;
const supertest = require('supertest');
const api = supertest('http://localhost:8000');

describe('API', () => {

  describe('Association', () => {

    let auth;
    const falseAuth = 'kkeufshi8sfh38h298h38fheskfh';

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

      context('With incorrect login', done => {
        it('Should return 403 status on no auth', done => {
          api.get('/association/user')
            .expect(403)
            .end((err, res) => {
              done(err);
            });
        });

        it('Should return 403 status on incorrect auth', done => {
          api.get('/association/user')
            .set('Authorization', 'Bearer ' + falseAuth)
            .expect(403)
            .end((err, res) => {
              done(err);
            });
        });
      });


    });

  });

});
