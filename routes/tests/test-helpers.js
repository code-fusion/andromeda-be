const {api} = require('./config');

const falseAuth = 'aduhwakduhk21i8e932';

const incorrectLoginContext = (method, url, data) => {
  context('With incorrect login', done => {
    it('Should return 403 status on no auth', done => {
      api[method](url)
        .send(data)
        .expect(403)
        .end((err, res) => {
          done(err);
        });
    });

    it('Should return 403 status on incorrect auth', done => {
      api[method](url)
        .send(data)
        .set('Authorization', 'Bearer ' + falseAuth)
        .expect(403)
        .end((err, res) => {
          done(err);
        });
    });
  });
}

module.exports = {
  incorrectLoginContext,
}