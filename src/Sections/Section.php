<?php

namespace JWT4L\Sections;

use JWT4L\Traits\AlgorithmCheck;
use JWT4L\Traits\Encoder;

abstract class Section implements \JsonSerializable
{
    use AlgorithmCheck, Encoder;

    /**
     * @var array
     */
    protected $claims;

    /**
     * Append or replace the default claims set.
     *
     * @param array $claims
     * @param bool $replace
     *
     * @return Section
     */
    public function with(array $claims, bool $replace = false)
    {
        $default = $replace ? [] : $this->claims;
        $this->claims = array_merge($default, $claims);

        return $this;
    }

    /**
     * Encode the claims.
     *
     * @return string
     */
    public function make()
    {
        return $this->encode($this->claims);
    }

    /**
     * Access the claim parameters.
     *
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return isset($this->claims[$name]) ? $this->claims[$name] : null;
    }

    /**
     * Expose claims on json encoding.
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->claims;
    }

    /**
     * Expose claims on dump.
     *
     * @return array
     */
    public function __debugInfo()
    {
        return $this->claims;
    }
}