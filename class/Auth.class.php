<?php
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

class Auth
{
    public function login (Request $request){

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        //validate if its a valid user then build a token
        $token = (new Builder())->setIssuer('codeFusion')
            ->setAudience('codeFusion')
            ->setIssuedAt(time())
            ->setExpiration(time() + 3600)
            ->getToken();

        return json_encode(['result' => 1, 'message' => 'Token generated successfully', 'token' => '' . $token,]);
    }

    function validateToken($token) {
        try {
            $token = (new Parser())->parse($token);
        } catch (Exception $exception) {
            return $exception;
        }

        $validationData = new ValidationData();
        $validationData->setIssuer('codeFusion');
        $validationData->setAudience('codeFusion');

        return $token->validate($validationData);
    }
}