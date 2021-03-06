<?php

namespace Symfony\Tests\Component\Serializer;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;
use Symfony\Tests\Component\Serializer\Fixtures\TraversableDummy;
use Symfony\Tests\Component\Serializer\Fixtures\NormalizableTraversableDummy;

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

class SerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Symfony\Component\Serializer\Exception\UnexpectedValueException
     */
    public function testNormalizeNoMatch()
    {
        $this->serializer = new Serializer(array($this->getMock('Symfony\Component\Serializer\Normalizer\CustomNormalizer')));
        $this->serializer->normalize(new \stdClass, 'xml');
    }

    public function testNormalizeTraversable()
    {
        $this->serializer = new Serializer(array(), array('json' => new JsonEncoder()));
        $result = $this->serializer->serialize(new TraversableDummy, 'json');
        $this->assertEquals('{"foo":"foo","bar":"bar"}', $result);
    }

    public function testNormalizeGivesPriorityToInterfaceOverTraversable()
    {
        $this->serializer = new Serializer(array(new CustomNormalizer), array('json' => new JsonEncoder()));
        $result = $this->serializer->serialize(new NormalizableTraversableDummy, 'json');
        $this->assertEquals('{"foo":"normalizedFoo","bar":"normalizedBar"}', $result);
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\UnexpectedValueException
     */
    public function testDenormalizeNoMatch()
    {
        $this->serializer = new Serializer(array($this->getMock('Symfony\Component\Serializer\Normalizer\CustomNormalizer')));
        $this->serializer->denormalize('foo', 'stdClass');
    }

    public function testSerialize()
    {
        $this->serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $data = array('title' => 'foo', 'numbers' => array(5, 3));
        $result = $this->serializer->serialize(Model::fromArray($data), 'json');
        $this->assertEquals(json_encode($data), $result);
    }

    public function testSerializeScalar()
    {
        $this->serializer = new Serializer(array(), array('json' => new JsonEncoder()));
        $result = $this->serializer->serialize('foo', 'json');
        $this->assertEquals('"foo"', $result);
    }

    public function testSerializeArrayOfScalars()
    {
        $this->serializer = new Serializer(array(), array('json' => new JsonEncoder()));
        $data = array('foo', array(5, 3));
        $result = $this->serializer->serialize($data, 'json');
        $this->assertEquals(json_encode($data), $result);
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\UnexpectedValueException
     */
    public function testSerializeNoEncoder()
    {
        $this->serializer = new Serializer(array(), array());
        $data = array('title' => 'foo', 'numbers' => array(5, 3));
        $this->serializer->serialize($data, 'json');
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\LogicException
     */
    public function testSerializeNoNormalizer()
    {
        $this->serializer = new Serializer(array(), array('json' => new JsonEncoder()));
        $data = array('title' => 'foo', 'numbers' => array(5, 3));
        $this->serializer->serialize(Model::fromArray($data), 'json');
    }

    public function testDeserialize()
    {
        $this->serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $data = array('title' => 'foo', 'numbers' => array(5, 3));
        $result = $this->serializer->deserialize(json_encode($data), '\Symfony\Tests\Component\Serializer\Model', 'json');
        $this->assertEquals($data, $result->toArray());
    }

    public function testDeserializeUseCache()
    {
        $this->serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $data = array('title' => 'foo', 'numbers' => array(5, 3));
        $this->serializer->deserialize(json_encode($data), '\Symfony\Tests\Component\Serializer\Model', 'json');
        $data = array('title' => 'bar', 'numbers' => array(2, 8));
        $result = $this->serializer->deserialize(json_encode($data), '\Symfony\Tests\Component\Serializer\Model', 'json');
        $this->assertEquals($data, $result->toArray());
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\LogicException
     */
    public function testDeserializeNoNormalizer()
    {
        $this->serializer = new Serializer(array(), array('json' => new JsonEncoder()));
        $data = array('title' => 'foo', 'numbers' => array(5, 3));
        $this->serializer->deserialize(json_encode($data), '\Symfony\Tests\Component\Serializer\Model', 'json');
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\UnexpectedValueException
     */
    public function testDeserializeWrongNormalizer()
    {
        $this->serializer = new Serializer(array(new CustomNormalizer()), array('json' => new JsonEncoder()));
        $data = array('title' => 'foo', 'numbers' => array(5, 3));
        $this->serializer->deserialize(json_encode($data), '\Symfony\Tests\Component\Serializer\Model', 'json');
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\UnexpectedValueException
     */
    public function testDeerializeNoEncoder()
    {
        $this->serializer = new Serializer(array(), array());
        $data = array('title' => 'foo', 'numbers' => array(5, 3));
        $this->serializer->deserialize(json_encode($data), '\Symfony\Tests\Component\Serializer\Model', 'json');
    }

    public function testEncode()
    {
        $this->serializer = new Serializer(array(), array('json' => new JsonEncoder()));
        $data = array('foo', array(5, 3));
        $result = $this->serializer->encode($data, 'json');
        $this->assertEquals(json_encode($data), $result);
    }

    public function testDecode()
    {
        $this->serializer = new Serializer(array(), array('json' => new JsonEncoder()));
        $data = array('foo', array(5, 3));
        $result = $this->serializer->decode(json_encode($data), 'json');
        $this->assertEquals($data, $result);
    }
}

class Model
{
    private $title;
    private $numbers;

    public static function fromArray($array)
    {
        $model = new self();
        if (isset($array['title'])) {
            $model->setTitle($array['title']);
        }
        if (isset($array['numbers'])) {
            $model->setNumbers($array['numbers']);
        }

        return $model;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getNumbers()
    {
        return $this->numbers;
    }

    public function setNumbers($numbers)
    {
        $this->numbers = $numbers;
    }

    public function toArray()
    {
        return array('title' => $this->title, 'numbers' => $this->numbers);
    }

}
