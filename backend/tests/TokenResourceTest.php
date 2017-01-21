<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Api\V1\Http\Resources\UserResource;
use Api\V1\Http\Resources\TokenResource;
use Api\V1\Models\Presenters\TokenPresenter;

/**
 * Class TokenResourceTest
 */
class TokenResourceTest extends TestCase
{
    public function testCreateToken()
    {
        $userResource = $this->app->make(UserResource::class);
        $tokenResource = $this->app->make(TokenResource::class);

        $attributes = ['name' => 'test', 'email' => 'test@test.test', 'password' => 'test_password'];

        $userPresenter = $userResource->create($attributes);
        $tokenPresenter = $tokenResource->create($attributes);

        $this->assertInstanceOf(TokenPresenter::class, $tokenPresenter, 'It should be an instance of TokenPresenter.');
        $this->assertEquals(strlen($tokenPresenter->api_token), 64, 'It should be 64 character length token.');
        $this->assertNotEquals($userPresenter->getOriginalModel()->api_token, $tokenPresenter->api_token, 'It should be a new token.');
    }

    public function testCreateTokenWithEmptyAttributes()
    {
        $tokenResource = $this->app->make(TokenResource::class);

        $this->expectException(ValidationException::class);

        try {
            $tokenResource->create([]);
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->messages();
            throw $e;
        }

        $this->assertArrayHasKey('name', $errors ?? [], 'It should include a name property error.');
        $this->assertArrayHasKey('password', $errors ?? [], 'It should include an password property error.');
    }

    public function testCreateTokenForNotExistingUser()
    {
        $tokenResource = $this->app->make(TokenResource::class);

        $attributes = ['email' => 'test@test.test', 'password' => 'test_password'];

        $this->expectException(ModelNotFoundException::class);

        $tokenResource->create($attributes);
    }
}
