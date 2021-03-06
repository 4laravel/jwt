<?php

namespace Tests\JWT4L\Unit\Checks;

use JWT4L\Checks\Expired;
use JWT4L\Exceptions\JWTExpired;
use JWT4L\Exceptions\JWTNoExpiredClaim;
use JWT4L\Managers\Generator;
use Tests\JWT4L\BaseTest;

class ExpiredTest extends BaseTest
{
    /**
     * @var Generator
     */
    private $generator;

    /**
     * @var Expired
     */
    private $check;

    /**
     * @var int
     */
    private $expiresIn = 5;

    /**
     * @var string
     */
    private $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->overrideConfiguration(['jwt.expires' => $this->expiresIn]);

        $this->generator = $this->app->make(Generator::class);
        $this->check = $this->app->make(Expired::class);

        $this->token = $this->generator->create();
    }

    /** @test */
    public function it_will_throw_a_proper_exception_if_the_token_has_expired()
    {
        $this->moveTime($this->expiresIn + 1);
        $this->expectException(JWTExpired::class);

        $this->check->validate($this->token);
    }

    /** @test */
    public function it_will_finish_silently_if_the_token_has_not_expired()
    {
        $this->moveTime($this->expiresIn - 1);
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $this->assertNull($this->check->validate($this->token));
    }

    /** @test **/
    public function it_will_throw_a_proper_exception_if_the_exp_claim_is_not_set()
    {
        $this->expectException(JWTNoExpiredClaim::class);
        $this->check->validate($this->generator->withPayload([], true)->create());
    }
}
