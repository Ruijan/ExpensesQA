<?php
namespace QA\SignIn;
use PHPUnit\Framework\TestCase;
class SignInTest extends TestCase
{
    private $baseURL = "https://pixelnos-ledger-api.herokuapp.com/BackEnd/index.php";
    public function testSignInWithNoParameters(){
        $url = $this->baseURL.'?action=connection/SignIn';
        $data = array();

        $result = $this->getResponseFromRequest($data, $url);
        $this->assertEquals("Missing parameters email, password in request SignIn", $result);
    }

    public function testSignInWithMissingPassword(){
        $url = $this->baseURL.'?action=connection/SignIn';
        $data = array('email' => 'value1');

        $result = $this->getResponseFromRequest($data, $url);
        $this->assertEquals("Missing parameters password in request SignIn", $result);
    }

    public function testSignInWithMissingEmail(){
        $url = $this->baseURL.'?action=connection/SignIn';
        $data = array('password' => 'value1');

        $result = $this->getResponseFromRequest($data, $url);
        $this->assertEquals("Missing parameters email in request SignIn", $result);
    }

    public function testSignInWithWrongCredentials(){
        $url = $this->baseURL.'?action=connection/SignIn';
        $data = array('email' => 'value1', 'password' => 'value1');

        $result = $this->getResponseFromRequest($data, $url);
        $this->assertEquals("ERROR: Email or password invalid", $result);
    }

    public function testSignInWithCorrectCredentials(){
        $this->signUp();
        $url = $this->baseURL.'?action=connection/SignIn';
        $data = array('email' => 'email@hotmail.com', 'password' => '123456789');
        $result = $this->getResponseFromRequest($data, $url);

        $this->assertEquals("Connected", $result);
    }

    private function signUp(){
        $url = $this->baseURL.'?action=connection/SignUp';
        $data = array('email' => 'email@hotmail.com',
            'password' => '123456789',
            'first_name' => 'Julien',
            'last_name' => 'Rechenmann');

        // use key 'http' even if you send the request to https://...
        $result = $this->getResponseFromRequest($data, $url);
        if($result == FALSE){
            echo "wrong";
        }
    }

    /**
     * @param array $data
     * @param string $url
     * @return false|string
     */
    protected function getResponseFromRequest(array $data, string $url)
    {
// use key 'http' even if you send the request to https://...
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
