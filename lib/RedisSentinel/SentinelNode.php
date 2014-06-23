<?php

namespace RedisSentinel;
use RedisSentinel\Exception\InvalidProperty;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints\IpValidator;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Validation;

/**
 * Class SentinelNode
 *
 * Represents one single sentinel node and provides identification if we want to connect to it
 *
 * @package RedisSentinel
 */
class SentinelNode {

    /**
     * The name of the sentinel set this node belongs to
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $ipAddress;

    /**
     * @var integer
     */
    private $port;

    public function __construct($name, $ipAddress, $port)
    {
        $this->guardThatIpAddressFormatIsValid($ipAddress);
        $this->guardThatServerPortIsValid($port);

        $this->name = $name;
        $this->ipAddress = $ipAddress;
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Validates that the proper IP address format is used when constructing the sentinel node
     * @param $ipAddress
     * @throws Exception\InvalidProperty
     */
    private function guardThatIpAddressFormatIsValid($ipAddress)
    {
        $ipValidator = Validation::createValidator();
        $violations = $ipValidator->validateValue($ipAddress, new Ip());
        if ($violations->count() > 0) {
            throw new InvalidProperty('A sentinel node requires a valid IP address');
        }
    }

    /**
     * @param $port
     * @throws Exception\InvalidProperty
     */
    private function guardThatServerPortIsValid($port)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validateValue($port, new Range(array('min' => 0, 'max' => 65535)));
        if ($violations->count() > 0) {
            throw new InvalidProperty('A sentinel node requires a valid service port');
        }
    }
} 