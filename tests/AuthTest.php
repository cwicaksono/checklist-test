<?php

class AuthTest extends TestCase
{
    public function testAuthWithEmptyEmailAndPassword(){
        $response = $this->call('GET', '/api/login');

        $this->assertEquals(422, $response->status());     
    }

    public function testAuthWithEmptyEmail(){
        $param = "?email=&password=123123";
        $response = $this->call('GET', '/api/login' . $param);

        $this->assertEquals(422, $response->status());     
    }

    public function testAuthWithEmptyPassword(){
        $param = "?email=cahyo.wicaksono@gmail.com&password=";
        $response = $this->call('GET', '/api/login' . $param);

        $this->assertEquals(422, $response->status());     
    }

    public function testAuthWithInvalidEmailOrPassword(){
        $param = "?email=invalid.email@gmail.com&password=123123";
        $response = $this->call('GET', '/api/login' . $param);

        $this->assertEquals(500, $response->status());     
    }

    public function testAuthWithValidEmailAndPassword(){
        $param = "?email=cahyo.wicaksono@gmail.com&password=123123";
        $response = $this->call('GET', '/api/login' . $param);

        $this->assertEquals(200, $response->status());     
    }
    // public function testBasicExample()
    // {
    //     $this->json('POST', '/user', ['name' => 'Sally'])
    //          ->seeJson([
    //             'created' => true,
    //          ]);
    // }
}