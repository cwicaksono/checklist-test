<?php

class ChecklistTest extends TestCase
{
    var $header = [
        'HTTP_Authorization' => 'Bearer UzdLVkl6M1V0MXVRZ3JVVmRrWUhIbTk1c21rNFFJRGtHYU00MUNrWA=='
    ];

    public function testCheckListDetailWithEmptyToken(){
        $this->header = [
            'HTTP_Authorization' => 'Bearer '
        ];

        $response = $this->get('/api/checklists/1', $this->header);

        $param = array(
            'error' => 'Unauthorized'
        );
        $response->seeStatusCode(401);
        $response->seeJson($param);
    }

    public function testCheckListDetailWithInvalidToken(){        
        $this->header = [
            'HTTP_Authorization' => 'Bearer INVALIDTOKEN=='
        ];

        $response = $this->get('/api/checklists/1', $this->header);

        $param = array(
            'error' => 'Unauthorized'
        );
        $response->seeStatusCode(401);
        $response->seeJson($param);
    }

    public function testCheckListDetailWithValidToken(){
        $response = $this->get('/api/checklists/1', $this->header);

        $content = json_decode($response->response->getContent());
        $this->assertEquals($content->data->type, 'checklists');
    }

    public function testCheckListDetailWithNoData(){
        $response = $this->get('/api/checklists/404', $this->header);

        $param = array(
            'error' => 'Not Found',
            'status' => '404'
        );
        $response->seeStatusCode(404);
        $response->seeJson($param);
    }

    // public function testCheckListDetailWithResponse500(){
    //     $this->assertTrue(true);
    // }

    // public function testCheckListCreateChecklist(){
        // $response = $this->get('/api/checklists/1', $this->header);

        // $content = json_decode($response->response->getContent());
        // print_r($content);
        // $this->assertEquals($content->data->type, 'checklists');
    // }
}