<?php
/**
 * Created by PhpStorm.
 * User: MSI-GP60
 * Date: 5/5/2019
 * Time: 10:19 PM
 */

namespace QA\SignUp;

use PHPUnit\Framework\TestCase;

class SignUpTest extends TestCase
{
    private $baseURL = "https://pixelnos-ledger-api.herokuapp.com/BackEnd/index.php";

    public function testSignUpWithCorrectValues(){
        $url = $this->baseURL.'?action=connection/SignUp';
        $data = array('email' => 'email'.rand(0,100000).'@hotmail.com',
            'password' => '123456789',
            'first_name' => 'Julien',
            'last_name' => 'Rechenmann');

        $result = $this->getResponseFromRequest($data, $url);
        $this->assertEquals("Signed Up", $result);
    }

    public function testSignUpWithMissingFirstName(){
        $url = $this->baseURL.'?action=connection/SignUp';
        $data = array('email' => 'email'.rand(0,100000).'@hotmail.com',
            'password' => '123456789',
            'last_name' => 'Rechenmann');

        $result = $this->getResponseFromRequest($data, $url);
        $this->assertEquals("Missing parameters first_name in request SignUp", $result);
    }

    public function testSignUpWithMissingLastName(){
        $url = $this->baseURL.'?action=connection/SignUp';
        $data = array('email' => 'email'.rand(0,100000).'@hotmail.com',
            'password' => '123456789',
            'first_name' => 'Julien');

        $result = $this->getResponseFromRequest($data, $url);
        $this->assertEquals("Missing parameters last_name in request SignUp", $result);
    }

    public function testSignUpWithMissingPassword(){
        $url = $this->baseURL.'?action=connection/SignUp';
        $data = array('email' => 'email'.rand(0,100000).'@hotmail.com',
            'first_name' => 'Julien',
            'last_name' => 'Rechenmann');

        $result = $this->getResponseFromRequest($data, $url);
        $this->assertEquals("Missing parameters password in request SignUp", $result);
    }

    public function testSignUpWithMissingEmail(){
        $url = $this->baseURL.'?action=connection/SignUp';
        $data = array('password' => '123456789',
            'first_name' => 'Julien',
            'last_name' => 'Rechenmann');

        $result = $this->getResponseFromRequest($data, $url);
        $this->assertEquals("Missing parameters email in request SignUp", $result);
    }

    /**
     * @param array $data
     * @param string $url
     * @return false|string
     */
    protected function getResponseFromRequest(array $data, string $url)
    {
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
}
