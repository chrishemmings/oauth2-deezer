<?php

namespace ChrisHemmings\OAuth2\Client\Test\Provider;

use ChrisHemmings\OAuth2\Client\Provider\Deezer;
use Mockery as m;
use ReflectionClass;

class DeezerTest extends \PHPUnit_Framework_TestCase
{
    protected $provider;

    protected static function getMethod($name)
    {
        $class = new ReflectionClass('ChrisHemmings\OAuth2\Client\Provider\Deezer');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    protected function setUp()
    {
        $this->provider = new Deezer([
            'clientId'      => 'mock_client_id',
            'clientSecret'  => 'mock_secret',
            'redirectUri'   => 'none',
        ]);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }


    public function testAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);
        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayHasKey('scope', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('approval_prompt', $query);
        $this->assertNotNull($this->provider->getState());
    }

    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        $this->assertEquals('/oauth/auth.php', $uri['path']);
    }

    public function testGetBaseAccessTokenUrl()
    {
        $params = [];
        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);
        $this->assertEquals('/oauth/access_token.php', $uri['path']);
    }

    public function testGetAccessToken()
    {
        $response = m::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn('{"access_token":"mock_access_token", "token_type":"bearer"}');
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);
        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertNull($token->getExpires());
        $this->assertNull($token->getRefreshToken());
        $this->assertNull($token->getResourceOwnerId());
    }

    public function testUserData()
    {
        $response_data = [
            'id' => rand(1000, 9999),
            'name' => uniqid(),
            'firstname' => uniqid(),
            'lastname' => uniqid(),
            'birthday' => uniqid(),
            'inscription_date' => uniqid(),
            'link' => uniqid(),
            'gender' => uniqid(),
            'is_kid' => true,
            'picture' => uniqid(),
            'picture_small' => uniqid(),
            'picture_medium' => uniqid(),
            'picture_big' => uniqid(),
            'lang' => uniqid(),
            'country' => uniqid(),
            'tracklist' => uniqid(),
            'type' => uniqid()
        ];

        $postResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $postResponse->shouldReceive('getBody')->andReturn('access_token=mock_access_token&expires=3600&refresh_token=mock_refresh_token');
        $postResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'text/html; charset=utf-8']);
        $postResponse->shouldReceive('getStatusCode')->andReturn(200);
        $userResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $userResponse->shouldReceive('getBody')->andReturn(json_encode($response_data));

        $userResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $userResponse->shouldReceive('getStatusCode')->andReturn(200);
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->times(2)
            ->andReturn($postResponse, $userResponse);
        $this->provider->setHttpClient($client);
        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        $user = $this->provider->getResourceOwner($token);

        $this->assertEquals($response_data['id'], $user->getId());
        $this->assertEquals($response_data['id'], $user->toArray()['id']);
        $this->assertEquals($response_data['name'], $user->getName());
        $this->assertEquals($response_data['name'], $user->toArray()['name']);
        $this->assertEquals($response_data['firstname'], $user->getFirstname());
        $this->assertEquals($response_data['firstname'], $user->toArray()['firstname']);
        $this->assertEquals($response_data['lastname'], $user->getLastname());
        $this->assertEquals($response_data['lastname'], $user->toArray()['lastname']);
        $this->assertEquals($response_data['birthday'], $user->getBirthday());
        $this->assertEquals($response_data['birthday'], $user->toArray()['birthday']);
        $this->assertEquals($response_data['inscription_date'], $user->getInscriptionDate());
        $this->assertEquals($response_data['inscription_date'], $user->toArray()['inscription_date']);
        $this->assertEquals($response_data['link'], $user->getLink());
        $this->assertEquals($response_data['link'], $user->toArray()['link']);
        $this->assertEquals($response_data['gender'], $user->getGender());
        $this->assertEquals($response_data['gender'], $user->toArray()['gender']);
        $this->assertEquals($response_data['is_kid'], $user->isKid());
        $this->assertEquals($response_data['is_kid'], $user->toArray()['is_kid']);
        $this->assertEquals($response_data['picture'], $user->getPicture());
        $this->assertEquals($response_data['picture'], $user->toArray()['picture']);
        $this->assertEquals($response_data['picture_small'], $user->getPictureSmall());
        $this->assertEquals($response_data['picture_small'], $user->toArray()['picture_small']);
        $this->assertEquals($response_data['picture_medium'], $user->getPictureMedium());
        $this->assertEquals($response_data['picture_medium'], $user->toArray()['picture_medium']);
        $this->assertEquals($response_data['picture_big'], $user->getPictureBig());
        $this->assertEquals($response_data['picture_big'], $user->toArray()['picture_big']);
        $this->assertEquals($response_data['lang'], $user->getLang());
        $this->assertEquals($response_data['lang'], $user->toArray()['lang']);
        $this->assertEquals($response_data['country'], $user->getCountry());
        $this->assertEquals($response_data['country'], $user->toArray()['country']);
        $this->assertEquals($response_data['tracklist'], $user->getTracklist());
        $this->assertEquals($response_data['tracklist'], $user->toArray()['tracklist']);
        $this->assertEquals($response_data['type'], $user->getType());
        $this->assertEquals($response_data['type'], $user->toArray()['type']);
    }

    /**
     * @expectedException League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function testExceptionThrownWhenErrorObjectReceived()
    {
        $message = uniqid();
        $status = rand(400, 600);
        $postResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $postResponse->shouldReceive('getBody')->andReturn(' {"error":"'.$message.'"}');
        $postResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $postResponse->shouldReceive('getStatusCode')->andReturn($status);
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->times(1)
            ->andReturn($postResponse);
        $this->provider->setHttpClient($client);
        $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
    }
}
